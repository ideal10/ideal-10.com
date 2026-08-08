<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class InterestLink extends Model
{
    protected $fillable = [
        'title',
        'description',
        'url',
        'original_name',
        'icon',
        'order',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'order' => 'integer',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order');
    }

    public function isFile(): bool
    {
        return Str::startsWith($this->url, '/');
    }

    public function domain(): ?string
    {
        if ($this->isFile()) {
            return null;
        }

        $stripped = preg_replace('#^https?://#', '', $this->url);

        return Str::before($stripped, '/');
    }
}
