<?php

namespace App\Http\Controllers\Api;

use App\Enums\NeptunePlayErrorCode;
use App\Http\Controllers\Controller;
use App\Http\Requests\Wallet\BalanceCallbackRequest;
use App\Http\Requests\Wallet\BatchTransactionCallbackRequest;
use App\Http\Requests\Wallet\TransactionCallbackRequest;
use App\Models\Player;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use OpenApi\Attributes as OA;
 
class WalletController extends Controller
{
    #[OA\Post(
        path: '/api/wallet/balance',
        summary: 'Get player balance (Seamless Wallet callback)',
        tags: ['Seamless Wallet'],
        security: [['basicAuth' => []]],
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
                description: 'Player balance returned',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'number', example: 1000.00),
                        new OA\Property(property: 'errorCode', type: 'integer', example: 0),
                    ]
                )
            ),
        ]
    )]
    public function balance(BalanceCallbackRequest $request): JsonResponse
    {
        $player = Player::where('user_code', $request->validated('userCode'))->first();

        if (! $player) {
            return response()->json([
                'success' => false,
                'message' => NeptunePlayErrorCode::USER_DOES_NOT_EXIST->message(),
                'errorCode' => NeptunePlayErrorCode::USER_DOES_NOT_EXIST->value,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => (float) $player->balance,
            'errorCode' => 0,
        ]);
    }

    #[OA\Post(
        path: '/api/wallet/transaction',
        summary: 'Process a game transaction (Seamless Wallet callback)',
        tags: ['Seamless Wallet'],
        security: [['basicAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['userCode', 'vendorCode', 'gameCode', 'historyId', 'roundId', 'gameType', 'transactionCode', 'isFinished', 'isCanceled', 'amount'],
                properties: [
                    new OA\Property(property: 'userCode', type: 'string', example: 'user123'),
                    new OA\Property(property: 'vendorCode', type: 'string', example: 'PGS'),
                    new OA\Property(property: 'gameCode', type: 'string', example: 'fortune-tiger'),
                    new OA\Property(property: 'historyId', type: 'integer', example: 12345),
                    new OA\Property(property: 'roundId', type: 'string', example: 'round-abc-123'),
                    new OA\Property(property: 'gameType', type: 'integer', example: 2, description: '1=Casino, 2=Slot, 3=Other, 4=Fishing'),
                    new OA\Property(property: 'transactionCode', type: 'string', example: 'txn-unique-001'),
                    new OA\Property(property: 'isFinished', type: 'boolean', example: false),
                    new OA\Property(property: 'isCanceled', type: 'boolean', example: false),
                    new OA\Property(property: 'amount', type: 'number', example: -10.00, description: 'Negative=bet/debit, Positive=win/credit'),
                    new OA\Property(property: 'detail', type: 'string', example: 'Bet on slot', nullable: true),
                    new OA\Property(property: 'createdAt', type: 'string', example: '2026-04-08T12:00:00Z', nullable: true),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Transaction processed',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'number', example: 990.00),
                        new OA\Property(property: 'errorCode', type: 'integer', example: 0),
                    ]
                )
            ),
        ]
    )]
    public function transaction(TransactionCallbackRequest $request): JsonResponse
    {
        $validated = $request->validated();

        return DB::transaction(function () use ($validated) {
            // Check duplicate transaction
            $existing = Transaction::where('transaction_code', $validated['transactionCode'])->exists();
            if ($existing) {
                return response()->json([
                    'success' => false,
                    'message' => NeptunePlayErrorCode::DUPLICATE_TRANSACTION->message(),
                    'errorCode' => NeptunePlayErrorCode::DUPLICATE_TRANSACTION->value,
                ]);
            }

            // Find player with lock
            $player = Player::where('user_code', $validated['userCode'])->lockForUpdate()->first();

            if (! $player) {
                return response()->json([
                    'success' => false,
                    'message' => NeptunePlayErrorCode::USER_DOES_NOT_EXIST->message(),
                    'errorCode' => NeptunePlayErrorCode::USER_DOES_NOT_EXIST->value,
                ]);
            }

            $amount = (float) $validated['amount'];
            $balanceBefore = (float) $player->balance;
            $balanceAfter = $balanceBefore + $amount;

            // Check sufficient balance for debit
            if ($amount < 0 && $balanceAfter < 0) {
                return response()->json([
                    'success' => false,
                    'message' => NeptunePlayErrorCode::INSUFFICIENT_USER_BALANCE->message(),
                    'errorCode' => NeptunePlayErrorCode::INSUFFICIENT_USER_BALANCE->value,
                ]);
            }

            // Update player balance
            $player->balance = $balanceAfter;
            $player->save();

            // Create transaction record
            Transaction::create([
                'transaction_code' => $validated['transactionCode'],
                'player_id' => $player->id,
                'user_code' => $validated['userCode'],
                'amount' => $amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'vendor_code' => $validated['vendorCode'],
                'game_code' => $validated['gameCode'],
                'round_id' => $validated['roundId'],
                'history_id' => $validated['historyId'],
                'game_type' => $validated['gameType'],
                'is_finished' => $validated['isFinished'],
                'is_canceled' => $validated['isCanceled'],
                'detail' => $validated['detail'] ?? null,
                'meta' => ['createdAt' => $validated['createdAt'] ?? null],
            ]);

            return response()->json([
                'success' => true,
                'message' => (float) $player->balance,
                'errorCode' => 0,
            ]);
        });
    }

    #[OA\Post(
        path: '/api/wallet/batch-transactions',
        summary: 'Process batch transactions (Seamless Wallet callback)',
        tags: ['Seamless Wallet'],
        security: [['basicAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['userCode', 'transactions'],
                properties: [
                    new OA\Property(property: 'userCode', type: 'string', example: 'user123'),
                    new OA\Property(
                        property: 'transactions',
                        type: 'array',
                        items: new OA\Items(
                            properties: [
                                new OA\Property(property: 'vendorCode', type: 'string'),
                                new OA\Property(property: 'gameCode', type: 'string'),
                                new OA\Property(property: 'historyId', type: 'integer'),
                                new OA\Property(property: 'roundId', type: 'string'),
                                new OA\Property(property: 'gameType', type: 'integer'),
                                new OA\Property(property: 'transactionCode', type: 'string'),
                                new OA\Property(property: 'isFinished', type: 'boolean'),
                                new OA\Property(property: 'isCanceled', type: 'boolean'),
                                new OA\Property(property: 'amount', type: 'number'),
                                new OA\Property(property: 'detail', type: 'string', nullable: true),
                                new OA\Property(property: 'createdAt', type: 'string', nullable: true),
                            ]
                        )
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Batch transactions processed',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'number', example: 950.00),
                        new OA\Property(property: 'errorCode', type: 'integer', example: 0),
                    ]
                )
            ),
        ]
    )]
    public function batchTransactions(BatchTransactionCallbackRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $batchId = (string) Str::uuid();

        return DB::transaction(function () use ($validated, $batchId) {
            $player = Player::where('user_code', $validated['userCode'])->lockForUpdate()->first();

            if (! $player) {
                return response()->json([
                    'success' => false,
                    'message' => NeptunePlayErrorCode::USER_DOES_NOT_EXIST->message(),
                    'errorCode' => NeptunePlayErrorCode::USER_DOES_NOT_EXIST->value,
                ]);
            }

            foreach ($validated['transactions'] as $txn) {
                // Check duplicate
                $existing = Transaction::where('transaction_code', $txn['transactionCode'])->exists();
                if ($existing) {
                    return response()->json([
                        'success' => false,
                        'message' => NeptunePlayErrorCode::DUPLICATE_TRANSACTION->message(),
                        'errorCode' => NeptunePlayErrorCode::DUPLICATE_TRANSACTION->value,
                    ]);
                }

                $amount = (float) $txn['amount'];
                $balanceBefore = (float) $player->balance;
                $balanceAfter = $balanceBefore + $amount;

                if ($amount < 0 && $balanceAfter < 0) {
                    return response()->json([
                        'success' => false,
                        'message' => NeptunePlayErrorCode::INSUFFICIENT_USER_BALANCE->message(),
                        'errorCode' => NeptunePlayErrorCode::INSUFFICIENT_USER_BALANCE->value,
                    ]);
                }

                $player->balance = $balanceAfter;
                $player->save();

                Transaction::create([
                    'transaction_code' => $txn['transactionCode'],
                    'player_id' => $player->id,
                    'user_code' => $validated['userCode'],
                    'amount' => $amount,
                    'balance_before' => $balanceBefore,
                    'balance_after' => $balanceAfter,
                    'vendor_code' => $txn['vendorCode'],
                    'game_code' => $txn['gameCode'],
                    'round_id' => $txn['roundId'],
                    'history_id' => $txn['historyId'],
                    'game_type' => $txn['gameType'],
                    'is_finished' => $txn['isFinished'],
                    'is_canceled' => $txn['isCanceled'],
                    'detail' => $txn['detail'] ?? null,
                    'batch_id' => $batchId,
                    'meta' => ['createdAt' => $txn['createdAt'] ?? null],
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => (float) $player->balance,
                'errorCode' => 0,
            ]);
        });
    }
}
