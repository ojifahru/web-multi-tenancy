@props([
    'href' => null,
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
])

@php
    $base =
        'inline-flex items-center justify-center rounded-xl font-semibold transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-uniba-gold focus-visible:ring-offset-2 focus-visible:ring-offset-uniba-bg hover:-translate-y-0.5 active:translate-y-0 disabled:pointer-events-none disabled:opacity-60';

    $sizes = [
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-5 py-2.5 text-base',
    ];

    $variants = [
        'primary' => 'bg-uniba-primary-blue text-white hover:bg-uniba-deep-blue',
        'gold' => 'bg-uniba-gold text-uniba-deep-blue hover:bg-uniba-soft-gold',
        'gold-outline' =>
            'border border-uniba-gold bg-transparent text-uniba-gold hover:bg-uniba-gold hover:text-uniba-deep-blue',
        'outline' => 'border border-uniba-border bg-white text-uniba-text hover:border-uniba-gold',
        'ghost' => 'text-uniba-primary-blue hover:text-uniba-deep-blue',
    ];

    $classes = $base . ' ' . ($sizes[$size] ?? $sizes['md']) . ' ' . ($variants[$variant] ?? $variants['primary']);
    $mergedClasses = trim($classes . ' ' . ($attributes->get('class') ?? ''));
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->except('class')->merge(['class' => $mergedClasses]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->except('class')->merge(['class' => $mergedClasses]) }}>
        {{ $slot }}
    </button>
@endif
