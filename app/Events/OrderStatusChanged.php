<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderStatusChanged implements ShouldQueue
{
    public $tries = 3;       // Количество попыток
    public $maxExceptions = 2; // Максимум исключений
    public $backoff = 60;    // Задержка между попытками в секундах
    /**
     * Create a new event instance.
     */
    public function __construct(
        public Order $order,
        public string $oldStatus,
        public string $newStatus
    )
    {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
