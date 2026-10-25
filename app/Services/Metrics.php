<?php

namespace App\Services;

use Prometheus\CollectorRegistry;
use Prometheus\Storage\Redis as RedisStorage;

class Metrics
{
    private CollectorRegistry $registry;

    public function __construct()
    {
        RedisStorage::setDefaultOptions([
            'host' => config('database.redis.default.host'),
            'port' => (int) config('database.redis.default.port'),
            'database' => 2,
        ]);

        $this->registry = new CollectorRegistry(new RedisStorage);
    }

    public function registry(): CollectorRegistry
    {
        return $this->registry;
    }

    public function incrementHttpRequests(string $method, int $status): void
    {
        $counter = $this->registry->getOrRegisterCounter(
            'shopflow',
            'http_requests_total',
            'Total HTTP requests',
            ['method', 'status'],
        );
        $counter->inc([$method, (string) $status]);
    }

    public function observeRequestDuration(string $method, float $seconds): void
    {
        $histogram = $this->registry->getOrRegisterHistogram(
            'shopflow',
            'http_request_duration_seconds',
            'HTTP request duration',
            ['method'],
            [0.01, 0.05, 0.1, 0.25, 0.5, 1.0, 2.5, 5.0],
        );
        $histogram->observe($seconds, [$method]);
    }
}
