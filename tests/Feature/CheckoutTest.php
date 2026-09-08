<?php

use App\Events\OrderPlaced;
use App\Models\Order;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Support\Facades\Event;

it('creates an order and decrements stock', function () {
    Event::fake();

    $user = User::factory()->create();
    $variant = ProductVariant::factory()->create(['stock' => 10, 'price' => 5000]);
    $token = auth('api')->login($user);

    $response = $this->withHeader('Authorization', "Bearer {$token}")
        ->postJson('/api/orders', [
            'items' => [['sku' => $variant->sku, 'quantity' => 3]],
        ]);

    $response->assertStatus(201)
        ->assertJsonPath('data.status', 'pending')
        ->assertJsonPath('data.total.amount', 15000);

    expect($variant->fresh()->stock)->toBe(7);

    $this->assertDatabaseHas('orders', ['user_id' => $user->id, 'total' => 15000]);
    $this->assertDatabaseHas('order_items', ['sku' => $variant->sku, 'quantity' => 3]);

    Event::assertDispatched(OrderPlaced::class);
});

it('rejects checkout when stock is insufficient', function () {
    Event::fake();

    $user = User::factory()->create();
    $variant = ProductVariant::factory()->create(['stock' => 2]);
    $token = auth('api')->login($user);

    $response = $this->withHeader('Authorization', "Bearer {$token}")
        ->postJson('/api/orders', [
            'items' => [['sku' => $variant->sku, 'quantity' => 5]],
        ]);

    $response->assertStatus(422)
        ->assertJsonPath('error.code', 'insufficient_stock');

    expect(Order::count())->toBe(0);
    expect($variant->fresh()->stock)->toBe(2);

    Event::assertNotDispatched(OrderPlaced::class);
});

it('requires authentication', function () {
    $variant = ProductVariant::factory()->create();

    $this->postJson('/api/orders', [
        'items' => [['sku' => $variant->sku, 'quantity' => 1]],
    ])->assertStatus(401)
        ->assertJsonPath('error.code', 'unauthenticated');
});
