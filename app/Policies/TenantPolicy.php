<?php

namespace App\Policies;

use App\Models\Tenant;
use App\Models\User;

class TenantPolicy
{
    public function view(User $user, Tenant $tenant): bool
    {
        return $user->tenants->contains($tenant->id);
    }

    public function update(User $user, Tenant $tenant): bool
    {
        return $user->tenants()
            ->wherePivot('tenant_id', $tenant->id)
            ->wherePivot('role', 'owner')
            ->exists();
    }

    public function delete(User $user, Tenant $tenant): bool
    {
        return $user->tenants()
            ->wherePivot('tenant_id', $tenant->id)
            ->wherePivot('role', 'owner')
            ->exists();
    }
}
