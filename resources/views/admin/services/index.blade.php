<x-app-layout>
    <x-slot name="header">
        <x-admin.page-header title="Servicios" action-label="Nuevo servicio" :action-route="route('admin.services.create')" />
    </x-slot>

    <div class="card overflow-hidden p-0">
        <table class="min-w-full divide-y divide-slate-100">
            <thead class="bg-emerald-50/60">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-emerald-700">Orden</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-emerald-700">Nombre</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-emerald-700">Icono</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($services as $service)
                    <tr class="hover:bg-emerald-50/40">
                        <td class="px-6 py-4 text-sm text-slate-500">{{ $service->order }}</td>
                        <td class="px-6 py-4 text-sm text-slate-900">{{ $service->name }}</td>
                        <td class="px-6 py-4 text-sm text-slate-500">{{ $service->svg }}</td>
                        <td class="px-6 py-4 text-right text-sm">
                            <a href="{{ route('admin.services.edit', $service) }}" class="btn-icon-edit" title="Editar">
                                <x-admin.icon name="pencil" class="h-4 w-4" />
                                <span class="sr-only">Editar</span>
                            </a>
                            <button type="button" class="btn-icon-delete" data-modal-target="delete-modal" data-modal-toggle="delete-modal" data-delete-action="{{ route('admin.services.destroy', $service) }}" title="Eliminar">
                                <x-admin.icon name="trash" class="h-4 w-4" />
                                <span class="sr-only">Eliminar</span>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <x-admin.delete-modal title="¿Eliminar este servicio?" />
</x-app-layout>
