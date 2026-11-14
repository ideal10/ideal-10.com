@php
    $fieldLabels = [
        'name' => 'Nombre', 'title' => 'Título', 'label' => 'Etiqueta', 'url' => 'URL', 'href' => 'Enlace',
        'slug' => 'Identificador', 'logo' => 'Logo subido', 'client_id' => 'Logo de cliente', 'order' => 'Orden',
        'active' => 'Activo', 'extra' => 'Solo en carrusel', 'description' => 'Descripción', 'svg' => 'Ícono SVG',
        'img' => 'Imagen', 'original_name' => 'Nombre del archivo', 'icon' => 'Ícono', 'number' => 'Número',
        'type' => 'Tipo', 'email' => 'Correo', 'password' => 'Contraseña', 'email_verified_at' => 'Correo verificado',
        'entity_id' => 'Entidad', 'body' => 'Resumen', 'paths' => 'Rutas', 'wide' => 'Ancho completo',
        'content' => 'Contenido', 'match' => 'Coincidencia',
    ];
    $booleanFields = ['active', 'extra', 'wide'];

    $formatValue = function (string $field, mixed $value) use ($booleanFields, $lookups) {
        if ($value === null || $value === '') {
            return null;
        }
        if (in_array($field, $booleanFields, true)) {
            return $value ? 'Sí' : 'No';
        }
        if (isset($lookups[$field])) {
            return $lookups[$field][$value] ?? "#{$value} (ya no existe)";
        }
        if (is_array($value)) {
            return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }

        return (string) $value;
    };

    $eventStyles = [
        'created' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
        'updated' => 'bg-amber-50 text-amber-700 ring-amber-200',
        'deleted' => 'bg-red-50 text-red-700 ring-red-200',
        'reordered' => 'bg-sky-50 text-sky-700 ring-sky-200',
        'login' => 'bg-slate-100 text-slate-700 ring-slate-200',
        'logout' => 'bg-slate-100 text-slate-500 ring-slate-200',
        'login_failed' => 'bg-red-600 text-white ring-red-600',
    ];

    $hasFilters = collect($filters)->filter()->isNotEmpty();

    $dayLabel = function ($date) {
        $today = now(config('app.display_timezone'));
        if ($date->isSameDay($today)) return 'Hoy';
        if ($date->isSameDay($today->clone()->subDay())) return 'Ayer';

        return ucfirst($date->translatedFormat('l j \d\e F \d\e Y'));
    };
@endphp

<x-app-layout>
    <x-slot name="header">
        <x-admin.page-header title="Auditoría" />
    </x-slot>

    {{-- Filters --}}
    <form method="GET" action="{{ route('admin.audit-logs.index') }}" class="card mb-6 !p-4">
        <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-[minmax(0,2fr)_repeat(3,minmax(0,1fr))_auto]">
            <label class="relative block sm:col-span-2 xl:col-span-1">
                <span class="sr-only">Buscar</span>
                <span class="pointer-events-none absolute inset-y-0 left-3.5 flex items-center text-slate-400">
                    <x-admin.icon name="magnifying-glass" class="h-4 w-4" />
                </span>
                <input type="search" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Buscar registro, persona o IP…" class="field-input !py-2.5 pl-10">
            </label>

            <select name="user" class="field-input !py-2.5" aria-label="Persona">
                <option value="">Todas las personas</option>
                @foreach ($users as $user)
                    <option value="{{ $user }}" @selected(($filters['user'] ?? null) === $user)>{{ $user }}</option>
                @endforeach
            </select>

            <select name="module" class="field-input !py-2.5" aria-label="Sección">
                <option value="">Todas las secciones</option>
                @foreach ($modules as $class => $label)
                    <option value="{{ $class }}" @selected(($filters['module'] ?? null) === $class)>{{ $label }}</option>
                @endforeach
            </select>

            <select name="event" class="field-input !py-2.5" aria-label="Acción">
                <option value="">Todas las acciones</option>
                @foreach ($events as $key => $label)
                    <option value="{{ $key }}" @selected(($filters['event'] ?? null) === $key)>{{ $label }}</option>
                @endforeach
            </select>

            <div class="flex items-center gap-2">
                <button type="submit" class="btn-primary !px-5 !py-2.5 flex-1">Filtrar</button>
                @if ($hasFilters)
                    <a href="{{ route('admin.audit-logs.index') }}" class="btn-icon" title="Quitar filtros">
                        <x-admin.icon name="x-mark" class="h-5 w-5" />
                        <span class="sr-only">Quitar filtros</span>
                    </a>
                @endif
            </div>
        </div>

        <div class="mt-3 flex flex-wrap items-center gap-3 text-sm text-slate-500">
            <label class="flex items-center gap-2">
                Desde
                <input type="date" name="from" value="{{ $filters['from'] ?? '' }}" class="field-input !w-auto !py-1.5">
            </label>
            <label class="flex items-center gap-2">
                Hasta
                <input type="date" name="to" value="{{ $filters['to'] ?? '' }}" class="field-input !w-auto !py-1.5">
            </label>
            <span class="ml-auto">{{ number_format($logs->total(), 0, ',', '.') }} {{ $logs->total() === 1 ? 'registro' : 'registros' }}</span>
        </div>
    </form>

    @if ($logs->isEmpty())
        <div class="card flex flex-col items-center py-16 text-center">
            <span class="icon-tile h-14 w-14"><x-admin.icon name="clipboard" class="h-7 w-7" /></span>
            @if ($hasFilters)
                <p class="mt-4 font-semibold text-slate-900">No hay acciones con esos filtros.</p>
                <a href="{{ route('admin.audit-logs.index') }}" class="mt-2 text-sm font-semibold text-emerald-700 hover:underline">Quitar filtros</a>
            @else
                <p class="mt-4 font-semibold text-slate-900">Todavía no hay acciones registradas.</p>
                <p class="mt-1 text-sm text-slate-500">Aquí aparecerá cada cambio que se haga en el admin y cada inicio de sesión.</p>
            @endif
        </div>
    @else
        <div class="space-y-6">
            @foreach ($logs->groupBy(fn ($log) => $log->localTime()->toDateString()) as $day => $dayLogs)
                <section>
                    <h2 class="mb-2 px-1 text-sm font-bold text-slate-500">{{ $dayLabel($dayLogs->first()->localTime()) }}</h2>

                    <ol class="divide-y divide-slate-100 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-100">
                        @foreach ($dayLogs as $log)
                            @php($changes = $log->changes ?? [])
                            <li x-data="{ open: false }">
                                <div class="flex items-start gap-4 px-5 py-3.5">
                                    <time class="w-12 shrink-0 pt-0.5 text-sm tabular-nums text-slate-400" datetime="{{ $log->created_at->toIso8601String() }}" title="{{ $log->localTime()->format('d/m/Y H:i:s') }}">
                                        {{ $log->localTime()->format('H:i') }}
                                    </time>

                                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-slate-100 text-xs font-bold text-slate-600" aria-hidden="true">
                                        {{ $log->user_name ? \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($log->user_name, 0, 1)) : '?' }}
                                    </span>

                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm leading-6 text-slate-700">
                                            <span class="font-semibold text-slate-900">{{ $log->user_name ?? 'Sin identificar' }}</span>
                                            <span class="mx-1 inline-flex items-center rounded-full px-2 py-0.5 align-middle text-xs font-semibold ring-1 {{ $eventStyles[$log->event] ?? 'bg-slate-100 text-slate-700 ring-slate-200' }}">{{ $log->eventLabel() }}</span>
                                            @if ($log->event === 'login_failed')
                                                como <span class="font-semibold text-slate-900">{{ $log->auditable_label ?: '(vacío)' }}</span>
                                            @elseif (! in_array($log->event, ['login', 'logout'], true))
                                                @if ($log->moduleLabel())
                                                    <span class="text-slate-500">{{ $log->moduleLabel() }}</span>
                                                    @if ($log->auditable_label)
                                                        <span class="text-slate-400">›</span>
                                                    @endif
                                                @endif
                                                <span class="font-semibold text-slate-900">{{ $log->auditable_label }}</span>
                                            @endif
                                        </p>

                                        @if ($changes && $log->event === 'updated')
                                            <p class="mt-0.5 text-xs text-slate-500">
                                                Cambió: {{ collect(array_keys($changes))->map(fn ($field) => \Illuminate\Support\Str::lower($fieldLabels[$field] ?? $field))->join(', ', ' y ') }}
                                            </p>
                                        @endif
                                    </div>

                                    <div class="flex shrink-0 items-center gap-2">
                                        @if ($log->ip_address)
                                            <span class="hidden text-xs tabular-nums text-slate-400 md:inline" title="{{ $log->user_agent }}">{{ $log->ip_address }}</span>
                                        @endif
                                        @if ($changes)
                                            <button type="button" @click="open = !open" class="btn-icon" :aria-expanded="open" title="Ver detalle">
                                                <span class="transition" :class="open && 'rotate-180'"><x-admin.icon name="chevron-down" class="h-4 w-4" /></span>
                                                <span class="sr-only">Ver detalle</span>
                                            </button>
                                        @endif
                                    </div>
                                </div>

                                @if ($changes)
                                    <div x-show="open" x-cloak class="border-t border-slate-100 bg-slate-50/70 px-5 py-4 sm:pl-[6.5rem]">
                                        <table class="w-full table-fixed text-left text-sm">
                                            <thead>
                                                <tr class="text-xs text-slate-500">
                                                    <th class="w-1/4 pb-2 font-semibold">Campo</th>
                                                    @if ($log->event !== 'created')<th class="pb-2 font-semibold">Antes</th>@endif
                                                    @if ($log->event !== 'deleted')<th class="pb-2 font-semibold">{{ $log->event === 'created' ? 'Valor' : 'Después' }}</th>@endif
                                                </tr>
                                            </thead>
                                            <tbody class="align-top">
                                                @foreach ($changes as $field => $change)
                                                    @php($before = $formatValue($field, $change['old'] ?? null))
                                                    @php($after = $formatValue($field, $change['new'] ?? null))
                                                    <tr class="border-t border-slate-200/70">
                                                        <td class="py-2 pr-3 font-medium text-slate-700">{{ $fieldLabels[$field] ?? $field }}</td>
                                                        @if ($log->event !== 'created')
                                                            <td class="py-2 pr-3 break-words {{ $log->event === 'updated' ? 'text-red-700/80 line-through decoration-red-300' : 'text-slate-700' }}">{{ $before ?? '—' }}</td>
                                                        @endif
                                                        @if ($log->event !== 'deleted')
                                                            <td class="py-2 break-words {{ $log->event === 'updated' ? 'text-emerald-800' : 'text-slate-700' }}">{{ $after ?? '—' }}</td>
                                                        @endif
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        @if ($log->user_agent)
                                            <p class="mt-3 truncate text-xs text-slate-400">{{ $log->ip_address }} · {{ $log->user_agent }}</p>
                                        @endif
                                    </div>
                                @endif
                            </li>
                        @endforeach
                    </ol>
                </section>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $logs->links() }}
        </div>
    @endif
</x-app-layout>
