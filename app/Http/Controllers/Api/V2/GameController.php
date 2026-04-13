<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Requests\Game\BatchRtpRequest;
use App\Http\Requests\Game\GameDetailRequest;
use App\Http\Requests\Game\GameListRequest;
use App\Http\Requests\Game\GetUserRtpRequest;
use App\Http\Requests\Game\LaunchUrlRequest;
use App\Http\Requests\Game\ResetAllUsersRtpRequest;
use App\Http\Requests\Game\SetUserRtpRequest;
use App\Services\NeptunePlayService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class GameController extends Controller
{
    public function __construct(
        private readonly NeptunePlayService $neptunePlayService,
    ) {}

    #[OA\Post(
        path: '/api/games/list',
        summary: 'Get game list from a vendor (POST)',
        tags: ['Games'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['vendorCode'],
                properties: [
                    new OA\Property(property: 'vendorCode', type: 'string', example: 'PGS'),
                    new OA\Property(property: 'language', type: 'string', example: 'en'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Game list retrieved',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(
                            property: 'message',
                            type: 'array',
                            items: new OA\Items(type: 'object')
                        ),
                        new OA\Property(property: 'errorCode', type: 'integer', example: 0),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthorized'),
            new OA\Response(response: 503, description: 'Vendor or game under maintenance'),
        ]
    )]
    public function listPost(GameListRequest $request): JsonResponse
    {
        $result = $this->neptunePlayService->gameList(
            $request->validated('vendorCode'),
            $request->validated('language', 'en'),
        );

        return response()->json($result);
    }

    #[OA\Post(
        path: '/api/game/detail',
        summary: 'Get game detail',
        tags: ['Games'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['vendorCode', 'gameCode'],
                properties: [
                    new OA\Property(property: 'vendorCode', type: 'string', example: 'PGS'),
                    new OA\Property(property: 'gameCode', type: 'string', example: 'fortune-tiger'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Game detail retrieved',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'object'),
                        new OA\Property(property: 'errorCode', type: 'integer', example: 0),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthorized'),
        ]
    )]
    public function detail(GameDetailRequest $request): JsonResponse
    {
        $result = $this->neptunePlayService->gameDetail(
            $request->validated('vendorCode'),
            $request->validated('gameCode'),
        );

        return response()->json($result);
    }

    #[OA\Post(
        path: '/api/game/launch-url',
        summary: 'Get game launch URL',
        tags: ['Games'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['vendorCode', 'gameCode', 'userCode', 'language'],
                properties: [
                    new OA\Property(property: 'vendorCode', type: 'string', example: 'PGS'),
                    new OA\Property(property: 'gameCode', type: 'string', example: 'fortune-tiger'),
                    new OA\Property(property: 'userCode', type: 'string', example: 'user123'),
                    new OA\Property(property: 'language', type: 'string', example: 'en'),
                    new OA\Property(property: 'lobbyUrl', type: 'string', example: 'https://example.com/lobby', nullable: true),
                    new OA\Property(property: 'theme', type: 'integer', example: 1, nullable: true),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Launch URL retrieved',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'https://game.example.com/launch?token=...'),
                        new OA\Property(property: 'errorCode', type: 'integer', example: 0),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthorized'),
        ]
    )]
    public function launchUrl(LaunchUrlRequest $request): JsonResponse
    {
        $result = $this->neptunePlayService->launchUrl(
            $request->validated('vendorCode'),
            $request->validated('gameCode'),
            $request->validated('userCode'),
            $request->validated('language'),
            $request->validated('lobbyUrl'),
            $request->validated('theme'),
        );

        return response()->json($result);
    }

    #[OA\Post(
        path: '/api/game/user/set-rtp',
        summary: 'Set user RTP for a vendor',
        tags: ['RTP'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['vendorCode', 'userCode', 'rtp'],
                properties: [
                    new OA\Property(property: 'vendorCode', type: 'string', example: 'PGS'),
                    new OA\Property(property: 'userCode', type: 'string', example: 'user123'),
                    new OA\Property(property: 'rtp', type: 'integer', example: 85, minimum: 30, maximum: 99),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'RTP set successfully',
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
    public function setUserRtp(SetUserRtpRequest $request): JsonResponse
    {
        $result = $this->neptunePlayService->setUserRtp(
            $request->validated('vendorCode'),
            $request->validated('userCode'),
            $request->validated('rtp'),
        );

        return response()->json($result);
    }

    #[OA\Post(
        path: '/api/game/user/get-rtp',
        summary: 'Get user RTP for a vendor',
        tags: ['RTP'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['vendorCode', 'userCode'],
                properties: [
                    new OA\Property(property: 'vendorCode', type: 'string', example: 'PGS'),
                    new OA\Property(property: 'userCode', type: 'string', example: 'user123'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'User RTP retrieved',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'integer', example: 85),
                        new OA\Property(property: 'errorCode', type: 'integer', example: 0),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthorized'),
        ]
    )]
    public function getUserRtp(GetUserRtpRequest $request): JsonResponse
    {
        $result = $this->neptunePlayService->getUserRtp(
            $request->validated('vendorCode'),
            $request->validated('userCode'),
        );

        return response()->json($result);
    }

    #[OA\Post(
        path: '/api/game/users/reset-rtp',
        summary: 'Reset RTP for all users of a vendor',
        tags: ['RTP'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['vendorCode', 'rtp'],
                properties: [
                    new OA\Property(property: 'vendorCode', type: 'string', example: 'PGS'),
                    new OA\Property(property: 'rtp', type: 'integer', example: 85, minimum: 30, maximum: 99),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'RTP reset for all users',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'integer', example: 85),
                        new OA\Property(property: 'errorCode', type: 'integer', example: 0),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthorized'),
        ]
    )]
    public function resetAllUsersRtp(ResetAllUsersRtpRequest $request): JsonResponse
    {
        $result = $this->neptunePlayService->resetAllUsersRtp(
            $request->validated('vendorCode'),
            $request->validated('rtp'),
        );

        return response()->json($result);
    }

    #[OA\Post(
        path: '/api/game/users/batch-rtp',
        summary: 'Batch set RTP for multiple users',
        tags: ['RTP'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['vendorCode', 'data'],
                properties: [
                    new OA\Property(property: 'vendorCode', type: 'string', example: 'PGS'),
                    new OA\Property(
                        property: 'data',
                        type: 'array',
                        items: new OA\Items(
                            properties: [
                                new OA\Property(property: 'userCode', type: 'string', example: 'user123'),
                                new OA\Property(property: 'rtp', type: 'integer', example: 85),
                            ]
                        )
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Batch RTP set successfully',
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
    public function batchRtp(BatchRtpRequest $request): JsonResponse
    {
        $result = $this->neptunePlayService->batchSetRtp(
            $request->validated('vendorCode'),
            $request->validated('data'),
        );

        return response()->json($result);
    }
}
