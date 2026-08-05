@php($navItem = $navItem ?? null)

<div class="space-y-4">
    <div>
        <x-input-label for="url" value="URL" />
        <x-text-input id="url" name="url" type="text" class="mt-1 block w-full" :value="old('url', $navItem?->url)" required />
        <x-input-error :messages="$errors->get('url')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="label" value="Texto del enlace" />
        <x-text-input id="label" name="label" type="text" class="mt-1 block w-full" :value="old('label', $navItem?->label)" required />
        <x-input-error :messages="$errors->get('label')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="match" value="Rutas adicionales que activan este enlace (separadas por coma, opcional)" />
        <x-text-input id="match" name="match" type="text" class="mt-1 block w-full" :value="old('match', $navItem && $navItem->match ? implode(', ', $navItem->match) : '')" />
        <x-input-error :messages="$errors->get('match')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="order" value="Orden" />
        <x-text-input id="order" name="order" type="number" class="mt-1 block w-full" :value="old('order', $navItem?->order ?? 0)" />
        <x-input-error :messages="$errors->get('order')" class="mt-2" />
    </div>
</div>
