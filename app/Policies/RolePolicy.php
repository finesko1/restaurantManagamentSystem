<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class RolePolicy
{
    public function accessWaiterHome(User $user)
    {
        return Auth::guard('waiter')->check();
    }

    public function accessCookHome(User $user)
    {
        return Auth::guard('cook')->check();
    }
}
