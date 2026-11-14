<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * One recorded action in the admin: a model created/updated/deleted, a bulk
 * reorder, or an authentication event. Rows are append-only.
 */
class AuditLog extends Model
{
    public const UPDATED_AT = null;

    public const EVENTS = [
        'created' => 'Creó',
        'updated' => 'Editó',
        'deleted' => 'Eliminó',
        'reordered' => 'Reordenó',
        'login' => 'Inició sesión',
        'logout' => 'Cerró sesión',
        'login_failed' => 'Intento de acceso fallido',
    ];

    /** Section name shown for each audited model. */
    public const MODULES = [
        NavItem::class => 'Elementos de menú',
        Service::class => 'Servicios',
        Client::class => 'Clientes',
        Entity::class => 'Entidades',
        EntityLink::class => 'Dependencias',
        Componente::class => 'Componentes',
        InterestLink::class => 'Enlaces de interés',
        SupportPhone::class => 'Teléfonos de soporte',
        User::class => 'Usuarios',
    ];

    protected $fillable = [
        'user_id',
        'user_name',
        'event',
        'auditable_type',
        'auditable_id',
        'auditable_label',
        'changes',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'changes' => 'array',
        'created_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** When it happened, in the timezone people read the log in. */
    public function localTime(): Carbon
    {
        return $this->created_at->clone()->timezone(config('app.display_timezone'));
    }

    public function eventLabel(): string
    {
        return self::EVENTS[$this->event] ?? $this->event;
    }

    public function moduleLabel(): ?string
    {
        return $this->auditable_type ? (self::MODULES[$this->auditable_type] ?? class_basename($this->auditable_type)) : null;
    }
}
