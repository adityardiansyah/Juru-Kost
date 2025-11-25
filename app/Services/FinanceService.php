<?php

namespace App\Services;

use App\Models\Expense;
use App\Models\Income;
use Carbon\Carbon;

class FinanceService
{
    /**
     * Get cashflow summary
     */
    public function getCashflowSummary(int $tenantId, Carbon $month): array
    {
        $incomes = Income::where('tenant_id', $tenantId)
            ->whereYear('transaction_date', $month->year)
            ->whereMonth('transaction_date', $month->month)
            ->get();

        $expenses = Expense::where('tenant_id', $tenantId)
            ->whereYear('transaction_date', $month->year)
            ->whereMonth('transaction_date', $month->month)
            ->get();

        $totalIncome = $incomes->sum('amount');
        $totalExpense = $expenses->sum('amount');
        $netCashflow = $totalIncome - $totalExpense;

        return [
            'total_income' => round($totalIncome, 2),
            'total_expense' => round($totalExpense, 2),
            'net_cashflow' => round($netCashflow, 2),
            'income_by_category' => $incomes->groupBy('income_category_id')->map->sum('amount'),
            'expense_by_category' => $expenses->groupBy('expense_category_id')->map->sum('amount'),
        ];
    }

    /**
     * Get monthly comparison
     */
    public function getMonthlyComparison(int $tenantId, int $months = 6): array
    {
        $data = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $date = now()->subMonths($i);

            $income = Income::where('tenant_id', $tenantId)
                ->whereYear('transaction_date', $date->year)
                ->whereMonth('transaction_date', $date->month)
                ->sum('amount');

            $expense = Expense::where('tenant_id', $tenantId)
                ->whereYear('transaction_date', $date->year)
                ->whereMonth('transaction_date', $date->month)
                ->sum('amount');

            $data[] = [
                'month' => $date->format('M Y'),
                'income' => round($income, 2),
                'expense' => round($expense, 2),
                'profit' => round($income - $expense, 2),
            ];
        }

        return $data;
    }
}
