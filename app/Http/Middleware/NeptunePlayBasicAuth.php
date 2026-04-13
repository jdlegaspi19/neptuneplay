<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NeptunePlayBasicAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $username = config('services.neptuneplay.callback_username');
        $password = config('services.neptuneplay.callback_password');

        if ($request->getUser() !== $username || $request->getPassword() !== $password) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
                'errorCode' => 401,
            ], 401, ['WWW-Authenticate' => 'Basic']);
        }

        return $next($request);
    }
}
