<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'original_price',
        'discount_percentage',
        'type',
        'is_active',
        'max_tenants',
        'current_tenants',
        'features',
        'bonus_features',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'is_active' => 'boolean',
        'features' => 'array',
        'bonus_features' => 'array',
    ];

    /**
     * Check if package is available for purchase
     */
    public function isAvailable(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        // If max_tenants is null, it's unlimited
        if ($this->max_tenants === null) {
            return true;
        }

        // Check if current tenants is less than max
        return $this->current_tenants < $this->max_tenants;
    }

    /**
     * Get remaining slots
     */
    public function getRemainingSlots(): ?int
    {
        if ($this->max_tenants === null) {
            return null; // unlimited
        }

        return max(0, $this->max_tenants - $this->current_tenants);
    }

    /**
     * Increment tenant count
     */
    public function incrementTenants(): void
    {
        $this->increment('current_tenants');

        // Auto-disable if reached max
        if ($this->max_tenants !== null && $this->current_tenants >= $this->max_tenants) {
            $this->update(['is_active' => false]);
        }
    }

    /**
     * Get orders for this package
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get paid orders count
     */
    public function getPaidOrdersCount(): int
    {
        return $this->orders()->where('payment_status', 'paid')->count();
    }
}
