@props(['title' => '¿Eliminar este elemento?', 'text' => 'Esta acción no se puede deshacer.'])

<div id="delete-modal" tabindex="-1" aria-hidden="true" class="fixed inset-0 z-50 hidden h-modal w-full items-center justify-center overflow-y-auto overflow-x-hidden p-4 md:h-full">
    <div class="relative h-full w-full max-w-md md:h-auto">
        <div class="relative rounded-2xl bg-white p-6 text-center shadow-xl">
            <button type="button" class="btn-icon absolute right-2.5 top-2.5" data-modal-hide="delete-modal">
                <x-admin.icon name="x-mark" class="h-4 w-4" />
                <span class="sr-only">Cerrar</span>
            </button>

            <div class="icon-tile mx-auto mb-4 h-12 w-12 bg-red-100 text-red-600">
                <x-admin.icon name="trash" class="h-6 w-6" />
            </div>

            <h3 class="mb-1 text-lg font-semibold text-slate-900">{{ $title }}</h3>
            <p class="mb-5 text-sm text-slate-500">{{ $text }}</p>

            <div class="flex justify-center gap-3">
                <button type="button" class="btn-secondary !px-4 !py-2 text-sm" data-modal-hide="delete-modal">Cancelar</button>

                <form id="delete-form" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-danger !px-4 !py-2 text-sm">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>
