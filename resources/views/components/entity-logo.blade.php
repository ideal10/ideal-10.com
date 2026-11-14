{{--
    Logo of an Entity or EntityLink, falling back to the name's initials
    on an emerald tile when no image has been uploaded. Size, radius and
    font size come from the caller's classes.
--}}
@props(['model'])

@if ($model->logo_url)
    <span {{ $attributes->merge(['class' => 'flex shrink-0 items-center justify-center overflow-hidden bg-white ring-1 ring-slate-200']) }}>
        <img src="{{ $model->logo_url }}" alt="Logo de {{ $model->name }}" class="h-full w-full object-contain p-[12%]" loading="lazy">
    </span>
@else
    <span {{ $attributes->merge(['class' => 'flex shrink-0 items-center justify-center bg-emerald-100 font-extrabold text-emerald-700']) }} aria-hidden="true">
        {{ $model->initials }}
    </span>
@endif
