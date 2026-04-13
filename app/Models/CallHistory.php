<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CallHistory extends Model
{
    protected $fillable = [
        'call_id', 'user_code', 'vendor_code', 'game_code', 'game_name',
        'type_name', 'status_name', 'call_amount', 'missed_amount',
        'applied_amount', 'agent_before_balance', 'agent_after_balance',
        'is_auto_call',
    ];

    protected function casts(): array
    {
        return [
            'call_id' => 'integer',
            'call_amount' => 'decimal:4',
            'missed_amount' => 'decimal:4',
            'applied_amount' => 'decimal:4',
            'agent_before_balance' => 'decimal:4',
            'agent_after_balance' => 'decimal:4',
            'is_auto_call' => 'boolean',
        ];
    }
}
