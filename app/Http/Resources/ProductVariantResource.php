<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
