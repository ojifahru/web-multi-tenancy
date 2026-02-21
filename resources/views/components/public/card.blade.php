@props([
    'hover' => true,
])

@php
    $classes = 'rounded-xl border border-uniba-primary-blue/10 bg-uniba-surface-0 shadow-sm';

    if ($hover) {
        $classes .=
            ' transition duration-300 hover:-translate-y-0.5 hover:border-uniba-primary-blue/25 hover:shadow-lg hover:shadow-uniba-primary-blue/10';
    }
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>
