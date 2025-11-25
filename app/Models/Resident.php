<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\BelongsToTenant;

class Resident extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'name',
        'phone',
        'email',
        'address',
        'id_card_number',
        'entry_date',
        'exit_date',
        'status',
    ];

    protected $casts = [
        'entry_date' => 'date',
        'exit_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(ResidentDocument::class);
    }

    public function roomLogs(): HasMany
    {
        return $this->hasMany(ResidentRoomLog::class);
    }

    public function currentRoom()
    {
        return $this->hasOne(ResidentRoomLog::class)
            ->whereNull('end_date')
            ->with('room')
            ->latest();
    }

    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class);
    }

    public function maintenanceRequests(): HasMany
    {
        return $this->hasMany(MaintenanceRequest::class);
    }
}
