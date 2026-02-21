@props([
    'title' => null,
    'description' => null,
    'message' => null,
])

<div {{ $attributes->merge(['class' => 'rounded-lg border border-uniba-primary-blue/10 bg-uniba-surface-0 p-6']) }}>
    @if (!empty($message))
        <div class="text-sm text-uniba-secondary">{{ $message }}</div>
    @else
        @if (!empty($title))
            <div class="text-sm font-semibold text-uniba-primary-blue">{{ $title }}</div>
        @endif
        @if (!empty($description))
            <div class="mt-1 text-sm text-uniba-secondary">{{ $description }}</div>
        @endif
    @endif
</div>
