<x-app-layout>
    <x-slot name="header">
        <x-admin.page-header title="Nuevo enlace de navegación" />
    </x-slot>

    <div class="mx-auto max-w-3xl">
        <x-admin.back-link :href="route('admin.nav-items.index')" />

        <div class="card">
            <form action="{{ route('admin.nav-items.store') }}" method="POST">
                @csrf
                @include('admin.nav-items._form')

                <div class="mt-6 flex justify-end">
                    <x-primary-button>Guardar</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
