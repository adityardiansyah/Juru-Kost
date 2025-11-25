<?php

namespace App\Policies;

use App\Models\Room;
use App\Models\User;

class RoomPolicy
{
    public function viewAny(User $user): bool
    {
        return session()->has('tenant_id');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('owner') || $user->hasRole('admin');
    }

    public function update(User $user, Room $room): bool
    {
        return $user->hasRole('owner') || $user->hasRole('admin');
    }

    public function delete(User $user, Room $room): bool
    {
        return $user->hasRole('owner');
    }
}
