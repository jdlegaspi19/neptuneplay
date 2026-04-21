<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Player;
use App\Models\User;
use App\Services\CaptchaService;
use App\Services\NeptunePlayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use OpenApi\Attributes as OA;

class RegisterController extends Controller
{
    public function __construct(
        private readonly NeptunePlayService $neptunePlayService,
        private readonly CaptchaService $captchaService,
    ) {}
 
    #[OA\Get(
        path: '/api/captcha',
        summary: 'Generate captcha code for registration',
        tags: ['Auth'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Captcha generated',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'captcha_key', type: 'string'),
                        new OA\Property(property: 'captcha_code', type: 'string', example: '42207'),
                    ]
                )
            ),
        ]
    )]
    public function captcha(): JsonResponse
    {
        $captcha = $this->captchaService->generate();
 
        return response()->json($captcha);
    }
 
    #[OA\Post(
        path: '/api/register',
        summary: 'Register a new user',
        description: 'Creates a local user account, registers on OroPlay, and creates a player record.',
        tags: ['Auth'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['username', 'email', 'password', 'password_confirmation', 'captcha_key', 'captcha_code'],
                properties: [
                    new OA\Property(property: 'username', type: 'string', example: 'player1'),
                    new OA\Property(property: 'email', type: 'string', example: 'player1@example.com'),
                    new OA\Property(property: 'password', type: 'string', example: 'secret123'),
                    new OA\Property(property: 'password_confirmation', type: 'string', example: 'secret123'),
                    new OA\Property(property: 'captcha_key', type: 'string', example: 'captcha_abc123'),
                    new OA\Property(property: 'captcha_code', type: 'string', example: '42207'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Registration successful',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Registration successful'),
                        new OA\Property(property: 'data', type: 'object',
                            properties: [
                                new OA\Property(property: 'user_code', type: 'string'),
                                new OA\Property(property: 'username', type: 'string'),
                                new OA\Property(property: 'email', type: 'string'),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function register(RegisterRequest $request): JsonResponse
    {
        // 1. Verify captcha
        $captchaKey = $request->input('captcha_key');
        $captchaCode = $request->validated('captcha_code');

        if (!$this->captchaService->verify($captchaKey, $captchaCode)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired verification code.',
                'errorCode' => 400,
            ], 422);
        }

        // 2. Generate a unique userCode (6-char random alphanumeric)
        $userCode = $this->generateUniqueUserCode();

        // 3. Use DB transaction to ensure consistency
        try {
            $user = DB::transaction(function () use ($request, $userCode) {
                // 3a. Create local user
                $user = User::create([
                    'name'      => $request->validated('username'),
                    'user_code' => $userCode,
                    'email'     => $request->validated('email'),
                    'password'  => $request->validated('password'),
                ]);

                // 3b. Call OroPlay API to create user
                $result = $this->neptunePlayService->createUser($userCode);

                if (!($result['success'] ?? false)) {
                    $errorCode = $result['errorCode'] ?? 500;
                    // If user already exists on OroPlay, that's OK (idempotent)
                    if ($errorCode !== 1) {
                        throw new \RuntimeException(
                            'Failed to create user on OroPlay: ' . ($result['message'] ?? 'Unknown error')
                        );
                    }
                }

                // 3c. Create local player record
                Player::create([
                    'user_code' => $userCode,
                    'balance'   => 0,
                    'currency'  => 'USD',
                    'is_active' => true,
                ]);

                return $user;
            });

            return response()->json([
                'success' => true,
                'message' => 'Registration successful',
                'data' => [
                    'user_code' => $user->user_code,
                    'username'  => $user->name,
                    'email'     => $user->email,
                ],
            ]);

        } catch (\RuntimeException $e) {
            Log::error('Registration failed (OroPlay)', [
                'username' => $request->validated('username'),
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Registration failed. Please try again later.',
                'errorCode' => 500,
            ], 500);
        }
    }

    /**
     * Generate a unique 6-character alphanumeric user code.
     */
    private function generateUniqueUserCode(): string
    {
        do {
            $code = Str::random(6);
        } while (Player::where('user_code', $code)->exists());

        return $code;
    }
}
