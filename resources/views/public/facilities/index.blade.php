<x-public.layout :tenant="$tenant" :title="__('facilities.title')">
    <section class="bg-uniba-surface-0 py-20 sm:py-24">
        <x-public.container class="max-w-6xl">
            <x-public.page-header :title="__('facilities.title')" :subtitle="__('facilities.subtitle')" :breadcrumbs="[
                ['label' => __('menu.beranda'), 'href' => localized_route('public.home')],
                ['label' => __('facilities.title')],
            ]">
                <x-slot:icon>
                    <svg class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path
                            d="M10.707 2.293a1 1 0 00-1.414 0l-7 7A1 1 0 003 11h1v6a1 1 0 001 1h4v-4a1 1 0 112 0v4h4a1 1 0 001-1v-6h1a1 1 0 00.707-1.707l-7-7z" />
                    </svg>
                </x-slot:icon>

                <x-slot:actions>
                    <x-public.button variant="outline" :href="localized_route('public.home')">
                        <span class="inline-flex items-center gap-2">
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M17 10a1 1 0 01-1 1H6.414l3.293 3.293a1 1 0 01-1.414 1.414l-5-5a1 1 0 010-1.414l5-5a1 1 0 011.414 1.414L6.414 9H16a1 1 0 011 1z"
                                    clip-rule="evenodd" />
                            </svg>
                            {{ __('common.actions.back') }}
                        </span>
                    </x-public.button>
                </x-slot:actions>
            </x-public.page-header>

            <form action="{{ localized_route('public.facilities.index') }}" method="GET"
                class="mb-10 flex flex-col gap-3 rounded-xl border border-uniba-primary-blue/10 bg-uniba-surface-2/60 p-4 sm:flex-row sm:items-center sm:justify-between">
                <label for="q" class="text-xs font-semibold uppercase tracking-[0.12em] text-uniba-primary-blue">
                    {{ __('facilities.search_label') }}
                </label>
                <div class="flex w-full gap-2 sm:w-auto sm:min-w-[20rem]">
                    <input id="q" name="q" type="search" value="{{ $search ?? '' }}"
                        placeholder="{{ __('facilities.search_placeholder') }}"
                        class="w-full rounded-lg border border-uniba-border bg-white px-3 py-2 text-sm text-uniba-text placeholder:text-uniba-text-secondary focus:border-uniba-primary-blue focus:outline-none focus:ring-2 focus:ring-uniba-primary-blue/20">
                    <x-public.button type="submit" size="sm">{{ __('common.actions.search') }}</x-public.button>
                </div>
            </form>

            @if ($facilities->isEmpty())
                <x-public.empty-state class="mt-6" title="{{ __('facilities.empty_title') }}"
                    description="{{ __('facilities.empty_description') }}" />
            @else
                <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($facilities as $facility)
                        @php($facilitySlug = $facility->resolveSlug())

                        @if (!empty($facilitySlug))
                            <a href="{{ localized_route('public.facilities.show', ['slug' => $facilitySlug]) }}"
                                class="block rounded-xl focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-uniba-gold focus-visible:ring-offset-2 focus-visible:ring-offset-uniba-bg">
                                <x-public.facility.card :facility="$facility" />
                            </a>
                        @else
                            <div class="block rounded-xl">
                                <x-public.facility.card :facility="$facility" />
                            </div>
                        @endif
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $facilities->links() }}
                </div>
            @endif
        </x-public.container>
    </section>
</x-public.layout>
