@php
    $isNew = ! $entity->exists;

    $rowErrors = collect($rows)->keys()->map(fn ($i) => collect(['name', 'href', 'logo', 'client_id'])
        ->flatMap(fn ($field) => $errors->get("links.$i.$field"))
        ->values()
        ->all()
    )->all();

    $editorState = [
        'name' => old('name', $entity->name ?? ''),
        'identity' => $identity,
        'rows' => $rows,
        'clients' => $clients,
        'errors' => $rowErrors,
        'suggestions' => $suggestions,
    ];
@endphp

<x-app-layout>
    <x-slot name="header">
        <x-admin.page-header :title="$isNew ? 'Nueva entidad' : $entity->name" />
    </x-slot>

    <div class="mx-auto max-w-6xl">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <x-admin.back-link :href="route('admin.entities.index')" />

        </div>

        @if ($errors->any())
            <div class="mb-4 rounded-xl border border-red-100 bg-red-50 p-4 text-sm text-red-700" role="alert">
                No se guardó: revisa los campos marcados en rojo.
                @if ($errors->has('links.*.logo') || $errors->has('logo'))
                    Las imágenes que habías subido hay que volver a elegirlas.
                @endif
            </div>
        @endif

        <form
            action="{{ $isNew ? route('admin.entities.store') : route('admin.entities.update', $entity) }}"
            method="POST"
            enctype="multipart/form-data"
            x-data="entityEditor({{ \Illuminate\Support\Js::from($editorState) }})"
            class="grid items-start gap-6 lg:grid-cols-[22rem_1fr]"
        >
            @csrf
            @unless ($isNew)
                @method('PUT')
            @endunless

            {{-- Identity ---------------------------------------------------- --}}
            <section class="card space-y-6 lg:sticky lg:top-20" aria-labelledby="entity-identity">
                <h2 id="entity-identity" class="sr-only">Datos de la entidad</h2>

                <div class="flex flex-col items-center text-center">
                    <button
                        type="button"
                        @click="openPicker(identity, name || 'la entidad')"
                        class="group relative flex h-40 w-40 items-center justify-center overflow-hidden rounded-[2rem] border-2 border-dashed transition focus:outline-none focus:ring-4 focus:ring-emerald-200"
                        :class="logoSrc(identity) ? 'border-transparent bg-white shadow-lg shadow-slate-200 ring-1 ring-slate-100' : 'border-emerald-200 bg-emerald-50/60 hover:border-emerald-400 hover:bg-emerald-50'"
                    >
                        <template x-if="logoSrc(identity)">
                            <img :src="logoSrc(identity)" alt="Logo de la entidad" class="h-full w-full object-contain p-4">
                        </template>
                        <template x-if="!logoSrc(identity)">
                            <span class="flex flex-col items-center gap-2 text-emerald-700">
                                <span class="text-4xl font-extrabold tracking-tight" x-text="name ? initials(name) : ''"></span>
                                <span x-show="!name"><x-admin.icon name="photo" class="h-7 w-7" /></span>
                                <span class="text-xs font-semibold">Elegir logo</span>
                            </span>
                        </template>
                        <span x-show="logoSrc(identity)" class="absolute inset-0 flex items-center justify-center bg-slate-900/50 text-sm font-semibold text-white opacity-0 transition group-hover:opacity-100">Cambiar logo</span>
                    </button>

                    <input :data-logo-input="identity.uid" type="file" name="logo" accept="image/png,image/jpeg,image/webp" class="sr-only" tabindex="-1" @change="pickFile(identity, $event)">
                    <input type="hidden" name="client_id" :value="identity.clientId ?? ''">
                    <input type="hidden" name="remove_logo" :value="identity.removeLogo ? 1 : 0">

                    <p class="mt-3 min-h-4 text-xs text-slate-500" x-text="logoSource(identity) || 'Elige uno de los logos de Clientes o sube una imagen.'"></p>
                    <button type="button" x-show="logoSrc(identity)" @click="clearLogo(identity)" class="mt-1 text-xs font-semibold text-red-600 hover:underline">Quitar logo</button>
                    <x-input-error :messages="$errors->get('logo')" class="mt-2" />
                    <x-input-error :messages="$errors->get('client_id')" class="mt-2" />
                </div>

                <div>
                    <label for="name" class="field-label">Nombre</label>
                    <input id="name" name="name" type="text" class="field-input" x-model="name" placeholder="Alcaldía de Cubarral" required>
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>


                <div class="flex flex-col gap-2 border-t border-slate-100 pt-6">
                    <button type="submit" class="btn-primary w-full">{{ $isNew ? 'Crear entidad' : 'Guardar cambios' }}</button>
                    @unless ($isNew)
                        <button type="button" class="rounded-xl px-4 py-2.5 text-sm font-semibold text-red-600 transition hover:bg-red-50" data-modal-target="delete-modal" data-modal-toggle="delete-modal" data-delete-action="{{ route('admin.entities.destroy', $entity) }}">
                            Eliminar entidad
                        </button>
                    @endunless
                </div>
            </section>

            {{-- Sub-entities ------------------------------------------------ --}}
            <section class="card p-0" aria-labelledby="entity-links">
                <header class="flex flex-wrap items-end justify-between gap-3 border-b border-slate-100 px-6 py-5">
                    <div>
                        <h2 id="entity-links" class="text-lg font-bold text-slate-900">
                            Subentidades
                            <span class="ml-1 rounded-full bg-emerald-50 px-2 py-0.5 align-middle text-xs font-bold text-emerald-700" x-text="rows.length"></span>
                        </h2>
                        <p class="mt-1 text-sm text-slate-500">Cada una aparece como un acceso en el menú: concejo, personería, servicios públicos…</p>
                    </div>
                    <button type="button" @click="addRow()" class="btn-secondary !px-4 !py-2">
                        <x-admin.icon name="plus" class="h-4 w-4" />
                        Agregar subentidad
                    </button>
                </header>

                <ol class="divide-y divide-slate-100">
                    <template x-for="(row, index) in rows" :key="row.uid">
                        <li class="group/row flex gap-4 px-6 py-5 transition hover:bg-slate-50/70">
                            <input type="hidden" :name="`links[${index}][id]`" :value="row.id ?? ''">
                            <input type="hidden" :name="`links[${index}][remove_logo]`" :value="row.removeLogo ? 1 : 0">

                            {{-- Logo --}}
                            <div class="flex w-16 shrink-0 flex-col items-center gap-1">
                                <button
                                    type="button"
                                    @click="openPicker(row, row.name || 'la subentidad')"
                                    class="group/logo relative flex h-16 w-16 items-center justify-center overflow-hidden rounded-2xl transition focus:outline-none focus:ring-4 focus:ring-emerald-200"
                                    :class="logoSrc(row) ? 'bg-white ring-1 ring-slate-200' : 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200'"
                                    :title="logoSource(row) || 'Elegir logo'"
                                >
                                    <template x-if="logoSrc(row)">
                                        <img :src="logoSrc(row)" :alt="`Logo de ${row.name}`" class="h-full w-full object-contain p-1.5">
                                    </template>
                                    <template x-if="!logoSrc(row)">
                                        <span class="text-lg font-extrabold" x-text="initials(row.name)"></span>
                                    </template>
                                    <span class="absolute inset-x-0 bottom-0 bg-slate-900/60 py-0.5 text-center text-[10px] font-semibold text-white opacity-0 transition group-hover/logo:opacity-100">Logo</span>
                                </button>
                                <input :data-logo-input="row.uid" type="file" :name="`links[${index}][logo]`" accept="image/png,image/jpeg,image/webp" class="sr-only" tabindex="-1" @change="pickFile(row, $event)">
                                <input type="hidden" :name="`links[${index}][client_id]`" :value="row.clientId ?? ''">
                                <button type="button" x-show="logoSrc(row)" @click="clearLogo(row)" class="text-[11px] font-semibold text-red-600 hover:underline">Quitar</button>
                            </div>

                            {{-- Fields --}}
                            <div class="grid min-w-0 flex-1 gap-3 sm:grid-cols-[minmax(0,2fr)_minmax(0,3fr)]">
                                <div>
                                    <label :for="`link-name-${row.uid}`" class="mb-1 block text-xs font-semibold text-slate-500">Nombre</label>
                                    <input :id="`link-name-${row.uid}`" data-row-name type="text" :name="`links[${index}][name]`" x-model="row.name" class="field-input !py-2.5" placeholder="Concejo" required>
                                </div>
                                <div>
                                    <label :for="`link-href-${row.uid}`" class="mb-1 block text-xs font-semibold text-slate-500">Enlace</label>
                                    <input :id="`link-href-${row.uid}`" type="text" inputmode="url" autocapitalize="off" spellcheck="false" :name="`links[${index}][href]`" x-model="row.href" class="field-input !py-2.5" placeholder="cubarralc.ideal-10.com" required>
                                </div>
                                <template x-if="row.errors.length">
                                    <ul class="space-y-0.5 text-sm text-red-600 sm:col-span-2">
                                        <template x-for="message in row.errors"><li x-text="message"></li></template>
                                    </ul>
                                </template>
                            </div>

                            {{-- Row actions --}}
                            <div class="flex shrink-0 flex-col items-center justify-center gap-0.5 sm:flex-row sm:self-center">
                                <button type="button" @click="moveRow(index, -1)" :disabled="index === 0" class="btn-icon disabled:pointer-events-none disabled:opacity-30" title="Subir">
                                    <x-admin.icon name="arrow-up" class="h-4 w-4" />
                                    <span class="sr-only">Subir</span>
                                </button>
                                <button type="button" @click="moveRow(index, 1)" :disabled="index === rows.length - 1" class="btn-icon disabled:pointer-events-none disabled:opacity-30" title="Bajar">
                                    <x-admin.icon name="arrow-down" class="h-4 w-4" />
                                    <span class="sr-only">Bajar</span>
                                </button>
                                <button type="button" @click="removeRow(index)" class="btn-icon-delete" title="Quitar subentidad">
                                    <x-admin.icon name="trash" class="h-4 w-4" />
                                    <span class="sr-only">Quitar subentidad</span>
                                </button>
                            </div>
                        </li>
                    </template>
                </ol>

                <div x-show="rows.length === 0" class="flex flex-col items-center px-6 py-14 text-center">
                    <span class="icon-tile h-14 w-14">
                        <x-admin.icon name="building" class="h-7 w-7" />
                    </span>
                    <p class="mt-4 font-semibold text-slate-900">Todavía no hay subentidades</p>
                    <p class="mt-1 max-w-sm text-sm text-slate-500">Agrega las dependencias a las que se podrá entrar desde el menú de esta entidad.</p>
                </div>

                <footer class="border-t border-slate-100 bg-slate-50/60 px-6 py-4" x-show="availableSuggestions.length">
                    <p class="text-xs font-semibold text-slate-500">Agregar rápido</p>
                    <div class="mt-2 flex flex-wrap gap-2">
                        <template x-for="suggestion in availableSuggestions" :key="suggestion">
                            <button type="button" @click="addRow(suggestion)" class="inline-flex items-center gap-1 rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-semibold text-slate-600 transition hover:border-emerald-300 hover:bg-emerald-50 hover:text-emerald-700">
                                <x-admin.icon name="plus" class="h-3 w-3" />
                                <span x-text="suggestion"></span>
                            </button>
                        </template>
                    </div>
                </footer>
            </section>
            {{-- Logo picker --------------------------------------------------- --}}
            <div
                x-show="picker.open"
                x-cloak
                x-transition.opacity
                @keydown.escape.window="closePicker()"
                class="fixed inset-0 z-50 flex items-end justify-center bg-slate-900/50 p-0 backdrop-blur-sm sm:items-center sm:p-6"
                role="dialog"
                aria-modal="true"
                aria-labelledby="logo-picker-title"
            >
                <div @click.outside="closePicker()" x-show="picker.open" x-transition class="flex max-h-[85vh] w-full max-w-3xl flex-col overflow-hidden rounded-t-3xl bg-white shadow-2xl sm:rounded-3xl">
                    <header class="flex items-start justify-between gap-4 border-b border-slate-100 px-6 pt-5 pb-4">
                        <div class="min-w-0">
                            <h2 id="logo-picker-title" class="text-lg font-bold text-slate-900">Elegir logo</h2>
                            <p class="truncate text-sm text-slate-500">Para <span class="font-semibold text-slate-700" x-text="picker.label"></span></p>
                        </div>
                        <button type="button" @click="closePicker()" class="btn-icon" title="Cerrar">
                            <x-admin.icon name="x-mark" class="h-5 w-5" />
                            <span class="sr-only">Cerrar</span>
                        </button>
                    </header>

                    <div class="flex flex-col gap-3 border-b border-slate-100 px-6 py-4 sm:flex-row">
                        <label class="relative block flex-1">
                            <span class="sr-only">Buscar cliente</span>
                            <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400">
                                <x-admin.icon name="magnifying-glass" class="h-4 w-4" />
                            </span>
                            <input x-ref="pickerSearch" type="search" x-model="picker.query" placeholder="Buscar entre los clientes…" class="field-input !py-2.5 pl-9" @keydown.enter.prevent>
                        </label>
                        <button type="button" @click="chooseUpload()" class="btn-secondary !px-4 !py-2.5">
                            <x-admin.icon name="photo" class="h-4 w-4" />
                            Subir otra imagen
                        </button>
                    </div>

                    <div class="overflow-y-auto px-6 py-5">
                        <ul class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4">
                            <template x-for="client in pickerClients" :key="client.id">
                                <li>
                                    <button
                                        type="button"
                                        @click="chooseClient(client)"
                                        class="relative flex h-full w-full flex-col items-center gap-2 rounded-2xl border p-3 text-center transition hover:-translate-y-0.5 hover:border-emerald-300 hover:shadow-md focus:outline-none focus:ring-4 focus:ring-emerald-200"
                                        :class="picker.target && picker.target.clientId === client.id ? 'border-emerald-500 bg-emerald-50 ring-2 ring-emerald-500' : 'border-slate-200 bg-white'"
                                    >
                                        <span x-show="client.score >= 2 && !picker.query" class="absolute top-2 left-2 rounded-full bg-emerald-600 px-1.5 py-0.5 text-[10px] font-bold text-white">Sugerido</span>
                                        <img :src="client.img" :alt="client.name" class="h-20 w-full object-contain" loading="lazy">
                                        <span class="line-clamp-2 text-xs font-semibold leading-4 text-slate-700" x-text="client.name"></span>
                                    </button>
                                </li>
                            </template>
                        </ul>

                        <div x-show="pickerClients.length === 0" class="py-10 text-center">
                            <p class="font-semibold text-slate-900">Ningún cliente coincide con “<span x-text="picker.query"></span>”.</p>
                            <p class="mt-1 text-sm text-slate-500">Puedes subir una imagen desde tu equipo, o agregar el cliente en la sección Clientes.</p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @unless ($isNew)
        <x-admin.delete-modal title="¿Eliminar esta entidad?" text="Se eliminarán también todas sus subentidades y logos. Esta acción no se puede deshacer." />
    @endunless
</x-app-layout>
