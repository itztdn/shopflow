<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'ShopFlow API',
    description: 'Headless e-commerce order processing API.',
)]
#[OA\Server(
    url: 'http://localhost:8080',
    description: 'Local development',
)]
#[OA\SecurityScheme(
    securityScheme: 'bearerAuth',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'JWT',
)]
abstract class Controller
{
    //
}
