<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CheckoutRequest;
use App\Http\Resources\OrderResource;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderService $orders,
    ) {}

    #[OA\Post(
        path: '/api/orders',
        summary: 'Check out the given items and create an order',
        tags: ['Orders'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'Idempotency-Key',
                description: 'Optional unique key to make checkout idempotent across retries. '
                    .'A repeated request with the same key returns the stored response '
                    .'with an Idempotent-Replay: true header instead of creating a second order.',
                in: 'header',
                required: false,
                schema: new OA\Schema(type: 'string'),
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['items'],
                properties: [
                    new OA\Property(
                        property: 'items',
                        type: 'array',
                        items: new OA\Items(
                            required: ['sku', 'quantity'],
                            properties: [
                                new OA\Property(property: 'sku', type: 'string', example: 'TEE-BLK-M'),
                                new OA\Property(property: 'quantity', type: 'integer', minimum: 1, maximum: 100, example: 2),
                            ],
                            type: 'object',
                        ),
                        minItems: 1,
                    ),
                ],
            ),
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Order created',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', ref: '#/components/schemas/Order'),
                    ],
                ),
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthenticated',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse'),
            ),
            new OA\Response(
                response: 409,
                description: 'A request with the same Idempotency-Key is still being processed',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse'),
            ),
            new OA\Response(
                response: 422,
                description: 'Validation failed or insufficient stock',
                content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse'),
            ),
        ],
    )]
    public function store(CheckoutRequest $request): JsonResponse
    {
        $order = $this->orders->checkout(
            user: $request->user(),
            items: $request->validated('items'),
        );

        return (new OrderResource($order))->response()->setStatusCode(201);
    }
}
