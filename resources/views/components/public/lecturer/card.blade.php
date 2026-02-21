@props(['lecturer'])

@php
    $imageUrl = $lecturer->getFirstMediaUrl('lecturer_image', 'preview');
@endphp

<x-public.card class="overflow-hidden">
    <div class="flex gap-4 p-5">
        <div class="shrink-0">
            @if (!empty($imageUrl))
                <img src="{{ $imageUrl }}" alt="{{ $lecturer->name }}" width="64" height="64" loading="lazy"
                    decoding="async" class="h-16 w-16 rounded-lg object-cover">
            @else
                <x-public.image-placeholder class="h-16 w-16 rounded-lg" :show-label="false" iconClass="h-5 w-5" />
            @endif
        </div>

        <div class="min-w-0 pt-0.5">
            <div class="text-base font-semibold leading-snug text-uniba-text">{{ $lecturer->name }}</div>
            <div class="mt-1 text-xs uppercase tracking-[0.08em] text-uniba-text-secondary">NIDN: {{ $lecturer->nidn }}
            </div>

            <div class="mt-3 flex flex-wrap gap-x-3 gap-y-1 text-xs">
                <a href="mailto:{{ $lecturer->email }}"
                    class="font-medium text-uniba-primary-blue underline-offset-2 hover:text-uniba-deep-blue hover:underline">
                    {{ $lecturer->email }}
                </a>
                @if (!empty($lecturer->phone))
                    <a href="tel:{{ $lecturer->phone }}" class="text-uniba-text-secondary hover:text-uniba-text">
                        {{ $lecturer->phone }}
                    </a>
                @endif
            </div>
        </div>
    </div>
</x-public.card>
