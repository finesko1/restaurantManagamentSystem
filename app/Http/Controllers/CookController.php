<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class CookController extends Controller
{
    use AuthorizesRequests;

    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    // Проверяем права при помощи политики
    public function index()
    {
        try {
            $orders = $this->orderService->getAllOrders();
            // Проверяем, что $orders не null
            if ($orders === null) {
                $orders = collect(); // Создаем пустую коллекцию, если заказов нет
            }
            return view('layouts.app', compact('orders'));
        } catch (Exception $e) {
            return redirect()->route('layouts.app')->with('error', 'An unexpected error occurred while retrieving orders.');
        }
    }
}
