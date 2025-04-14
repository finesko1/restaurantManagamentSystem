<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CookController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\WaiterController;
use App\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('layouts.app');
})->name('home');
Route::get('/userRole', function () {
    return response()->json([
        'role' => Auth::guard('cook')->check() ? 'cook' : (Auth::guard('waiter')->check() ? 'waiter' : null),
    ]);
});
Route::get('/user', function () {
    $user = Auth::guard('cook')->user();

    if ($user) {
        return response()->json([
            'user' => $user,
            'role' => 'cook',
        ]);
    }

    $user = Auth::guard('waiter')->user();

    if ($user) {
        return response()->json([
            'user' => $user,
            'role' => 'waiter',
        ]);
    }

    return response()->json([
        'user' => null,
        'role' => null,
    ]);
});


// Защита путей к авторизации от авторизованного пользователя
Route::group(['middleware' => RedirectIfAuthenticated::class], function () {
    Auth::routes();
});

Route::post('/cook/logout', [LoginController::class, 'logout'])
    ->name('cook.logout')
    ->middleware('auth:cook');
Route::post('/waiter/logout', [LoginController::class, 'logout'])
    ->name('waiter.logout')
    ->middleware('auth:waiter');


// Ресурсы для официанта
Route::prefix('waiter')->middleware('auth:waiter')->group(function () {
    // Представление меню
    Route::get('/menu', [MenuController::class, 'index'])
        ->name('menu.index');
    Route::get('/menu/{id}', [MenuController::class, 'show'])
        ->name('menu.show');

    // Представление заказов
    Route::get('/home', [WaiterController::class, 'index'])
        ->name('waiter.index');

    // Управление заказами
    Route::prefix('order')->group(function () {
        Route::get('/', [OrderController::class, 'index'])
            ->name('order.index');
        Route::post('/', [OrderController::class, 'store'])
            ->name('order.store');
        Route::get('/{order_id}/items', [OrderItemController::class, 'showByOrderId'])
            ->name('order.itemsByOrderId.show');
//        Route::get('/{id}', [OrderController::class, 'show'])
//            ->name('order.show');
        Route::delete('/{id}', [OrderController::class, 'destroy'])
            ->name('order.destroy');

        // Управление блюдами в заказах
        Route::prefix('items')->group(function () {
            Route::get('/', [OrderItemController::class, 'index'])
                ->name('order.items.index');
            Route::post('/', [OrderItemController::class, 'store'])
                ->name('order.item.store');
            Route::get('/{id}', [OrderItemController::class, 'show'])
                ->name('order.item.show');
            Route::patch('/{id}', [OrderItemController::class, 'updateStatus'])
                ->name('order.item.status.update');
            Route::delete('/{id}', [OrderItemController::class, 'destroy'])
                ->name('order.item.destroy');
        });
    });
});


// Ресурсы для повара
Route::group(['middleware' => 'auth:cook', 'prefix' => 'cook'], function () {
    // Представление меню
    Route::get('/menu', [MenuController::class, 'index'])
        ->name('menu.index');
    Route::get('/menu/{id}', [MenuController::class, 'show'])
        ->name('menu.show');

    // Представление заказов
    Route::get('/home', [CookController::class, 'index'])
        ->name('cook.index');

    // Управление заказами
    Route::get('/order', [OrderController::class, 'index'])
        ->name('order.index');
    Route::get('/order/{id}', [OrderController::class, 'show'])
        ->name('order.show');
    Route::patch('/order/{id}', [OrderController::class, 'updateStatus'])
        ->name('order.status.update');

    // Управление блюдами
    Route::get('/order/{order_id}/items', [OrderItemController::class, 'showByOrderId'])
        ->name('order.itemsByOrderId.show');
    Route::patch('/order/items/{id}', [OrderItemController::class, 'updateStatus'])
        ->name('order.item.status.update');
});

Route::get('/{any}', function () {
    return view('layouts.app');
});
