<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Componente extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'body',
        'paths',
        'wide',
        'content',
        'order',
    ];

    protected $casts = [
        'paths' => 'array',
        'wide' => 'boolean',
        'order' => 'integer',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order');
    }
}
