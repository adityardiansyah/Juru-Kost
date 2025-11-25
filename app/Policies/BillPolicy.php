<?php

namespace App\Policies;

use App\Models\Bill;
use App\Models\User;

class BillPolicy
{
    public function viewAny(User $user): bool
    {
        return session()->has('tenant_id');
    }

    public function view(User $user, Bill $bill): bool
    {
        return $bill->tenant_id === session('tenant_id');
    }

    public function create(User $user): bool
    {
        $allowedRoles = ['owner', 'admin', 'accountant'];
        
        foreach ($allowedRoles as $role) {
            if ($user->hasRole($role)) {
                return true;
            }
        }
        
        return false;
    }

    public function update(User $user, Bill $bill): bool
    {
        if ($bill->tenant_id !== session('tenant_id')) {
            return false;
        }
        
        // Cannot update paid bills
        if ($bill->status === 'paid') {
            return false;
        }
        
        return $user->hasRole('owner') || $user->hasRole('admin') || $user->hasRole('accountant');
    }

    public function delete(User $user, Bill $bill): bool
    {
        if ($bill->tenant_id !== session('tenant_id')) {
            return false;
        }
        
        // Cannot delete paid bills
        if ($bill->status === 'paid') {
            return false;
        }
        
        // Only owner can delete
        return $user->hasRole('owner');
    }

    public function addCharge(User $user, Bill $bill): bool
    {
        if ($bill->tenant_id !== session('tenant_id')) {
            return false;
        }
        
        // Cannot add charge to paid bills
        if ($bill->status === 'paid') {
            return false;
        }
        
        return $user->hasRole('owner') || $user->hasRole('admin') || $user->hasRole('accountant');
    }

    public function generateMonthly(User $user): bool
    {
        return $user->hasRole('owner') || $user->hasRole('admin');
    }
}
