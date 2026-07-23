<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $products = Product::query()
            ->active()
            ->with('category')
            ->latest('id')
            ->paginate(perPage: min($request->integer('per_page', 15), 50));

        return ProductResource::collection($products);
    }

    public function show(Product $product): ProductResource
    {
        $product->load(['category', 'variants' => fn($q) => $q->active()]);

        return new ProductResource($product);
    }
}
