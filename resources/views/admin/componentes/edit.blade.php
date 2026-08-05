<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar componente</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <form action="{{ route('admin.componentes.update', $componente) }}" method="POST">
                    @csrf
                    @method('PUT')
                    @include('admin.componentes._form')

                    <div class="mt-6 flex justify-end">
                        <x-primary-button>Actualizar</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
