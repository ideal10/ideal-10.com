@php($componente = $componente ?? null)

<div class="space-y-4">
    <div>
        <x-input-label for="slug" value="Slug (define la URL /componentes/{slug})" />
        <x-text-input id="slug" name="slug" type="text" class="mt-1 block w-full" :value="old('slug', $componente?->slug)" required />
        <x-input-error :messages="$errors->get('slug')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="name" value="Nombre" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $componente?->name)" required />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="body" value="Descripción corta (tarjeta)" />
        <textarea id="body" name="body" rows="2" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('body', $componente?->body) }}</textarea>
        <x-input-error :messages="$errors->get('body')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="paths" value="Trazos del ícono SVG (uno por línea, atributo 'd' de cada <path>)" />
        <textarea id="paths" name="paths" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm font-mono text-xs" required>{{ old('paths', $componente && $componente->paths ? implode("\n", $componente->paths) : '') }}</textarea>
        <x-input-error :messages="$errors->get('paths')" class="mt-2" />
    </div>

    <div class="flex items-center">
        <input id="wide" name="wide" type="checkbox" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm" {{ old('wide', $componente?->wide) ? 'checked' : '' }}>
        <label for="wide" class="ms-2 text-sm text-gray-600">Tarjeta ancha (ocupa 2 columnas en la grilla)</label>
    </div>

    <div>
        <x-input-label for="content" value="Contenido completo de la página (Markdown)" />
        <textarea id="content" name="content" rows="16" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm font-mono text-xs">{{ old('content', $componente?->content) }}</textarea>
        <x-input-error :messages="$errors->get('content')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="order" value="Orden" />
        <x-text-input id="order" name="order" type="number" class="mt-1 block w-full" :value="old('order', $componente?->order ?? 0)" />
        <x-input-error :messages="$errors->get('order')" class="mt-2" />
    </div>
</div>
