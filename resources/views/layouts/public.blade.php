<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @php
        $tenantName = $tenant->resolveLocalizedValue('name') ?? __('common.site.university_short');
        $tenantMetaTitle = $tenant->resolveLocalizedValue('meta_title');
        $tenantMetaDescription = $tenant->resolveLocalizedValue('meta_description');
        $tenantMetaKeywords = $tenant->resolveLocalizedValue('meta_keywords');

        $pageTitle = !empty($title)
            ? __('common.meta.title_format', ['page' => $title, 'tenant' => $tenantName])
            : $tenantMetaTitle ?? __('common.meta.title_simple', ['tenant' => $tenantName]);

        $pageDescription =
            $description ?? ($tenantMetaDescription ?? __('common.meta.description', ['tenant' => $tenantName]));

        $pageImage =
            $image ?? $tenant->getFirstMediaUrl('study_program_banner') ?:
            (!empty($tenant->banner_path)
                ? (str_starts_with($tenant->banner_path, ['http://', 'https://', '/'])
                    ? $tenant->banner_path
                    : asset('storage/' . ltrim($tenant->banner_path, '/')))
                : null);

        $faviconUrl = $tenant->getFirstMediaUrl('study_program_favicon');
    @endphp

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $pageTitle }}</title>

    <meta name="description" content="{{ $pageDescription }}">
    @if (!empty($tenantMetaKeywords))
        <meta name="keywords" content="{{ $tenantMetaKeywords }}">
    @endif

    <link rel="canonical" href="{{ url()->current() }}">

    @if (!empty($faviconUrl))
        <link rel="icon" href="{{ $faviconUrl }}">
        <link rel="apple-touch-icon" href="{{ $faviconUrl }}">
    @endif

    <meta property="og:site_name" content="{{ $tenantName }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    @if (!empty($pageImage))
        <meta property="og:image" content="{{ $pageImage }}">
    @endif

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $pageDescription }}">
    @if (!empty($pageImage))
        <meta name="twitter:image" content="{{ $pageImage }}">
    @endif

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-uniba-surface-1 text-uniba-text antialiased">
    <a href="#main-content"
        class="sr-only focus:not-sr-only focus:absolute focus:left-6 focus:top-4 focus:z-50 focus:rounded-md focus:bg-uniba-surface-0 focus:px-3 focus:py-2 focus:text-sm focus:font-semibold focus:text-uniba-primary-blue focus:shadow">
        {{ __('common.actions.skip_to_content') }}
    </a>
    <x-public.header :tenant="$tenant" />

    <main id="main-content" class="focus:outline-none">
        {{ $slot }}
    </main>

    <x-public.footer :tenant="$tenant" />
</body>

</html>
