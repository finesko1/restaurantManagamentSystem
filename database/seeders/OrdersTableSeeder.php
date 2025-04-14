<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Создание 10 заказов
        for ($i = 0; $i < 10; $i++) {
            $order = Order::factory()->create(); // Создаем заказ

            // Создание 3 элементов заказа для каждого заказа
            OrderItem::factory()->count(3)->create(['order_id' => $order->id]);
        }
    }
}
