@props([
    'news' => null,
    'item' => null,
])

@php
    $item = $news ?? $item;
    $imageUrl = $item->getFirstMediaUrl('news_image', 'preview');
    $newsSlug = $item->resolveSlug();
    $newsTitle = $item->resolveLocalizedValue('title') ?? __('news.title');
    $newsExcerpt = $item->resolveLocalizedValue('excerpt');
@endphp

@if (!empty($newsSlug))
    <a href="{{ localized_route('public.news.show', ['slug' => $newsSlug]) }}"
        class="group block rounded-xl focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-uniba-gold focus-visible:ring-offset-2 focus-visible:ring-offset-uniba-bg">
    @else
        <div class="group block rounded-xl">
@endif
<x-public.card class="overflow-hidden">
    @if (!empty($imageUrl))
        <img src="{{ $imageUrl }}" alt="{{ $newsTitle }}" width="960" height="540" loading="lazy" decoding="async"
            class="aspect-video w-full object-cover transition duration-500 group-hover:scale-[1.02]">
    @else
        <x-public.image-placeholder class="aspect-video border-b border-uniba-border"
            label="{{ __('news.image_placeholder') }}" />
    @endif

    <div class="p-5">
        @if (!empty($item->published_at))
            <div class="text-[11px] font-semibold uppercase tracking-[0.14em] text-uniba-primary-blue">
                {{ $item->published_at->format('d M Y') }}
            </div>
        @endif

        <div
            class="mt-2 text-base font-semibold leading-snug text-uniba-text transition-colors group-hover:text-uniba-primary-blue">
            {{ $newsTitle }}
        </div>

        @if (!empty($newsExcerpt))
            <div class="mt-3 text-sm leading-relaxed text-uniba-text-secondary">
                {{ \Illuminate\Support\Str::limit($newsExcerpt, 110) }}</div>
        @endif

        <div class="mt-4 text-xs font-semibold uppercase tracking-[0.12em] text-uniba-primary-blue">
            {{ __('news.read_more') }} &rarr;
        </div>
    </div>
</x-public.card>
@if (!empty($newsSlug))
    </a>
@else
    </div>
@endif
