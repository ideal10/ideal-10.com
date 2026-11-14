<?php

namespace App\Models;

use App\Models\Concerns\HasLogo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Entity extends Model
{
    use HasLogo;

    protected $fillable = [
        'slug',
        'name',
        'client_id',
        'logo',
    ];

    protected static function booted(): void
    {
        // The slug is only an internal identifier (admin URLs, public menu
        // URL), so it is generated once from the name and never edited, which
        // keeps existing links stable when an entity is renamed.
        static::creating(function (Entity $entity) {
            if (blank($entity->slug)) {
                $entity->slug = static::uniqueSlug($entity->name);
            }
        });

        // Links are removed by the DB cascade, which skips model events, so
        // clean up every logo file here before the rows disappear.
        static::deleting(function (Entity $entity) {
            $entity->links->each->deleteLogoFile();
            $entity->deleteLogoFile();
        });
    }

    public static function uniqueSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'entidad';
        $slug = $base;

        for ($i = 2; static::where('slug', $slug)->exists(); $i++) {
            $slug = "{$base}-{$i}";
        }

        return $slug;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function links(): HasMany
    {
        return $this->hasMany(EntityLink::class)->orderBy('order');
    }
}
