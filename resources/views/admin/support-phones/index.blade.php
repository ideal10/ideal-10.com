@php
    $orderedIds = $phones->pluck('id')->values();
    $swap = function (int $a, int $b) use ($orderedIds) {
        $ids = $orderedIds->all();
        [$ids[$a], $ids[$b]] = [$ids[$b], $ids[$a]];

        return $ids;
    };
@endphp

<x-app-layout>
    <x-slot name="header">
        <x-admin.page-header title="Teléfonos de soporte" />
    </x-slot>

    <div class="mb-4 flex items-center justify-between">
        <x-admin.view-link :href="route('contacto')" />
        <x-admin.create-link :href="route('admin.support-phones.create')" label="Nuevo número" />
    </div>

    <div class="card overflow-hidden p-0">
        <table class="min-w-full divide-y divide-slate-100">
            <thead class="bg-emerald-50/60">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-emerald-700">Orden</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-emerald-700">Número</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-emerald-700">Tipo</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-emerald-700">Activo</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($phones as $i => $phone)
                    <tr class="cursor-pointer hover:bg-emerald-50/40" data-edit-href="{{ route('admin.support-phones.edit', $phone) }}">
                        <td class="px-6 py-4 text-sm text-slate-500">
                            <div class="flex items-center gap-1">
                                {{ $phone->order }}
                                <form action="{{ route('admin.support-phones.reorder') }}" method="POST">
                                    @csrf
                                    @foreach ($swap($i, max($i - 1, 0)) as $id)
                                        <input type="hidden" name="ids[]" value="{{ $id }}">
                                    @endforeach
                                    <button type="submit" class="btn-icon" @disabled($i === 0)>↑</button>
                                </form>
                                <form action="{{ route('admin.support-phones.reorder') }}" method="POST">
                                    @csrf
                                    @foreach ($swap($i, min($i + 1, $phones->count() - 1)) as $id)
                                        <input type="hidden" name="ids[]" value="{{ $id }}">
                                    @endforeach
                                    <button type="submit" class="btn-icon" @disabled($i === $phones->count() - 1)>↓</button>
                                </form>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-900">{{ $phone->number }}</td>
                        <td class="px-6 py-4 text-sm text-slate-500">{{ $phone->isWhatsapp() ? 'WhatsApp' : 'Llamada' }}</td>
                        <td class="px-6 py-4 text-sm">
                            <form action="{{ route('admin.support-phones.toggle', $phone) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="{{ $phone->active ? 'text-emerald-700' : 'text-slate-400' }} font-semibold">
                                    {{ $phone->active ? 'Sí' : 'No' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-4 text-right text-sm whitespace-nowrap">
                            <a href="{{ route('admin.support-phones.edit', $phone) }}" class="btn-icon-edit" title="Editar">
                                <x-admin.icon name="pencil" class="h-4 w-4" />
                                <span class="sr-only">Editar</span>
                            </a>
                            <button type="button" class="btn-icon-delete" data-modal-target="delete-modal" data-modal-toggle="delete-modal" data-delete-action="{{ route('admin.support-phones.destroy', $phone) }}" title="Eliminar">
                                <x-admin.icon name="trash" class="h-4 w-4" />
                                <span class="sr-only">Eliminar</span>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <x-admin.delete-modal title="¿Eliminar este número?" />
</x-app-layout>
