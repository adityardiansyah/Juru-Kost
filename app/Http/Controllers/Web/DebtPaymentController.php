<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Debt;
use App\Models\DebtPayment;
use App\Services\DebtService;
use Illuminate\Http\Request;

class DebtPaymentController extends Controller
{
    protected $debtService;

    public function __construct(DebtService $debtService)
    {
        $this->debtService = $debtService;
    }

    /**
     * Store a new debt payment (auto-creates expense)
     */
    public function store(Request $request, Debt $debt)
    {
        $validated = $request->validate([
            'payment_date' => 'required|date',
            'due_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'principal_paid' => 'nullable|numeric|min:0',
            'interest_paid' => 'nullable|numeric|min:0',
            'late_fee' => 'nullable|numeric|min:0',
            'proof_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'notes' => 'nullable|string',
        ]);

        // Handle file upload
        if ($request->hasFile('proof_file')) {
            $validated['proof_file'] = $request->file('proof_file')->store('payment-proofs', 'public');
        }

        // Record payment (will auto-create expense)
        $payment = $this->debtService->recordPayment($debt->id, $validated);

        return redirect()->route('debts.show', $debt)
            ->with('success', 'Pembayaran berhasil dicatat dan otomatis tercatat di pengeluaran!');
    }

    /**
     * Delete a debt payment (also deletes linked expense)
     */
    public function destroy(DebtPayment $payment)
    {
        $debtId = $payment->debt_id;

        // Delete payment and linked expense
        $this->debtService->deletePayment($payment);

        return redirect()->route('debts.show', $debtId)
            ->with('success', 'Pembayaran berhasil dihapus!');
    }
}
