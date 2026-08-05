<x-app-layout>
    <x-slot name="header">
        <x-admin.page-header title="Entidades (menú)" action-label="Nueva entidad" :action-route="route('admin.entities.create')" />
    </x-slot>

    <div class="card overflow-hidden p-0">
        <table class="min-w-full divide-y divide-slate-100">
            <thead class="bg-emerald-50/60">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-emerald-700">Nombre</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-emerald-700">Slug</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-emerald-700">Enlaces</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($entities as $entity)
                    <tr class="cursor-pointer hover:bg-emerald-50/40" data-edit-href="{{ route('admin.entities.edit', $entity) }}">
                        <td class="px-6 py-4 text-sm text-slate-900">{{ $entity->name }}</td>
                        <td class="px-6 py-4 text-sm text-slate-500">/menu_entidades/{{ $entity->slug }}</td>
                        <td class="px-6 py-4 text-sm text-slate-500">{{ $entity->links_count }}</td>
                        <td class="px-6 py-4 text-right text-sm">
                            <a href="{{ route('admin.entities.edit', $entity) }}" class="btn-icon-edit" title="Editar">
                                <x-admin.icon name="pencil" class="h-4 w-4" />
                                <span class="sr-only">Editar</span>
                            </a>
                            <button type="button" class="btn-icon-delete" data-modal-target="delete-modal" data-modal-toggle="delete-modal" data-delete-action="{{ route('admin.entities.destroy', $entity) }}" title="Eliminar">
                                <x-admin.icon name="trash" class="h-4 w-4" />
                                <span class="sr-only">Eliminar</span>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <x-admin.delete-modal title="¿Eliminar esta entidad?" text="Se eliminará junto con todos sus enlaces. Esta acción no se puede deshacer." />
</x-app-layout>
