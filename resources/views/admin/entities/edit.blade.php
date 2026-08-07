@php
    $newLinksStart = $links->count();
    $oldLinks = old('links', []);
    $newLinkRows = [];
    $newLinkErrors = [];
    foreach ($oldLinks as $idx => $row) {
        if ($idx < $newLinksStart) {
            continue;
        }
        $newLinkRows[] = ['name' => $row['name'] ?? '', 'href' => $row['href'] ?? ''];
        $newLinkErrors[] = $errors->first('links.'.$idx.'.href');
    }
@endphp

<x-app-layout>
    <x-slot name="header">
        <x-admin.page-header title="Editar entidad: {{ $entity->name }}" />
    </x-slot>

    <div class="mx-auto max-w-3xl">
        <x-admin.back-link :href="route('admin.entities.index')" />

        <div class="card">
            <form action="{{ route('admin.entities.update', $entity) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <div>
                        <x-input-label for="slug" value="Slug (define la URL /menu_entidades/{slug})" />
                        <x-text-input id="slug" name="slug" type="text" class="mt-1 block w-full" :value="old('slug', $entity->slug)" required />
                        <x-input-error :messages="$errors->get('slug')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="name" value="Nombre de la entidad" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $entity->name)" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                </div>

                <h3 class="mt-8 mb-2 text-sm font-semibold text-slate-700 uppercase">Enlaces</h3>

                <div class="space-y-3">
                    @foreach ($links as $i => $link)
                        <div class="grid grid-cols-12 gap-2 items-start border-b border-slate-100 pb-3">
                            <input type="hidden" name="links[{{ $i }}][id]" value="{{ $link->id }}">
                            <input type="hidden" name="links[{{ $i }}][order]" value="{{ $link->order }}">
                            <div class="col-span-4">
                                <x-text-input name="links[{{ $i }}][name]" type="text" class="block w-full" value="{{ old('links.'.$i.'.name', $link->name) }}" placeholder="Nombre" />
                            </div>
                            <div class="col-span-6">
                                <x-text-input name="links[{{ $i }}][href]" type="text" class="block w-full" value="{{ old('links.'.$i.'.href', $link->href) }}" placeholder="https://..." />
                            </div>
                            <div class="col-span-2 flex items-center h-full pt-2">
                                <x-checkbox id="remove-{{ $i }}" name="links[{{ $i }}][remove]" value="1" class="!text-red-600" />
                                <label for="remove-{{ $i }}" class="ms-2 text-sm text-red-600">Eliminar</label>
                            </div>
                            <x-input-error :messages="$errors->get('links.'.$i.'.href')" class="col-span-12" />
                        </div>
                    @endforeach

                    <div x-data="{
                            rows: {{ \Illuminate\Support\Js::from($newLinkRows) }},
                            errors: {{ \Illuminate\Support\Js::from($newLinkErrors) }},
                            startIndex: {{ $newLinksStart }},
                        }">
                        <template x-for="(row, idx) in rows" :key="idx">
                            <div class="grid grid-cols-12 gap-2 items-start border-b border-slate-100 pb-3">
                                <div class="col-span-4">
                                    <input type="text" :name="'links[' + (startIndex + idx) + '][name]'" x-model="row.name" class="field-input block w-full" placeholder="Nombre (nuevo)">
                                </div>
                                <div class="col-span-6">
                                    <input type="text" :name="'links[' + (startIndex + idx) + '][href]'" x-model="row.href" class="field-input block w-full" placeholder="https://... (nuevo)">
                                </div>
                                <div class="col-span-2 flex h-full items-center justify-end pt-2">
                                    <button type="button" @click="rows.splice(idx, 1); errors.splice(idx, 1)" class="btn-icon-delete" title="Quitar">
                                        <x-admin.icon name="x-mark" class="h-4 w-4" />
                                        <span class="sr-only">Quitar</span>
                                    </button>
                                </div>
                                <p class="col-span-12 text-sm text-red-600" x-show="errors[idx]" x-text="errors[idx]"></p>
                            </div>
                        </template>

                        <button type="button" @click="rows.push({ name: '', href: '' }); errors.push('')" class="btn-secondary !px-4 !py-2 text-sm">
                            + Agregar enlace
                        </button>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <x-primary-button>Guardar cambios</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
