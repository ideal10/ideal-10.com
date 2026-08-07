<x-app-layout>
    <x-slot name="header">
        <x-admin.page-header title="Editar usuario" />
    </x-slot>

    <div class="mx-auto max-w-3xl">
        <x-admin.back-link :href="route('admin.users.index')" />

        <div class="card">
            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')
                @include('admin.users._form')

                <div class="mt-6 flex justify-end">
                    <x-primary-button>Actualizar</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
