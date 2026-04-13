<?php

use App\Http\Controllers\Api\V2\AgentController;
use App\Http\Controllers\Api\V2\AuthController;
use App\Http\Controllers\Api\V2\BettingController;
use App\Http\Controllers\Api\V2\CallController;
use App\Http\Controllers\Api\V2\DiagnosticController;
use App\Http\Controllers\Api\V2\GameController;
use App\Http\Controllers\Api\V2\StatusController;
use App\Http\Controllers\Api\V2\UserController;
use App\Http\Controllers\Api\V2\VendorController;
use App\Http\Controllers\Api\DataController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\WalletController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Section 1: Diagnostic (Debug)
|--------------------------------------------------------------------------
*/
Route::get('diagnostic/test-neptuneplay-auth', [DiagnosticController::class, 'testNeptunePlayAuth']);

/*
|--------------------------------------------------------------------------
| Section 1.5: Registration & Captcha
|--------------------------------------------------------------------------
*/
Route::get('captcha', [RegisterController::class, 'captcha']);
Route::post('register', [RegisterController::class, 'register']);

/*
|--------------------------------------------------------------------------
| Section 2: Integration API (Proxy to NeptunePlay)
|--------------------------------------------------------------------------
*/
Route::middleware('throttle:neptuneplay-api')->group(function () {

    // Status
    Route::get('status', [StatusController::class, 'index']);

    // Auth
    Route::post('auth/createtoken', [AuthController::class, 'createToken']);

    // Vendors
    Route::get('vendors/list', [VendorController::class, 'list']);

    // Games
    Route::post('games/list', [GameController::class, 'listPost']);
    Route::post('game/detail', [GameController::class, 'detail']);
    Route::post('game/launch-url', [GameController::class, 'launchUrl']);

    // RTP
    Route::post('game/user/set-rtp', [GameController::class, 'setUserRtp']);
    Route::post('game/user/get-rtp', [GameController::class, 'getUserRtp']);
    Route::post('game/users/reset-rtp', [GameController::class, 'resetAllUsersRtp']);
    Route::post('game/users/batch-rtp', [GameController::class, 'batchRtp']);

    // Betting
    Route::post('betting/history/by-id', [BettingController::class, 'historyById']);
    Route::post('betting/history/by-date-v2', [BettingController::class, 'historyByDate']);
    Route::post('betting/history/detail', [BettingController::class, 'detail']);

    // Agent
    Route::get('agent/balance', [AgentController::class, 'balance']);

    // User (Transfer API)
    Route::get('users/list', [UserController::class, 'list']);
    Route::post('user/create', [UserController::class, 'create']);
    Route::post('user/balance', [UserController::class, 'balance']);
    Route::post('user/deposit', [UserController::class, 'deposit']);
    Route::post('user/withdraw', [UserController::class, 'withdraw']);
    Route::post('user/withdraw-all', [UserController::class, 'withdrawAll']);
    Route::post('user/balance-history', [UserController::class, 'balanceHistory']);

    // Call
    Route::get('call/active-users', [CallController::class, 'activeUsers']);
    Route::post('call/send', [CallController::class, 'send']);
    Route::post('call/cancel', [CallController::class, 'cancel']);
    Route::post('call/histories', [CallController::class, 'histories']);
});

/*
|--------------------------------------------------------------------------
| Section 3: Seamless Wallet Callbacks (NeptunePlay calls us)
|--------------------------------------------------------------------------
*/
Route::middleware(['neptuneplay.basic', 'throttle:wallet-callbacks'])->group(function () {
    Route::post('wallet/balance', [WalletController::class, 'balance']);
    Route::post('wallet/transaction', [WalletController::class, 'transaction']);
    Route::post('wallet/batch-transactions', [WalletController::class, 'batchTransactions']);
});

/*
|--------------------------------------------------------------------------
| Section 4: Data List Routes (Check data in tables)
|--------------------------------------------------------------------------
*/
Route::prefix('data')->group(function () {
    Route::get('players', [DataController::class, 'players']);
    Route::get('transactions', [DataController::class, 'transactions']);
    Route::get('vendors', [DataController::class, 'vendors']);
    Route::get('games', [DataController::class, 'games']);
    Route::get('betting-histories', [DataController::class, 'bettingHistories']);
    Route::get('user-balance-logs', [DataController::class, 'userBalanceLogs']);
    Route::get('call-histories', [DataController::class, 'callHistories']);
    Route::get('agent-tokens', [DataController::class, 'agentTokens']);
    Route::get('user-rtps', [DataController::class, 'userRtps']);
});
