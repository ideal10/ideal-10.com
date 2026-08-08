<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class NavItem extends Model
{
    protected $fillable = [
        'url',
        'label',
        'match',
        'order',
    ];

    protected $casts = [
        'match' => 'array',
        'order' => 'integer',
    ];

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order');
    }
}
