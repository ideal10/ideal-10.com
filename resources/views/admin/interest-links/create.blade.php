<x-app-layout>
    <x-slot name="header">
        <x-admin.page-header title="Nuevo enlace de interés" />
    </x-slot>

    <div class="mx-auto max-w-3xl">
        <x-admin.back-link :href="route('admin.interest-links.index')" />

        <div class="card">
            <form action="{{ route('admin.interest-links.store') }}" method="POST" enctype="multipart/form-data" data-upload-progress>
                @csrf
                @include('admin.interest-links._form')

                <div class="mt-6 flex justify-end">
                    <x-primary-button>Guardar</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
