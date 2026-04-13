<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Services\NeptunePlayService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class VendorController extends Controller
{
    public function __construct(
        private readonly NeptunePlayService $neptunePlayService,
    ) {}

    #[OA\Get(
        path: '/api/vendors/list',
        summary: 'Get list of game vendors',
        tags: ['Vendors'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Vendor list retrieved',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(
                            property: 'message',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: 'vendorCode', type: 'string', example: 'PGS'),
                                    new OA\Property(property: 'type', type: 'integer', example: 2),
                                    new OA\Property(property: 'name', type: 'string', example: 'PG Soft'),
                                    new OA\Property(property: 'url', type: 'string', example: 'https://example.com'),
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
    public function list(): JsonResponse
    {
        $result = $this->neptunePlayService->vendorList();

        return response()->json($result);
    }
}
