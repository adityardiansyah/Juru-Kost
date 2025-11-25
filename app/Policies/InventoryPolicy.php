<?php

namespace App\Policies;

use App\Models\User;

class InventoryPolicy
{
    public function viewAny(User $user): bool
    {
        return session()->has('tenant_id');
    }

    public function view(User $user, Inventory $inventory): bool
    {
        return $inventory->tenant_id === session('tenant_id');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('owner') || $user->hasRole('admin');
    }

    public function update(User $user, Inventory $inventory): bool
    {
        if ($inventory->tenant_id !== session('tenant_id')) {
            return false;
        }

        return $user->hasRole('owner') || $user->hasRole('admin');
    }

    public function delete(User $user, Inventory $inventory): bool
    {
        if ($inventory->tenant_id !== session('tenant_id')) {
            return false;
        }

        return $user->hasRole('owner');
    }

    public function stockIn(User $user, Inventory $inventory): bool
    {
        if ($inventory->tenant_id !== session('tenant_id')) {
            return false;
        }

        return $user->hasRole('owner') || $user->hasRole('admin');
    }

    public function stockOut(User $user, Inventory $inventory): bool
    {
        if ($inventory->tenant_id !== session('tenant_id')) {
            return false;
        }

        return $user->hasRole('owner') || $user->hasRole('admin');
    }
}
