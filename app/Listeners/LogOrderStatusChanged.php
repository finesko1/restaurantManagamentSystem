<?php

namespace App\Listeners;

use App\Events\OrderStatusChanged;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogOrderStatusChanged implements ShouldQueue
{
    public $tries = 3;       // Количество попыток
    public $maxExceptions = 2; // Максимум исключений
    public $backoff = 60;    // Задержка между попытками в секундах
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderStatusChanged $event): void
    {
        Log::info("Order status changed: #{$event->order->id}", [
            'old_status' => $event->oldStatus,
            'new_status' => $event->newStatus
        ]);
    }
}
