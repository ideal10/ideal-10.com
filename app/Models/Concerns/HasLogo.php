<?php

namespace App\Models\Concerns;

use App\Models\Client;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Logo handling for models that either reuse an existing client's logo
 * (`client_id`) or store an uploaded image path on the "public" disk
 * (`logo`). An uploaded image wins if both are somehow set.
 */
trait HasLogo
{
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    protected function logoUrl(): Attribute
    {
        return Attribute::get(fn () => $this->logo
            ? Storage::disk('public')->url($this->logo)
            : $this->client?->img);
    }

    /**
     * Up to two uppercase initials, used as a fallback when there is no logo.
     */
    protected function initials(): Attribute
    {
        return Attribute::get(function () {
            $words = collect(preg_split('/\s+/u', trim((string) $this->name)))
                ->reject(fn ($word) => $word === '' || in_array(Str::lower($word), ['de', 'del', 'la', 'el', 'los', 'las', 'y'], true))
                ->values();

            return Str::upper(Str::substr($words->get(0, ''), 0, 1).Str::substr($words->get(1, ''), 0, 1)) ?: '?';
        });
    }

    public function deleteLogoFile(): void
    {
        if ($this->logo) {
            Storage::disk('public')->delete($this->logo);
        }
    }
}
