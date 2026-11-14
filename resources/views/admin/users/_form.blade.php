@php($user = $user ?? null)

<div class="space-y-4">
    <div>
        <x-input-label for="name" value="Nombre" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user?->name)" required autofocus />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="username" value="Usuario" />
        <x-text-input id="username" name="username" type="text" class="mt-1 block w-full" :value="old('username', $user?->username)" autocapitalize="off" spellcheck="false" />
        <p class="mt-1 text-xs text-slate-500">Opcional. Para entrar con él en vez del correo. Letras, números, punto, guion o guion bajo.</p>
        <x-input-error :messages="$errors->get('username')" class="mt-2" />
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
</div>
