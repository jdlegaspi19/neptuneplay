<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Services\NeptunePlayService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class AgentController extends Controller
{
    public function __construct(
        private readonly NeptunePlayService $neptunePlayService,
    ) {}

    #[OA\Get(
        path: '/api/agent/balance',
        summary: 'Get agent balance',
        tags: ['Agent'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Agent balance retrieved',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'number', example: 10000.00),
                        new OA\Property(property: 'errorCode', type: 'integer', example: 0),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthorized'),
        ]
    )]
    public function balance(): JsonResponse
    {
        $result = $this->neptunePlayService->agentBalance();

        return response()->json($result);
    }
}
