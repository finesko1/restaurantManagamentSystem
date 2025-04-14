<?php

namespace App\Services;

use App\Events\OrderCreated;
use App\Events\OrderStatusChanged;
use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class OrderService
{
    /**
     * Получение всех заказов
     *
     * @return Collection
     */
    public function getAllOrders(): Collection
    {
        return Order::all();
    }

    /**
     * Получение заказа по *id*
     */
    public function getOrderById(Request $request): Order
    {
        $id = $this->validateId($request);
        return Order::findOrFail($id);
    }

    /**
     * Создание нового заказа
     */
    public function createOrder(Request $request)
    {
        $validatedData = $this->validateCreateOrderRequest($request);

        $order = Order::create([
            'table_id' => $validatedData['table_id'],
            'status' => $validatedData['status'] ?? 'pending',
        ]);

        event(new OrderCreated($order));
    }

    /**
     * Обновление статуса заказа
     */
    public function updateStatusOrder(Request $request)
    {
        $validatedData = $this->validateUpdateOrderStatusRequest($request);
        $order = Order::findOrFail($validatedData['id']);
        $oldStatus = $order->status;

        $order->update([
            'status' => $validatedData['status']
        ]);

        event(new OrderStatusChanged($order, $oldStatus, $validatedData['status']));
    }

    /**
     * Удаление заказа
     */
    public function deleteOrder(Request $request)
    {
        $validatedId = $this->validateId($request);
        Order::destroy($validatedId);
    }

    /**
     * Валидация id запроса
     */
    protected function validateId(Request $request)
    {
        return $request->validate([
            'id' => 'required|integer',
        ]);
    }

    /**
     * Валидация запроса при создании заказа
     */
    protected function validateCreateOrderRequest(Request $request)
    {
        return $request->validate([
            'table_id' => 'required|integer',
            'status' => 'in:pending,in_progress,completed,canceled',
        ]);
    }

    /**
     * Валидация запроса при обновлении статуса заказа
     */
    protected function validateUpdateOrderStatusRequest(Request $request)
    {
        return $request->validate([
            'id' => 'required|integer',
            'status' => 'required|in:pending,in_progress,completed,canceled',
        ]);
    }
}
