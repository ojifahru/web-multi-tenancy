<x-public.layout :tenant="$tenant" :title="$facility->name" :description="$facility->description">
    <section class="bg-uniba-surface-0 py-20 sm:py-24">
        <x-public.container class="max-w-5xl">
            <x-public.page-header :title="$facility->name" subtitle="Detail fasilitas program studi." :breadcrumbs="[
                ['label' => 'Home', 'href' => route('public.home')],
                ['label' => 'Fasilitas', 'href' => route('public.facilities.index')],
                ['label' => $facility->name],
            ]">
                <x-slot:actions>
                    <x-public.button variant="outline" :href="route('public.facilities.index')">
                        Kembali
                    </x-public.button>
                </x-slot:actions>
            </x-public.page-header>

            @php
                $imageUrl = $facility->getFirstMediaUrl('facility_image', 'preview');
            @endphp

            @if (!empty($imageUrl))
                <img src="{{ $imageUrl }}" alt="{{ $facility->name }}"
                    width="1280" height="720" loading="eager" fetchpriority="high" decoding="async"
                    class="aspect-video w-full rounded-xl border border-uniba-border object-cover">
            @endif

            <div class="mt-8 rounded-xl border border-uniba-primary-blue/10 bg-uniba-surface-2/60 p-8">
                <h2 class="text-xl font-light tracking-wide text-uniba-deep-blue">Deskripsi Fasilitas</h2>
                <div class="mt-4 text-sm leading-relaxed text-uniba-text-secondary">
                    {{ $facility->description ?: 'Deskripsi fasilitas belum tersedia.' }}
                </div>
            </div>
        </x-public.container>
    </section>

    @if (($relatedFacilities ?? collect())->isNotEmpty())
        <section class="bg-uniba-surface-1 py-20">
            <x-public.container class="max-w-6xl">
                <x-public.section-heading title="Fasilitas Lainnya"
                    subtitle="Sarana pendukung lain yang tersedia di program studi ini." />

                <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($relatedFacilities as $relatedFacility)
                        <a href="{{ route('public.facilities.show', ['slug' => $relatedFacility->slug]) }}"
                            class="block rounded-xl focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-uniba-gold focus-visible:ring-offset-2 focus-visible:ring-offset-uniba-bg">
                            <x-public.facility.card :facility="$relatedFacility" />
                        </a>
                    @endforeach
                </div>
            </x-public.container>
        </section>
    @endif
</x-public.layout>
