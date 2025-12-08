<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_superuser' => 'boolean',
    ];

    public function tenants(): BelongsToMany
    {
        return $this->belongsToMany(Tenant::class, 'tenant_user')
            ->withPivot('role_id')
            ->withTimestamps();
    }

    /**
     * Get the role for a specific tenant
     */
    public function roleInTenant($tenantId): ?Role
    {
        $tenant = $this->tenants()->where('tenants.id', $tenantId)->first();
        return $tenant ? Role::find($tenant->pivot->role_id) : null;
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'tenant_user')
            ->withPivot('tenant_id')
            ->withTimestamps();
    }

    public function hasRole(string $roleName, $tenantId = null): bool
    {
        if ($this->is_superuser) {
            return true;
        }

        $tenantId = $tenantId ?? session('tenant_id');

        return $this->roles()
            ->wherePivot('tenant_id', $tenantId)
            ->where('name', $roleName)
            ->exists();
    }

    public function hasPermission(string $permissionName, $tenantId = null): bool
    {
        if ($this->is_superuser) {
            return true;
        }

        $tenantId = $tenantId ?? session('tenant_id');

        // Get user's role for this tenant
        $role = $this->roles()
            ->wherePivot('tenant_id', $tenantId)
            ->first();

        if (!$role) {
            return false;
        }

        // Check if role has permission
        return $role->permissions()->where('name', $permissionName)->exists();
    }

    public function isOwner($tenantId = null): bool
    {
        return $this->hasRole('owner', $tenantId);
    }
}
