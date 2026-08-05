<x-app-layout>
    <x-slot name="header">
        <x-admin.page-header title="Nueva entidad" />
    </x-slot>

    <div class="mx-auto max-w-3xl">
        <x-admin.back-link :href="route('admin.entities.index')" />

        <div class="card">
            <form action="{{ route('admin.entities.store') }}" method="POST">
                @csrf

                <div class="space-y-4">
                    <div>
                        <x-input-label for="slug" value="Slug (define la URL /menu_entidades/{slug})" />
                        <x-text-input id="slug" name="slug" type="text" class="mt-1 block w-full" :value="old('slug')" required />
                        <x-input-error :messages="$errors->get('slug')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="name" value="Nombre de la entidad" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                </div>

                <p class="mt-4 text-sm text-slate-500">Podrás añadir sus enlaces después de crearla.</p>

                <div class="mt-6 flex justify-end">
                    <x-primary-button>Guardar</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
