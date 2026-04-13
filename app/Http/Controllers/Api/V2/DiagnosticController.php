<?php

namespace App\Http\Controllers\Api\V2;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use OpenApi\Attributes as OA;

class DiagnosticController
{
    #[OA\Get(
        path: '/api/diagnostic/test-neptuneplay-auth',
        summary: 'Test NeptunePlay API Authentication',
        description: 'Tests 5 different authentication methods to find which one works with NeptunePlay API',
        tags: ['Diagnostic'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Diagnostic test results',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Diagnostic tests completed. Check logs for results.'),
                        new OA\Property(
                            property: 'results',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'test1_standard_json', type: 'object'),
                                new OA\Property(property: 'test2_with_accept', type: 'object'),
                                new OA\Property(property: 'test3_bearer_token', type: 'object'),
                                new OA\Property(property: 'test4_form_encoded', type: 'object'),
                                new OA\Property(property: 'test5_get_request', type: 'object'),
                            ]
                        ),
                    ]
                )
            ),
        ]
    )]
    public function testNeptunePlayAuth()
    {
        $baseUrl = config('services.neptuneplay.api_url');
        $clientId = config('services.neptuneplay.client_id');
        $clientSecret = config('services.neptuneplay.client_secret');

        $results = [
            'credentials' => [
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
            ]
        ];

        // Test 1: Standard POST with JSON and SSL verify disabled
        try {
            $response = Http::withoutVerifying()
                ->post("{$baseUrl}/auth/createtoken", [
                    'clientId' => $clientId,
                    'clientSecret' => $clientSecret,
                ]);
            $results['test1_standard_json'] = [
                'status' => $response->status(),
                'body' => $response->json(),
            ];
        } catch (\Exception $e) {
            $results['test1_standard_json'] = ['error' => $e->getMessage()];
        }

        // Test 2: With Accept header (SSL disabled)
        try {
            $response = Http::withoutVerifying()
                ->withHeaders(['Accept' => 'application/json'])
                ->post("{$baseUrl}/auth/createtoken", [
                    'clientId' => $clientId,
                    'clientSecret' => $clientSecret,
                ]);
            $results['test2_with_accept'] = [
                'status' => $response->status(),
                'body' => $response->json(),
            ];
        } catch (\Exception $e) {
            $results['test2_with_accept'] = ['error' => $e->getMessage()];
        }

        // Test 3: With Client-Id and Client-Secret headers instead
        try {
            $response = Http::withoutVerifying()
                ->withHeaders([
                    'Client-Id' => $clientId,
                    'Client-Secret' => $clientSecret,
                ])
                ->post("{$baseUrl}/auth/createtoken");
            $results['test3_headers'] = [
                'status' => $response->status(),
                'body' => $response->json(),
            ];
        } catch (\Exception $e) {
            $results['test3_headers'] = ['error' => $e->getMessage()];
        }

        // Test 4: Form encoded instead of JSON (SSL disabled)
        try {
            $response = Http::withoutVerifying()
                ->asForm()
                ->post("{$baseUrl}/auth/createtoken", [
                    'clientId' => $clientId,
                    'clientSecret' => $clientSecret,
                ]);
            $results['test4_form_encoded'] = [
                'status' => $response->status(),
                'body' => $response->body(),
            ];
        } catch (\Exception $e) {
            $results['test4_form_encoded'] = ['error' => $e->getMessage()];
        }

        // Test 5: Check API status
        try {
            $response = Http::withoutVerifying()->get("{$baseUrl}/status");
            $results['test5_api_status'] = [
                'status' => $response->status(),
                'body' => $response->json(),
            ];
        } catch (\Exception $e) {
            $results['test5_api_status'] = ['error' => $e->getMessage()];
        }

        Log::info('NeptunePlay Diagnostic Tests', $results);

        return response()->json([
            'message' => 'Diagnostic tests completed. Check logs for results.',
            'results' => $results,
        ]);
    }
}
