@props(['href'])

<a href="{{ $href }}" target="_blank" rel="noopener" class="mb-4 inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm font-semibold text-slate-600 shadow-sm transition hover:border-emerald-200 hover:bg-emerald-50 hover:text-emerald-700">
    Ver en el sitio
    <x-admin.icon name="arrow-up-right" class="h-4 w-4" />
</a>
