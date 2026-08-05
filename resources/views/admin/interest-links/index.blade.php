@php
    $orderedIds = $links->pluck('id')->values();
    $swap = function (int $a, int $b) use ($orderedIds) {
        $ids = $orderedIds->all();
        [$ids[$a], $ids[$b]] = [$ids[$b], $ids[$a]];

        return $ids;
    };
@endphp

<x-app-layout>
    <x-slot name="header">
        <x-admin.page-header title="Enlaces de interés" action-label="Nuevo enlace" :action-route="route('admin.interest-links.create')" />
    </x-slot>

    <div class="card overflow-hidden p-0">
        <table class="min-w-full divide-y divide-slate-100">
            <thead class="bg-emerald-50/60">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-emerald-700">Orden</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-emerald-700">Título</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-emerald-700">Tipo</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-emerald-700">Activo</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($links as $i => $link)
                    <tr class="cursor-pointer hover:bg-emerald-50/40" data-edit-href="{{ route('admin.interest-links.edit', $link) }}">
                        <td class="px-6 py-4 text-sm text-slate-500">
                            <div class="flex items-center gap-1">
                                {{ $link->order }}
                                <form action="{{ route('admin.interest-links.reorder') }}" method="POST">
                                    @csrf
                                    @foreach ($swap($i, max($i - 1, 0)) as $id)
                                        <input type="hidden" name="ids[]" value="{{ $id }}">
                                    @endforeach
                                    <button type="submit" class="btn-icon" @disabled($i === 0)>↑</button>
                                </form>
                                <form action="{{ route('admin.interest-links.reorder') }}" method="POST">
                                    @csrf
                                    @foreach ($swap($i, min($i + 1, $links->count() - 1)) as $id)
                                        <input type="hidden" name="ids[]" value="{{ $id }}">
                                    @endforeach
                                    <button type="submit" class="btn-icon" @disabled($i === $links->count() - 1)>↓</button>
                                </form>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-900 max-w-xs truncate">{{ $link->title }}</td>
                        <td class="px-6 py-4 text-sm text-slate-500">{{ $link->isFile() ? 'Archivo' : 'Externo' }}</td>
                        <td class="px-6 py-4 text-sm">
                            <form action="{{ route('admin.interest-links.toggle', $link) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="{{ $link->active ? 'text-emerald-700' : 'text-slate-400' }} font-semibold">
                                    {{ $link->active ? 'Sí' : 'No' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-4 text-right text-sm whitespace-nowrap">
                            <a href="{{ route('admin.interest-links.edit', $link) }}" class="btn-icon-edit" title="Editar">
                                <x-admin.icon name="pencil" class="h-4 w-4" />
                                <span class="sr-only">Editar</span>
                            </a>
                            <button type="button" class="btn-icon-delete" data-modal-target="delete-modal" data-modal-toggle="delete-modal" data-delete-action="{{ route('admin.interest-links.destroy', $link) }}" title="Eliminar">
                                <x-admin.icon name="trash" class="h-4 w-4" />
                                <span class="sr-only">Eliminar</span>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <x-admin.delete-modal title="¿Eliminar este enlace?" />
</x-app-layout>
