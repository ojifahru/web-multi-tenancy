<x-public.layout :tenant="$tenant" :title="__('lecturers.title')">
    <section class="bg-uniba-surface-1 py-20 sm:py-24">
        <x-public.container class="max-w-6xl">
            <x-public.page-header :title="__('lecturers.title')" :subtitle="__('lecturers.subtitle')" :breadcrumbs="[['label' => __('menu.beranda'), 'href' => localized_route('public.home')], ['label' => __('lecturers.title')]]">
                <x-slot:icon>
                    <svg class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path
                            d="M10 2a1 1 0 01.447.105l8 4a1 1 0 010 1.788l-8 4a1 1 0 01-.894 0l-8-4a1 1 0 010-1.788l8-4A1 1 0 0110 2z" />
                        <path
                            d="M2 9.5V14a1 1 0 00.553.894l7 3.5a1 1 0 00.894 0l7-3.5A1 1 0 0018 14V9.5l-7.553 3.776a2 2 0 01-1.894 0L2 9.5z" />
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

            <form action="{{ localized_route('public.lecturers.index') }}" method="GET"
                class="mb-10 flex flex-col gap-3 rounded-xl border border-uniba-border bg-white p-4 sm:flex-row sm:items-center sm:justify-between">
                <label for="q" class="text-xs font-semibold uppercase tracking-[0.12em] text-uniba-primary-blue">
                    {{ __('lecturers.search_label') }}
                </label>
                <div class="flex w-full gap-2 sm:w-auto sm:min-w-[20rem]">
                    <input id="q" name="q" type="search" value="{{ $search ?? '' }}"
                        placeholder="{{ __('lecturers.search_placeholder') }}"
                        class="w-full rounded-lg border border-uniba-border bg-white px-3 py-2 text-sm text-uniba-text placeholder:text-uniba-text-secondary focus:border-uniba-primary-blue focus:outline-none focus:ring-2 focus:ring-uniba-primary-blue/20">
                    <x-public.button type="submit" size="sm">{{ __('common.actions.search') }}</x-public.button>
                </div>
            </form>

            @if ($lecturers->isEmpty())
                <x-public.empty-state class="mt-6" title="{{ __('lecturers.empty_title') }}"
                    description="{{ __('lecturers.empty_description') }}" />
            @else
                <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($lecturers as $lecturer)
                        <x-public.lecturer.card :lecturer="$lecturer" />
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $lecturers->links() }}
                </div>
            @endif
        </x-public.container>
    </section>
</x-public.layout>
