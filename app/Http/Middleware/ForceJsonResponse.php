<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Проставляет Accept: application/json всем API-запросам.
 *
 * Нужен, потому, что часть логики фреймворка (например, редирект гостя
 * в auth-middleware) ветвится по $request->expectsJson() до того, как
 * дело дойдёт до обработчика исключений. Объявляем формат на входе.
 */
class ForceJsonResponse
{
    public function handle(Request $request, Closure $next): Response
    {
        $request->headers->set('Accept', 'application/json');

        return $next($request);
    }
}
