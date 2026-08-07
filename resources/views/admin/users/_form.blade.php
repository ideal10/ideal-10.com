@php($user = $user ?? null)

<div class="space-y-4">
    <div>
        <x-input-label for="name" value="Nombre" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user?->name)" required autofocus />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="email" value="Email" />
        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user?->email)" required autocomplete="username" />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="password" :value="$user ? 'Nueva contraseña (dejar en blanco para mantener la actual)' : 'Contraseña'" />
        <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" :required="! $user" />
        <x-input-error :messages="$errors->get('password')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="password_confirmation" value="Confirmar contraseña" />
        <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" :required="! $user" />
        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
    </div>

    <div class="flex items-center">
        <x-checkbox id="is_admin" name="is_admin" value="1" :checked="old('is_admin', $user?->is_admin)" />
        <label for="is_admin" class="ms-2 text-sm text-slate-600">Administrador — puede gestionar usuarios además del resto del contenido</label>
    </div>
    <x-input-error :messages="$errors->get('is_admin')" class="mt-2" />
</div>
