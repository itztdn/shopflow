<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

/**
 * Идемпотентность для небезопасных операций (POST).
 *
 * Клиент присылает заголовок Idempotency-Key. Первый запрос с данным ключом
 * выполняется и его ответ кэшируется. Повторы с тем же ключом получают
 * сохранённый ответ, не выполняя операцию заново.
 */
class Idempotency
{
    private const TTL_SECONDS = 86400;

    public function handle(Request $request, Closure $next): Response
    {
        $key = $request->header('Idempotency-Key');

        if (! $key) {
            return $next($request);
        }

        $cacheKey = "idempotency:{$request->user()?->id}:{$key}";

        if ($cached = Cache::get($cacheKey)) {
            return response($cached['body'], $cached['status'])
                ->header('Content-Type', 'application/json')
                ->header('Idempotent-Replay', 'true');
        }

        $lock = Cache::lock("{$cacheKey}:lock", 10);

        if (! $lock->get()) {
            return response()->json([
                'error' => [
                    'code'    => 'idempotency_conflict',
                    'message' => 'A request with this key is already being processed.',
                ],
            ], 409);
        }

        try {
            $response = $next($request);

            if ($response->getStatusCode() < 300) {
                Cache::put($cacheKey, [
                    'status' => $response->getStatusCode(),
                    'body'   => $response->getContent(),
                ], self::TTL_SECONDS);
            }

            return $response;
        } finally {
            $lock->release();
        }
    }
}
