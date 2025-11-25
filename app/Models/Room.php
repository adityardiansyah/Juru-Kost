<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'room_type_id',
        'room_number',
        'price',
        'facilities',
        'photos',
        'status',
    ];

    protected $casts = [
        'photos' => 'array',
        'price' => 'decimal:2',
    ];

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }

    public function statusLogs(): HasMany
    {
        return $this->hasMany(RoomStatusLog::class);
    }

    public function residentLogs(): HasMany
    {
        return $this->hasMany(ResidentRoomLog::class);
    }

    public function currentResident()
    {
        return $this->hasOne(ResidentRoomLog::class)
            ->whereNull('end_date')
            ->latest();
    }

    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class);
    }
}
