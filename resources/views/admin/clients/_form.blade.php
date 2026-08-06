@php($client = $client ?? null)

<div class="space-y-4">
    <div>
        <x-input-label for="name" value="Nombre del cliente" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $client?->name)" required />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="image" value="Logo del cliente (jpg, jpeg, png, webp, svg — máx 5MB)" />
        <input id="image" name="image" type="file" accept="image/*" class="mt-1 block w-full text-sm">
        @if ($client?->img)
            <p class="mt-1 flex items-center gap-2 text-sm text-slate-500">
                <img src="{{ $client->img }}" alt="{{ $client->name }}" class="h-10 w-10 rounded border border-slate-200 object-contain">
                Imagen actual. Sube una nueva solo si quieres reemplazarla.
            </p>
        @endif
        <x-input-error :messages="$errors->get('image')" class="mt-2" />
    </div>

    <div class="flex items-center">
        <x-checkbox id="extra" name="extra" value="1" :checked="old('extra', $client?->extra)" />
        <label for="extra" class="ms-2 text-sm text-slate-600">Solo mostrar en el carrusel de "Nosotros" (no en la grilla del home)</label>
    </div>

    <div>
        <x-input-label for="order" value="Orden" />
        <x-text-input id="order" name="order" type="number" class="mt-1 block w-full" :value="old('order', $client?->order ?? $nextOrder ?? 0)" />
        <x-input-error :messages="$errors->get('order')" class="mt-2" />
    </div>
</div>
