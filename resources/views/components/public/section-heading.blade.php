@props(['title', 'subtitle' => null, 'actionHref' => null, 'actionLabel' => null])

<div class="flex flex-wrap items-end justify-between gap-5">
    <div>
        <h2 class="text-2xl font-light tracking-wide text-uniba-deep-blue sm:text-3xl">{{ $title }}</h2>
        <div class="mt-2 h-0.5 w-16 rounded bg-uniba-gold"></div>

        @if (!empty($subtitle))
            <p class="mt-4 max-w-2xl text-sm leading-relaxed text-uniba-text-secondary">{{ $subtitle }}</p>
        @endif
    </div>

    @if (!empty($actionHref) && !empty($actionLabel))
        <a href="{{ $actionHref }}"
            class="rounded-md text-xs font-semibold uppercase tracking-[0.14em] text-uniba-primary-blue transition hover:text-uniba-deep-blue focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-uniba-gold focus-visible:ring-offset-2 focus-visible:ring-offset-uniba-bg">
            {{ $actionLabel }}
        </a>
    @endif
</div>
