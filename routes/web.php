<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CookController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\WaiterController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::group(['middleware' => 'guest'], function () {
    Auth::routes();
});

Route::post('/{role}/logout', [LoginController::class, 'logout'])
    ->name('logout')
    ->middleware('auth:{role}');


// Ресурсы для официанта
Route::group(['middleware' => 'auth:waiter'], function () {
    // Представление заказов
    Route::get('/waiter/home', [WaiterController::class, 'index'])
        ->name('waiter.index');

    // Управление заказами
    Route::get('/order', [OrderController::class, 'index'])
        ->name('order.index');
    Route::post('/order', [OrderController::class, 'store'])
        ->name('order.store');
    Route::get('/order/{id}', [OrderController::class, 'show'])
        ->name('order.show');
    Route::delete('/order/{id}', [OrderController::class, 'destroy'])
        ->name('order.destroy');

    // Управление блюдами
    Route::get('/order/items', [OrderItemController::class, 'index'])
        ->name('order.items.index');
    Route::post('/order/items', [OrderItemController::class, 'store'])
        ->name('order.items.store');
    Route::get('/order/items/{id}', [OrderItemController::class, 'show'])
        ->name('order.items.show');
    Route::delete('/order/items/{id}', [OrderItemController::class, 'destroy'])
        ->name('order.items.destroy');
});

// Ресурсы для повара
Route::group(['middleware' => 'auth:cook'], function () {
    // Представление заказов
    Route::get('/cook/home', [CookController::class, 'index'])
        ->name('cook.index');

    // Управление заказами
    Route::get('/order', [OrderController::class, 'index'])
        ->name('order.index');
    Route::get('/order/{id}', [OrderController::class, 'show'])
        ->name('order.show');
    Route::patch('/order/{id}/status', [OrderController::class, 'updateStatus'])
        ->name('order.status.update');
});
