<?php

namespace App\Http\Middleware;

use App\Services\Metrics;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CollectMetrics
{
    public function __construct(private readonly Metrics $metrics) {}

    public function handle(Request $request, Closure $next): Response
    {
        $start = microtime(true);

        $response = $next($request);

        $duration = microtime(true) - $start;

        $this->metrics->incrementHttpRequests($request->method(), $response->getStatusCode());
        $this->metrics->observeRequestDuration($request->method(), $duration);

        return $response;
    }
}
