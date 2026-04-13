<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Requests\Call\CallHistoriesRequest;
use App\Http\Requests\Call\CancelCallRequest;
use App\Http\Requests\Call\SendCallRequest;
use App\Services\NeptunePlayService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class CallController extends Controller
{
    public function __construct(
        private readonly NeptunePlayService $neptunePlayService,
    ) {}

    #[OA\Get(
        path: '/api/call/active-users',
        summary: 'Get list of active users currently playing',
        tags: ['Call'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Active users list',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(
                            property: 'message',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: 'vendorCode', type: 'string'),
                                    new OA\Property(property: 'vendorName', type: 'string'),
                                    new OA\Property(property: 'userCode', type: 'string'),
                                    new OA\Property(property: 'gameCode', type: 'string'),
                                    new OA\Property(property: 'gameName', type: 'string'),
                                    new OA\Property(property: 'connectDateTime', type: 'integer'),
                                ]
                            )
                        ),
                        new OA\Property(property: 'errorCode', type: 'integer', example: 0),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthorized'),
        ]
    )]
    public function activeUsers(): JsonResponse
    {
        $result = $this->neptunePlayService->activeUsers();

        return response()->json($result);
    }

    #[OA\Post(
        path: '/api/call/send',
        summary: 'Send a call to an active user',
        tags: ['Call'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['vendorCode', 'gameCode', 'userCode', 'amount', 'type'],
                properties: [
                    new OA\Property(property: 'vendorCode', type: 'string', example: 'PGS'),
                    new OA\Property(property: 'gameCode', type: 'string', example: 'fortune-tiger'),
                    new OA\Property(property: 'userCode', type: 'string', example: 'user123'),
                    new OA\Property(property: 'amount', type: 'number', example: 100.00),
                    new OA\Property(property: 'type', type: 'integer', example: 1),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Call sent successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'integer', example: 456),
                        new OA\Property(property: 'errorCode', type: 'integer', example: 0),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthorized'),
        ]
    )]
    public function send(SendCallRequest $request): JsonResponse
    {
        $result = $this->neptunePlayService->sendCall(
            $request->validated('vendorCode'),
            $request->validated('gameCode'),
            $request->validated('userCode'),
            $request->validated('amount'),
            $request->validated('type'),
        );

        return response()->json($result);
    }

    #[OA\Post(
        path: '/api/call/cancel',
        summary: 'Cancel a call',
        tags: ['Call'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['vendorCode', 'gameCode', 'userCode', 'callId'],
                properties: [
                    new OA\Property(property: 'vendorCode', type: 'string', example: 'PGS'),
                    new OA\Property(property: 'gameCode', type: 'string', example: 'fortune-tiger'),
                    new OA\Property(property: 'userCode', type: 'string', example: 'user123'),
                    new OA\Property(property: 'callId', type: 'integer', example: 456),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Call cancelled',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'errorCode', type: 'integer', example: 0),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthorized'),
        ]
    )]
    public function cancel(CancelCallRequest $request): JsonResponse
    {
        $result = $this->neptunePlayService->cancelCall(
            $request->validated('vendorCode'),
            $request->validated('gameCode'),
            $request->validated('userCode'),
            $request->validated('callId'),
        );

        return response()->json($result);
    }

    #[OA\Post(
        path: '/api/call/histories',
        summary: 'Get call histories',
        tags: ['Call'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['pageIndex', 'pageSize'],
                properties: [
                    new OA\Property(property: 'pageIndex', type: 'integer', example: 0),
                    new OA\Property(property: 'pageSize', type: 'integer', example: 20, maximum: 100),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Call histories retrieved',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(
                            property: 'message',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: 'id', type: 'integer'),
                                    new OA\Property(property: 'callId', type: 'integer'),
                                    new OA\Property(property: 'userCode', type: 'string'),
                                    new OA\Property(property: 'vendorCode', type: 'string'),
                                    new OA\Property(property: 'gameCode', type: 'string'),
                                    new OA\Property(property: 'gameName', type: 'string'),
                                    new OA\Property(property: 'typeName', type: 'string'),
                                    new OA\Property(property: 'statusName', type: 'string'),
                                    new OA\Property(property: 'callAmount', type: 'number'),
                                ]
                            )
                        ),
                        new OA\Property(property: 'errorCode', type: 'integer', example: 0),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthorized'),
        ]
    )]
    public function histories(CallHistoriesRequest $request): JsonResponse
    {
        $result = $this->neptunePlayService->callHistories(
            $request->validated('pageIndex'),
            $request->validated('pageSize'),
        );

        return response()->json($result);
    }
}
