<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    title: 'NeptunePlay Integration API',
    version: '2.0',
    description: 'Proxy endpoints for NeptunePlay integration and Seamless Wallet callbacks'
)]
#[OA\Server(url: "https://gamingsite.beesites.net/neptuneplay-backend", description: "Production API Server")]
#[OA\SecurityScheme(
    securityScheme: 'bearerAuth',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'JWT',
    description: 'Bearer token obtained from /api/v2/auth/createtoken'
)]
#[OA\SecurityScheme(
    securityScheme: 'basicAuth',
    type: 'http',
    scheme: 'basic',
    description: 'Basic Auth for Seamless Wallet callbacks'
)]
abstract class Controller
{
}
