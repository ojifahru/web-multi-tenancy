@props(['lecturer', 'href' => null])

@php
    $imageUrl = $lecturer->getFirstMediaUrl('lecturer_image', 'preview');
    $isClickable = ! empty($href);
@endphp

<x-public.card :hover="$isClickable" class="overflow-hidden">
    @if ($isClickable)
        <a href="{{ $href }}"
            class="group block cursor-pointer focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-uniba-gold focus-visible:ring-offset-2 focus-visible:ring-offset-uniba-bg">
    @endif
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
            <div class="mt-1 text-xs uppercase tracking-[0.08em] text-uniba-text-secondary">{{ __('lecturers.nidn_label') }}: {{ $lecturer->nidn }}
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

            @if ($isClickable)
                <div
                    class="mt-4 inline-flex items-center gap-1 text-xs font-semibold uppercase tracking-[0.08em] text-uniba-primary-blue transition group-hover:translate-x-0.5 group-hover:text-uniba-deep-blue">
                    <span>{{ __('lecturers.view_profile') }}</span>
                    <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M3 10a.75.75 0 01.75-.75h10.19L9.47 4.78a.75.75 0 011.06-1.06l5.5 5.25a.75.75 0 010 1.06l-5.5 5.25a.75.75 0 11-1.06-1.06l4.47-4.47H3.75A.75.75 0 013 10z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
            @endif
        </div>
    </div>
    @if ($isClickable)
        </a>
    @endif
</x-public.card>
