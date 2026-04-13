<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = [
        'transaction_code',
        'player_id',
        'user_code',
        'amount',
        'balance_before',
        'balance_after',
        'vendor_code',
        'game_code',
        'round_id',
        'history_id',
        'game_type',
        'is_finished',
        'is_canceled',
        'detail',
        'batch_id',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:4',
            'balance_before' => 'decimal:4',
            'balance_after' => 'decimal:4',
            'history_id' => 'integer',
            'game_type' => 'integer',
            'is_finished' => 'boolean',
            'is_canceled' => 'boolean',
            'meta' => 'array',
        ];
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }
}
