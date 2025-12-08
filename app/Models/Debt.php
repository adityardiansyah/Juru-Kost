<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\BelongsToTenant;
use Carbon\Carbon;

class Debt extends Model
{
    use HasFactory, BelongsToTenant, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'creditor_name',
        'debt_type',
        'principal_amount',
        'interest_rate',
        'total_amount',
        'start_date',
        'end_date',
        'installment_amount',
        'installment_frequency',
        'total_installments',
        'status',
        'description',
        'contract_file',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'principal_amount' => 'decimal:2',
        'interest_rate' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'installment_amount' => 'decimal:2',
    ];

    // Relationships
    public function payments(): HasMany
    {
        return $this->hasMany(DebtPayment::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePaidOff($query)
    {
        return $query->where('status', 'paid_off');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue');
    }

    // Accessors
    public function getTotalPaidAttribute()
    {
        return $this->payments()->where('status', 'paid')->sum('amount');
    }

    public function getRemainingBalanceAttribute()
    {
        return $this->total_amount - $this->total_paid;
    }

    public function getPaymentProgressPercentageAttribute()
    {
        if ($this->total_amount == 0) {
            return 0;
        }
        return ($this->total_paid / $this->total_amount) * 100;
    }

    public function getPaidInstallmentsCountAttribute()
    {
        return $this->payments()->where('status', 'paid')->count();
    }

    public function getRemainingInstallmentsAttribute()
    {
        return $this->total_installments - $this->paid_installments_count;
    }

    // Methods
    public function calculateAmortization()
    {
        $schedule = [];
        $remainingPrincipal = $this->principal_amount;
        $monthlyInterestRate = $this->interest_rate / 100 / 12;

        $currentDate = Carbon::parse($this->start_date);

        for ($i = 1; $i <= $this->total_installments; $i++) {
            // Calculate interest for this period
            $interestPayment = $remainingPrincipal * $monthlyInterestRate;
            $principalPayment = $this->installment_amount - $interestPayment;

            // Adjust last payment if needed
            if ($i == $this->total_installments) {
                $principalPayment = $remainingPrincipal;
                $totalPayment = $principalPayment + $interestPayment;
            } else {
                $totalPayment = $this->installment_amount;
            }

            $schedule[] = [
                'installment_number' => $i,
                'due_date' => $currentDate->copy(),
                'principal' => round($principalPayment, 2),
                'interest' => round($interestPayment, 2),
                'total' => round($totalPayment, 2),
                'remaining_balance' => round($remainingPrincipal - $principalPayment, 2),
            ];

            $remainingPrincipal -= $principalPayment;

            // Move to next period based on frequency
            $currentDate = $this->getNextDueDate($currentDate);
        }

        return $schedule;
    }

    public function getNextDueDate($fromDate = null)
    {
        $date = $fromDate ? Carbon::parse($fromDate) : Carbon::parse($this->start_date);

        switch ($this->installment_frequency) {
            case 'monthly':
                return $date->addMonth();
            case 'quarterly':
                return $date->addMonths(3);
            case 'yearly':
                return $date->addYear();
            default:
                return $date->addMonth();
        }
    }

    public function updateStatus()
    {
        if ($this->remaining_balance <= 0) {
            $this->status = 'paid_off';
        } elseif ($this->end_date < now()) {
            $this->status = 'overdue';
        } else {
            $this->status = 'active';
        }

        $this->save();
    }
}
