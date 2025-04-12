<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CookController;
use App\Http\Controllers\WaiterController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/waiter/home', [WaiterController::class, 'index'])->middleware('auth:waiter')->name('waiter.home');
Route::get('/cook/home', [CookController::class, 'index'])->middleware('auth:cook')->name('cook.home');

Route::group(['middleware' => 'guest'], function () {
    Auth::routes();
});

Route::post('/{role}/logout', [LoginController::class, 'logout'])
    ->name('logout')
    ->middleware('auth:{role}');
