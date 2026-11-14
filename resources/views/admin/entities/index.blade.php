<x-app-layout>
    <x-slot name="header">
        <x-admin.page-header title="Entidades" />
    </x-slot>

    <div x-data="{ query: '' }">
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-slate-500">
                    {{ $entities->count() }} {{ $entities->count() === 1 ? 'entidad' : 'entidades' }} ·
                    {{ $entities->sum('links_count') }} subentidades en total
                </p>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                @if ($entities->count() > 3)
                    <label class="relative block">
                        <span class="sr-only">Buscar entidad</span>
                        <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400">
                            <x-admin.icon name="magnifying-glass" class="h-4 w-4" />
                        </span>
                        <input type="search" x-model="query" placeholder="Buscar entidad…" class="field-input !py-2.5 pl-9 sm:w-64">
                    </label>
                @endif

                <a href="{{ route('admin.entities.create') }}" class="btn-primary !px-4 !py-2.5">
                    <x-admin.icon name="plus" class="h-4 w-4" />
                    Nueva entidad
                </a>
            </div>
        </div>

        @if ($entities->isEmpty())
            <div class="card flex flex-col items-center py-16 text-center">
                <span class="icon-tile h-16 w-16">
                    <x-admin.icon name="building" class="h-8 w-8" />
                </span>
                <h2 class="mt-5 text-lg font-bold text-slate-900">Aún no hay entidades</h2>
                <p class="mt-1 max-w-sm text-sm text-slate-500">Crea la primera, por ejemplo una alcaldía, y agrega sus subentidades: concejo, personería, servicios públicos…</p>
                <a href="{{ route('admin.entities.create') }}" class="btn-primary mt-6">Crear entidad</a>
            </div>
        @else
            <ul class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
                @foreach ($entities as $entity)
                    <li
                        x-show="!query || {{ \Illuminate\Support\Js::from(\Illuminate\Support\Str::lower(\Illuminate\Support\Str::ascii($entity->name.' '.$entity->links->pluck('name')->join(' ')))) }}.includes(query.toLowerCase().normalize('NFD').replace(/[̀-ͯ]/g, ''))"
                        class="group relative flex flex-col overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:border-emerald-200 hover:shadow-xl hover:shadow-emerald-100/60"
                    >
                        <div class="flex items-center gap-4 p-5">
                            <x-entity-logo :model="$entity" class="h-16 w-16 rounded-2xl text-xl" />

                            <div class="min-w-0">
                                <h2 class="truncate text-base font-bold text-slate-900">
                                    <a href="{{ route('admin.entities.edit', $entity) }}" class="after:absolute after:inset-0 focus:outline-none">
                                        {{ $entity->name }}
                                    </a>
                                </h2>
                            </div>
                        </div>

                        <div class="flex-1 border-t border-slate-100 px-5 py-4">
                            @if ($entity->links->isEmpty())
                                <p class="text-sm text-slate-400">Sin subentidades todavía</p>
                            @else
                                <div class="flex items-center gap-3">
                                    <div class="flex -space-x-2">
                                        @foreach ($entity->links->take(5) as $link)
                                            <x-entity-logo :model="$link" class="h-8 w-8 rounded-full text-[10px] ring-2 ring-white" :title="$link->name" />
                                        @endforeach
                                    </div>
                                    <span class="text-sm font-semibold text-slate-700">
                                        {{ $entity->links_count }} {{ $entity->links_count === 1 ? 'subentidad' : 'subentidades' }}
                                    </span>
                                </div>
                                <p class="mt-2 line-clamp-2 text-xs leading-5 text-slate-500">{{ $entity->links->pluck('name')->join(', ') }}</p>
                            @endif
                        </div>

                        <div class="relative z-10 flex items-center justify-end gap-1 border-t border-slate-100 bg-slate-50/60 px-3 py-2">
                            <a href="{{ route('admin.entities.edit', $entity) }}" class="btn-icon-edit" title="Editar">
                                <x-admin.icon name="pencil" class="h-4 w-4" />
                                <span class="sr-only">Editar</span>
                            </a>
                            <button type="button" class="btn-icon-delete" data-modal-target="delete-modal" data-modal-toggle="delete-modal" data-delete-action="{{ route('admin.entities.destroy', $entity) }}" title="Eliminar">
                                <x-admin.icon name="trash" class="h-4 w-4" />
                                <span class="sr-only">Eliminar</span>
                            </button>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <x-admin.delete-modal title="¿Eliminar esta entidad?" text="Se eliminarán también todas sus subentidades y logos. Esta acción no se puede deshacer." />
</x-app-layout>
