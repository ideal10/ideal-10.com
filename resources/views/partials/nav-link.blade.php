@php
    $current = request()->path() === '/' ? '/' : '/'.request()->path();
    $aliases = array_merge([$item->url], $item->match ?? []);
    $active = in_array($current, $aliases, true);
@endphp

<li>
    <a href="{{ $item->url }}" class="{{ $active ? 'nav-link-active' : 'nav-link' }}"@if($active) aria-current="page"@endif>{{ $item->label }}</a>
</li>
