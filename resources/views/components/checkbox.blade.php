@props(['disabled' => false, 'checked' => false])

<input type="checkbox" @disabled($disabled) @checked($checked) {{ $attributes->merge(['class' => 'h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500']) }}>
