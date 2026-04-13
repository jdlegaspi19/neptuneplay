<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = [
        'provider', 'vendor_code', 'game_id', 'game_code',
        'game_name', 'slug', 'thumbnail', 'is_new', 'under_maintenance',
    ];

    protected function casts(): array
    {
        return [
            'is_new' => 'boolean',
            'under_maintenance' => 'boolean',
        ];
    }
}
