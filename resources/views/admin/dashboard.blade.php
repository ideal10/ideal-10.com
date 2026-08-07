<x-app-layout>
    <x-slot name="header">
        <x-admin.page-header title="Panel de administración" />
    </x-slot>

    <div class="space-y-10">
        <div>
            <h2 class="text-lg font-bold text-slate-900">Hola, {{ Auth::user()->name }}</h2>
            <p class="mt-1 text-sm text-slate-500">{{ now()->format('d/m/Y') }} &middot; {{ $totalItems }} elementos de contenido en total</p>
        </div>

        {{-- Content sections, grouped — doubles as counts + quick access --}}
        <div class="space-y-8">
            @foreach ($groups as $group)
                <div>
                    <p class="admin-sidebar-group-label mb-4">{{ $group['label'] }}</p>
                    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($group['items'] as $item)
                            <a href="{{ route($item['index']) }}" class="card-interactive group flex items-center gap-4">
                                <div class="icon-tile icon-tile-hover h-12 w-12">
                                    <x-admin.icon :name="$item['icon']" class="h-6 w-6" />
                                </div>
                                <div>
                                    <p class="text-2xl font-extrabold leading-none text-slate-900">{{ $counts[$item['index']] ?? 0 }}</p>
                                    <p class="mt-1.5 text-sm font-semibold text-slate-600">{{ $item['label'] }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            {{-- Recent activity --}}
            <div class="card lg:col-span-2">
                <h3 class="font-bold text-slate-900">Actividad reciente</h3>
                <p class="mt-1 text-sm text-slate-500">Últimos elementos creados o editados en el sitio.</p>

                <ul class="mt-5 divide-y divide-slate-100">
                    @forelse ($recent as $entry)
                        <li>
                            <a href="{{ route($entry['edit_route'], $entry['model']) }}" class="group flex items-center gap-4 py-4">
                                <div class="icon-tile icon-tile-hover h-10 w-10">
                                    <x-admin.icon :name="$entry['icon']" class="h-5 w-5" />
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-semibold text-slate-900">{{ $entry['title'] }}</p>
                                    <p class="mt-0.5 text-xs text-slate-500">{{ $entry['label'] }}</p>
                                </div>
                                <span class="shrink-0 text-xs text-slate-400">{{ $entry['updated_at']->format('d/m/Y H:i') }}</span>
                            </a>
                        </li>
                    @empty
                        <li class="py-8 text-center text-sm text-slate-500">Todavía no hay actividad registrada.</li>
                    @endforelse
                </ul>
            </div>

            {{-- Side column: needs attention + settings --}}
            <div class="space-y-6">
                <div class="card-soft">
                    <h3 class="font-bold text-slate-900">Por revisar</h3>
                    <a href="{{ route('admin.interest-links.index') }}" class="mt-4 flex items-center justify-between rounded-xl bg-white/70 px-4 py-3.5 transition hover:bg-white">
                        <span class="text-sm font-semibold text-slate-700">Enlaces de interés inactivos</span>
                        <span class="text-lg font-extrabold text-emerald-700">{{ $inactiveLinks }}</span>
                    </a>
                </div>

                @foreach ($standalone as $item)
                    <a href="{{ route($item['index']) }}" class="card-interactive group flex items-center gap-4">
                        <div class="icon-tile icon-tile-hover h-12 w-12">
                            <x-admin.icon :name="$item['icon']" class="h-6 w-6" />
                        </div>
                        <div>
                            <p class="font-semibold text-slate-900">{{ $item['label'] }}</p>
                            <p class="text-sm text-slate-500">Ajustes del sitio</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
