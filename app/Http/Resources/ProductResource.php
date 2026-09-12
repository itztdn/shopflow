<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

/**
 * @mixin Product
 */
#[OA\Schema(
    schema: 'Product',
    properties: [
        new OA\Property(property: 'slug', type: 'string', example: 'basic-tee'),
        new OA\Property(property: 'name', type: 'string', example: 'Basic Tee'),
        new OA\Property(property: 'description', type: 'string', nullable: true),
        new OA\Property(property: 'category', ref: '#/components/schemas/Category'),
        new OA\Property(
            property: 'variants',
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/ProductVariant'),
        ),
    ],
    type: 'object',
)]

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'slug' => $this->slug,
            'name' => $this->name,
            'description' => $this->description,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'variants' => ProductVariantResource::collection($this->whenLoaded('variants')),
        ];
    }
}
