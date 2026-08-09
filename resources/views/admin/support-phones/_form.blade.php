@php($phone = $phone ?? null)

<div class="space-y-4">
    <div>
        <x-input-label for="number" value="Número" />
        <x-text-input id="number" name="number" type="text" class="mt-1 block w-full" :value="old('number', $phone?->number)" placeholder="320 249 7418" required />
        <x-input-error :messages="$errors->get('number')" class="mt-2" />
    </div>

    <div>
        <x-input-label value="Tipo de contacto" />
        <div class="mt-1 flex gap-6">
            <label class="inline-flex items-center gap-2">
                <input type="radio" name="type" value="whatsapp" class="border-slate-300 text-emerald-600 focus:ring-emerald-500" {{ old('type', $phone?->type ?? 'whatsapp') === 'whatsapp' ? 'checked' : '' }}>
                <span class="text-sm text-slate-700">WhatsApp</span>
            </label>
            <label class="inline-flex items-center gap-2">
                <input type="radio" name="type" value="dial" class="border-slate-300 text-emerald-600 focus:ring-emerald-500" {{ old('type', $phone?->type ?? 'whatsapp') === 'dial' ? 'checked' : '' }}>
                <span class="text-sm text-slate-700">Llamada</span>
            </label>
        </div>
        <x-input-error :messages="$errors->get('type')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="order" value="Orden" />
        <x-text-input id="order" name="order" type="number" class="mt-1 block w-full" :value="old('order', $phone?->order ?? $nextOrder ?? 0)" />
        <x-input-error :messages="$errors->get('order')" class="mt-2" />
    </div>

    <div class="flex items-center">
        <x-checkbox id="active" name="active" value="1" :checked="old('active', $phone?->active ?? true)" />
        <label for="active" class="ms-2 text-sm text-slate-600">Activo (visible en el sitio público)</label>
    </div>
</div>
