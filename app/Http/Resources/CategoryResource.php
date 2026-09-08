<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

/**
 * @mixin \App\Models\Category
 */
#[OA\Schema(
    schema: 'Category',
    properties: [
        new OA\Property(property: 'slug', type: 'string', example: 't-shirts'),
        new OA\Property(property: 'name', type: 'string', example: 'T-Shirts'),
        new OA\Property(property: 'description', type: 'string', nullable: true),
        new OA\Property(
            property: 'children',
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/Category'),
        ),
    ],
    type: 'object',
)]

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'slug'        => $this->slug,
            'name'        => $this->name,
            'description' => $this->description,
            'children'    => CategoryResource::collection($this->whenLoaded('children')),
        ];
    }
}
