<?php

namespace App\Models;

use App\Models\Concerns\HasLogo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EntityLink extends Model
{
    use HasLogo;

    protected $fillable = [
        'entity_id',
        'name',
        'href',
        'client_id',
        'logo',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    public function entity(): BelongsTo
    {
        return $this->belongsTo(Entity::class);
    }

    /**
     * Bare host of the link (e.g. "elcalvario.ideal-10.com"), for display.
     */
    public function host(): string
    {
        return parse_url($this->href, PHP_URL_HOST) ?: $this->href;
    }
}
