<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\RolePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    // Массив для связывания моделей с политиками
    protected $policies = [
        User::class => RolePolicy::class,
    ];

    public function boot()
    {
        // Регистрируем все политики
        $this->registerPolicies();
    }
}
