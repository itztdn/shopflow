<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\OrderItem
 */
class OrderItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'sku'        => $this->sku,
            'name'       => $this->name,
            'quantity'   => $this->quantity,
            'unit_price' => [
                'amount'    => $this->unit_price,
                'currency'  => 'USD',
                'formatted' => number_format($this->unit_price / 100, 2),
            ],
        ];
    }
}
