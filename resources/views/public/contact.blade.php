<x-public.layout :tenant="$tenant" title="Kontak Kami">
    <section class="bg-uniba-surface-1 py-20 sm:py-24">
        <x-public.container>
            <x-public.page-header title="Kontak Kami"
                subtitle="Hubungi kami untuk informasi akademik, kegiatan, atau layanan program studi."
                :breadcrumbs="[['label' => 'Home', 'href' => route('public.home')], ['label' => 'Kontak Kami']]">
                <x-slot:icon>
                    <svg class="h-8 w-8" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M2 5a2 2 0 012-2h12a2 2 0 012 2v7a2 2 0 01-2 2H7.414l-3.707 3.707A1 1 0 012 17.707V5z"
                            clip-rule="evenodd" />
                    </svg>
                </x-slot:icon>

                <x-slot:actions>
                    @if (!empty($tenant->email))
                        <x-public.button variant="gold" :href="'mailto:' . $tenant->email">
                            <span class="inline-flex items-center gap-2">
                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path d="M2.94 6.34A2 2 0 014.5 5h11A2 2 0 0117.06 6.34L10 10.5 2.94 6.34z" />
                                    <path
                                        d="M18 8.08l-7.553 4.25a1 1 0 01-.894 0L2 8.08V14a2 2 0 002 2h12a2 2 0 002-2V8.08z" />
                                </svg>
                                Kirim Email
                            </span>
                        </x-public.button>
                    @endif
                    @if (!empty($tenant->phone))
                        <x-public.button variant="outline" :href="'tel:' . $tenant->phone">
                            <span class="inline-flex items-center gap-2">
                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path
                                        d="M2.003 5.884l3.5-1.4a1 1 0 011.21.48l1.5 2.6a1 1 0 01-.146 1.207l-1.2 1.2a12.06 12.06 0 005.1 5.1l1.2-1.2a1 1 0 011.207-.146l2.6 1.5a1 1 0 01.48 1.21l-1.4 3.5a1 1 0 01-1.01.63C7.43 19.06.94 12.57 1.372 6.894a1 1 0 01.63-1.01z" />
                                </svg>
                                Telepon
                            </span>
                        </x-public.button>
                    @endif
                </x-slot:actions>
            </x-public.page-header>

            @if (session('success'))
                <div
                    class="mt-8 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mt-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <div class="font-semibold">Periksa kembali data formulir Anda.</div>
                    <ul class="mt-2 list-disc space-y-1 pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mt-12 grid gap-6 lg:grid-cols-3">
                <x-public.card class="p-6 lg:col-span-2">
                    <div class="text-sm font-semibold text-uniba-primary-blue">Kirim Pesan</div>
                    <p class="mt-2 text-sm text-uniba-secondary">
                        Isi formulir berikut. Tim kami akan merespons melalui email atau telepon yang Anda cantumkan.
                    </p>

                    <form class="mt-5 grid gap-4 sm:grid-cols-2" action="{{ route('public.contact.store') }}"
                        method="POST">
                        @csrf

                        <div>
                            <label for="name"
                                class="text-xs font-semibold uppercase tracking-[0.12em] text-uniba-primary-blue">
                                Nama *
                            </label>
                            <input id="name" name="name" type="text" value="{{ old('name') }}"
                                placeholder="Nama lengkap"
                                class="mt-2 w-full rounded-lg border border-uniba-border bg-white px-3 py-2 text-sm text-uniba-text placeholder:text-uniba-text-secondary focus:border-uniba-primary-blue focus:outline-none focus:ring-2 focus:ring-uniba-primary-blue/20">
                            @error('name')
                                <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email"
                                class="text-xs font-semibold uppercase tracking-[0.12em] text-uniba-primary-blue">
                                Email *
                            </label>
                            <input id="email" name="email" type="email" value="{{ old('email') }}"
                                placeholder="nama@email.com"
                                class="mt-2 w-full rounded-lg border border-uniba-border bg-white px-3 py-2 text-sm text-uniba-text placeholder:text-uniba-text-secondary focus:border-uniba-primary-blue focus:outline-none focus:ring-2 focus:ring-uniba-primary-blue/20">
                            @error('email')
                                <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone"
                                class="text-xs font-semibold uppercase tracking-[0.12em] text-uniba-primary-blue">
                                Telepon
                            </label>
                            <input id="phone" name="phone" type="text" value="{{ old('phone') }}"
                                placeholder="08xxxxxxxxxx"
                                class="mt-2 w-full rounded-lg border border-uniba-border bg-white px-3 py-2 text-sm text-uniba-text placeholder:text-uniba-text-secondary focus:border-uniba-primary-blue focus:outline-none focus:ring-2 focus:ring-uniba-primary-blue/20">
                            @error('phone')
                                <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="subject"
                                class="text-xs font-semibold uppercase tracking-[0.12em] text-uniba-primary-blue">
                                Subjek *
                            </label>
                            <input id="subject" name="subject" type="text" value="{{ old('subject') }}"
                                placeholder="Contoh: Pertanyaan pendaftaran"
                                class="mt-2 w-full rounded-lg border border-uniba-border bg-white px-3 py-2 text-sm text-uniba-text placeholder:text-uniba-text-secondary focus:border-uniba-primary-blue focus:outline-none focus:ring-2 focus:ring-uniba-primary-blue/20">
                            @error('subject')
                                <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="message"
                                class="text-xs font-semibold uppercase tracking-[0.12em] text-uniba-primary-blue">
                                Pesan *
                            </label>
                            <textarea id="message" name="message" rows="5" placeholder="Tulis pesan Anda di sini..."
                                class="mt-2 w-full rounded-lg border border-uniba-border bg-white px-3 py-2 text-sm text-uniba-text placeholder:text-uniba-text-secondary focus:border-uniba-primary-blue focus:outline-none focus:ring-2 focus:ring-uniba-primary-blue/20">{{ old('message') }}</textarea>
                            @error('message')
                                <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex flex-col gap-3 sm:col-span-2 sm:flex-row sm:items-center sm:justify-between">
                            <p class="text-xs text-uniba-secondary">Kolom bertanda * wajib diisi.</p>
                            <x-public.button type="submit">Kirim Pesan</x-public.button>
                        </div>
                    </form>

                    <div class="mt-8 border-t border-uniba-border pt-6">
                        <div class="inline-flex items-center gap-2 text-sm font-semibold text-uniba-primary-blue">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path
                                    d="M18 10c0 3.866-3.582 7-8 7a9.19 9.19 0 01-3.62-.73L2 17l1.04-3.12A6.33 6.33 0 012 10c0-3.866 3.582-7 8-7s8 3.134 8 7z" />
                            </svg>
                            Media Sosial
                        </div>
                        <div class="mt-3 flex flex-wrap gap-3 text-sm">
                            @if (!empty($tenant->facebook_link))
                                <a class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-uniba-border text-uniba-text-secondary transition hover:border-uniba-primary-blue hover:text-uniba-primary-blue"
                                    target="_blank" rel="noopener noreferrer" href="{{ $tenant->facebook_link }}"
                                    aria-label="Facebook">
                                    <span class="sr-only">Facebook</span>
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                        <path
                                            d="M22 12.07C22 6.51 17.52 2 12 2S2 6.51 2 12.07c0 5.03 3.66 9.2 8.44 9.93v-7.02H7.9V12.1h2.54V9.9c0-2.52 1.49-3.92 3.77-3.92 1.09 0 2.23.2 2.23.2v2.46h-1.25c-1.24 0-1.63.77-1.63 1.57v1.89h2.77l-.44 2.88h-2.33V22c4.78-.73 8.44-4.9 8.44-9.93z" />
                                    </svg>
                                </a>
                            @endif
                            @if (!empty($tenant->instagram_link))
                                <a class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-uniba-border text-uniba-text-secondary transition hover:border-uniba-primary-blue hover:text-uniba-primary-blue"
                                    target="_blank" rel="noopener noreferrer" href="{{ $tenant->instagram_link }}"
                                    aria-label="Instagram">
                                    <span class="sr-only">Instagram</span>
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                        <path
                                            d="M12 2c2.72 0 3.06.01 4.12.06 1.06.05 1.78.22 2.42.47.66.26 1.22.6 1.78 1.17.56.56.9 1.12 1.16 1.78.26.64.43 1.36.48 2.42.05 1.06.06 1.4.06 4.12s-.01 3.06-.06 4.12c-.05 1.06-.22 1.78-.48 2.42-.26.66-.6 1.22-1.16 1.78-.56.56-1.12.9-1.78 1.16-.64.26-1.36.43-2.42.48-1.06.05-1.4.06-4.12.06s-3.06-.01-4.12-.06c-1.06-.05-1.78-.22-2.42-.48-.66-.26-1.22-.6-1.78-1.16-.56-.56-.9-1.12-1.16-1.78-.26-.64-.43-1.36-.48-2.42C2.01 15.06 2 14.72 2 12s.01-3.06.06-4.12c.05-1.06.22-1.78.48-2.42.26-.66.6-1.22 1.16-1.78.56-.56 1.12-.9 1.78-1.17.64-.25 1.36-.42 2.42-.47C8.94 2.01 9.28 2 12 2zm0 2.16c-2.67 0-2.99.01-4.02.06-.95.04-1.46.2-1.8.33-.44.17-.75.37-1.08.7-.33.33-.53.64-.7 1.08-.13.34-.29.85-.33 1.8-.05 1.03-.06 1.35-.06 4.02s.01 2.99.06 4.02c.04.95.2 1.46.33 1.8.17.44.37.75.7 1.08.33.33.64.53 1.08.7.34.13.85.29 1.8.33 1.03.05 1.35.06 4.02.06s2.99-.01 4.02-.06c.95-.04 1.46-.2 1.8-.33.44-.17.75-.37 1.08-.7.33-.33.53-.64.7-1.08.13-.34.29-.85.33-1.8.05-1.03.06-1.35.06-4.02s-.01-2.99-.06-4.02c-.04-.95-.2-1.46-.33-1.8-.17-.44-.37-.75-.7-1.08-.33-.33-.64-.53-1.08-.7-.34-.13-.85-.29-1.8-.33-1.03-.05-1.35-.06-4.02-.06zm0 3.68a4.16 4.16 0 110 8.32 4.16 4.16 0 010-8.32zm0 6.86a2.7 2.7 0 100-5.4 2.7 2.7 0 000 5.4zm5.3-7.99a.97.97 0 110 1.94.97.97 0 010-1.94z" />
                                    </svg>
                                </a>
                            @endif
                            @if (!empty($tenant->twitter_link))
                                <a class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-uniba-border text-uniba-text-secondary transition hover:border-uniba-primary-blue hover:text-uniba-primary-blue"
                                    target="_blank" rel="noopener noreferrer" href="{{ $tenant->twitter_link }}"
                                    aria-label="Twitter">
                                    <span class="sr-only">Twitter</span>
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                        <path
                                            d="M18.9 2H22l-6.78 7.75L23 22h-6.1l-4.78-6.25L6.7 22H3.6l7.24-8.28L1.5 2h6.25l4.32 5.7L18.9 2zm-1.07 18.17h1.69L6.84 3.73H5.03l12.8 16.44z" />
                                    </svg>
                                </a>
                            @endif
                            @if (!empty($tenant->linkedin_link))
                                <a class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-uniba-border text-uniba-text-secondary transition hover:border-uniba-primary-blue hover:text-uniba-primary-blue"
                                    target="_blank" rel="noopener noreferrer" href="{{ $tenant->linkedin_link }}"
                                    aria-label="LinkedIn">
                                    <span class="sr-only">LinkedIn</span>
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                        <path
                                            d="M4.98 3.5a2.5 2.5 0 11-.01 5.01 2.5 2.5 0 01.01-5.01zM3 9h4v12H3V9zm7 0h3.84v1.71h.05c.54-1.02 1.85-2.09 3.81-2.09 4.07 0 4.82 2.68 4.82 6.16V21h-4v-5.55c0-1.32-.03-3.02-1.84-3.02-1.84 0-2.12 1.44-2.12 2.93V21h-4V9z" />
                                    </svg>
                                </a>
                            @endif
                            @if (empty($tenant->facebook_link) &&
                                    empty($tenant->instagram_link) &&
                                    empty($tenant->twitter_link) &&
                                    empty($tenant->linkedin_link))
                                <span class="text-uniba-secondary">—</span>
                            @endif
                        </div>
                    </div>
                </x-public.card>

                <x-public.card class="p-6">
                    <div class="text-sm font-semibold text-uniba-primary-blue">Informasi Kontak</div>

                    <div class="mt-4 grid gap-4">
                        <div>
                            <div class="inline-flex items-center gap-2 text-xs font-semibold text-uniba-secondary">
                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path d="M2.94 6.34A2 2 0 014.5 5h11A2 2 0 0117.06 6.34L10 10.5 2.94 6.34z" />
                                    <path
                                        d="M18 8.08l-7.553 4.25a1 1 0 01-.894 0L2 8.08V14a2 2 0 002 2h12a2 2 0 002-2V8.08z" />
                                </svg>
                                Email
                            </div>
                            <div class="mt-1 text-sm">
                                @if (!empty($tenant->email))
                                    <a class="font-semibold text-uniba-primary-blue hover:text-uniba-deep-blue"
                                        href="mailto:{{ $tenant->email }}">
                                        {{ $tenant->email }}
                                    </a>
                                @else
                                    <span class="text-uniba-secondary">—</span>
                                @endif
                            </div>
                        </div>

                        <div>
                            <div class="inline-flex items-center gap-2 text-xs font-semibold text-uniba-secondary">
                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path
                                        d="M2.003 5.884l3.5-1.4a1 1 0 011.21.48l1.5 2.6a1 1 0 01-.146 1.207l-1.2 1.2a12.06 12.06 0 005.1 5.1l1.2-1.2a1 1 0 011.207-.146l2.6 1.5a1 1 0 01.48 1.21l-1.4 3.5a1 1 0 01-1.01.63C7.43 19.06.94 12.57 1.372 6.894a1 1 0 01.63-1.01z" />
                                </svg>
                                Telepon
                            </div>
                            <div class="mt-1 text-sm">
                                @if (!empty($tenant->phone))
                                    <a class="text-uniba-secondary hover:text-uniba-text" href="tel:{{ $tenant->phone }}">
                                        {{ $tenant->phone }}
                                    </a>
                                @else
                                    <span class="text-uniba-secondary">—</span>
                                @endif
                            </div>
                        </div>

                        <div>
                            <div class="inline-flex items-center gap-2 text-xs font-semibold text-uniba-secondary">
                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M5.05 7.05a7 7 0 119.9 9.9L10 19l-4.95-2.05a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                        clip-rule="evenodd" />
                                </svg>
                                Alamat
                            </div>
                            <div class="mt-1 text-sm text-uniba-secondary">
                                {{ $tenant->address ?? '—' }}
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 rounded-lg bg-uniba-primary-blue/10 p-4">
                        <div class="text-xs font-semibold text-uniba-secondary">Catatan</div>
                        <div class="mt-2 text-sm text-uniba-secondary">
                            Untuk respon lebih cepat, gunakan email resmi program studi.
                        </div>
                    </div>
                </x-public.card>
            </div>
        </x-public.container>
    </section>
</x-public.layout>
