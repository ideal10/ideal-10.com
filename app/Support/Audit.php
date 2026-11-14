<?php

namespace App\Support;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * Writes audit log entries. Models using the Auditable trait call this on
 * every create/update/delete; controllers call it directly for actions
 * that bypass model events (bulk reorders) or have no model (auth).
 */
class Audit
{
    /** Attributes never worth showing in a change list. */
    private const IGNORED = ['created_at', 'updated_at', 'remember_token'];

    /** Attributes whose value must never be stored. */
    private const SECRET = ['password'];

    private const MAX_VALUE_LENGTH = 500;

    private static bool $muted = false;

    /**
     * Run a callback without recording the model events it triggers, e.g. to
     * replace a series of "order changed" entries with a single summary.
     */
    public static function quietly(callable $callback): mixed
    {
        $previous = self::$muted;
        self::$muted = true;

        try {
            return $callback();
        } finally {
            self::$muted = $previous;
        }
    }

    public static function modelEvent(string $event, Model $model): void
    {
        if (self::$muted) {
            return;
        }

        $changes = match ($event) {
            'created' => self::diff([], $model->getAttributes()),
            'updated' => self::diff(
                array_intersect_key($model->getOriginal(), $model->getChanges()),
                $model->getChanges(),
            ),
            'deleted' => self::diff($model->getOriginal(), []),
            default => [],
        };

        // An update that only touched ignored columns is not worth a row.
        if ($event === 'updated' && $changes === []) {
            return;
        }

        self::record($event, $model, $changes);
    }

    /**
     * @param  Model|class-string<Model>|null  $subject  a model instance, or a model class for actions on a whole section
     */
    public static function record(string $event, Model|string|null $subject = null, array $changes = [], ?User $user = null, ?string $label = null): AuditLog
    {
        $user ??= Auth::user();
        $request = app()->runningInConsole() ? null : request();

        return AuditLog::create([
            'user_id' => $user?->getKey(),
            'user_name' => $user?->name,
            'event' => $event,
            'auditable_type' => $subject instanceof Model ? $subject::class : $subject,
            'auditable_id' => $subject instanceof Model ? $subject->getKey() : null,
            'auditable_label' => $label ?? ($subject instanceof Model ? self::labelFor($subject) : null),
            'changes' => $changes ?: null,
            'ip_address' => $request?->ip(),
            'user_agent' => $request ? Str::limit((string) $request->userAgent(), 500, '') : null,
        ]);
    }

    public static function labelFor(Model $model): string
    {
        if (method_exists($model, 'auditLabel')) {
            return $model->auditLabel();
        }

        // Raw attributes on purpose: some models define methods with these
        // names (e.g. SupportPhone::label()), which getAttribute() would
        // try to treat as relationships.
        $attributes = $model->getAttributes();

        foreach (['name', 'title', 'label', 'number', 'email'] as $attribute) {
            if (filled($attributes[$attribute] ?? null)) {
                return (string) $attributes[$attribute];
            }
        }

        return '#'.$model->getKey();
    }

    /**
     * @return array<string, array{old: mixed, new: mixed}>
     */
    private static function diff(array $old, array $new): array
    {
        $changes = [];

        foreach (array_keys($old + $new) as $key) {
            if (in_array($key, self::IGNORED, true) || $key === 'id') {
                continue;
            }

            $before = $old[$key] ?? null;
            $after = $new[$key] ?? null;

            if ($before === null && $after === null) {
                continue;
            }

            $changes[$key] = in_array($key, self::SECRET, true)
                ? ['old' => $before === null ? null : '••••••', 'new' => $after === null ? null : '••••••']
                : ['old' => self::clean($before), 'new' => self::clean($after)];
        }

        return $changes;
    }

    private static function clean(mixed $value): mixed
    {
        if (is_string($value) && mb_strlen($value) > self::MAX_VALUE_LENGTH) {
            return mb_substr($value, 0, self::MAX_VALUE_LENGTH).'…';
        }

        return $value;
    }
}
