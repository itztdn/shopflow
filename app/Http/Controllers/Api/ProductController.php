<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenApi\Attributes as OA;

class ProductController extends Controller
{
    #[OA\Get(
        path: '/api/products',
        summary: 'List active products (paginated)',
        tags: ['Catalog'],
        parameters: [
            new OA\Parameter(
                name: 'per_page',
                description: 'Items per page (max 50)',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'integer', default: 15),
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Paginated list of products',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/Product'),
                        ),
                        new OA\Property(property: 'meta', ref: '#/components/schemas/PaginationMeta'),
                    ],
                ),
            ),
        ],
    )]
    public function index(Request $request): AnonymousResourceCollection
    {
        $products = Product::query()
            ->active()
            ->with('category')
            ->latest('id')
            ->paginate(perPage: min($request->integer('per_page', 15), 50));

        return ProductResource::collection($products);
    }

    #[OA\Get(
        path: '/api/products/{product}',
        summary: 'Get a single product by slug',
        tags: ['Catalog'],
        parameters: [
            new OA\Parameter(
                name: 'product',
                description: 'Product slug',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string'),
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Product details with variants',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', ref: '#/components/schemas/Product'),
                    ],
                ),
            ),
            new OA\Response(
                response: 404,
                description: 'Product not found',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse'),
            ),
        ],
    )]
    public function show(Product $product): ProductResource
    {
        $product->load(['category', 'variants' => fn($q) => $q->active()]);

        return new ProductResource($product);
    }
}
