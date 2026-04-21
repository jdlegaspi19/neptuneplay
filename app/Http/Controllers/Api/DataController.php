<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AgentToken;
use App\Models\BettingHistory;
use App\Models\CallHistory;
use App\Models\Game;
use App\Models\Player;
use App\Models\Transaction;
use App\Models\UserBalanceLog;
use App\Models\UserRtp;
use App\Models\Vendor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class DataController extends Controller
{
    #[OA\Get(
        path: '/api/data/players',
        summary: 'List all players',
        tags: ['Data'],
        parameters: [
            new OA\Parameter(name: 'user_code', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Players list'),
        ]
    )]
    public function players(Request $request): JsonResponse
    {
        $query = Player::query();

        if ($request->has('user_code')) {
            $query->where('user_code', 'like', '%' . $request->input('user_code') . '%');
        }

        $query = $query->orderBy('id', 'desc');
        return response()->json($query->get());
    }

    #[OA\Get(
        path: '/api/data/transactions',
        summary: 'List all transactions',
        tags: ['Data'],
        parameters: [
            new OA\Parameter(name: 'user_code', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'vendor_code', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'game_code', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'round_id', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Transactions list'),
        ]
    )]
    public function transactions(Request $request): JsonResponse
    {
        $query = Transaction::query();
 
        if ($request->has('user_code')) {
            $query->where('user_code', $request->input('user_code'));
        }
        if ($request->has('vendor_code')) {
            $query->where('vendor_code', $request->input('vendor_code'));
        }
        if ($request->has('game_code')) {
            $query->where('game_code', $request->input('game_code'));
        }
        if ($request->has('round_id')) {
            $query->where('round_id', $request->input('round_id'));
        }
 
        $query = $query->orderBy('id', 'desc');
        return response()->json($query->get());
    }
 
    #[OA\Get(
        path: '/api/data/vendors',
        summary: 'List all vendors',
        tags: ['Data'],
        parameters: [
            new OA\Parameter(name: 'type', in: 'query', required: false, schema: new OA\Schema(type: 'integer'), description: '1=Live Casino, 2=Slot, 3=Mini-Game, 4=Fishing-Game, 5=Sports, 6=Board-Game'),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Vendors list'),
        ]
    )]
    public function vendors(Request $request): JsonResponse
    {
        $query = Vendor::query();

        if ($request->has('type')) {
            $query->where('type', $request->input('type'));
        }

        $query = $query->orderBy('id', 'desc');
        return response()->json($query->get());
    }

    #[OA\Get(
        path: '/api/data/games',
        summary: 'List all games',
        tags: ['Data'],
        parameters: [
            new OA\Parameter(name: 'vendor_code', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'game_name', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Games list'),
        ]
    )]
    public function games(Request $request): JsonResponse
    {
        $query = Game::query();

        if ($request->has('vendor_code')) {
            $query->where('vendor_code', $request->input('vendor_code'));
        }
        if ($request->has('game_name')) {
            $query->where('game_name', 'like', '%' . $request->input('game_name') . '%');
        }

        $query = $query->orderBy('id', 'desc');
        return response()->json($query->get());
    }

    #[OA\Get(
        path: '/api/data/betting-histories',
        summary: 'List all betting histories',
        tags: ['Data'],
        parameters: [
            new OA\Parameter(name: 'user_code', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'vendor_code', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'status', in: 'query', required: false, schema: new OA\Schema(type: 'integer'), description: '0=Unfinished, 1=Finished, 2=Canceled'),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Betting histories list'),
        ]
    )]
    public function bettingHistories(Request $request): JsonResponse
    {
        $query = BettingHistory::query();

        if ($request->has('user_code')) {
            $query->where('user_code', $request->input('user_code'));
        }
        if ($request->has('vendor_code')) {
            $query->where('vendor_code', $request->input('vendor_code'));
        }
        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        $query = $query->orderBy('id', 'desc');
        return response()->json($query->get());
    }

    #[OA\Get(
        path: '/api/data/user-balance-logs',
        summary: 'List all user balance logs',
        tags: ['Data'],
        parameters: [
            new OA\Parameter(name: 'user_code', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'type', in: 'query', required: false, schema: new OA\Schema(type: 'integer'), description: '1=Deposit, 2=Withdraw'),
        ],
        responses: [
            new OA\Response(response: 200, description: 'User balance logs list'),
        ]
    )]
    public function userBalanceLogs(Request $request): JsonResponse
    {
        $query = UserBalanceLog::query();

        if ($request->has('user_code')) {
            $query->where('user_code', $request->input('user_code'));
        }
        if ($request->has('type')) {
            $query->where('type', $request->input('type'));
        }

        $query = $query->orderBy('id', 'desc');
        return response()->json($query->get());
    }

    #[OA\Get(
        path: '/api/data/call-histories',
        summary: 'List all call histories',
        tags: ['Data'],
        parameters: [
            new OA\Parameter(name: 'user_code', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'vendor_code', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Call histories list'),
        ]
    )]
    public function callHistories(Request $request): JsonResponse
    {
        $query = CallHistory::query();

        if ($request->has('user_code')) {
            $query->where('user_code', $request->input('user_code'));
        }
        if ($request->has('vendor_code')) {
            $query->where('vendor_code', $request->input('vendor_code'));
        }

        $query = $query->orderBy('id', 'desc');
        return response()->json($query->get());
    }

    #[OA\Get(
        path: '/api/data/agent-tokens',
        summary: 'List all agent tokens',
        tags: ['Data'],
        parameters: [],
        responses: [
            new OA\Response(response: 200, description: 'Agent tokens list'),
        ]
    )]
    public function agentTokens(Request $request): JsonResponse
    {
        return response()->json(AgentToken::orderBy('id', 'desc')->get());
    }

    #[OA\Get(
        path: '/api/data/user-rtps',
        summary: 'List all user RTPs',
        tags: ['Data'],
        parameters: [
            new OA\Parameter(name: 'user_code', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'vendor_code', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'User RTPs list'),
        ]
    )]
    public function userRtps(Request $request): JsonResponse
    {
        $query = UserRtp::query();

        if ($request->has('user_code')) {
            $query->where('user_code', $request->input('user_code'));
        }
        if ($request->has('vendor_code')) {
            $query->where('vendor_code', $request->input('vendor_code'));
        }

        $query = $query->orderBy('id', 'desc');
        return response()->json($query->get());
    }
}
