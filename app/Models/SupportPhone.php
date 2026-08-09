<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class SupportPhone extends Model
{
    protected $fillable = [
        'number',
        'type',
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

    public function isWhatsapp(): bool
    {
        return $this->type === 'whatsapp';
    }

    public function href(): string
    {
        $digits = preg_replace('/\D/', '', $this->number);

        return $this->isWhatsapp()
            ? "https://wa.me/+57{$digits}"
            : "tel:+57{$digits}";
    }

    public function label(): string
    {
        return $this->isWhatsapp() ? 'WhatsApp' : 'Teléfono';
    }
}
