@php($service = $service ?? null)

<div class="space-y-4">
    <div>
        <x-input-label for="name" value="Nombre del servicio" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $service?->name)" required />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="description" value="Descripción" />
        <x-textarea id="description" name="description" rows="4" class="mt-1 block w-full" required>{{ old('description', $service?->description) }}</x-textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="svg" value="Icono (ruta dentro de _includes/svg, ej. svg/code.svg)" />
        <x-text-input id="svg" name="svg" type="text" class="mt-1 block w-full" :value="old('svg', $service?->svg)" required />
        <x-input-error :messages="$errors->get('svg')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="order" value="Orden" />
        <x-text-input id="order" name="order" type="number" class="mt-1 block w-full" :value="old('order', $service?->order ?? 0)" />
        <x-input-error :messages="$errors->get('order')" class="mt-2" />
    </div>
</div>
