<x-public.layout :tenant="$tenant" title="Tentang Kami">
    <section class="bg-uniba-surface-1 py-20 sm:py-24">
        <x-public.container>
            <x-public.page-header title="Tentang Kami"
                subtitle="Mengenal lebih dekat profil, visi, misi, dan tujuan program studi."
                :breadcrumbs="[['label' => 'Home', 'href' => route('public.home')], ['label' => 'Tentang Kami']]">
                <x-slot:icon>
                    <svg class="h-8 w-8" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-11.5a.75.75 0 10-1.5 0v.5a.75.75 0 001.5 0v-.5zm0 3a.75.75 0 00-1.5 0v4a.75.75 0 001.5 0v-4z"
                            clip-rule="evenodd" />
                    </svg>
                </x-slot:icon>
            </x-public.page-header>

            <div class="grid gap-6 lg:grid-cols-3">
                <x-public.card class="overflow-hidden lg:col-span-2">
                    @php
                        $bannerUrl = $tenant->getFirstMediaUrl('study_program_banner');
                    @endphp

                    @if (!empty($bannerUrl))
                        <img src="{{ $bannerUrl }}" alt="Banner {{ $tenant->name }}"
                            width="1600" height="600" loading="eager" fetchpriority="high" decoding="async"
                            class="aspect-[16/6] w-full border-b border-uniba-border object-cover">
                    @endif

                    <div class="p-6">
                        <h2 class="text-lg font-semibold text-uniba-deep-blue">{{ $tenant->name }}</h2>
                        <div class="mt-1 text-sm text-uniba-secondary">
                            {{ $tenant->faculty ?? 'Program Studi' }}
                            @if (!empty($tenant->degree_level))
                                · {{ $tenant->degree_level }}
                            @endif
                        </div>

                        <div class="prose prose-sm mt-5 max-w-none text-uniba-text-secondary">
                            {!! $aboutHtml !!}
                        </div>
                    </div>
                </x-public.card>

                <x-public.card class="p-6">
                    <div class="text-sm font-semibold uppercase tracking-[0.12em] text-uniba-primary-blue">Profil Singkat
                    </div>
                    <dl class="mt-5 space-y-4 text-sm">
                        <div>
                            <dt class="text-uniba-secondary">Akreditasi</dt>
                            <dd class="font-semibold text-uniba-text">{{ $tenant->accreditation ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-uniba-secondary">Tahun Berdiri</dt>
                            <dd class="font-semibold text-uniba-text">{{ $tenant->established_year ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-uniba-secondary">Jumlah Mahasiswa</dt>
                            <dd class="font-semibold text-uniba-text">{{ $tenant->student ?? 0 }}</dd>
                        </div>
                        <div>
                            <dt class="text-uniba-secondary">Email</dt>
                            <dd class="font-semibold text-uniba-text">{{ $tenant->email ?? '—' }}</dd>
                        </div>
                    </dl>

                    <div class="mt-6">
                        <x-public.button variant="outline" :href="route('public.contact')" class="w-full justify-center">
                            Hubungi Kami
                        </x-public.button>
                    </div>
                </x-public.card>
            </div>

            <div class="mt-8 space-y-6">
                <x-public.card class="p-6">
                    <div class="text-xs font-semibold uppercase tracking-[0.14em] text-uniba-gold">Visi</div>
                    <div class="prose prose-sm mt-3 max-w-none text-uniba-text-secondary">
                        {!! $visionHtml !!}
                    </div>
                </x-public.card>

                <div class="grid gap-6 lg:grid-cols-2">
                    <x-public.card class="p-6">
                        <div class="text-xs font-semibold uppercase tracking-[0.14em] text-uniba-primary-blue">Misi</div>
                        <div class="richtext-list prose prose-sm mt-3 max-w-none text-uniba-text-secondary">
                            {!! $missionHtml !!}
                        </div>
                    </x-public.card>

                    <x-public.card class="p-6">
                        <div class="text-xs font-semibold uppercase tracking-[0.14em] text-uniba-primary-blue">Tujuan</div>
                        <div class="richtext-list prose prose-sm mt-3 max-w-none text-uniba-text-secondary">
                            {!! $objectivesHtml !!}
                        </div>
                    </x-public.card>
                </div>
            </div>
        </x-public.container>
    </section>
</x-public.layout>
