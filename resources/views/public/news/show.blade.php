@php
    $newsTitle = $newsItem->resolveLocalizedValue('title') ?? __('news.title');
    $newsExcerpt = $newsItem->resolveLocalizedValue('excerpt');
@endphp

<x-public.layout :tenant="$tenant" :title="$newsTitle" :description="$newsExcerpt">
    <section class="bg-uniba-surface-0 py-20 sm:py-24">
        <x-public.container class="max-w-4xl">
            <x-public.page-header :title="$newsTitle" :breadcrumbs="[
                ['label' => __('menu.beranda'), 'href' => localized_route('public.home')],
                ['label' => __('news.title'), 'href' => localized_route('public.news.index')],
                ['label' => $newsTitle],
            ]">
                <x-slot:actions>
                    <x-public.button variant="outline" :href="localized_route('public.news.index')">
                        <span class="inline-flex items-center gap-2">
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M17 10a1 1 0 01-1 1H6.414l3.293 3.293a1 1 0 01-1.414 1.414l-5-5a1 1 0 010-1.414l5-5a1 1 0 011.414 1.414L6.414 9H16a1 1 0 011 1z"
                                    clip-rule="evenodd" />
                            </svg>
                            {{ __('common.actions.back') }}
                        </span>
                    </x-public.button>
                </x-slot:actions>
            </x-public.page-header>

            <div
                class="mt-3 flex flex-wrap items-center gap-4 text-xs font-semibold uppercase tracking-[0.12em] text-uniba-text-secondary">
                @if (!empty($newsItem->published_at))
                    <span class="inline-flex items-center gap-2">
                        <svg class="h-4 w-4 text-uniba-text-secondary" viewBox="0 0 20 20" fill="currentColor"
                            aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm10 7H4v7a1 1 0 001 1h10a1 1 0 001-1V9z"
                                clip-rule="evenodd" />
                        </svg>
                        {{ $newsItem->published_at->format('d M Y') }}
                    </span>
                @endif
                <span>{{ __('news.official_article') }}</span>
            </div>

            @php
                $imageUrl = $newsItem->getFirstMediaUrl('news_image', 'preview');
            @endphp

            @if (!empty($imageUrl))
                <img src="{{ $imageUrl }}" alt="{{ $newsTitle }}" width="1280" height="720" loading="eager"
                    fetchpriority="high" decoding="async"
                    class="mt-6 aspect-video w-full rounded-lg border border-uniba-border object-cover">
            @else
                <x-public.image-placeholder class="mt-6 aspect-video rounded-lg border border-uniba-border"
                    label="{{ __('news.image_placeholder') }}" iconClass="h-10 w-10" textClass="text-xs" />
            @endif

            @if (!empty($newsExcerpt))
                <p
                    class="mt-8 border-l-4 border-uniba-gold bg-uniba-surface-2/60 px-5 py-4 text-base leading-relaxed text-uniba-secondary">
                    {{ $newsExcerpt }}
                </p>
            @endif

            <article
                class="prose mt-10 max-w-none prose-slate prose-headings:tracking-wide prose-a:text-uniba-primary-blue">
                {!! $newsContentHtml !!}
            </article>

            <div class="mt-10 flex flex-wrap items-center gap-3 border-t border-uniba-border pt-6">
                <span
                    class="text-xs font-semibold uppercase tracking-[0.12em] text-uniba-text-secondary">{{ __('common.labels.share') }}</span>
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-uniba-border text-uniba-text-secondary transition hover:border-uniba-primary-blue hover:text-uniba-primary-blue"
                    aria-label="{{ __('news.share.facebook') }}">
                    <span class="sr-only">{{ __('news.share.facebook') }}</span>
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path
                            d="M22 12.07C22 6.51 17.52 2 12 2S2 6.51 2 12.07c0 5.03 3.66 9.2 8.44 9.93v-7.02H7.9V12.1h2.54V9.9c0-2.52 1.49-3.92 3.77-3.92 1.09 0 2.23.2 2.23.2v2.46h-1.25c-1.24 0-1.63.77-1.63 1.57v1.89h2.77l-.44 2.88h-2.33V22c4.78-.73 8.44-4.9 8.44-9.93z" />
                    </svg>
                </a>
                <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($newsTitle) }}"
                    target="_blank" rel="noopener noreferrer"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-uniba-border text-uniba-text-secondary transition hover:border-uniba-primary-blue hover:text-uniba-primary-blue"
                    aria-label="{{ __('news.share.x') }}">
                    <span class="sr-only">{{ __('news.share.x') }}</span>
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path
                            d="M18.9 2H22l-6.78 7.75L23 22h-6.1l-4.78-6.25L6.7 22H3.6l7.24-8.28L1.5 2h6.25l4.32 5.7L18.9 2zm-1.07 18.17h1.69L6.84 3.73H5.03l12.8 16.44z" />
                    </svg>
                </a>
                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}"
                    target="_blank" rel="noopener noreferrer"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-uniba-border text-uniba-text-secondary transition hover:border-uniba-primary-blue hover:text-uniba-primary-blue"
                    aria-label="{{ __('news.share.linkedin') }}">
                    <span class="sr-only">{{ __('news.share.linkedin') }}</span>
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path
                            d="M4.98 3.5a2.5 2.5 0 11-.01 5.01 2.5 2.5 0 01.01-5.01zM3 9h4v12H3V9zm7 0h3.84v1.71h.05c.54-1.02 1.85-2.09 3.81-2.09 4.07 0 4.82 2.68 4.82 6.16V21h-4v-5.55c0-1.32-.03-3.02-1.84-3.02-1.84 0-2.12 1.44-2.12 2.93V21h-4V9z" />
                    </svg>
                </a>
            </div>
        </x-public.container>
    </section>

    @if (($relatedNews ?? collect())->isNotEmpty())
        <section class="bg-uniba-surface-1 py-20">
            <x-public.container class="max-w-6xl">
                <x-public.section-heading :title="__('news.related_title')" :subtitle="__('news.related_subtitle')" />
                <div class="mt-10 grid gap-8 md:grid-cols-3">
                    @foreach ($relatedNews as $relatedItem)
                        <x-public.news.card :news="$relatedItem" />
                    @endforeach
                </div>
            </x-public.container>
        </section>
    @endif
</x-public.layout>
