@props(['facility'])

@php
    $imageUrl = $facility->getFirstMediaUrl('facility_image', 'preview');
    $facilityName = $facility->resolveLocalizedValue('name') ?? __('facilities.title');
    $facilityDescription = $facility->resolveLocalizedValue('description');
@endphp

<x-public.card class="group overflow-hidden">
    @if (!empty($imageUrl))
        <img src="{{ $imageUrl }}" alt="{{ $facilityName }}" width="960" height="540" loading="lazy" decoding="async"
            class="aspect-video w-full object-cover transition duration-500 group-hover:scale-[1.02]">
    @else
        <x-public.image-placeholder class="aspect-video" label="{{ __('facilities.image_placeholder') }}" />
    @endif

    <div class="p-5">
        <div
            class="text-base font-semibold leading-snug text-uniba-text transition-colors group-hover:text-uniba-primary-blue">
            {{ $facilityName }}
        </div>
        @if (!empty($facilityDescription))
            <div class="mt-3 text-sm leading-relaxed text-uniba-text-secondary">
                {{ \Illuminate\Support\Str::limit($facilityDescription, 110) }}
            </div>
        @endif
    </div>
</x-public.card>
