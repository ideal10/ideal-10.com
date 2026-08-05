@php($client = $client ?? null)

<div class="space-y-4">
    <div>
        <x-input-label for="name" value="Nombre del cliente" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $client?->name)" required />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="img" value="Archivo de imagen (en assets/img/clients/, ej. cubarral.png)" />
        <x-text-input id="img" name="img" type="text" class="mt-1 block w-full" :value="old('img', $client?->img)" required />
        <x-input-error :messages="$errors->get('img')" class="mt-2" />
    </div>

    <div class="flex items-center">
        <input id="extra" name="extra" type="checkbox" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm" {{ old('extra', $client?->extra) ? 'checked' : '' }}>
        <label for="extra" class="ms-2 text-sm text-gray-600">Solo mostrar en el carrusel de "Nosotros" (no en la grilla del home)</label>
    </div>

    <div>
        <x-input-label for="order" value="Orden" />
        <x-text-input id="order" name="order" type="number" class="mt-1 block w-full" :value="old('order', $client?->order ?? 0)" />
        <x-input-error :messages="$errors->get('order')" class="mt-2" />
    </div>
</div>
