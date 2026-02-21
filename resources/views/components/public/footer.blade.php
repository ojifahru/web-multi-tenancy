@props(['tenant'])

<footer class="mt-16 border-t border-uniba-primary-blue/10 bg-uniba-surface-0 text-uniba-text">
    <div class="mx-auto max-w-7xl px-6 py-16 lg:px-8">
        <div class="grid gap-10 md:grid-cols-2 lg:grid-cols-4">
            <div class="lg:pr-6">
                @php
                    $logoUrl = $tenant->getFirstMediaUrl('study_program_logo');
                @endphp

                @if (!empty($logoUrl))
                    <div class="flex items-start gap-3">
                        <img src="{{ $logoUrl }}" alt="Logo {{ $tenant->name }}" width="64" height="64"
                            loading="lazy" decoding="async" class="h-16 w-16 rounded bg-white">
                        <div>
                            <p class="text-base font-semibold tracking-wide text-uniba-deep-blue">{{ $tenant->name }}</p>
                            <p class="mt-1 text-xs uppercase tracking-[0.12em] text-uniba-text-secondary">Universitas
                                Batam</p>
                        </div>
                    </div>
                @else
                    <div class="text-base font-semibold text-uniba-deep-blue">{{ $tenant->name }}</div>
                @endif

                @if (!empty($tenant->description))
                    <p class="mt-4 text-sm leading-relaxed text-uniba-text-secondary">
                        {{ \Illuminate\Support\Str::limit(strip_tags($tenant->description), 120) }}
                    </p>
                @endif
            </div>

            <div>
                <div class="text-xs font-semibold uppercase tracking-[0.14em] text-uniba-primary-blue">Navigasi</div>
                <div class="mt-5 grid gap-2 text-sm">
                    <a href="{{ route('public.home') }}"
                        class="rounded-sm text-uniba-text-secondary transition hover:text-uniba-primary-blue focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-uniba-gold focus-visible:ring-offset-2 focus-visible:ring-offset-white">Home</a>
                    <a href="{{ route('public.news.index') }}"
                        class="rounded-sm text-uniba-text-secondary transition hover:text-uniba-primary-blue focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-uniba-gold focus-visible:ring-offset-2 focus-visible:ring-offset-white">Berita</a>
                    <a href="{{ route('public.about') }}"
                        class="rounded-sm text-uniba-text-secondary transition hover:text-uniba-primary-blue focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-uniba-gold focus-visible:ring-offset-2 focus-visible:ring-offset-white">Tentang Kami</a>
                    <a href="{{ route('public.lecturers.index') }}"
                        class="rounded-sm text-uniba-text-secondary transition hover:text-uniba-primary-blue focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-uniba-gold focus-visible:ring-offset-2 focus-visible:ring-offset-white">Dosen</a>
                    <a href="{{ route('public.facilities.index') }}"
                        class="rounded-sm text-uniba-text-secondary transition hover:text-uniba-primary-blue focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-uniba-gold focus-visible:ring-offset-2 focus-visible:ring-offset-white">Fasilitas</a>
                    <a href="{{ route('public.contact') }}"
                        class="rounded-sm text-uniba-text-secondary transition hover:text-uniba-primary-blue focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-uniba-gold focus-visible:ring-offset-2 focus-visible:ring-offset-white">Kontak Kami</a>
                </div>
            </div>

            <div>
                <div class="text-xs font-semibold uppercase tracking-[0.14em] text-uniba-primary-blue">Akademik</div>

                <div class="mt-5 space-y-2 text-sm text-uniba-text-secondary">
                    <div>Akreditasi: <span
                            class="font-semibold text-uniba-text">{{ $tenant->accreditation ?? '—' }}</span></div>
                    <div>Tahun Berdiri: <span
                            class="font-semibold text-uniba-text">{{ $tenant->established_year ?? '—' }}</span></div>
                </div>
            </div>

            <div>
                <div class="text-xs font-semibold uppercase tracking-[0.14em] text-uniba-primary-blue">Kontak</div>

                <div class="mt-4 space-y-2 text-sm">
                    @if (!empty($tenant->address))
                        <div class="flex items-start gap-2 text-uniba-text-secondary">
                            <svg class="mt-0.5 h-4 w-4 shrink-0 text-uniba-text-secondary" viewBox="0 0 20 20"
                                fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M5.05 7.05a7 7 0 119.9 9.9L10 19l-4.95-2.05a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span>{{ $tenant->address }}</span>
                        </div>
                    @endif

                    @if (!empty($tenant->email))
                        <a href="mailto:{{ $tenant->email }}"
                            class="flex items-center gap-2 font-semibold text-uniba-primary-blue hover:text-uniba-deep-blue">
                            <svg class="h-4 w-4 text-uniba-text-secondary" viewBox="0 0 20 20" fill="currentColor"
                                aria-hidden="true">
                                <path d="M2.94 6.34A2 2 0 014.5 5h11A2 2 0 0117.06 6.34L10 10.5 2.94 6.34z" />
                                <path
                                    d="M18 8.08l-7.553 4.25a1 1 0 01-.894 0L2 8.08V14a2 2 0 002 2h12a2 2 0 002-2V8.08z" />
                            </svg>
                            <span>{{ $tenant->email }}</span>
                        </a>
                    @endif

                    @if (!empty($tenant->phone))
                        <a href="tel:{{ $tenant->phone }}"
                            class="flex items-center gap-2 text-uniba-text-secondary hover:text-uniba-text">
                            <svg class="h-4 w-4 text-uniba-text-secondary" viewBox="0 0 20 20" fill="currentColor"
                                aria-hidden="true">
                                <path
                                    d="M2.003 5.884l3.5-1.4a1 1 0 011.21.48l1.5 2.6a1 1 0 01-.146 1.207l-1.2 1.2a12.06 12.06 0 005.1 5.1l1.2-1.2a1 1 0 011.207-.146l2.6 1.5a1 1 0 01.48 1.21l-1.4 3.5a1 1 0 01-1.01.63C7.43 19.06.94 12.57 1.372 6.894a1 1 0 01.63-1.01z" />
                            </svg>
                            <span>{{ $tenant->phone }}</span>
                        </a>
                    @endif
                </div>

                <div class="mt-6 flex flex-wrap gap-3">
                    @if (!empty($tenant->facebook_link))
                        <a href="{{ $tenant->facebook_link }}"
                            class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-uniba-border text-uniba-text-secondary transition hover:border-uniba-primary-blue hover:text-uniba-primary-blue"
                            target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                            <span class="sr-only">Facebook</span>
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path
                                    d="M22 12.07C22 6.51 17.52 2 12 2S2 6.51 2 12.07c0 5.03 3.66 9.2 8.44 9.93v-7.02H7.9V12.1h2.54V9.9c0-2.52 1.49-3.92 3.77-3.92 1.09 0 2.23.2 2.23.2v2.46h-1.25c-1.24 0-1.63.77-1.63 1.57v1.89h2.77l-.44 2.88h-2.33V22c4.78-.73 8.44-4.9 8.44-9.93z" />
                            </svg>
                        </a>
                    @endif
                    @if (!empty($tenant->instagram_link))
                        <a href="{{ $tenant->instagram_link }}"
                            class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-uniba-border text-uniba-text-secondary transition hover:border-uniba-primary-blue hover:text-uniba-primary-blue"
                            target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                            <span class="sr-only">Instagram</span>
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path
                                    d="M12 2c2.72 0 3.06.01 4.12.06 1.06.05 1.78.22 2.42.47.66.26 1.22.6 1.78 1.17.56.56.9 1.12 1.16 1.78.26.64.43 1.36.48 2.42.05 1.06.06 1.4.06 4.12s-.01 3.06-.06 4.12c-.05 1.06-.22 1.78-.48 2.42-.26.66-.6 1.22-1.16 1.78-.56.56-1.12.9-1.78 1.16-.64.26-1.36.43-2.42.48-1.06.05-1.4.06-4.12.06s-3.06-.01-4.12-.06c-1.06-.05-1.78-.22-2.42-.48-.66-.26-1.22-.6-1.78-1.16-.56-.56-.9-1.12-1.16-1.78-.26-.64-.43-1.36-.48-2.42C2.01 15.06 2 14.72 2 12s.01-3.06.06-4.12c.05-1.06.22-1.78.48-2.42.26-.66.6-1.22 1.16-1.78.56-.56 1.12-.9 1.78-1.17.64-.25 1.36-.42 2.42-.47C8.94 2.01 9.28 2 12 2zm0 2.16c-2.67 0-2.99.01-4.02.06-.95.04-1.46.2-1.8.33-.44.17-.75.37-1.08.7-.33.33-.53.64-.7 1.08-.13.34-.29.85-.33 1.8-.05 1.03-.06 1.35-.06 4.02s.01 2.99.06 4.02c.04.95.2 1.46.33 1.8.17.44.37.75.7 1.08.33.33.64.53 1.08.7.34.13.85.29 1.8.33 1.03.05 1.35.06 4.02.06s2.99-.01 4.02-.06c.95-.04 1.46-.2 1.8-.33.44-.17.75-.37 1.08-.7.33-.33.53-.64.7-1.08.13-.34.29-.85.33-1.8.05-1.03.06-1.35.06-4.02s-.01-2.99-.06-4.02c-.04-.95-.2-1.46-.33-1.8-.17-.44-.37-.75-.7-1.08-.33-.33-.64-.53-1.08-.7-.34-.13-.85-.29-1.8-.33-1.03-.05-1.35-.06-4.02-.06zm0 3.68a4.16 4.16 0 110 8.32 4.16 4.16 0 010-8.32zm0 6.86a2.7 2.7 0 100-5.4 2.7 2.7 0 000 5.4zm5.3-7.99a.97.97 0 110 1.94.97.97 0 010-1.94z" />
                            </svg>
                        </a>
                    @endif
                    @if (!empty($tenant->twitter_link))
                        <a href="{{ $tenant->twitter_link }}"
                            class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-uniba-border text-uniba-text-secondary transition hover:border-uniba-primary-blue hover:text-uniba-primary-blue"
                            target="_blank" rel="noopener noreferrer" aria-label="Twitter">
                            <span class="sr-only">Twitter</span>
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path
                                    d="M18.9 2H22l-6.78 7.75L23 22h-6.1l-4.78-6.25L6.7 22H3.6l7.24-8.28L1.5 2h6.25l4.32 5.7L18.9 2zm-1.07 18.17h1.69L6.84 3.73H5.03l12.8 16.44z" />
                            </svg>
                        </a>
                    @endif
                    @if (!empty($tenant->linkedin_link))
                        <a href="{{ $tenant->linkedin_link }}"
                            class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-uniba-border text-uniba-text-secondary transition hover:border-uniba-primary-blue hover:text-uniba-primary-blue"
                            target="_blank" rel="noopener noreferrer" aria-label="LinkedIn">
                            <span class="sr-only">LinkedIn</span>
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path
                                    d="M4.98 3.5a2.5 2.5 0 11-.01 5.01 2.5 2.5 0 01.01-5.01zM3 9h4v12H3V9zm7 0h3.84v1.71h.05c.54-1.02 1.85-2.09 3.81-2.09 4.07 0 4.82 2.68 4.82 6.16V21h-4v-5.55c0-1.32-.03-3.02-1.84-3.02-1.84 0-2.12 1.44-2.12 2.93V21h-4V9z" />
                            </svg>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div
            class="mt-12 flex flex-col gap-2 border-t border-uniba-border pt-6 text-xs text-uniba-text-secondary sm:flex-row sm:items-center sm:justify-between">
            <div>© {{ now()->year }} {{ $tenant->name }}. All rights reserved.</div>
            <div>Universitas Batam (UNIBA)</div>
        </div>
    </div>
</footer>
