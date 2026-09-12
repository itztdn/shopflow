<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SendOrderConfirmation implements ShouldQueue
{
    public int $tries = 3;

    public array $backoff = [10, 30, 60];

    public function handle(OrderPlaced $event): void
    {
        $order = Order::with('items')->find($event->orderId);

        if (! $order) {
            return;
        }

        Log::info('Order confirmation sent', [
            'order_id' => $order->id,
            'total' => $order->total,
            'items' => $order->items->count(),
        ]);
    }
}
