<?php

namespace App\Support;

use App\Models\Client;
use App\Models\Componente;
use App\Models\Entity;
use App\Models\InterestLink;
use App\Models\NavItem;
use App\Models\Service;
use App\Models\User;

class AdminNavigation
{
    /**
     * Grouped sidebar sections. Single source of truth for both the
     * sidebar (App\Support\AdminNavigation::groups()) and the dashboard
     * quick-links + counts.
     */
    public static function groups(): array
    {
        return [
            [
                'label' => 'Navegación',
                'items' => [
                    ['label' => 'Elementos de menú', 'icon' => 'list', 'index' => 'admin.nav-items.index', 'pattern' => 'admin.nav-items.*', 'model' => NavItem::class],
                ],
            ],
            [
                'label' => 'Contenido',
                'items' => [
                    ['label' => 'Servicios', 'icon' => 'briefcase', 'index' => 'admin.services.index', 'pattern' => 'admin.services.*', 'model' => Service::class],
                    ['label' => 'Clientes', 'icon' => 'users', 'index' => 'admin.clients.index', 'pattern' => 'admin.clients.*', 'model' => Client::class],
                    ['label' => 'Entidades', 'icon' => 'building', 'index' => 'admin.entities.index', 'pattern' => 'admin.entities.*', 'model' => Entity::class],
                    ['label' => 'Componentes', 'icon' => 'cube', 'index' => 'admin.componentes.index', 'pattern' => 'admin.componentes.*', 'model' => Componente::class],
                    ['label' => 'Enlaces de interés', 'icon' => 'link', 'index' => 'admin.interest-links.index', 'pattern' => 'admin.interest-links.*', 'model' => InterestLink::class],
                ],
            ],
            [
                'label' => 'Sistema',
                'adminOnly' => true,
                'items' => [
                    ['label' => 'Usuarios', 'icon' => 'shield-check', 'index' => 'admin.users.index', 'pattern' => 'admin.users.*', 'model' => User::class],
                ],
            ],
        ];
    }

    /**
     * Groups visible to the given user — filters out admin-only groups
     * (e.g. "Sistema") for non-admin users.
     */
    public static function visibleGroups(?User $user): array
    {
        return array_values(array_filter(
            self::groups(),
            fn (array $group) => empty($group['adminOnly']) || $user?->is_admin
        ));
    }

    /**
     * Sidebar items that don't belong to a group (rendered separately).
     */
    public static function standalone(): array
    {
        return [
            ['label' => 'Configuración', 'icon' => 'cog', 'index' => 'admin.site-settings.edit', 'pattern' => 'admin.site-settings.*', 'adminOnly' => true],
        ];
    }

    /**
     * Standalone items visible to the given user — filters out admin-only
     * items (e.g. "Configuración") for non-admin users.
     */
    public static function visibleStandalone(?User $user): array
    {
        return array_values(array_filter(
            self::standalone(),
            fn (array $item) => empty($item['adminOnly']) || $user?->is_admin
        ));
    }
}
