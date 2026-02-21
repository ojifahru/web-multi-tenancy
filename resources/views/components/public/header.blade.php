@props(['tenant'])

<header
    class="sticky top-0 z-50 border-b border-uniba-primary-blue/10 bg-uniba-surface-0/90 text-uniba-text shadow-sm backdrop-blur"
    x-data="{ open: false }" @keydown.escape.window="open = false">
    <div class="mx-auto max-w-7xl px-6 py-5 lg:px-8">
        @php
            $isActive = function (string $pattern): bool {
                return request()->routeIs($pattern);
            };

            $desktopLinkBase =
                'relative text-xs font-semibold uppercase tracking-[0.14em] after:absolute after:-bottom-1.5 after:left-0 after:h-0.5 after:rounded after:bg-uniba-gold after:transition-all';
            $desktopLinkInactive =
                'text-uniba-text-secondary after:w-0 hover:text-uniba-primary-blue hover:after:w-full';
            $desktopLinkActive = 'text-uniba-primary-blue after:w-full';

            $mobileLinkBase = 'rounded-md px-3 py-2 text-sm font-semibold';
            $mobileLinkInactive = 'text-uniba-text-secondary hover:bg-uniba-surface-2 hover:text-uniba-primary-blue';
            $mobileLinkActive = 'bg-uniba-surface-2 text-uniba-primary-blue';
        @endphp

        <div class="flex items-center justify-between gap-4">
            <a href="{{ route('public.home') }}" class="flex items-center gap-3">
                @php
                    $logoUrl = $tenant->getFirstMediaUrl('study_program_logo');
                @endphp

                @if (!empty($logoUrl))
                    <img src="{{ $logoUrl }}" alt="Logo {{ $tenant->name }}" width="40" height="40"
                        loading="eager" decoding="async" class="h-10 w-10 rounded bg-white">
                @endif

                <div class="leading-tight">
                    <div class="text-sm font-semibold tracking-wide">{{ $tenant->name }}</div>

                    <div class="text-xs uppercase tracking-[0.14em] text-uniba-text-secondary">Universitas Batam</div>

                </div>
            </a>

            <div class="flex items-center gap-3">
                <nav class="hidden items-center gap-7 md:flex">
                    <a href="{{ route('public.home') }}"
                        class="{{ $desktopLinkBase }} {{ $isActive('public.home') ? $desktopLinkActive : $desktopLinkInactive }}"
                        @if ($isActive('public.home')) aria-current="page" @endif>
                        Beranda
                    </a>
                    <a href="{{ route('public.about') }}"
                        class="{{ $desktopLinkBase }} {{ $isActive('public.about') ? $desktopLinkActive : $desktopLinkInactive }}"
                        @if ($isActive('public.about')) aria-current="page" @endif>
                        Tentang Kami
                    </a>
                    <a href="{{ route('public.news.index') }}"
                        class="{{ $desktopLinkBase }} {{ $isActive('public.news.*') ? $desktopLinkActive : $desktopLinkInactive }}"
                        @if ($isActive('public.news.*')) aria-current="page" @endif>
                        Berita
                    </a>
                    <a href="{{ route('public.lecturers.index') }}"
                        class="{{ $desktopLinkBase }} {{ $isActive('public.lecturers.*') ? $desktopLinkActive : $desktopLinkInactive }}"
                        @if ($isActive('public.lecturers.*')) aria-current="page" @endif>
                        Dosen
                    </a>
                    <a href="{{ route('public.facilities.index') }}"
                        class="{{ $desktopLinkBase }} {{ $isActive('public.facilities.*') ? $desktopLinkActive : $desktopLinkInactive }}"
                        @if ($isActive('public.facilities.*')) aria-current="page" @endif>
                        Fasilitas
                    </a>
                    <a href="{{ route('public.contact') }}"
                        class="{{ $desktopLinkBase }} {{ $isActive('public.contact') ? $desktopLinkActive : $desktopLinkInactive }}"
                        @if ($isActive('public.contact')) aria-current="page" @endif>
                        Kontak
                    </a>
                </nav>

                <x-public.button variant="gold" size="sm" :href="route('public.contact')" class="hidden md:inline-flex">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                    </svg>
                    Hubungi Kami
                </x-public.button>

                <button type="button"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-md border border-uniba-primary-blue/20 text-uniba-primary-blue hover:border-uniba-gold md:hidden"
                    @click="open = ! open" :aria-expanded="open.toString()" aria-controls="public-mobile-navigation"
                    aria-label="Toggle menu">
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M3 5h14a1 1 0 010 2H3a1 1 0 010-2zm0 6h14a1 1 0 010 2H3a1 1 0 010-2zm0 6h14a1 1 0 010 2H3a1 1 0 010-2z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>

        <div id="public-mobile-navigation" class="md:hidden" x-cloak x-show="open" x-transition>
            <div class="mt-4 grid gap-2 rounded-lg border border-uniba-primary-blue/10 bg-uniba-surface-0 p-3 shadow-sm">
                <a href="{{ route('public.home') }}"
                    class="{{ $mobileLinkBase }} {{ $isActive('public.home') ? $mobileLinkActive : $mobileLinkInactive }}"
                    @click="open = false" @if ($isActive('public.home')) aria-current="page" @endif>
                    Home
                </a>
                <a href="{{ route('public.news.index') }}"
                    class="{{ $mobileLinkBase }} {{ $isActive('public.news.*') ? $mobileLinkActive : $mobileLinkInactive }}"
                    @click="open = false" @if ($isActive('public.news.*')) aria-current="page" @endif>
                    Berita
                </a>
                <a href="{{ route('public.about') }}"
                    class="{{ $mobileLinkBase }} {{ $isActive('public.about') ? $mobileLinkActive : $mobileLinkInactive }}"
                    @click="open = false" @if ($isActive('public.about')) aria-current="page" @endif>
                    Tentang
                </a>
                <a href="{{ route('public.lecturers.index') }}"
                    class="{{ $mobileLinkBase }} {{ $isActive('public.lecturers.*') ? $mobileLinkActive : $mobileLinkInactive }}"
                    @click="open = false" @if ($isActive('public.lecturers.*')) aria-current="page" @endif>
                    Dosen
                </a>
                <a href="{{ route('public.facilities.index') }}"
                    class="{{ $mobileLinkBase }} {{ $isActive('public.facilities.*') ? $mobileLinkActive : $mobileLinkInactive }}"
                    @click="open = false" @if ($isActive('public.facilities.*')) aria-current="page" @endif>
                    Fasilitas
                </a>
                <a href="{{ route('public.contact') }}"
                    class="{{ $mobileLinkBase }} {{ $isActive('public.contact') ? $mobileLinkActive : $mobileLinkInactive }}"
                    @click="open = false" @if ($isActive('public.contact')) aria-current="page" @endif>
                    Kontak
                </a>

                <x-public.button variant="gold" size="sm" :href="route('public.contact')" class="mt-1 w-full"
                    @click="open = false">
                    Hubungi Kami
                </x-public.button>
            </div>
        </div>
    </div>
</header>
