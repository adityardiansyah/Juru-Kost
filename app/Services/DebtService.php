<?php

namespace App\Services;

use App\Models\Debt;
use App\Models\DebtPayment;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DebtService
{
    /**
     * Create a new debt and optionally generate payment schedule
     */
    public function createDebt(array $data): Debt
    {
        // Calculate total amount if not provided
        if (!isset($data['total_amount'])) {
            $principal = $data['principal_amount'];
            $rate = $data['interest_rate'] / 100;
            $months = $data['total_installments'];

            // Simple interest calculation
            $totalInterest = $principal * $rate * ($months / 12);
            $data['total_amount'] = $principal + $totalInterest;
        }

        // Calculate installment amount if not provided
        if (!isset($data['installment_amount'])) {
            $data['installment_amount'] = $data['total_amount'] / $data['total_installments'];
        }

        $debt = Debt::create($data);

        // Optionally generate payment schedule
        if (isset($data['generate_schedule']) && $data['generate_schedule']) {
            $this->generatePaymentSchedule($debt);
        }

        return $debt;
    }

    /**
     * Generate payment schedule for a debt
     */
    public function generatePaymentSchedule(Debt $debt): void
    {
        $schedule = $debt->calculateAmortization();

        foreach ($schedule as $installment) {
            DebtPayment::create([
                'debt_id' => $debt->id,
                'tenant_id' => $debt->tenant_id,
                'due_date' => $installment['due_date'],
                'amount' => $installment['total'],
                'principal_paid' => $installment['principal'],
                'interest_paid' => $installment['interest'],
                'status' => 'pending',
            ]);
        }
    }

    /**
     * Record a debt payment and auto-create expense
     */
    public function recordPayment(int $debtId, array $paymentData): DebtPayment
    {
        return DB::transaction(function () use ($debtId, $paymentData) {
            $debt = Debt::findOrFail($debtId);

            // Get the debt payment expense category
            $expenseCategory = ExpenseCategory::where('name', 'Pembayaran Hutang')->first();

            // Create expense entry first
            $expense = Expense::create([
                'tenant_id' => $debt->tenant_id,
                'expense_category_id' => $expenseCategory->id,
                'transaction_date' => $paymentData['payment_date'],
                'amount' => $paymentData['amount'],
                'description' => "Pembayaran cicilan {$debt->creditor_name} - {$debt->debt_type}",
                'proof_file' => $paymentData['proof_file'] ?? null,
            ]);

            // Create debt payment and link to expense
            $payment = DebtPayment::create([
                'debt_id' => $debtId,
                'tenant_id' => $debt->tenant_id,
                'expense_id' => $expense->id,
                'payment_date' => $paymentData['payment_date'],
                'due_date' => $paymentData['due_date'] ?? now(),
                'amount' => $paymentData['amount'],
                'principal_paid' => $paymentData['principal_paid'] ?? 0,
                'interest_paid' => $paymentData['interest_paid'] ?? 0,
                'late_fee' => $paymentData['late_fee'] ?? 0,
                'status' => 'paid',
                'proof_file' => $paymentData['proof_file'] ?? null,
                'notes' => $paymentData['notes'] ?? null,
            ]);

            // Update debt status
            $debt->updateStatus();

            return $payment;
        });
    }

    /**
     * Get debt summary for dashboard
     */
    public function getDebtSummary(int $tenantId): array
    {
        $activeDebts = Debt::where('tenant_id', $tenantId)->active()->get();
        $allDebts = Debt::where('tenant_id', $tenantId)->get();

        $totalDebt = $allDebts->sum('total_amount');
        $totalPaid = $allDebts->sum('total_paid');
        $remainingBalance = $totalDebt - $totalPaid;

        // Get overdue payments
        $overduePayments = DebtPayment::where('tenant_id', $tenantId)
            ->overdue()
            ->count();

        // Calculate monthly obligation (sum of all active debts' installments)
        $monthlyObligation = $activeDebts->sum('installment_amount');

        return [
            'total_debt' => round($totalDebt, 2),
            'total_paid' => round($totalPaid, 2),
            'remaining_balance' => round($remainingBalance, 2),
            'active_debts_count' => $activeDebts->count(),
            'overdue_payments_count' => $overduePayments,
            'monthly_obligation' => round($monthlyObligation, 2),
            'payment_progress' => $totalDebt > 0 ? round(($totalPaid / $totalDebt) * 100, 2) : 0,
        ];
    }

    /**
     * Check and update overdue payments
     */
    public function checkOverduePayments(int $tenantId): array
    {
        $overduePayments = DebtPayment::where('tenant_id', $tenantId)
            ->where('status', 'pending')
            ->where('due_date', '<', now())
            ->get();

        foreach ($overduePayments as $payment) {
            $payment->status = 'overdue';
            $payment->save();

            // Also update debt status
            $payment->debt->updateStatus();
        }

        return $overduePayments->toArray();
    }

    /**
     * Calculate remaining balance for a debt
     */
    public function calculateRemainingBalance(int $debtId): float
    {
        $debt = Debt::findOrFail($debtId);
        return $debt->remaining_balance;
    }

    /**
     * Get upcoming payments (next 30 days)
     */
    public function getUpcomingPayments(int $tenantId, int $days = 30): array
    {
        return DebtPayment::where('tenant_id', $tenantId)
            ->where('status', 'pending')
            ->whereBetween('due_date', [now(), now()->addDays($days)])
            ->with('debt')
            ->orderBy('due_date')
            ->get()
            ->toArray();
    }

    /**
     * Delete a debt payment and its linked expense
     */
    public function deletePayment(DebtPayment $payment): bool
    {
        return DB::transaction(function () use ($payment) {
            // Delete linked expense if exists
            if ($payment->expense_id) {
                $payment->expense()->delete();
            }

            // Delete payment
            $deleted = $payment->delete();

            // Update debt status
            $payment->debt->updateStatus();

            return $deleted;
        });
    }
}
