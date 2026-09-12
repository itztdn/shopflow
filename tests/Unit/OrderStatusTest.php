<?php

use App\Enums\OrderStatus;

describe('OrderStatus transitions', function () {

    it('allows valid forward transitions', function () {
        expect(OrderStatus::Pending->canTransitionTo(OrderStatus::Paid))->toBeTrue();
        expect(OrderStatus::Paid->canTransitionTo(OrderStatus::Shipped))->toBeTrue();
        expect(OrderStatus::Shipped->canTransitionTo(OrderStatus::Delivered))->toBeTrue();
    });

    it('forbids skipping steps', function () {
        expect(OrderStatus::Pending->canTransitionTo(OrderStatus::Shipped))->toBeFalse();
    });

    it('forbids leaving terminal states', function () {
        expect(OrderStatus::Delivered->canTransitionTo(OrderStatus::Pending))->toBeFalse();
        expect(OrderStatus::Cancelled->canTransitionTo(OrderStatus::Paid))->toBeFalse();
        expect(OrderStatus::Delivered->isTerminal())->toBeTrue();
    });

    it('allows cancellation only before shipping', function (OrderStatus $from, bool $canCancel) {
        expect($from->canTransitionTo(OrderStatus::Cancelled))->toBe($canCancel);
    })->with([
        'from pending' => [OrderStatus::Pending, true],
        'from paid' => [OrderStatus::Paid, true],
        'from shipped' => [OrderStatus::Shipped, false],
        'from delivered' => [OrderStatus::Delivered, false],
    ]);
});
