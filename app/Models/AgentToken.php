<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentToken extends Model
{
    protected $fillable = ['token', 'expiration'];

    protected function casts(): array
    {
        return [
            'expiration' => 'datetime',
        ];
    }
}
