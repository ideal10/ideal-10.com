<x-app-layout>
    <x-slot name="header">
        <x-admin.page-header title="Elementos de menú" />
    </x-slot>

    <x-admin.create-link :href="route('admin.nav-items.create')" label="Nuevo enlace" />

    <div class="card overflow-hidden p-0">
        <table class="min-w-full divide-y divide-slate-100">
            <thead class="bg-emerald-50/60">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-emerald-700">Orden</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-emerald-700">Texto</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-emerald-700">URL</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($navItems as $item)
                    <tr class="cursor-pointer hover:bg-emerald-50/40" data-edit-href="{{ route('admin.nav-items.edit', $item) }}">
                        <td class="px-6 py-4 text-sm text-slate-500">{{ $item->order }}</td>
                        <td class="px-6 py-4 text-sm text-slate-900">{{ $item->label }}</td>
                        <td class="px-6 py-4 text-sm text-slate-500">{{ $item->url }}</td>
                        <td class="px-6 py-4 text-right text-sm">
                            <a href="{{ route('admin.nav-items.edit', $item) }}" class="btn-icon-edit" title="Editar">
                                <x-admin.icon name="pencil" class="h-4 w-4" />
                                <span class="sr-only">Editar</span>
                            </a>
                            <button type="button" class="btn-icon-delete" data-modal-target="delete-modal" data-modal-toggle="delete-modal" data-delete-action="{{ route('admin.nav-items.destroy', $item) }}" title="Eliminar">
                                <x-admin.icon name="trash" class="h-4 w-4" />
                                <span class="sr-only">Eliminar</span>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <x-admin.delete-modal title="¿Eliminar este enlace de navegación?" />
</x-app-layout>
