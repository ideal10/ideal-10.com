@props(['disabled' => false])

<textarea @disabled($disabled) {{ $attributes->merge(['class' => 'field-input']) }}>{{ $slot }}</textarea>
