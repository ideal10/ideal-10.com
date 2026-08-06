<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'name',
        'img',
        'original_name',
        'extra',
        'order',
    ];

    protected $casts = [
        'extra' => 'boolean',
        'order' => 'integer',
    ];

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order');
    }

    /**
     * Clients shown in the home page grid (excludes ticker-only "extra" clients).
     */
    public function scopeHome(Builder $query): Builder
    {
        return $query->where('extra', false);
    }
}
