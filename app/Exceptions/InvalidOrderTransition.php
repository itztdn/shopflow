<?php

namespace App\Exceptions;

use App\Enums\OrderStatus;

class InvalidOrderTransition extends ApiException
{
    public function __construct(OrderStatus $from, OrderStatus $to)
    {
        parent::__construct(
            errorCode: 'invalid_status_transition',
            message: "Cannot transition order from '{$from->value}' to '{$to->value}'.",
            status: 422,
        );
    }
}
