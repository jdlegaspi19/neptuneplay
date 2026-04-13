<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vendor extends Model
{
    protected $fillable = ['vendor_code', 'name', 'type', 'url', 'under_maintenance'];

    protected function casts(): array
    {
        return [
            'type' => 'integer',
            'under_maintenance' => 'boolean',
        ];
    }

    public function games(): HasMany
    {
        return $this->hasMany(Game::class, 'vendor_code', 'vendor_code');
    }
}
