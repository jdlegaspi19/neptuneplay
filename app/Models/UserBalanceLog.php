<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBalanceLog extends Model
{
    protected $fillable = [
        'order_no', 'user_code', 'amount', 'type',
        'agent_before_balance', 'user_before_balance',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:4',
            'type' => 'integer',
            'agent_before_balance' => 'decimal:4',
            'user_before_balance' => 'decimal:4',
        ];
    }
}
