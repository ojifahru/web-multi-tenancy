@php
    $facilityName = $facility->resolveLocalizedValue('name') ?? __('facilities.title');
    $facilityDescription = $facility->resolveLocalizedValue('description');
@endphp

<x-public.layout :tenant="$tenant" :title="$facilityName" :description="$facilityDescription">
    <section class="bg-uniba-surface-0 py-20 sm:py-24">
        <x-public.container class="max-w-5xl">
            <x-public.page-header :title="$facilityName" :subtitle="__('facilities.detail_subtitle')" :breadcrumbs="[
                ['label' => __('menu.beranda'), 'href' => localized_route('public.home')],
                ['label' => __('facilities.title'), 'href' => localized_route('public.facilities.index')],
                ['label' => $facilityName],
            ]">
                <x-slot:actions>
                    <x-public.button variant="outline" :href="localized_route('public.facilities.index')">
                        {{ __('common.actions.back') }}
                    </x-public.button>
                </x-slot:actions>
            </x-public.page-header>

            @php
                $imageUrl = $facility->getFirstMediaUrl('facility_image', 'preview');
            @endphp

            @if (!empty($imageUrl))
                <img src="{{ $imageUrl }}" alt="{{ $facilityName }}" width="1280" height="720" loading="eager"
                    fetchpriority="high" decoding="async"
                    class="aspect-video w-full rounded-xl border border-uniba-border object-cover">
            @endif

            <div class="mt-8 rounded-xl border border-uniba-primary-blue/10 bg-uniba-surface-2/60 p-8">
                <h2 class="text-xl font-light tracking-wide text-uniba-deep-blue">{{ __('facilities.detail_title') }}
                </h2>
                <div class="mt-4 text-sm leading-relaxed text-uniba-text-secondary">
                    {{ $facilityDescription ?: __('facilities.detail_empty_description') }}
                </div>
            </div>
        </x-public.container>
    </section>

    @if (($relatedFacilities ?? collect())->isNotEmpty())
        <section class="bg-uniba-surface-1 py-20">
            <x-public.container class="max-w-6xl">
                <x-public.section-heading :title="__('facilities.related_title')" :subtitle="__('facilities.related_subtitle')" />

                <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($relatedFacilities as $relatedFacility)
                        @php($relatedFacilitySlug = $relatedFacility->resolveSlug())

                        @if (!empty($relatedFacilitySlug))
                            <a href="{{ localized_route('public.facilities.show', ['slug' => $relatedFacilitySlug]) }}"
                                class="block rounded-xl focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-uniba-gold focus-visible:ring-offset-2 focus-visible:ring-offset-uniba-bg">
                                <x-public.facility.card :facility="$relatedFacility" />
                            </a>
                        @else
                            <div class="block rounded-xl">
                                <x-public.facility.card :facility="$relatedFacility" />
                            </div>
                        @endif
                    @endforeach
                </div>
            </x-public.container>
        </section>
    @endif
</x-public.layout>
