@if (session('status'))
    <div id="admin-flash" class="mb-4 flex items-center justify-between rounded-xl border border-emerald-100 bg-emerald-50 p-4 text-sm font-medium text-emerald-700" role="alert">
        <span>{{ session('status') }}</span>
        <button type="button" class="btn-icon" data-dismiss-target="#admin-flash" aria-label="Cerrar">
            <x-admin.icon name="x-mark" class="h-4 w-4" />
        </button>
    </div>
@endif
