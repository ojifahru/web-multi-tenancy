@php
    $currentLocale = app()->getLocale();
    $availableLocales = array_values(array_filter(
        get_available_locales(),
        static fn (string $locale): bool => $locale !== $currentLocale
    ));
    $localeFlags = [
        'id' => '🇮🇩',
        'en' => '🇬🇧',
    ];
@endphp

<details class="relative" aria-label="{{ __('labels.labels.language') }}">
    <summary
        class="flex cursor-pointer items-center gap-2 rounded-md bg-uniba-surface-0 px-3 py-1.5 text-[11px] font-semibold uppercase tracking-[0.12em] text-uniba-primary-blue transition hover:border-uniba-primary-blue/30 hover:bg-uniba-surface-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-uniba-gold focus-visible:ring-offset-2 focus-visible:ring-offset-uniba-bg">
        <span aria-hidden="true">{{ $localeFlags[$currentLocale] ?? '🏳️' }}</span>
        <span class="sr-only">{{ __('labels.labels.language') }}: {{ get_locale_name($currentLocale) }}</span>
        <svg class="h-3.5 w-3.5 text-uniba-primary-blue" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd"
                  d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.94a.75.75 0 111.08 1.04l-4.24 4.5a.75.75 0 01-1.08 0l-4.24-4.5a.75.75 0 01.02-1.06z"
                  clip-rule="evenodd"/>
        </svg>
    </summary>

    <div
        class="absolute right-0 z-10 mt-2 w-44 rounded-lg border border-uniba-primary-blue/10 bg-uniba-surface-0 p-1 shadow-lg">
        @foreach ($availableLocales as $locale)
            <a href="{{ locale_url($locale) }}"
               class="flex items-center gap-2 rounded-md px-3 py-2 text-[11px] font-semibold uppercase tracking-[0.12em] text-uniba-text-secondary transition hover:bg-uniba-surface-2 hover:text-uniba-primary-blue focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-uniba-gold focus-visible:ring-offset-2 focus-visible:ring-offset-uniba-bg">
                <span aria-hidden="true">{{ $localeFlags[$locale] ?? '🏳️' }}</span>
                <span>{{ get_locale_name($locale) }}</span>
            </a>
        @endforeach
    </div>
</details>
