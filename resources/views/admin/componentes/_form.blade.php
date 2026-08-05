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
        <x-textarea id="body" name="body" rows="2" class="mt-1 block w-full" required>{{ old('body', $componente?->body) }}</x-textarea>
        <x-input-error :messages="$errors->get('body')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="paths" value="Trazos del ícono SVG (uno por línea, atributo 'd' de cada <path>)" />
        <x-textarea id="paths" name="paths" rows="3" class="mt-1 block w-full font-mono text-xs" required>{{ old('paths', $componente && $componente->paths ? implode("\n", $componente->paths) : '') }}</x-textarea>
        <x-input-error :messages="$errors->get('paths')" class="mt-2" />
    </div>

    <div class="flex items-center">
        <x-checkbox id="wide" name="wide" value="1" :checked="old('wide', $componente?->wide)" />
        <label for="wide" class="ms-2 text-sm text-slate-600">Tarjeta ancha (ocupa 2 columnas en la grilla)</label>
    </div>

    <div>
        <x-input-label for="content" value="Contenido completo de la página (Markdown)" />
        <x-textarea id="content" name="content" rows="16" class="mt-1 block w-full font-mono text-xs">{{ old('content', $componente?->content) }}</x-textarea>
        <x-input-error :messages="$errors->get('content')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="order" value="Orden" />
        <x-text-input id="order" name="order" type="number" class="mt-1 block w-full" :value="old('order', $componente?->order ?? 0)" />
        <x-input-error :messages="$errors->get('order')" class="mt-2" />
    </div>
</div>
