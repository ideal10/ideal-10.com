@props(['title', 'actionLabel' => null, 'actionRoute' => null])

<div class="flex flex-wrap items-center justify-between gap-3">
    <h1 class="text-xl font-bold tracking-tight text-slate-900">{{ $title }}</h1>

    @if ($actionLabel && $actionRoute)
        <a href="{{ $actionRoute }}" class="btn-primary !px-4 !py-2 text-xs">{{ $actionLabel }}</a>
    @endif
</div>
