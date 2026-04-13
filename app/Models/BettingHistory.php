<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BettingHistory extends Model
{
    protected $fillable = [
        'history_id', 'user_code', 'round_id', 'game_code', 'game_name',
        'vendor_code', 'bet_amount', 'win_amount', 'before_balance',
        'after_balance', 'detail', 'status',
    ];

    protected function casts(): array
    {
        return [
            'history_id' => 'integer',
            'bet_amount' => 'decimal:4',
            'win_amount' => 'decimal:4',
            'before_balance' => 'decimal:4',
            'after_balance' => 'decimal:4',
            'status' => 'integer',
        ];
    }
}
