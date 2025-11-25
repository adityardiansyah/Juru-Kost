<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Asset extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'room_id',
        'name',
        'code',
        'qr_code',
        'purchase_price',
        'purchase_date',
        'useful_life_years',
        'current_value',
        'condition',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'purchase_price' => 'decimal:2',
        'current_value' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($asset) {
            if (!$asset->code) {
                $asset->code = 'AST-' . strtoupper(substr(uniqid(), -8));
            }
            
            $asset->current_value = $asset->purchase_price;
        });

        static::created(function ($asset) {
            // Generate QR Code
            $qrCode = QrCode::format('png')
                ->size(300)
                ->generate($asset->code);
            
            $path = 'qrcodes/' . $asset->code . '.png';
            Storage::disk('public')->put($path, $qrCode);
            
            $asset->qr_code = $path;
            $asset->save();
        });
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function maintenanceLogs(): HasMany
    {
        return $this->hasMany(AssetMaintenanceLog::class);
    }

    public function calculateDepreciation(): float
    {
        $age = now()->diffInYears($this->purchase_date);
        
        if ($age >= $this->useful_life_years) {
            return 0;
        }

        $annualDepreciation = $this->purchase_price / $this->useful_life_years;
        $totalDepreciation = $annualDepreciation * $age;
        
        return max(0, $this->purchase_price - $totalDepreciation);
    }

    public function updateCurrentValue(): void
    {
        $this->current_value = $this->calculateDepreciation();
        $this->save();
    }
}
