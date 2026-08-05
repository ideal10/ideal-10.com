@php($link = $link ?? null)
@php($isFile = $link && $link->isFile())

<div class="space-y-4">
    <div>
        <x-input-label for="title" value="Título" />
        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $link?->title)" required />
        <x-input-error :messages="$errors->get('title')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="description" value="Descripción (opcional)" />
        <textarea id="description" name="description" rows="2" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description', $link?->description) }}</textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-2" />
    </div>

    <div>
        <x-input-label value="Tipo de enlace" />
        <div class="mt-1 flex gap-6">
            <label class="inline-flex items-center gap-2">
                <input type="radio" name="link_type" value="external" data-link-type-toggle {{ old('link_type', $isFile ? 'file' : 'external') === 'external' ? 'checked' : '' }}>
                <span class="text-sm text-gray-700">Enlace externo</span>
            </label>
            <label class="inline-flex items-center gap-2">
                <input type="radio" name="link_type" value="file" data-link-type-toggle {{ old('link_type', $isFile ? 'file' : 'external') === 'file' ? 'checked' : '' }}>
                <span class="text-sm text-gray-700">Archivo descargable</span>
            </label>
        </div>
        <x-input-error :messages="$errors->get('link_type')" class="mt-2" />
    </div>

    <div data-link-type-panel="external">
        <x-input-label for="url" value="URL" />
        <x-text-input id="url" name="url" type="text" class="mt-1 block w-full" :value="old('url', $isFile ? '' : $link?->url)" />
        <x-input-error :messages="$errors->get('url')" class="mt-2" />
    </div>

    <div data-link-type-panel="file">
        <x-input-label for="file" value="Archivo (xlsx, xls, csv, pdf, doc, docx — máx 10MB)" />
        <input id="file" name="file" type="file" class="mt-1 block w-full text-sm">
        @if ($isFile)
            <p class="mt-1 text-sm text-gray-500">Archivo actual: <a href="{{ $link->url }}" class="text-indigo-600 hover:underline">{{ basename($link->url) }}</a>. Sube uno nuevo solo si quieres reemplazarlo.</p>
        @endif
        <x-input-error :messages="$errors->get('file')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="icon" value="Icono (opcional)" />
        <x-text-input id="icon" name="icon" type="text" class="mt-1 block w-full" :value="old('icon', $link?->icon)" />
        <x-input-error :messages="$errors->get('icon')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="order" value="Orden" />
        <x-text-input id="order" name="order" type="number" class="mt-1 block w-full" :value="old('order', $link?->order ?? 0)" />
        <x-input-error :messages="$errors->get('order')" class="mt-2" />
    </div>

    <div class="flex items-center">
        <input id="active" name="active" type="checkbox" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm" {{ old('active', $link?->active ?? true) ? 'checked' : '' }}>
        <label for="active" class="ms-2 text-sm text-gray-600">Activo (visible en el sitio público)</label>
    </div>
</div>

<script>
    (function () {
        const radios = document.querySelectorAll('[data-link-type-toggle]');
        const panels = {
            external: document.querySelector('[data-link-type-panel="external"]'),
            file: document.querySelector('[data-link-type-panel="file"]'),
        };

        function sync() {
            const selected = document.querySelector('[data-link-type-toggle]:checked')?.value ?? 'external';
            panels.external.style.display = selected === 'external' ? '' : 'none';
            panels.file.style.display = selected === 'file' ? '' : 'none';
        }

        radios.forEach((radio) => radio.addEventListener('change', sync));
        sync();
    })();
</script>
