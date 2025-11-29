<?php

namespace App\Observers;

use App\Models\Income;
use App\Models\IncomeCategory;
use App\Models\Payment;

class PaymentObserver
{
    public function created(Payment $payment): void
    {
        // Find or create "Pembayaran Penghuni" category
        $category = IncomeCategory::firstOrCreate(
            ['name' => 'Pembayaran Penghuni'],
            ['description' => 'Pemasukan dari pembayaran tagihan penghuni']
        );

        // Create income record
        Income::create([
            'tenant_id' => $payment->tenant_id,
            'income_category_id' => $category->id,
            'transaction_date' => $payment->payment_date,
            'amount' => $payment->amount,
            'description' => 'Pembayaran dari ' . $payment->bill->resident->name . ' - ' . $payment->bill->bill_number,
            'proof_file' => $payment->proof_file,
        ]);
    }

    /**
     * Handle the Payment "updated" event.
     */
    public function updated(Payment $payment): void
    {
        //
    }

    /**
     * Handle the Payment "deleted" event.
     */
    public function deleted(Payment $payment): void
    {
        //
    }

    /**
     * Handle the Payment "restored" event.
     */
    public function restored(Payment $payment): void
    {
        //
    }

    /**
     * Handle the Payment "force deleted" event.
     */
    public function forceDeleted(Payment $payment): void
    {
        //
    }
}
