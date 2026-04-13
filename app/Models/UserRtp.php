<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRtp extends Model
{
    protected $fillable = ['user_code', 'vendor_code', 'rtp'];

    protected function casts(): array
    {
        return [
            'rtp' => 'integer',
        ];
    }
}
