<?php

namespace App\Exceptions;

use Exception;

/**
 * Базовое исключение для ожидаемых ошибок API.
 */
class ApiException extends Exception
{
    public function __construct(
        protected string $errorCode,
        string $message,
        protected int $status = 400,
        protected array $details = [],
    ) {
        parent::__construct($message);
    }

    public function errorCode(): string
    {
        return $this->errorCode;
    }

    public function status(): int
    {
        return $this->status;
    }

    public function details(): array
    {
        return $this->details;
    }
}
