<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Order',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 42),
        new OA\Property(
            property: 'status',
            type: 'string',
            enum: ['pending', 'paid', 'shipped', 'delivered', 'cancelled'],
            example: 'pending',
        ),
        new OA\Property(
            property: 'total',
            properties: [
                new OA\Property(property: 'amount', description: 'Total in minor units', type: 'integer', example: 4990),
                new OA\Property(property: 'currency', type: 'string', example: 'USD'),
                new OA\Property(property: 'formatted', type: 'string', example: '49.90'),
            ],
            type: 'object',
        ),
        new OA\Property(
            property: 'items',
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/OrderItem'),
        ),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
    ],
    type: 'object',
)]

/**
 * @mixin \App\Models\Order
 */
class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'status'     => $this->status->value,
            'total'      => [
                'amount'    => $this->total,
                'currency'  => 'USD',
                'formatted' => number_format($this->total / 100, 2),
            ],
            'items'      => OrderItemResource::collection($this->whenLoaded('items')),
            'created_at' => $this->created_at,
        ];
    }
}
