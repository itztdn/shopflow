<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ProductVariant',
    properties: [
        new OA\Property(property: 'sku', type: 'string', example: 'SAA-478277'),
        new OA\Property(property: 'name', type: 'string', example: 'S / white'),
        new OA\Property(
            property: 'price',
            properties: [
                new OA\Property(property: 'amount', type: 'integer', example: 45517),
                new OA\Property(property: 'currency', type: 'string', example: 'USD'),
                new OA\Property(property: 'formatted', type: 'string', example: '455.17'),
            ],
            type: 'object',
        ),
        new OA\Property(property: 'in_stock', type: 'boolean', example: true),
        new OA\Property(property: 'stock', type: 'integer', example: 27),
    ],
    type: 'object',
)]

/**
 * @mixin \App\Models\ProductVariant
 */
class ProductVariantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'sku'  => $this->sku,
            'name' => $this->name,
            'price' => [
                'amount'    => $this->price,
                'currency'  => 'USD',
                'formatted' => number_format($this->price / 100, 2),
            ],

            'in_stock'   => $this->stock > 0,
            'stock'      => $this->stock,
        ];
    }
}
