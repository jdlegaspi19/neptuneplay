<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\CreateTokenRequest;
use App\Models\User;
use App\Services\NeptunePlayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use OpenApi\Attributes as OA;

class AuthController extends Controller
{
    public function __construct(
        private readonly NeptunePlayService $neptunePlayService,
    ) {}

    #[OA\Post(
        path: '/api/auth/createtoken',
        summary: 'Create authentication token',
        description: 'Authenticate user with username and password, then create OroPlay token',
        tags: ['Auth'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['username', 'password'],
                properties: [
                    new OA\Property(property: 'username', type: 'string', example: 'player1'),
                    new OA\Property(property: 'password', type: 'string', example: 'secret123'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Token created successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'token', type: 'string', example: 'eyJhbGciOiJIUzI1NiIs...'),
                        new OA\Property(property: 'expiration', type: 'integer', example: 1712577600),
                        new OA\Property(property: 'user_code', type: 'string', example: 'USER123'),
                        new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
                        new OA\Property(property: 'email', type: 'string', example: 'john@example.com'),
                        new OA\Property(property: 'errorCode', type: 'integer', example: 0),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Invalid credentials'),
        ]
    )]
    public function createToken(CreateTokenRequest $request): JsonResponse
    {
        
        $validated = $request->validated();

        // Find user by username
        $user = User::where('name', $validated['username'])->first();

        // Verify password
        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid username or password.',
                'errorCode' => 401,
            ], 401);
        }

        // Get OroPlay credentials from .env and create token
        $result = $this->neptunePlayService->createToken(
            config('services.neptuneplay.client_id'),
            config('services.neptuneplay.client_secret'),
        );

        // Add user details to response
        $result['user_code'] = $user->user_code;
        $result['name'] = $user->name;
        $result['email'] = $user->email;

        return response()->json($result);
    }
}
