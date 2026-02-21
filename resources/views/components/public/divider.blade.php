@props([
    'label' => null,
])

<div {{ $attributes->merge(['class' => 'flex items-center gap-4']) }}>
    <div class="h-px flex-1 bg-uniba-border"></div>

    @if (!empty($label))
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-uniba-gold">{{ $label }}</p>
    @endif

    <div class="h-px flex-1 bg-uniba-border"></div>
</div>
