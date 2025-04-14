<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class LogOrderCreated implements ShouldQueue
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
    public function handle(OrderCreated $event): void
    {
        Log::info("New order created: #{$event->order->id}", [
            'table_id' => $event->order->table_id,
            'status' => $event->order->status
        ]);
    }
}
