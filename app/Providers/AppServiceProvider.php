<?php

namespace App\Providers;

use App\Models\OrderItem;
use App\Services\MenuService;
use App\Services\OrderItemService;
use App\Services\OrderService;
use Illuminate\Support\ServiceProvider;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Регистрация MenuService
        $this->app->singleton(MenuService::class, function () {
            return new MenuService();
        });
        // Регистрация OrderService
        $this->app->singleton(OrderService::class, function () {
            return new OrderService();
        });
        // Регистрация OrderItemService
        $this->app->singleton(OrderItemService::class, function () {
            return new OrderItemService();
        });
    }
}
