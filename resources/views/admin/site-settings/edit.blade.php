<x-app-layout>
    <x-slot name="header">
        <x-admin.page-header title="Configuración del sitio" />
    </x-slot>

    <div class="mx-auto max-w-3xl">
        <x-admin.back-link :href="route('admin.dashboard')" />

        <div class="card">
            <form action="{{ route('admin.site-settings.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <div>
                        <x-input-label for="site_url" value="URL del sitio" />
                        <x-text-input id="site_url" name="site_url" type="text" class="mt-1 block w-full" :value="old('site_url', $setting->site_url)" required />
                        <x-input-error :messages="$errors->get('site_url')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="lang" value="Idioma (atributo lang del <html>)" />
                        <x-text-input id="lang" name="lang" type="text" class="mt-1 block w-full" :value="old('lang', $setting->lang)" required />
                        <x-input-error :messages="$errors->get('lang')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="title" value="Título del sitio" />
                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $setting->title)" required />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="mailer" value="URL de Formspree para el formulario de contacto" />
                        <x-text-input id="mailer" name="mailer" type="text" class="mt-1 block w-full" :value="old('mailer', $setting->mailer)" required />
                        <x-input-error :messages="$errors->get('mailer')" class="mt-2" />
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <x-primary-button>Guardar</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
