@php
    $search = fn (string $text) => \Illuminate\Support\Str::lower(\Illuminate\Support\Str::ascii($text));

    // id => [entity search text, [sub-entity search text, ...]]
    $searchIndex = $entities->mapWithKeys(fn ($entity) => [
        $entity->id => [
            $search($entity->name.' '.$entity->slug),
            $entity->links->map(fn ($link) => $search($link->name.' '.$link->host()))->values(),
        ],
    ]);

@endphp

<x-app-layout>
    <x-slot name="header">
        <x-admin.page-header title="Directorio de entidades" />
    </x-slot>

    <div x-data="entityDirectory({{ \Illuminate\Support\Js::from($searchIndex) }})" @keydown.escape.window="close()">
        {{-- Search --}}
        @if ($entities->isNotEmpty())
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <label class="relative block w-full sm:max-w-sm">
                    <span class="sr-only">Buscar entidad o dependencia</span>
                    <span class="pointer-events-none absolute inset-y-0 left-3.5 flex items-center text-slate-400">
                        <x-admin.icon name="magnifying-glass" class="h-4 w-4" />
                    </span>
                    <input type="search" x-model="query" placeholder="Buscar municipio o dependencia…" class="field-input !py-2.5 pl-10">
                </label>
                <p class="text-sm text-slate-500" x-show="query" x-cloak x-text="visibleCount === 1 ? '1 entidad coincide' : `${visibleCount} entidades coinciden`"></p>
            </div>
        @endif

        @if ($entities->isEmpty())
            <div class="card flex flex-col items-center py-16 text-center">
                <span class="icon-tile h-16 w-16"><x-admin.icon name="squares" class="h-8 w-8" /></span>
                <h2 class="mt-5 text-lg font-bold text-slate-900">El directorio está vacío</h2>
                <p class="mt-1 max-w-sm text-sm text-slate-500">Cuando agregues entidades y sus dependencias aparecerán aquí.</p>
                <a href="{{ route('admin.entities.create') }}" class="btn-primary mt-6">Agregar entidad</a>
            </div>
        @else
            {{-- Entity cards --------------------------------------------- --}}
            <ul class="mt-6 grid gap-6 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
                @foreach ($entities as $entity)
                    <li x-show="entityVisible({{ $entity->id }})" x-transition.opacity>
                        <button
                            type="button"
                            @click="open({{ $entity->id }})"
                            class="group flex h-full w-full flex-col overflow-hidden rounded-3xl bg-white text-left shadow-sm ring-1 ring-slate-100 transition duration-300 ease-out hover:-translate-y-1.5 hover:shadow-2xl hover:shadow-emerald-900/10 hover:ring-emerald-200 focus:outline-none focus-visible:ring-4 focus-visible:ring-emerald-300"
                        >
                            {{-- Cover: the logo itself, blown up and blurred, tints each card with its own colors. --}}
                            <span class="relative block h-36 overflow-hidden bg-gradient-to-br from-emerald-500 to-teal-700">
                                @if ($entity->logo_url)
                                    <img src="{{ $entity->logo_url }}" alt="" class="absolute inset-0 h-full w-full scale-150 object-cover opacity-70 blur-2xl saturate-150" aria-hidden="true">
                                    <span class="absolute inset-0 bg-gradient-to-b from-white/10 to-white/40"></span>
                                @else
                                    <span class="absolute inset-0 opacity-25 [background-image:radial-gradient(rgba(255,255,255,.8)_1px,transparent_1px)] [background-size:14px_14px]"></span>
                                @endif
                            </span>

                            <span class="relative -mt-14 flex justify-center">
                                <span class="rounded-[1.75rem] bg-white p-1.5 shadow-xl shadow-slate-900/10 transition duration-300 group-hover:scale-105 group-hover:-rotate-2">
                                    <x-entity-logo :model="$entity" class="h-24 w-24 rounded-3xl text-3xl" />
                                </span>
                            </span>

                            <span class="flex flex-1 flex-col items-center px-5 pt-4 pb-5 text-center">
                                <span class="text-lg font-extrabold leading-tight text-slate-900">{{ $entity->name }}</span>
                                <span class="mt-1 text-sm text-slate-500">
                                    {{ $entity->links->count() }} {{ $entity->links->count() === 1 ? 'dependencia' : 'dependencias' }}
                                </span>

                                {{-- While searching, name the sub-entities that matched. --}}
                                <span class="mt-3 flex flex-wrap justify-center gap-1.5" x-show="matchingLinks({{ $entity->id }}).length" x-cloak>
                                    @foreach ($entity->links as $i => $link)
                                        <span x-show="matchingLinks({{ $entity->id }}).includes({{ $i }})" class="rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-200">{{ $link->name }}</span>
                                    @endforeach
                                </span>

                                @if ($entity->links->isNotEmpty())
                                    <span class="mt-auto flex items-center pt-4" x-show="!matchingLinks({{ $entity->id }}).length">
                                        <span class="flex -space-x-2.5">
                                            @foreach ($entity->links->take(5) as $link)
                                                <x-entity-logo :model="$link" class="h-9 w-9 rounded-full text-[10px] ring-[3px] ring-white" />
                                            @endforeach
                                        </span>
                                        @if ($entity->links->count() > 5)
                                            <span class="-ml-2.5 flex h-9 w-9 items-center justify-center rounded-full bg-slate-100 text-[11px] font-bold text-slate-600 ring-[3px] ring-white">+{{ $entity->links->count() - 5 }}</span>
                                        @endif
                                    </span>
                                @endif
                            </span>
                        </button>
                    </li>
                @endforeach
            </ul>

            <div x-show="query && visibleCount === 0" x-cloak class="mt-8 rounded-3xl border-2 border-dashed border-slate-200 py-14 text-center">
                <p class="text-lg font-bold text-slate-900">Nada coincide con “<span x-text="query"></span>”</p>
                <p class="mt-1 text-sm text-slate-500">Prueba con el nombre del municipio o de la dependencia.</p>
            </div>

            {{-- Side panel ---------------------------------------------- --}}
            <div x-show="selected !== null" x-cloak class="fixed inset-0 z-50" role="dialog" aria-modal="true">
                <div x-show="selected !== null" x-transition.opacity.duration.300ms @click="close()" class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"></div>

                <div
                    x-show="selected !== null"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="translate-x-full"
                    x-transition:enter-end="translate-x-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="translate-x-0"
                    x-transition:leave-end="translate-x-full"
                    class="absolute inset-y-0 right-0 flex w-full max-w-xl flex-col overflow-hidden bg-slate-50 shadow-2xl"
                >
                    @foreach ($entities as $entity)
                        <template x-if="selected === {{ $entity->id }}">
                            <div class="flex h-full flex-col overflow-y-auto">
                                {{-- Panel cover --}}
                                <header class="relative shrink-0 overflow-hidden bg-gradient-to-br from-emerald-600 to-teal-800 px-6 pt-6 pb-8 text-white sm:px-8">
                                    @if ($entity->logo_url)
                                        <img src="{{ $entity->logo_url }}" alt="" class="absolute inset-0 h-full w-full scale-150 object-cover opacity-50 blur-3xl saturate-150" aria-hidden="true">
                                        <span class="absolute inset-0 bg-gradient-to-t from-slate-900/70 via-slate-900/30 to-transparent"></span>
                                    @else
                                        <span class="absolute inset-0 opacity-20 [background-image:radial-gradient(rgba(255,255,255,.8)_1px,transparent_1px)] [background-size:16px_16px]"></span>
                                    @endif

                                    <div class="relative flex justify-end">
                                        <button type="button" x-ref="panelClose" @click="close()" class="rounded-full bg-white/15 p-2 text-white ring-1 ring-white/30 backdrop-blur transition hover:bg-white/25 focus:outline-none focus-visible:ring-4 focus-visible:ring-white/50" title="Cerrar">
                                            <x-admin.icon name="x-mark" class="h-5 w-5" />
                                            <span class="sr-only">Cerrar</span>
                                        </button>
                                    </div>

                                    <div class="relative mt-2 flex flex-col items-center text-center sm:flex-row sm:items-end sm:gap-6 sm:text-left">
                                        <span class="rounded-[2rem] bg-white p-2 shadow-2xl">
                                            <x-entity-logo :model="$entity" class="h-28 w-28 rounded-[1.6rem] text-4xl" />
                                        </span>
                                        <div class="mt-4 min-w-0 sm:mt-0 sm:pb-1">
                                            <h2 class="text-2xl font-extrabold tracking-tight drop-shadow sm:text-3xl">{{ $entity->name }}</h2>
                                            <p class="mt-1 text-sm text-white/85">{{ $entity->links->count() }} {{ $entity->links->count() === 1 ? 'dependencia' : 'dependencias' }}</p>
                                            <div class="mt-3 flex flex-wrap justify-center gap-2 sm:justify-start">
                                                <a href="{{ route('admin.entities.edit', $entity) }}" class="inline-flex items-center gap-1.5 rounded-full bg-white/15 px-3.5 py-1.5 text-xs font-bold text-white ring-1 ring-white/40 backdrop-blur transition hover:bg-white/25">
                                                    <x-admin.icon name="pencil" class="h-3.5 w-3.5" />
                                                    Editar
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </header>

                                {{-- Sub-entities --}}
                                <div class="flex-1 px-6 py-6 sm:px-8">
                                    @if ($entity->links->isEmpty())
                                        <div class="rounded-3xl border-2 border-dashed border-slate-200 bg-white py-12 text-center">
                                            <p class="font-bold text-slate-900">Todavía no tiene dependencias</p>
                                            <a href="{{ route('admin.entities.edit', $entity) }}" class="mt-3 inline-block text-sm font-semibold text-emerald-700 hover:underline">Agregar dependencias</a>
                                        </div>
                                    @else
                                        <ul class="divide-y divide-slate-100 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-100">
                                            @foreach ($entity->links as $link)
                                                <li>
                                                    <a
                                                        href="{{ $link->href }}"
                                                        target="_blank"
                                                        rel="noopener"
                                                        class="group flex items-center gap-4 px-4 py-3 transition hover:bg-emerald-50/60 focus:outline-none focus-visible:bg-emerald-50"
                                                    >
                                                        <x-entity-logo :model="$link" class="h-11 w-11 rounded-xl text-sm" />
                                                        <span class="min-w-0 flex-1">
                                                            <span class="block truncate text-sm font-bold text-slate-900 group-hover:text-emerald-700">{{ $link->name }}</span>
                                                            <span class="block truncate text-xs text-slate-500">{{ $link->host() }}</span>
                                                        </span>
                                                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-slate-100 text-slate-400 transition group-hover:bg-emerald-600 group-hover:text-white">
                                                            <x-admin.icon name="arrow-up-right" class="h-4 w-4" />
                                                        </span>
                                                        <span class="sr-only">(se abre en una pestaña nueva)</span>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        </template>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
