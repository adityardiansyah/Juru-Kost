<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('bill.resident')
            ->latest('payment_date')
            ->paginate(20);

        return view('payments.index', compact('payments'));
    }

    public function create(Request $request)
    {
        $billId = $request->bill_id;
        $bill = $billId ? Bill::with('resident')->findOrFail($billId) : null;

        return view('payments.create', compact('bill'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bill_id' => 'required|exists:bills,id',
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,transfer,e-wallet,other',
            'proof_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'notes' => 'nullable|string',
        ]);

        $bill = Bill::findOrFail($validated['bill_id']);

        // Check remaining amount
        $remainingAmount = $bill->total_amount - $bill->paid_amount;
        if ($validated['amount'] > $remainingAmount) {
            return back()->with('error', 'Jumlah pembayaran melebihi sisa tagihan!');
        }

        // Handle file upload
        if ($request->hasFile('proof_file')) {
            $path = $request->file('proof_file')->store('proofs', 'public');
            $validated['proof_file'] = $path;
        }

        Payment::create([
            'tenant_id' => session('tenant_id'),
            'bill_id' => $validated['bill_id'],
            'payment_date' => $validated['payment_date'],
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'proof_file' => $validated['proof_file'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('bills.show', $bill)
            ->with('success', 'Pembayaran berhasil dicatat!');
    }

    public function show(Payment $payment)
    {
        $payment->load('bill.resident');
        return view('payments.show', compact('payment'));
    }
}
