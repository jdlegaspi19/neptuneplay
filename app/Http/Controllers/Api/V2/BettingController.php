<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Requests\Betting\BettingDetailRequest;
use App\Http\Requests\Betting\BettingHistoryByDateRequest;
use App\Http\Requests\Betting\BettingHistoryByIdRequest;
use App\Services\NeptunePlayService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class BettingController extends Controller
{
    public function __construct(
        private readonly NeptunePlayService $neptunePlayService,
    ) {}

    #[OA\Post(
        path: '/api/betting/history/by-id',
        summary: 'Get betting history by ID',
        tags: ['Betting'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['id'],
                properties: [
                    new OA\Property(property: 'id', type: 'integer', example: 12345),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Betting history retrieved',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(
                            property: 'message',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'id', type: 'integer'),
                                new OA\Property(property: 'userCode', type: 'string'),
                                new OA\Property(property: 'roundId', type: 'string'),
                                new OA\Property(property: 'gameCode', type: 'string'),
                                new OA\Property(property: 'gameName', type: 'string'),
                                new OA\Property(property: 'vendorCode', type: 'string'),
                                new OA\Property(property: 'betAmount', type: 'number'),
                                new OA\Property(property: 'winAmount', type: 'number'),
                                new OA\Property(property: 'beforeBalance', type: 'number'),
                                new OA\Property(property: 'afterBalance', type: 'number'),
                                new OA\Property(property: 'status', type: 'integer', description: '0=Unfinished, 1=Finished, 2=Canceled'),
                            ]
                        ),
                        new OA\Property(property: 'errorCode', type: 'integer', example: 0),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthorized'),
        ]
    )]
    public function historyById(BettingHistoryByIdRequest $request): JsonResponse
    {
        $result = $this->neptunePlayService->bettingHistoryById(
            $request->validated('id'),
        );

        return response()->json($result);
    }

    #[OA\Post(
        path: '/api/betting/history/by-date-v2',
        summary: 'Get betting history by date (V2)',
        tags: ['Betting'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['startDate', 'limit'],
                properties: [
                    new OA\Property(property: 'startDate', type: 'string', example: '2026-04-01'),
                    new OA\Property(property: 'limit', type: 'integer', example: 100, maximum: 5000),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Betting history retrieved',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(
                            property: 'message',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'nextStartDate', type: 'string'),
                                new OA\Property(property: 'limit', type: 'integer'),
                                new OA\Property(property: 'histories', type: 'array', items: new OA\Items(type: 'object')),
                            ]
                        ),
                        new OA\Property(property: 'errorCode', type: 'integer', example: 0),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthorized'),
        ]
    )]
    public function historyByDate(BettingHistoryByDateRequest $request): JsonResponse
    {
        $result = $this->neptunePlayService->bettingHistoryByDate(
            $request->validated('startDate'),
            $request->validated('limit'),
        );

        return response()->json($result);
    }

    #[OA\Post(
        path: '/api/betting/history/detail',
        summary: 'Get betting detail page URL',
        tags: ['Betting'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['id'],
                properties: [
                    new OA\Property(property: 'id', type: 'integer', example: 12345),
                    new OA\Property(property: 'language', type: 'string', example: 'en', nullable: true),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Betting detail URL retrieved',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'https://detail.example.com/bet/12345'),
                        new OA\Property(property: 'errorCode', type: 'integer', example: 0),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthorized'),
        ]
    )]
    public function detail(BettingDetailRequest $request): JsonResponse
    {
        $result = $this->neptunePlayService->bettingDetail(
            $request->validated('id'),
            $request->validated('language'),
        );

        return response()->json($result);
    }
}
