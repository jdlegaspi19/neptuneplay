<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\BalanceHistoryRequest;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\DepositRequest;
use App\Http\Requests\User\UserBalanceRequest;
use App\Http\Requests\User\WithdrawAllRequest;
use App\Http\Requests\User\WithdrawRequest;
use App\Services\NeptunePlayService;
use App\Models\Player;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class UserController extends Controller
{
    public function __construct(
        private readonly NeptunePlayService $neptunePlayService,
    ) {}

    #[OA\Get(
        path: '/api/users/list',
        summary: 'Get list of all users',
        tags: ['User'],
        parameters: [
            new OA\Parameter(name: 'page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', default: 1)),
            new OA\Parameter(name: 'per_page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', default: 15)),
            new OA\Parameter(name: 'user_code', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Users list retrieved',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'current_page', type: 'integer', example: 1),
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: 'id', type: 'integer'),
                                    new OA\Property(property: 'user_code', type: 'string'),
                                    new OA\Property(property: 'balance', type: 'number'),
                                    new OA\Property(property: 'currency', type: 'string'),
                                    new OA\Property(property: 'is_active', type: 'boolean'),
                                ]
                            )
                        ),
                        new OA\Property(property: 'total', type: 'integer'),
                    ]
                )
            ),
        ]
    )]
    public function list(Request $request): JsonResponse
    {
        $query = Player::query();

        if ($request->has('user_code')) {
            $query->where('user_code', 'like', '%' . $request->query('user_code') . '%');
        }

        return response()->json($query->orderBy('id', 'desc')->paginate($request->query('per_page', 15)));
    }

    #[OA\Post(
        path: '/api/user/create',
        summary: 'Create a new user (Transfer API)',
        tags: ['User'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['userCode'],
                properties: [
                    new OA\Property(property: 'userCode', type: 'string', example: 'user123'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'User created',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string'),
                        new OA\Property(property: 'errorCode', type: 'integer', example: 0),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthorized'),
        ]
    )]
    public function create(CreateUserRequest $request): JsonResponse
    {
        $result = $this->neptunePlayService->createUser(
            $request->validated('userCode'),
        );

        return response()->json($result);
    }

    #[OA\Post(
        path: '/api/user/balance',
        summary: 'Get user balance (Transfer API)',
        tags: ['User'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['userCode'],
                properties: [
                    new OA\Property(property: 'userCode', type: 'string', example: 'user123'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'User balance retrieved',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'number', example: 1000.00),
                        new OA\Property(property: 'errorCode', type: 'integer', example: 0),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthorized'),
        ]
    )]
    public function balance(UserBalanceRequest $request): JsonResponse
    {
        $result = $this->neptunePlayService->userBalance(
            $request->validated('userCode'),
        );

        return response()->json($result);
    }

    #[OA\Post(
        path: '/api/user/deposit',
        summary: 'Deposit to user balance (Transfer API)',
        tags: ['User'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['userCode', 'balance'],
                properties: [
                    new OA\Property(property: 'userCode', type: 'string', example: 'user123'),
                    new OA\Property(property: 'balance', type: 'number', example: 500.00),
                    new OA\Property(property: 'orderNo', type: 'string', example: 'ORD-001', nullable: true),
                    new OA\Property(property: 'vendorCode', type: 'string', example: 'PGS', nullable: true),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Deposit successful',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'number', example: 1500.00),
                        new OA\Property(property: 'errorCode', type: 'integer', example: 0),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthorized'),
        ]
    )]
    public function deposit(DepositRequest $request): JsonResponse
    {
        $result = $this->neptunePlayService->deposit(
            $request->validated('userCode'),
            $request->validated('balance'),
            $request->validated('orderNo'),
            $request->validated('vendorCode'),
        );

        return response()->json($result);
    }

    #[OA\Post(
        path: '/api/user/withdraw',
        summary: 'Withdraw from user balance (Transfer API)',
        tags: ['User'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['userCode', 'balance'],
                properties: [
                    new OA\Property(property: 'userCode', type: 'string', example: 'user123'),
                    new OA\Property(property: 'balance', type: 'number', example: 200.00),
                    new OA\Property(property: 'orderNo', type: 'string', example: 'ORD-002', nullable: true),
                    new OA\Property(property: 'vendorCode', type: 'string', example: 'PGS', nullable: true),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Withdrawal successful',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'number', example: 800.00),
                        new OA\Property(property: 'errorCode', type: 'integer', example: 0),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthorized'),
        ]
    )]
    public function withdraw(WithdrawRequest $request): JsonResponse
    {
        $result = $this->neptunePlayService->withdraw(
            $request->validated('userCode'),
            $request->validated('balance'),
            $request->validated('orderNo'),
            $request->validated('vendorCode'),
        );

        return response()->json($result);
    }

    #[OA\Post(
        path: '/api/user/withdraw-all',
        summary: 'Withdraw all user balance (Transfer API)',
        tags: ['User'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['userCode'],
                properties: [
                    new OA\Property(property: 'userCode', type: 'string', example: 'user123'),
                    new OA\Property(property: 'vendorCode', type: 'string', example: 'PGS', nullable: true),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'All balance withdrawn',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'number', example: 1000.00),
                        new OA\Property(property: 'errorCode', type: 'integer', example: 0),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthorized'),
        ]
    )]
    public function withdrawAll(WithdrawAllRequest $request): JsonResponse
    {
        $result = $this->neptunePlayService->withdrawAll(
            $request->validated('userCode'),
            $request->validated('vendorCode'),
        );

        return response()->json($result);
    }

    #[OA\Post(
        path: '/api/user/balance-history',
        summary: 'Get user balance history by order number',
        tags: ['User'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['orderNo'],
                properties: [
                    new OA\Property(property: 'orderNo', type: 'string', example: 'ORD-001'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Balance history retrieved',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(
                            property: 'message',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'id', type: 'integer'),
                                new OA\Property(property: 'userCode', type: 'string'),
                                new OA\Property(property: 'amount', type: 'number'),
                                new OA\Property(property: 'type', type: 'integer', description: '1=deposit, 2=withdraw'),
                                new OA\Property(property: 'agentBeforeBalance', type: 'number'),
                                new OA\Property(property: 'userBeforeBalance', type: 'number'),
                            ]
                        ),
                        new OA\Property(property: 'errorCode', type: 'integer', example: 0),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthorized'),
        ]
    )]
    public function balanceHistory(BalanceHistoryRequest $request): JsonResponse
    {
        $result = $this->neptunePlayService->balanceHistory(
            $request->validated('orderNo'),
        );

        return response()->json($result);
    }
}
