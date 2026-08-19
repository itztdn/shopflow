<?php

namespace App\Services;

use App\Events\OrderPlaced;
use App\Exceptions\ApiException;
use App\Models\Order;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class OrderService
{
    /**
     * @param  array<int, array{sku: string, quantity: int}>  $items
     */
    public function checkout(User $user, array $items): Order
    {
        $order = DB::transaction(function () use ($user, $items) {
            $order = Order::create([
                'user_id' => $user->id,
                'total'   => 0,
            ]);

            $total = 0;

            foreach ($items as $line) {
                $variant = ProductVariant::query()
                    ->where('sku', $line['sku'])
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($variant->stock < $line['quantity']) {
                    throw new ApiException(
                        errorCode: 'insufficient_stock',
                        message: "Not enough stock for SKU {$variant->sku}.",
                        status: 422,
                        details: ['sku' => $variant->sku, 'available' => $variant->stock],
                    );
                }

                $variant->decrement('stock', $line['quantity']);

                $order->items()->create([
                    'product_variant_id' => $variant->id,
                    'sku'                => $variant->sku,
                    'name'               => $variant->name,
                    'unit_price'         => $variant->price,
                    'quantity'           => $line['quantity'],
                ]);

                $total += $variant->price * $line['quantity'];
            }

            $order->update(['total' => $total]);

            return $order->load('items');
        });

        OrderPlaced::dispatch($order->id);

        return $order;
    }
}
