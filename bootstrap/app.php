<?php

use App\Enums\NeptunePlayErrorCode;
use App\Exceptions\NeptunePlayApiException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'neptuneplay.basic' => \App\Http\Middleware\NeptunePlayBasicAuth::class,
            'force.json' => \App\Http\Middleware\ForceJsonResponse::class,
        ]);

        $middleware->api(prepend: [
            \App\Http\Middleware\CorsPreflight::class,
            \App\Http\Middleware\ForceJsonResponse::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (NeptunePlayApiException $e, Request $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                $httpStatus = match ($e->errorCode) {
                    NeptunePlayErrorCode::UNAUTHORIZED => 401,
                    NeptunePlayErrorCode::BAD_REQUEST => 400,
                    NeptunePlayErrorCode::VENDOR_UNDER_MAINTENANCE,
                    NeptunePlayErrorCode::GAME_UNDER_MAINTENANCE => 503,
                    NeptunePlayErrorCode::UNKNOWN_SERVER_ERROR => 500,
                    default => 422,
                };

                return response()->json([
                    'success' => false,
                    'message' => $e->errorCode->message(),
                    'errorCode' => $e->errorCode->value,
                ], $httpStatus);
            }
        });
    })->create();
