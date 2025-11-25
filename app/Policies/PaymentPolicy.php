<?php

namespace App\Policies;

use App\Models\Payment;
use App\Models\User;

class PaymentPolicy
{
    public function viewAny(User $user): bool
    {
        return session()->has('tenant_id');
    }

    public function view(User $user, Payment $payment): bool
    {
        return $payment->tenant_id === session('tenant_id');
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

    public function delete(User $user, Payment $payment): bool
    {
        if ($payment->tenant_id !== session('tenant_id')) {
            return false;
        }
        
        // Only owner can delete payments
        return $user->hasRole('owner');
    }
}
