@php
    $lecturerName = $lecturer->name;
    $lecturerBiography = $lecturer->resolveLocalizedValue('biography');
    $lecturerImage = $lecturer->getFirstMediaUrl('lecturer_image', 'preview');
@endphp

<x-public.layout :tenant="$tenant" :title="$lecturerName" :description="$lecturerBiography">
    <section class="bg-uniba-surface-0 py-20 sm:py-24">
        <x-public.container class="max-w-5xl">
            <x-public.page-header :title="$lecturerName" :subtitle="__('lecturers.detail_subtitle')" :breadcrumbs="[
                ['label' => __('menu.beranda'), 'href' => localized_route('public.home')],
                ['label' => __('lecturers.title'), 'href' => localized_route('public.lecturers.index')],
                ['label' => $lecturerName],
            ]">
                <x-slot:actions>
                    <x-public.button variant="outline" :href="localized_route('public.lecturers.index')">
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

            <div
                class="mt-8 grid gap-8 rounded-2xl border border-uniba-primary-blue/10 bg-uniba-surface-1 p-6 sm:p-8 lg:grid-cols-[240px,1fr]">
                <div>
                    @if (!empty($lecturerImage))
                        <img src="{{ $lecturerImage }}" alt="{{ $lecturerName }}" width="480" height="480"
                            loading="eager" fetchpriority="high" decoding="async"
                            class="aspect-square w-full rounded-xl border border-uniba-border object-cover">
                    @else
                        <x-public.image-placeholder class="aspect-square rounded-xl border border-uniba-border"
                            :show-label="false" iconClass="h-10 w-10" />
                    @endif
                </div>

                <div class="min-w-0">
                    <h2 class="text-2xl font-light tracking-wide text-uniba-deep-blue">{{ $lecturerName }}</h2>

                    <dl class="mt-4 grid gap-3 text-sm text-uniba-text-secondary sm:grid-cols-2">
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-[0.1em] text-uniba-primary-blue">
                                {{ __('lecturers.nidn_label') }}
                            </dt>
                            <dd class="mt-1 text-uniba-text">{{ $lecturer->nidn }}</dd>
                        </div>

                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-[0.1em] text-uniba-primary-blue">
                                {{ __('lecturers.email_label') }}
                            </dt>
                            <dd class="mt-1">
                                <a href="mailto:{{ $lecturer->email }}"
                                    class="text-uniba-primary-blue underline-offset-2 hover:text-uniba-deep-blue hover:underline">
                                    {{ $lecturer->email }}
                                </a>
                            </dd>
                        </div>

                        @if (!empty($lecturer->phone))
                            <div>
                                <dt class="text-xs font-semibold uppercase tracking-[0.1em] text-uniba-primary-blue">
                                    {{ __('lecturers.phone_label') }}
                                </dt>
                                <dd class="mt-1">
                                    <a href="tel:{{ $lecturer->phone }}"
                                        class="text-uniba-text hover:text-uniba-deep-blue hover:underline">
                                        {{ $lecturer->phone }}
                                    </a>
                                </dd>
                            </div>
                        @endif
                    </dl>

                    <div class="mt-8 border-t border-uniba-primary-blue/10 pt-6">
                        <h3 class="text-lg font-light tracking-wide text-uniba-deep-blue">
                            {{ __('lecturers.biography_title') }}</h3>
                        <div class="mt-3 text-sm leading-relaxed text-uniba-text-secondary">
                            {{ $lecturerBiography ?: __('lecturers.biography_empty') }}
                        </div>
                    </div>
                </div>
            </div>
        </x-public.container>
    </section>

    @if (($relatedLecturers ?? collect())->isNotEmpty())
        <section class="bg-uniba-surface-1 py-20">
            <x-public.container class="max-w-6xl">
                <x-public.section-heading :title="__('lecturers.related_title')" :subtitle="__('lecturers.related_subtitle')" />

                <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($relatedLecturers as $relatedLecturer)
                        <x-public.lecturer.card :lecturer="$relatedLecturer" :href="!empty($relatedLecturer->slug)
                            ? localized_route('public.lecturers.show', ['slug' => $relatedLecturer->slug])
                            : null" />
                    @endforeach
                </div>
            </x-public.container>
        </section>
    @endif
</x-public.layout>
