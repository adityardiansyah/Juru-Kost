<?php

namespace App\Policies;

use App\Models\Asset;
use App\Models\User;

class AssetPolicy
{
    public function viewAny(User $user): bool
    {
        return session()->has('tenant_id');
    }

    public function view(User $user, Asset $asset): bool
    {
        return $asset->tenant_id === session('tenant_id');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('owner') || $user->hasRole('admin');
    }

    public function update(User $user, Asset $asset): bool
    {
        if ($asset->tenant_id !== session('tenant_id')) {
            return false;
        }

        return $user->hasRole('owner') || $user->hasRole('admin');
    }

    public function delete(User $user, Asset $asset): bool
    {
        if ($asset->tenant_id !== session('tenant_id')) {
            return false;
        }

        return $user->hasRole('owner');
    }

    public function addMaintenance(User $user, Asset $asset): bool
    {
        if ($asset->tenant_id !== session('tenant_id')) {
            return false;
        }

        return $user->hasRole('owner') || $user->hasRole('admin');
    }
}
