@php
    $groups = \App\Support\AdminNavigation::visibleGroups(auth()->user());
    $standalone = \App\Support\AdminNavigation::visibleStandalone(auth()->user());
@endphp

<aside id="admin-sidebar" class="admin-sidebar" aria-label="Menú de administración" tabindex="-1">
    <div class="flex h-full flex-col overflow-y-auto px-3 py-4">
        <a href="{{ route('admin.dashboard') }}" class="mb-6 flex items-center px-2" aria-label="Inicio del panel">
            {{-- Background is set via inline style (not a Tailwind bg-[url(...)] utility) so Vite's
                 CSS pipeline never fingerprints this path — it must stay exactly /assets/img/*. --}}
            <div class="h-10 w-[130px] bg-contain bg-no-repeat bg-left" style="background-image:url('/assets/img/big_logo_green.png')"></div>
        </a>

        <nav class="flex flex-1 flex-col gap-6">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'admin-sidebar-link-active' : 'admin-sidebar-link' }}">
                <x-admin.icon name="home" class="h-5 w-5 shrink-0" />
                Inicio
            </a>

            @foreach ($groups as $group)
                <div>
                    <p class="admin-sidebar-group-label mb-2">{{ $group['label'] }}</p>
                    <div class="space-y-1">
                        @foreach ($group['items'] as $item)
                            <a href="{{ route($item['index']) }}" class="{{ request()->routeIs($item['pattern']) ? 'admin-sidebar-link-active' : 'admin-sidebar-link' }}">
                                <x-admin.icon :name="$item['icon']" class="h-5 w-5 shrink-0" />
                                {{ $item['label'] }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <div class="mt-auto border-t border-slate-100 pt-4">
                @foreach ($standalone as $item)
                    <a href="{{ route($item['index']) }}" class="{{ request()->routeIs($item['pattern']) ? 'admin-sidebar-link-active' : 'admin-sidebar-link' }}">
                        <x-admin.icon :name="$item['icon']" class="h-5 w-5 shrink-0" />
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </div>
        </nav>
    </div>
</aside>
