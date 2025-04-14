<?php

namespace App\Services;

use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;

class OrderItemService
{
    /**
     * Получение всех блюд
     *
     * @return Collection
     */
    public function getAllOrderItems(): Collection
    {
        return OrderItem::all();
    }

    /**
     * Получение блюда по *id элемента заказа*
     */
    public function getOrderItemByItemId($orderId): Collection
    {
        $validatedItemId = $this->validatedId($orderId);

        return OrderItem::findOrFail($validatedItemId);
    }

    /**
     * Получение блюд по *id заказа*
     */
    public function getOrderItemsByOrderId($orderId): Collection
    {
        $validatedOrderId = $this->validatedOrderId($orderId);

        return OrderItem::where('order_id', $validatedOrderId)->get();
    }

    /**
     * Получение блюд по *id меню*
     */
    public function getOrderItemsByMenuId($menuId): Collection
    {
        $validatedMenuId = $this->validatedMenuId($menuId);

        return OrderItem::where('menu_id', $validatedMenuId['id'])->get();
    }

    /**
     * Создание элемента заказа
     */
    public function createOrderItem(Request $request)
    {
        $validatedData = $this->validatedRequest($request);

        // Поиск меню по ID и обработка возможной ошибки
        $menu = Menu::find($validatedData['menu_id']);
        if (!$menu) {
            return response()->json(['error' => 'Menu item not found'], 404);
        }

        // Поиск существующего элемента заказа с тем же order_id и menu_id
        $existingOrderItem = OrderItem::where('order_id', $validatedData['order_id'])
            ->where('menu_id', $validatedData['menu_id'])
            ->first();

        if ($existingOrderItem) {
            // Обновление существующего элемента заказа
            $existingOrderItem->count += $validatedData['count'];
            $existingOrderItem->price = $existingOrderItem->count * $menu->price;
            $existingOrderItem->status = 'pending';
            $existingOrderItem->save();
        } else {
            // Создание нового элемента заказа
            OrderItem::create(array_merge(
                $validatedData,
                [
                    "price" => $validatedData['count'] * $menu->price,
                ]
            ));
        }
    }

    /**
     * Обновление элемента заказа
     */
    public function updateOrderItem(Request $request)
    {
        $validatedData = $this->validatedStatus($request);
        $status = $validatedData['status'];
        $orderItem = OrderItem::findOrFail($validatedData['id']);
        $order = Order::findOrFail($orderItem->order_id);

        if ($status == 'in_progress')
        {
            $order->update([
                "status" => $validatedData['in_progress']
            ]);
        }
        else if ($status == 'cancelled')
        {
            $order->update([
                "status" => $validatedData['cancelled']
            ]);
        }

        $orderItem->update([
            "status" => $validatedData['status']
        ]);

        $items =  OrderItem::where('order_id', $orderItem->order_id)->get();
        $allCompleted = $items->every(function ($item) {
                return $item->status == 'completed';
            }
        );
        if ($allCompleted)
        {
            $order->status = 'completed';
        }

        $order->save();
        $orderItem->save();
    }

    /*
     * Удаление элемента заказа
     */
    public function deleteOrderItem($id)
    {
        $validatedId = $this->validatedId($id);
        $orderItem = OrderItem::findOrFail($validatedId);
        $orderItem->delete();
    }

    /**
     * Валидация *id* элемента заказа
     */
    protected function validatedId($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|numeric|exists:order_items,id'
        ]);

        if ($validator->fails()) {
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        return $id;
    }

    /**
     * Валидация *id* заказа
     */
    protected function validatedOrderId($orderId)
    {
        $validator = Validator::make(['order_id' => $orderId], [
            'order_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        return $orderId;
    }

    /**
     * Валидация *id* меню
     */
    protected function validatedMenuId($menu_id)
    {
        $validator = Validator::make(['order_id' => $menu_id], [
            'order_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        return $menu_id;
    }

    /**
     * Валидация запроса на создание элемента
     */
    protected function validatedRequest(Request $request)
    {
        return $request->validate([
            'order_id' => 'required|integer|exists:orders,id',
            'menu_id' => 'required|integer|exists:menus,id',
            'count' => 'required|integer|min:0',
        ]);
    }

    /**
     * Валидация статуса запроса
     */
    protected function validatedStatus(Request $request)
    {
        return $request->validate([
            'id' => 'required|integer|exists:order_items,id',
            'status' => 'required|in:pending,in_progress,completed,canceled',
            'price' => 'sometimes|numeric|min:0' // Добавляем валидацию для цены
        ]);
    }
}
