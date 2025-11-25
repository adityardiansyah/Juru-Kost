<?php

namespace App\Policies;

use App\Models\Resident;
use App\Models\User;

class ResidentPolicy
{
    public function viewAny(User $user): bool
    {
        return session()->has('tenant_id');
    }

    public function view(User $user, Resident $resident): bool
    {
        return $resident->tenant_id === session('tenant_id');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('owner') || $user->hasRole('admin');
    }

    public function update(User $user, Resident $resident): bool
    {
        if ($resident->tenant_id !== session('tenant_id')) {
            return false;
        }

        return $user->hasRole('owner') || $user->hasRole('admin');
    }

    public function delete(User $user, Resident $resident): bool
    {
        if ($resident->tenant_id !== session('tenant_id')) {
            return false;
        }

        // Only owner can delete, and must not have unpaid bills
        return $user->hasRole('owner');
    }

    public function uploadDocument(User $user, Resident $resident): bool
    {
        if ($resident->tenant_id !== session('tenant_id')) {
            return false;
        }

        return $user->hasRole('owner') || $user->hasRole('admin');
    }

    public function moveRoom(User $user, Resident $resident): bool
    {
        if ($resident->tenant_id !== session('tenant_id')) {
            return false;
        }

        return $user->hasRole('owner') || $user->hasRole('admin');
    }
}
