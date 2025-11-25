<?php

namespace App\Traits;

use App\Models\Scopes\Scope\TenantScope;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToTenant
{
    protected static function bootBelongsToTenant(): void
    {
        // Tambahkan GlobalScope untuk filter tenant
        static::addGlobalScope(new TenantScope);

        // Auto-fill tenant_id saat creating
        static::creating(function ($model) {
            if (!$model->tenant_id && session()->has('tenant_id')) {
                $model->tenant_id = session('tenant_id');
            }
        });
    }

    /**
     * Relasi ke Tenant
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Scope untuk bypass tenant filter
     */
    public function scopeWithoutTenantScope($query)
    {
        return $query->withoutGlobalScope(TenantScope::class);
    }
}
