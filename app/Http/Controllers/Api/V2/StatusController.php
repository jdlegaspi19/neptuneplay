<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Services\NeptunePlayService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class StatusController extends Controller
{
    public function __construct(
        private readonly NeptunePlayService $neptunePlayService,
    ) {}

    #[OA\Get(
        path: '/api/status',
        summary: 'Check API status',
        tags: ['Status'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'API is running',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'success'),
                        new OA\Property(property: 'errorCode', type: 'integer', example: 0),
                    ]
                )
            ),
        ]
    )]
    public function index(): JsonResponse
    {
        $result = $this->neptunePlayService->status();

        return response()->json($result);
    }
}
