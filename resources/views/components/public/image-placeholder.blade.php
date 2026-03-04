@props([
    'label' => __('common.placeholders.image_unavailable'),
    'showLabel' => true,
    'iconClass' => 'h-8 w-8',
    'textClass' => 'text-[11px]',
])

<div {{ $attributes->merge(['class' => 'w-full bg-uniba-surface-2']) }}>
    <div class="flex h-full flex-col items-center justify-center gap-2 text-center">
        <svg class="{{ $iconClass }} text-uniba-primary-blue/25" viewBox="0 0 24 24" fill="currentColor"
            aria-hidden="true">
            <path
                d="M19 5H5a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2V7a2 2 0 00-2-2zm0 12H5V7h14v10zM8.5 13.5l2.5 3 3.5-4.5L19 17H5l3.5-3.5z" />
        </svg>

        @if ($showLabel)
            <p class="{{ $textClass }} font-semibold uppercase tracking-[0.12em] text-uniba-text-secondary">
                {{ $label }}
            </p>
        @endif
    </div>
</div>
