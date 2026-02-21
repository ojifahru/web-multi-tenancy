@props(['facility'])

@php
    $imageUrl = $facility->getFirstMediaUrl('facility_image', 'preview');
@endphp

<x-public.card class="group overflow-hidden">
    @if (!empty($imageUrl))
        <img src="{{ $imageUrl }}" alt="{{ $facility->name }}"
            width="960" height="540" loading="lazy" decoding="async"
            class="aspect-video w-full object-cover transition duration-500 group-hover:scale-[1.02]">
    @else
        <x-public.image-placeholder class="aspect-video" label="Gambar fasilitas belum tersedia" />
    @endif

    <div class="p-5">
        <div
            class="text-base font-semibold leading-snug text-uniba-text transition-colors group-hover:text-uniba-primary-blue">
            {{ $facility->name }}
        </div>
        @if (!empty($facility->description))
            <div class="mt-3 text-sm leading-relaxed text-uniba-text-secondary">
                {{ \Illuminate\Support\Str::limit($facility->description, 110) }}
            </div>
        @endif
    </div>
</x-public.card>
