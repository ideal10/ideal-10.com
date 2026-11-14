<?php

namespace App\Models\Concerns;

use App\Support\Audit;

/**
 * Records every create, update and delete of the model in the audit log.
 */
trait Auditable
{
    public static function bootAuditable(): void
    {
        static::created(fn ($model) => Audit::modelEvent('created', $model));
        static::updated(fn ($model) => Audit::modelEvent('updated', $model));
        static::deleted(fn ($model) => Audit::modelEvent('deleted', $model));
    }
}
