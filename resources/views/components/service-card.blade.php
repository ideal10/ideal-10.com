@props(['name', 'description', 'svg'])

@php
    $icon = match ($svg) {
        'svg/balance.svg' => 'icon.balance',
        'svg/cloud.svg' => 'icon.cloud',
        'svg/code.svg' => 'icon.code',
        'svg/desktop-pc.svg' => 'icon.desktop-pc',
        'svg/shield-check.svg' => 'icon.shield-check',
        default => 'icon.code',
    };
@endphp

<div class="card-interactive group">
    <div class="icon-tile icon-tile-hover mb-3 size-9 dark:group-hover:bg-emerald-600 dark:group-hover:text-white">
        <x-dynamic-component :component="$icon" />
    </div>
    <h3 class="text-sm font-bold leading-6 text-slate-900 dark:text-white">{{ $name }}</h3>
    <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-300">{{ $description }}</p>
</div>
