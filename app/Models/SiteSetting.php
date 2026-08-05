<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'site_url',
        'lang',
        'title',
        'mailer',
    ];

    /**
     * Site-wide settings are a single row; this is the only one we ever read.
     */
    public static function current(): self
    {
        return static::query()->firstOrFail();
    }
}
