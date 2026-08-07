<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CheckoutRequest;
use App\Http\Resources\OrderResource;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderService $orders,
    ) {}

    public function store(CheckoutRequest $request): JsonResponse
    {
        $order = $this->orders->checkout(
            user: $request->user(),
            items: $request->validated('items'),
        );

        return (new OrderResource($order))->response()->setStatusCode(201);
    }
}
