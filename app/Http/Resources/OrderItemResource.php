<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

/**
 * @mixin \App\Models\OrderItem
 */
#[OA\Schema(
    schema: 'OrderItem',
    properties: [
        new OA\Property(property: 'sku', type: 'string', example: 'TEE-BLK-M'),
        new OA\Property(property: 'name', type: 'string', example: 'Basic Tee — Black / M'),
        new OA\Property(property: 'quantity', type: 'integer', example: 2),
        new OA\Property(
            property: 'unit_price',
            properties: [
                new OA\Property(property: 'amount', description: 'Unit price in minor units', type: 'integer', example: 2495),
                new OA\Property(property: 'currency', type: 'string', example: 'USD'),
                new OA\Property(property: 'formatted', type: 'string', example: '24.95'),
            ],
            type: 'object',
        ),
    ],
    type: 'object',
)]

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
