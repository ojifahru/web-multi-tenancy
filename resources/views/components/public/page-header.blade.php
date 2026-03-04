@props(['title', 'subtitle' => null, 'breadcrumbs' => []])

<div {{ $attributes->merge(['class' => 'mb-10']) }}>
    <nav aria-label="Breadcrumb" class="text-sm">
        <ol class="flex flex-wrap items-center gap-x-2 gap-y-1 text-uniba-secondary">
            @foreach ($breadcrumbs as $index => $crumb)
                @php
                    $isLast = $index === count($breadcrumbs) - 1;
                    $label = $crumb['label'] ?? '';
                    $href = $crumb['href'] ?? null;
                @endphp

                <li class="flex items-center gap-2">
                    @if (!empty($href) && !$isLast)
                        <a href="{{ $href }}"
                            class="max-w-64 truncate text-xs font-semibold uppercase tracking-[0.12em] text-uniba-primary-blue hover:text-uniba-deep-blue">
                            {{ $label }}
                        </a>
                    @else
                        <span
                            class="max-w-64 truncate text-xs uppercase tracking-[0.12em] {{ $isLast ? 'font-semibold text-uniba-text' : '' }}">
                            {{ $label }}
                        </span>
                    @endif

                    @if (!$isLast)
                        <svg class="h-4 w-4 text-uniba-secondary" viewBox="0 0 20 20" fill="currentColor"
                            aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>

    <div class="mt-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="flex items-center gap-3 text-3xl font-light leading-tight tracking-tight text-uniba-deep-blue sm:text-4xl">
                @isset($icon)
                    {{ $icon }}
                @endisset
                <span>{{ $title }}</span>
            </h1>

            <div class="mt-4 h-0.5 w-16 rounded bg-uniba-gold"></div>

            @if (!empty($subtitle))
                <p class="mt-4 max-w-3xl text-base leading-relaxed text-uniba-secondary">{{ $subtitle }}</p>
            @endif
        </div>

        @isset($actions)
            <div class="flex flex-wrap gap-3">
                {{ $actions }}
            </div>
        @endisset
    </div>
</div>
