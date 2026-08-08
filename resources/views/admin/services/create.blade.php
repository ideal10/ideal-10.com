<x-app-layout>
    <x-slot name="header">
        <x-admin.page-header title="Nuevo servicio" />
    </x-slot>

    <div class="mx-auto max-w-3xl">
        <x-admin.back-link :href="route('admin.services.index')" />

        <div class="card">
            <form action="{{ route('admin.services.store') }}" method="POST">
                @csrf
                @include('admin.services._form')

                <div class="mt-6 flex justify-end">
                    <x-primary-button>Guardar</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
