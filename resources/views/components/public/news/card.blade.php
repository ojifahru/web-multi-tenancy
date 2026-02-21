@props([
    'news' => null,
    'item' => null,
])

@php
    $item = $news ?? $item;
    $imageUrl = $item->getFirstMediaUrl('news_image', 'preview');
@endphp

<a href="{{ route('public.news.show', ['slug' => $item->slug]) }}"
    class="group block rounded-xl focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-uniba-gold focus-visible:ring-offset-2 focus-visible:ring-offset-uniba-bg">
    <x-public.card class="overflow-hidden">
        @if (!empty($imageUrl))
            <img src="{{ $imageUrl }}" alt="{{ $item->title }}"
                width="960" height="540" loading="lazy" decoding="async"
                class="aspect-video w-full object-cover transition duration-500 group-hover:scale-[1.02]">
        @else
            <x-public.image-placeholder class="aspect-video border-b border-uniba-border" label="Gambar belum tersedia" />
        @endif

        <div class="p-5">
            @if (!empty($item->published_at))
                <div class="text-[11px] font-semibold uppercase tracking-[0.14em] text-uniba-primary-blue">
                    {{ $item->published_at->format('d M Y') }}
                </div>
            @endif

            <div
                class="mt-2 text-base font-semibold leading-snug text-uniba-text transition-colors group-hover:text-uniba-primary-blue">
                {{ $item->title }}
            </div>

            @if (!empty($item->excerpt))
                <div class="mt-3 text-sm leading-relaxed text-uniba-text-secondary">
                    {{ \Illuminate\Support\Str::limit($item->excerpt, 110) }}</div>
            @endif

            <div class="mt-4 text-xs font-semibold uppercase tracking-[0.12em] text-uniba-primary-blue">
                Baca Selengkapnya &rarr;
            </div>
        </div>
    </x-public.card>
</a>
