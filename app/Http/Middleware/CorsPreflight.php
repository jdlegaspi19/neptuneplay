<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CorsPreflight
{
    /**
     * Allowed origins for CORS requests
     */
    protected array $allowedOrigins = [
        'http://localhost:80',
        'http://localhost:8080',
        'http://localhost:8082',
        'http://localhost:3000',
        'http://localhost:5173',
        'http://localhost:5174',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $origin = $request->headers->get('Origin');

        // Check if origin is allowed
        $isAllowedOrigin = $this->isOriginAllowed($origin);

        // Handle preflight requests
        if ($request->isMethod('OPTIONS')) {
            return $this->handlePreflightRequest($origin, $isAllowedOrigin);
        }

        // Get response from next middleware
        $response = $next($request);

        // Add CORS headers to actual request
        if ($isAllowedOrigin && $origin) {
            $response->headers->set('Access-Control-Allow-Origin', $origin);
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
            $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
            $response->headers->set('Access-Control-Expose-Headers', 'Content-Length, X-JSON-Response');
            $response->headers->set('Access-Control-Max-Age', '86400');
        }

        return $response;
    }

    /**
     * Check if the given origin is allowed
     */
    protected function isOriginAllowed(?string $origin): bool
    {
        if (!$origin) {
            return false;
        }

        // Check against allowed origins list
        if (in_array($origin, $this->allowedOrigins)) {
            return true;
        }

        // Allow env-based origins
        $allowedFromEnv = env('CORS_ALLOWED_ORIGINS', '');
        if ($allowedFromEnv) {
            $origins = array_map('trim', explode(',', $allowedFromEnv));
            if (in_array($origin, $origins)) {
                return true;
            }
        }

        // Allow same origin
        $appUrl = config('app.url');
        if ($origin === $appUrl) {
            return true;
        }

        return false;
    }

    /**
     * Handle preflight (OPTIONS) requests
     */
    protected function handlePreflightRequest(?string $origin, bool $isAllowedOrigin): Response
    {
        $headers = [
            'Access-Control-Allow-Methods' => 'GET, POST, PUT, PATCH, DELETE, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-Requested-With',
            'Access-Control-Max-Age' => '86400',
        ];

        if ($isAllowedOrigin && $origin) {
            $headers['Access-Control-Allow-Origin'] = $origin;
            $headers['Access-Control-Allow-Credentials'] = 'true';
        }

        return response('', 204)->withHeaders($headers);
    }
}
