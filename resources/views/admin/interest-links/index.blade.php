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
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Enlaces de interés</h2>
            <a href="{{ route('admin.interest-links.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">Nuevo enlace</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            @include('admin._nav')

            @if (session('status'))
                <div class="mb-4 p-4 bg-green-50 text-green-700 rounded-md">{{ session('status') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Orden</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Título</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Activo</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($links as $i => $link)
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    <div class="flex items-center gap-1">
                                        {{ $link->order }}
                                        <form action="{{ route('admin.interest-links.reorder') }}" method="POST">
                                            @csrf
                                            @foreach ($swap($i, max($i - 1, 0)) as $id)
                                                <input type="hidden" name="ids[]" value="{{ $id }}">
                                            @endforeach
                                            <button type="submit" class="ml-2 text-gray-400 hover:text-gray-700" @disabled($i === 0)>↑</button>
                                        </form>
                                        <form action="{{ route('admin.interest-links.reorder') }}" method="POST">
                                            @csrf
                                            @foreach ($swap($i, min($i + 1, $links->count() - 1)) as $id)
                                                <input type="hidden" name="ids[]" value="{{ $id }}">
                                            @endforeach
                                            <button type="submit" class="text-gray-400 hover:text-gray-700" @disabled($i === $links->count() - 1)>↓</button>
                                        </form>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate">{{ $link->title }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $link->isFile() ? 'Archivo' : 'Externo' }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <form action="{{ route('admin.interest-links.toggle', $link) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="{{ $link->active ? 'text-green-700' : 'text-gray-400' }} font-semibold">
                                            {{ $link->active ? 'Sí' : 'No' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="px-6 py-4 text-right text-sm space-x-3 whitespace-nowrap">
                                    <a href="{{ route('admin.interest-links.edit', $link) }}" class="text-indigo-600 hover:text-indigo-900">Editar</a>
                                    <form action="{{ route('admin.interest-links.destroy', $link) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este enlace?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
