<?php

namespace App\Policies;

use App\Models\User;

class RolePolicy
{
    public function accessWaiterHome(User $user)
    {
        return $user->role === 'waiter';
    }

    public function accessCookHome(User $user)
    {
        return $user->role === 'cook';
    }
}
