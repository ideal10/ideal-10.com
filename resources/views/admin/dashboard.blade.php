<x-app-layout>
    <x-slot name="header">
        <x-admin.page-header title="Panel de administración" />
    </x-slot>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($groups as $group)
            @foreach ($group['items'] as $item)
                <a href="{{ route($item['index']) }}" class="card-interactive group flex items-center gap-4">
                    <div class="icon-tile icon-tile-hover h-12 w-12">
                        <x-admin.icon :name="$item['icon']" class="h-6 w-6" />
                    </div>
                    <div>
                        <p class="font-semibold text-slate-900">{{ $item['label'] }}</p>
                        <p class="text-sm text-slate-500">{{ $counts[$item['index']] ?? 0 }} registros</p>
                    </div>
                </a>
            @endforeach
        @endforeach

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
</x-app-layout>
