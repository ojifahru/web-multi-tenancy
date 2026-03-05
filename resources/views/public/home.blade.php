@php
    $tenantName = $tenant->resolveLocalizedValue('name') ?? __('common.site.university_short');
    $tenantDescription = $tenant->resolveLocalizedValue('description');
@endphp

<x-public.layout :tenant="$tenant" :title="$tenantName">
    @php
        $bannerUrl = $tenant->getFirstMediaUrl('study_program_banner');

        if (empty($bannerUrl) && !empty($tenant->banner_path)) {
            $bannerUrl = str_starts_with($tenant->banner_path, ['http://', 'https://', '/'])
                ? $tenant->banner_path
                : asset('storage/' . ltrim($tenant->banner_path, '/'));
        }
    @endphp

    {{-- ═══════════════════════════════════════════════
         HERO
    ═══════════════════════════════════════════════ --}}
    <section
        class="relative overflow-hidden bg-[linear-gradient(145deg,var(--color-uniba-surface-0)_0%,var(--color-uniba-surface-1)_52%,var(--color-uniba-surface-2)_100%)] py-16 sm:py-20">
        <div
            class="pointer-events-none absolute -top-24 right-0 h-72 w-72 rounded-full bg-uniba-primary-blue/10 blur-3xl">
        </div>
        <div class="pointer-events-none absolute bottom-8 left-0 h-56 w-56 rounded-full bg-uniba-gold/15 blur-3xl"></div>
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="grid items-center gap-16 lg:grid-cols-2">
                {{-- Left: Text --}}
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-uniba-primary-blue">
                        {{ __('home.hero.program_label') }}</p>

                    <h1
                        class="mt-5 text-4xl font-light leading-tight tracking-wide text-uniba-deep-blue sm:text-5xl lg:text-6xl">
                        {{ $tenantName }}
                    </h1>

                    <div class="mt-5 h-0.5 w-16 bg-uniba-gold"></div>

                    <p class="mt-7 max-w-2xl text-base leading-relaxed text-uniba-text-secondary">
                        {{ $tenantDescription ? \Illuminate\Support\Str::limit(strip_tags($tenantDescription), 180) : __('home.hero.description_default') }}
                    </p>

                    <div class="mt-10 flex flex-wrap gap-4">
                        <a href="{{ localized_route('public.news.index') }}"
                            class="inline-flex items-center gap-2 rounded-xl border border-uniba-primary-blue bg-uniba-primary-blue px-7 py-3 text-sm font-semibold tracking-wide text-white transition-all hover:bg-uniba-deep-blue focus:outline-none focus:ring-2 focus:ring-uniba-primary-blue/30">
                            {{ __('home.hero.cta_news') }}
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M3 10a.75.75 0 01.75-.75h10.19L9.47 4.78a.75.75 0 011.06-1.06l5.5 5.25a.75.75 0 010 1.06l-5.5 5.25a.75.75 0 11-1.06-1.06l4.47-4.47H3.75A.75.75 0 013 10z"
                                    clip-rule="evenodd" />
                            </svg>
                        </a>
                        <a href="#tentang"
                            class="inline-flex items-center gap-2 rounded-xl border border-uniba-gold px-7 py-3 text-sm font-semibold tracking-wide text-uniba-deep-blue transition-all hover:bg-uniba-gold/10 focus:outline-none focus:ring-2 focus:ring-uniba-gold/30">
                            {{ __('home.hero.cta_about') }}
                        </a>
                    </div>
                </div>

                {{-- Right: Banner image --}}
                <div class="relative">
                    @if (!empty($bannerUrl))
                        <img src="{{ $bannerUrl }}" alt="Banner {{ $tenantName }}" width="1600" height="900"
                            loading="eager" fetchpriority="high" decoding="async"
                            class="aspect-video w-full rounded-2xl border border-uniba-border object-cover shadow-sm">
                    @else
                        <x-public.image-placeholder class="aspect-video rounded-2xl border border-uniba-border"
                            label="{{ __('home.hero.banner_placeholder') }}" iconClass="h-12 w-12"
                            textClass="text-xs" />
                    @endif
                </div>
            </div>

            {{-- Stats row --}}
            <div
                class="mt-12 grid grid-cols-1 gap-4 rounded-2xl border border-uniba-primary-blue/10 bg-uniba-surface-2/80 p-6 shadow-inner shadow-uniba-primary-blue/5 sm:grid-cols-2 lg:grid-cols-4 lg:gap-6 lg:p-8">
                @foreach ($heroStats as $stat)
                    @php
                        $numericValue = is_numeric($stat['value']) ? (int) $stat['value'] : null;
                        $canAnimateValue =
                            !is_null($numericValue) && in_array($stat['icon'], ['students', 'lecturers'], true);
                    @endphp

                    <div
                        class="group rounded-xl border border-white/70 bg-uniba-surface-0/95 p-5 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-uniba-primary-blue/10">
                        <div class="flex items-center gap-2 text-sm font-semibold text-uniba-primary-blue">
                            <span
                                class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-uniba-primary-blue/10 text-uniba-primary-blue transition duration-300 group-hover:scale-110 group-hover:bg-uniba-primary-blue/20">
                                @if ($stat['icon'] === 'students')
                                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path
                                            d="M10 3a5 5 0 00-5 5v1a3 3 0 01-2.121 2.879A1 1 0 003 13h14a1 1 0 00.121-1.121A3 3 0 0115 9V8a5 5 0 00-5-5z" />
                                        <path d="M7 14a3 3 0 006 0H7z" />
                                    </svg>
                                @elseif ($stat['icon'] === 'lecturers')
                                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path d="M10 2l8 4-8 4-8-4 8-4z" />
                                        <path d="M4 8v4c0 2.21 2.686 4 6 4s6-1.79 6-4V8l-6 3-6-3z" />
                                    </svg>
                                @elseif ($stat['icon'] === 'accreditation')
                                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd"
                                            d="M10 2a1 1 0 01.894.553l1.12 2.27 2.506.364a1 1 0 01.554 1.706l-1.813 1.768.428 2.495a1 1 0 01-1.451 1.054L10 11.03l-2.24 1.178a1 1 0 01-1.45-1.054l.428-2.495-1.813-1.768a1 1 0 01.554-1.706l2.506-.363 1.12-2.271A1 1 0 0110 2z"
                                            clip-rule="evenodd" />
                                        <path
                                            d="M5 13.5a1 1 0 011.58-.814L10 15.25l3.42-2.564A1 1 0 0115 13.5v3a1 1 0 11-2 0v-.999l-2.4 1.8a1 1 0 01-1.2 0L7 15.5v1a1 1 0 11-2 0v-3z" />
                                    </svg>
                                @else
                                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd"
                                            d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v9a3 3 0 003 3h10a3 3 0 003-3V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm9 7H5v6a1 1 0 001 1h8a1 1 0 001-1V9z"
                                            clip-rule="evenodd" />
                                    </svg>
                                @endif
                            </span>
                            <p>{{ $stat['label'] }}</p>
                        </div>
                        <p class="mt-2 text-xs text-uniba-text-secondary">{{ $stat['desc'] }}</p>
                        <p class="mt-4 text-3xl font-light tracking-tight text-uniba-deep-blue">
                            @if ($canAnimateValue)
                                <span data-count-up data-target="{{ $numericValue }}">0</span>
                            @else
                                {{ $stat['value'] }}
                            @endif
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════
         TENTANG / ABOUT
    ═══════════════════════════════════════════════ --}}
    <section id="tentang" class="scroll-mt-28 bg-uniba-surface-1 py-16 sm:py-20">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            {{-- Section label --}}
            <div class="flex items-center gap-4">
                <div class="h-px flex-1 bg-uniba-primary-blue/15"></div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-uniba-primary-blue">
                    {{ __('home.about.section_label') }}</p>
                <div class="h-px flex-1 bg-uniba-primary-blue/15"></div>
            </div>

            {{-- Visi - full width, prominent --}}
            <div class="mt-10 border-l-4 border-uniba-gold bg-uniba-surface-0 px-10 py-10 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-widest text-uniba-gold">
                    {{ __('home.about.vision_label') }}</p>
                <p class="mt-4 text-xl font-light leading-relaxed text-uniba-deep-blue sm:text-2xl">
                    {!! $visionHtml !!}
                </p>
            </div>

            {{-- Misi & Tujuan side by side --}}
            <div class="mt-8 grid gap-8 lg:grid-cols-2">
                <div class="border border-uniba-primary-blue/10 bg-uniba-surface-0 px-8 py-8">
                    <p class="text-xs font-semibold uppercase tracking-widest text-uniba-primary-blue">
                        {{ __('home.about.mission_label') }}</p>
                    <div class="richtext-list prose prose-sm mt-5 max-w-none text-uniba-text-secondary">
                        {!! $missionHtml !!}
                    </div>
                </div>

                <div class="border border-uniba-primary-blue/10 bg-uniba-surface-0 px-8 py-8">
                    <p class="text-xs font-semibold uppercase tracking-widest text-uniba-primary-blue">
                        {{ __('home.about.objectives_label') }}</p>
                    <div class="richtext-list prose prose-sm mt-5 max-w-none text-uniba-text-secondary">
                        {!! $objectivesHtml !!}
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════
         DOSEN
    ═══════════════════════════════════════════════ --}}
    <section id="dosen" class="scroll-mt-28 bg-uniba-surface-0 py-16 sm:py-20">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="flex items-end justify-between gap-6 border-b border-uniba-primary-blue/10 pb-8">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-uniba-gold">
                        {{ __('home.lecturers.kicker') }}</p>
                    <h2 class="mt-2 text-2xl font-light tracking-tight text-uniba-deep-blue sm:text-3xl">
                        {{ __('home.lecturers.title') }}</h2>
                </div>
                <a href="{{ localized_route('public.lecturers.index') }}"
                    class="shrink-0 rounded-md text-xs font-semibold uppercase tracking-widest text-uniba-primary-blue transition hover:text-uniba-deep-blue focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-uniba-gold focus-visible:ring-offset-2 focus-visible:ring-offset-white">
                    {{ __('home.lecturers.view_all') }}
                </a>
            </div>

            @if (($lecturers ?? collect())->isEmpty())
                <x-public.empty-state class="mt-10" title="{{ __('home.lecturers.empty_title') }}"
                    description="{{ __('home.lecturers.empty_description') }}" />
            @else
                <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($lecturers as $lecturer)
                        <x-public.lecturer.card :lecturer="$lecturer" :href="!empty($lecturer->slug)
                            ? localized_route('public.lecturers.show', ['slug' => $lecturer->slug])
                            : null" />
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════
         BERITA
    ═══════════════════════════════════════════════ --}}
    <section id="berita-terbaru" class="scroll-mt-28 bg-uniba-surface-2/60 py-16 sm:py-20">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="flex items-end justify-between gap-6 border-b border-uniba-primary-blue/10 pb-8">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-uniba-gold">
                        {{ __('home.news.kicker') }}</p>
                    <h2 class="mt-2 text-2xl font-light tracking-tight text-uniba-deep-blue sm:text-3xl">
                        {{ __('home.news.title') }}
                    </h2>
                </div>
                <a href="{{ localized_route('public.news.index') }}"
                    class="shrink-0 rounded-md text-xs font-semibold uppercase tracking-widest text-uniba-primary-blue transition hover:text-uniba-deep-blue focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-uniba-gold focus-visible:ring-offset-2 focus-visible:ring-offset-uniba-bg">
                    {{ __('home.news.view_all') }}
                </a>
            </div>

            @if ($latestNews->isEmpty())
                <x-public.empty-state class="mt-10" title="{{ __('home.news.empty_title') }}"
                    description="{{ __('home.news.empty_description') }}" />
            @else
                <div class="mt-10 grid gap-8 md:grid-cols-3">
                    @foreach ($latestNews as $news)
                        <x-public.news.card :news="$news" />
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════
         FASILITAS
    ═══════════════════════════════════════════════ --}}
    <section id="fasilitas" class="scroll-mt-28 bg-uniba-surface-0 py-16 sm:py-20">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="flex items-end justify-between gap-6 border-b border-uniba-primary-blue/10 pb-8">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-uniba-gold">
                        {{ __('home.facilities.kicker') }}</p>
                    <h2 class="mt-2 text-2xl font-light tracking-tight text-uniba-deep-blue sm:text-3xl">
                        {{ __('home.facilities.title') }}</h2>
                </div>
                <a href="{{ localized_route('public.facilities.index') }}"
                    class="shrink-0 rounded-md text-xs font-semibold uppercase tracking-widest text-uniba-primary-blue transition hover:text-uniba-deep-blue focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-uniba-gold focus-visible:ring-offset-2 focus-visible:ring-offset-white">
                    {{ __('home.facilities.view_all') }}
                </a>
            </div>

            @if (($facilities ?? collect())->isEmpty())
                <x-public.empty-state class="mt-10" title="{{ __('home.facilities.empty_title') }}"
                    description="{{ __('home.facilities.empty_description') }}" />
            @else
                <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($facilities as $facility)
                        <x-public.facility.card :facility="$facility" />
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════
         CTA BAND
    ═══════════════════════════════════════════════ --}}
    <section
        class="bg-[linear-gradient(135deg,var(--color-uniba-deep-blue)_0%,var(--color-uniba-primary-blue)_58%,var(--color-uniba-deep-blue)_100%)]">
        <div class="mx-auto max-w-7xl px-6 py-14 lg:px-8 lg:py-16">
            <div class="flex flex-col items-start gap-8 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-uniba-gold">
                        {{ __('home.cta.kicker') }}
                    </p>
                    <h2 class="mt-3 text-2xl font-light tracking-tight text-white sm:text-3xl">
                        {{ __('home.cta.title') }}
                    </h2>
                    <p class="mt-2 text-sm text-white/60">{{ __('home.cta.description') }}</p>
                </div>
                <a href="{{ localized_route('public.contact') }}"
                    class="shrink-0 inline-flex items-center gap-2 rounded-xl border border-uniba-gold bg-uniba-gold px-8 py-3.5 text-sm font-semibold tracking-wide text-uniba-deep-blue transition-all hover:bg-uniba-soft-gold focus:outline-none focus:ring-2 focus:ring-uniba-gold focus:ring-offset-2 focus:ring-offset-uniba-deep-blue">
                    {{ __('home.cta.button') }}
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M3 10a.75.75 0 01.75-.75h10.19L9.47 4.78a.75.75 0 011.06-1.06l5.5 5.25a.75.75 0 010 1.06l-5.5 5.25a.75.75 0 11-1.06-1.06l4.47-4.47H3.75A.75.75 0 013 10z"
                            clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
        </div>
    </section>
</x-public.layout>
