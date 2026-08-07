<x-app-layout>
    <x-slot name="header">
        <x-admin.page-header title="Usuarios" />
    </x-slot>

    <x-admin.create-link :href="route('admin.users.create')" label="Nuevo usuario" />

    <div class="card overflow-hidden p-0">
        <table class="min-w-full divide-y divide-slate-100">
            <thead class="bg-emerald-50/60">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-emerald-700">Nombre</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-emerald-700">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-emerald-700">Rol</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($users as $user)
                    <tr class="cursor-pointer hover:bg-emerald-50/40" data-edit-href="{{ route('admin.users.edit', $user) }}">
                        <td class="px-6 py-4 text-sm text-slate-900">{{ $user->name }}</td>
                        <td class="px-6 py-4 text-sm text-slate-500">{{ $user->email }}</td>
                        <td class="px-6 py-4 text-sm text-slate-500">{{ $user->is_admin ? 'Administrador' : 'Usuario' }}</td>
                        <td class="px-6 py-4 text-right text-sm">
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn-icon-edit" title="Editar">
                                <x-admin.icon name="pencil" class="h-4 w-4" />
                                <span class="sr-only">Editar</span>
                            </a>
                            @unless ($user->is(auth()->user()))
                                <button type="button" class="btn-icon-delete" data-modal-target="delete-modal" data-modal-toggle="delete-modal" data-delete-action="{{ route('admin.users.destroy', $user) }}" title="Eliminar">
                                    <x-admin.icon name="trash" class="h-4 w-4" />
                                    <span class="sr-only">Eliminar</span>
                                </button>
                            @endunless
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <x-admin.delete-modal title="¿Eliminar este usuario?" />
</x-app-layout>
