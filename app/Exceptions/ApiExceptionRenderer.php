<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class ApiExceptionRenderer
{
    public static function render(Throwable $e): JsonResponse
    {
        return match (true) {
            $e instanceof ApiException => self::respond(
                $e->errorCode(),
                $e->getMessage(),
                $e->status(),
                $e->details()
            ),

            $e instanceof ValidationException => self::respond(
                'validation_failed',
                'The given data was invalid.',
                422,
                $e->errors()
            ),

            $e instanceof AuthenticationException => self::respond(
                'unauthenticated',
                'Authentication required.',
                401
            ),

            $e instanceof AuthorizationException => self::respond(
                'forbidden',
                'This action is not allowed.',
                403
            ),

            $e instanceof ModelNotFoundException,
            $e instanceof NotFoundHttpException => self::respond(
                'not_found',
                'Resource not found.',
                404
            ),

            $e instanceof MethodNotAllowedHttpException => self::respond(
                'method_not_allowed',
                'HTTP method is not supported for this route.',
                405
            ),

            $e instanceof ThrottleRequestsException => self::respond(
                'too_many_requests',
                'Too many requests. Please slow down.',
                429
            ),

            default => self::unexpected($e),
        };
    }

    protected static function unexpected(Throwable $e): JsonResponse
    {
        $details = config('app.debug')
            ? ['exception' => $e::class, 'message' => $e->getMessage()]
            : [];

        return self::respond('server_error', 'Internal server error.', 500, $details);
    }

    protected static function respond(
        string $code,
        string $message,
        int $status,
        array $details = [],
    ): JsonResponse {
        $payload = ['code' => $code, 'message' => $message];

        if ($details !== []) {
            $payload['details'] = $details;
        }

        return response()->json(['error' => $payload], $status);
    }
}
