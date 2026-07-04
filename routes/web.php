<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/health', function () {
    $checks = [
        'app'      => true,
        'database' => rescue(fn() => DB::connection()->getPdo() !== null, false),
        'redis'    => rescue(fn() => Redis::connection()->ping() !== null, false),
    ];

    $healthy = ! in_array(false, $checks, true);

    return response()->json(
        ['status' => $healthy ? 'ok' : 'degraded', 'checks' => $checks],
        $healthy ? 200 : 503,
    );
});
