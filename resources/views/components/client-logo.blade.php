@props(['name', 'img'])

<div class="card-interactive flex min-h-28 items-center justify-center p-4">
    <figure class="text-center">
        <div class="mx-auto flex size-16 items-center justify-center">
            <img class="max-h-16 max-w-16 object-contain transition duration-200 hover:scale-105" src="/assets/img/clients/{{ $img }}" width="64" height="64" alt="{{ $name }}">
        </div>
        <figcaption class="mt-2 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ $name }}</figcaption>
    </figure>
</div>
