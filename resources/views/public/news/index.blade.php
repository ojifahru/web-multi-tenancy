<x-public.layout :tenant="$tenant" :title="__('news.title')">
    <section class="bg-uniba-surface-1 py-20 sm:py-24">
        <x-public.container class="max-w-6xl">
            <x-public.page-header :title="__('news.title')" :subtitle="__('news.subtitle')" :breadcrumbs="[['label' => __('menu.beranda'), 'href' => localized_route('public.home')], ['label' => __('news.title')]]">
                <x-slot:icon>
                    <svg class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V7.414A2 2 0 0017.414 6L15 3.586A2 2 0 0013.586 3H4zm1 4a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm0 4a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100-2h4a1 1 0 100 2H7z"
                            clip-rule="evenodd" />
                    </svg>
                </x-slot:icon>
            </x-public.page-header>

            <form action="{{ localized_route('public.news.index') }}" method="GET"
                class="mb-10 flex flex-col gap-3 rounded-xl border border-uniba-border bg-white p-4 sm:flex-row sm:items-center sm:justify-between">
                <label for="q" class="text-xs font-semibold uppercase tracking-[0.12em] text-uniba-primary-blue">
                    {{ __('news.search_label') }}
                </label>
                <div class="flex w-full gap-2 sm:w-auto sm:min-w-[20rem]">
                    <input id="q" name="q" type="search" value="{{ $search ?? '' }}"
                        placeholder="{{ __('news.search_placeholder') }}"
                        class="w-full rounded-lg border border-uniba-border bg-white px-3 py-2 text-sm text-uniba-text placeholder:text-uniba-text-secondary focus:border-uniba-primary-blue focus:outline-none focus:ring-2 focus:ring-uniba-primary-blue/20">
                    <x-public.button type="submit" size="sm">{{ __('common.actions.search') }}</x-public.button>
                </div>
            </form>

            @if ($news->isEmpty())
                <x-public.empty-state title="{{ __('news.empty_title') }}"
                    description="{{ __('news.empty_description') }}" />
            @else
                <div class="grid gap-8 md:grid-cols-3">
                    @foreach ($news as $item)
                        <x-public.news.card :news="$item" />
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $news->links() }}
                </div>
            @endif
        </x-public.container>
    </section>
</x-public.layout>
