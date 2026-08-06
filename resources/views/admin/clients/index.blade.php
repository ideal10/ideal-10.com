<x-app-layout>
    <x-slot name="header">
        <x-admin.page-header title="Clientes" />
    </x-slot>

    <x-admin.create-link :href="route('admin.clients.create')" label="Nuevo cliente" />

    <div class="card overflow-hidden p-0">
        <table class="min-w-full divide-y divide-slate-100">
            <thead class="bg-emerald-50/60">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-emerald-700">Orden</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-emerald-700">Nombre</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-emerald-700">Imagen</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-emerald-700">Solo ticker</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($clients as $client)
                    <tr class="cursor-pointer hover:bg-emerald-50/40" data-edit-href="{{ route('admin.clients.edit', $client) }}">
                        <td class="px-6 py-4 text-sm text-slate-500">{{ $client->order }}</td>
                        <td class="px-6 py-4 text-sm text-slate-900">{{ $client->name }}</td>
                        <td class="px-6 py-4"><img src="{{ $client->img }}" alt="{{ $client->name }}" class="h-8 w-8 rounded border border-slate-200 object-contain"></td>
                        <td class="px-6 py-4 text-sm text-slate-500">{{ $client->extra ? 'Sí' : 'No' }}</td>
                        <td class="px-6 py-4 text-right text-sm">
                            <a href="{{ route('admin.clients.edit', $client) }}" class="btn-icon-edit" title="Editar">
                                <x-admin.icon name="pencil" class="h-4 w-4" />
                                <span class="sr-only">Editar</span>
                            </a>
                            <button type="button" class="btn-icon-delete" data-modal-target="delete-modal" data-modal-toggle="delete-modal" data-delete-action="{{ route('admin.clients.destroy', $client) }}" title="Eliminar">
                                <x-admin.icon name="trash" class="h-4 w-4" />
                                <span class="sr-only">Eliminar</span>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <x-admin.delete-modal title="¿Eliminar este cliente?" />
</x-app-layout>
