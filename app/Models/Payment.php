<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'bill_id',
        'payment_number',
        'payment_date',
        'amount',
        'payment_method',
        'proof_file',
        'notes',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            if (!$payment->payment_number) {
                $payment->payment_number = 'PAY-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
            }
        });

        static::created(function ($payment) {
            $bill = $payment->bill;
            $bill->paid_amount += $payment->amount;
            $bill->updateStatus();
        });
    }

    public function bill(): BelongsTo
    {
        return $this->belongsTo(Bill::class);
    }
}
