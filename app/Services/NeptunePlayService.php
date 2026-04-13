<?php

namespace App\Services;

use App\Enums\NeptunePlayErrorCode;
use App\Exceptions\NeptunePlayApiException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NeptunePlayService
{
    private string $baseUrl;
    private string $clientId;
    private string $clientSecret;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.neptuneplay.api_url'), '/');
        $this->clientId = config('services.neptuneplay.client_id');
        $this->clientSecret = config('services.neptuneplay.client_secret');
    }

    // ── Token Management ─────────────────────────────────────────────

    public function createToken(?string $clientId = null, ?string $clientSecret = null): array
    {
        $payload = [
            'clientId' => $clientId ?? $this->clientId,
            'clientSecret' => $clientSecret ?? $this->clientSecret,
        ];

Log::info('NeptunePlay API Call', [
            'url' => "{$this->baseUrl}/auth/createtoken",
            'payload' => $payload,
        ]);

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'User-Agent' => 'NeptunePlay-Laravel-Client/1.0',
        ])->post("{$this->baseUrl}/auth/createtoken", $payload);

        Log::info('NeptunePlay API Response', [
            'status' => $response->status(),
            'headers' => $response->headers(),
            'body' => $response->body(),
            'json' => $response->json(),
        ]);

        return $this->handleResponse($response);
    }

    private function getToken(): string
    {
        return Cache::remember('neptuneplay_bearer_token', 3500, function () {
            $result = $this->createToken();

            if (isset($result['token'])) {
                return $result['token'];
            }

            throw new NeptunePlayApiException(
                NeptunePlayErrorCode::UNAUTHORIZED,
                $result,
                'Failed to obtain bearer token'
            );
        });
    }

    // ── Status ───────────────────────────────────────────────────────

    public function status(): array
    {
        $response = Http::get("{$this->baseUrl}/status");

        return $this->handleResponse($response);
    }

    // ── Vendors ──────────────────────────────────────────────────────

    public function vendorList(): array
    {
        return $this->authenticatedGet('/vendors/list');
    }

    // ── Games ────────────────────────────────────────────────────────

    public function gameList(string $vendorCode, string $language = 'en'): array
    {
        return $this->authenticatedPost('/games/list', [
            'vendorCode' => $vendorCode,
            'language' => $language,
        ]);
    }

    public function gameDetail(string $vendorCode, string $gameCode): array
    {
        return $this->authenticatedPost('/game/detail', [
            'vendorCode' => $vendorCode,
            'gameCode' => $gameCode,
        ]);
    }

    public function launchUrl(
        string $vendorCode,
        string $gameCode,
        string $userCode,
        string $language,
        ?string $lobbyUrl = null,
        ?int $theme = null
    ): array {
        $data = [
            'vendorCode' => $vendorCode,
            'gameCode' => $gameCode,
            'userCode' => $userCode,
            'language' => $language,
        ];

        if ($lobbyUrl !== null) {
            $data['lobbyUrl'] = $lobbyUrl;
        }
        if ($theme !== null) {
            $data['theme'] = $theme;
        }

        return $this->authenticatedPost('/game/launch-url', $data);
    }

    // ── Betting ──────────────────────────────────────────────────────

    public function bettingHistoryById(int $id): array
    {
        return $this->authenticatedPost('/betting/history/by-id', [
            'id' => $id,
        ]);
    }

    public function bettingHistoryByDate(string $startDate, int $limit): array
    {
        return $this->authenticatedPost('/betting/history/by-date-v2', [
            'startDate' => $startDate,
            'limit' => $limit,
        ]);
    }

    public function bettingDetail(int $id, ?string $language = null): array
    {
        $data = ['id' => $id];
        if ($language !== null) {
            $data['language'] = $language;
        }

        return $this->authenticatedPost('/betting/history/detail', $data);
    }

    // ── Agent ────────────────────────────────────────────────────────

    public function agentBalance(): array
    {
        return $this->authenticatedGet('/agent/balance');
    }

    // ── User ─────────────────────────────────────────────────────────

    public function createUser(string $userCode): array
    {
        return $this->authenticatedPost('/user/create', [
            'userCode' => $userCode,
        ]);
    }

    public function userBalance(string $userCode): array
    {
        return $this->authenticatedPost('/user/balance', [
            'userCode' => $userCode,
        ]);
    }

    public function deposit(string $userCode, float $balance, ?string $orderNo = null, ?string $vendorCode = null): array
    {
        $data = [
            'userCode' => $userCode,
            'balance' => $balance,
        ];
        if ($orderNo !== null) {
            $data['orderNo'] = $orderNo;
        }
        if ($vendorCode !== null) {
            $data['vendorCode'] = $vendorCode;
        }

        return $this->authenticatedPost('/user/deposit', $data);
    }

    public function withdraw(string $userCode, float $balance, ?string $orderNo = null, ?string $vendorCode = null): array
    {
        $data = [
            'userCode' => $userCode,
            'balance' => $balance,
        ];
        if ($orderNo !== null) {
            $data['orderNo'] = $orderNo;
        }
        if ($vendorCode !== null) {
            $data['vendorCode'] = $vendorCode;
        }

        return $this->authenticatedPost('/user/withdraw', $data);
    }

    public function withdrawAll(string $userCode, ?string $vendorCode = null): array
    {
        $data = ['userCode' => $userCode];
        if ($vendorCode !== null) {
            $data['vendorCode'] = $vendorCode;
        }

        return $this->authenticatedPost('/user/withdraw-all', $data);
    }

    public function balanceHistory(string $orderNo): array
    {
        return $this->authenticatedPost('/user/balance-history', [
            'orderNo' => $orderNo,
        ]);
    }

    // ── RTP ──────────────────────────────────────────────────────────

    public function setUserRtp(string $vendorCode, string $userCode, int $rtp): array
    {
        return $this->authenticatedPost('/game/user/set-rtp', [
            'vendorCode' => $vendorCode,
            'userCode' => $userCode,
            'rtp' => $rtp,
        ]);
    }

    public function getUserRtp(string $vendorCode, string $userCode): array
    {
        return $this->authenticatedPost('/game/user/get-rtp', [
            'vendorCode' => $vendorCode,
            'userCode' => $userCode,
        ]);
    }

    public function resetAllUsersRtp(string $vendorCode, int $rtp): array
    {
        return $this->authenticatedPost('/game/users/reset-rtp', [
            'vendorCode' => $vendorCode,
            'rtp' => $rtp,
        ]);
    }

    public function batchSetRtp(string $vendorCode, array $data): array
    {
        return $this->authenticatedPost('/game/users/batch-rtp', [
            'vendorCode' => $vendorCode,
            'data' => $data,
        ]);
    }

    // ── Call ─────────────────────────────────────────────────────────

    public function activeUsers(): array
    {
        return $this->authenticatedGet('/call/active-users');
    }

    public function sendCall(string $vendorCode, string $gameCode, string $userCode, float $amount, int $type): array
    {
        return $this->authenticatedPost('/call/send', [
            'vendorCode' => $vendorCode,
            'gameCode' => $gameCode,
            'userCode' => $userCode,
            'amount' => $amount,
            'type' => $type,
        ]);
    }

    public function cancelCall(string $vendorCode, string $gameCode, string $userCode, int $callId): array
    {
        return $this->authenticatedPost('/call/cancel', [
            'vendorCode' => $vendorCode,
            'gameCode' => $gameCode,
            'userCode' => $userCode,
            'callId' => $callId,
        ]);
    }

    public function callHistories(int $pageIndex, int $pageSize): array
    {
        return $this->authenticatedPost('/call/histories', [
            'pageIndex' => $pageIndex,
            'pageSize' => $pageSize,
        ]);
    }

    // ── Private HTTP Helpers ─────────────────────────────────────────

    private function authenticatedGet(string $path): array
    {
        $response = Http::withToken($this->getToken())
            ->get("{$this->baseUrl}{$path}");

        return $this->handleResponse($response);
    }

    private function authenticatedPost(string $path, array $data = []): array
    {
        $response = Http::withToken($this->getToken())
            ->post("{$this->baseUrl}{$path}", $data);

        return $this->handleResponse($response);
    }

    private function handleResponse(Response $response): array
    {
        $body = $response->json() ?? [];

        if ($response->failed()) {
            $httpStatus = $response->status();
            $errorCode = NeptunePlayErrorCode::tryFrom($body['errorCode'] ?? $httpStatus)
                ?? NeptunePlayErrorCode::tryFrom($httpStatus)
                ?? NeptunePlayErrorCode::UNKNOWN_SERVER_ERROR;

            throw new NeptunePlayApiException($errorCode, $body);
        }

        $errorCodeValue = $body['errorCode'] ?? 0;
        if ($errorCodeValue !== 0) {
            $errorCode = NeptunePlayErrorCode::tryFrom($errorCodeValue)
                ?? NeptunePlayErrorCode::UNKNOWN_SERVER_ERROR;

            throw new NeptunePlayApiException($errorCode, $body);
        }

        return $body;
    }
}
