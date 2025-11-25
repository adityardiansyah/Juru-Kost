<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Inventory extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'name',
        'sku',
        'quantity',
        'min_stock',
        'unit',
        'unit_price',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($inventory) {
            if (!$inventory->sku) {
                $inventory->sku = 'SKU-' . strtoupper(substr(uniqid(), -8));
            }
        });
    }

    public function logs(): HasMany
    {
        return $this->hasMany(InventoryLog::class);
    }

    public function isLowStock(): bool
    {
        return $this->quantity <= $this->min_stock;
    }

    public function addStock(int $quantity, string $notes = null, int $userId = null): void
    {
        $this->quantity += $quantity;
        $this->save();
        $this->logs()->create([
            'tenant_id' => $this->tenant_id,
            'type' => 'in',
            'quantity' => $quantity,
            'notes' => $notes,
            'user_id' => $userId ?? Auth::id(),
        ]);
    }

    public function reduceStock(int $quantity, string $notes = null, int $userId = null): void
    {
        $this->quantity = max(0, $this->quantity - $quantity);
        $this->save();
        $this->logs()->create([
            'tenant_id' => $this->tenant_id,
            'type' => 'out',
            'quantity' => $quantity,
            'notes' => $notes,
            'user_id' => $userId ?? Auth::id(),
        ]);
    }
}
