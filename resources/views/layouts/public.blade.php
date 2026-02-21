<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @php
        $pageTitle = !empty($title)
            ? $title . ' — ' . ($tenant->meta_title ?? $tenant->name)
            : $tenant->meta_title ?? $tenant->name;

        $pageDescription =
            $description ??
            ($tenant->meta_description ??
                \Illuminate\Support\Str::limit(strip_tags((string) $tenant->description), 160));

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
    @if (!empty($tenant->meta_keywords))
        <meta name="keywords" content="{{ $tenant->meta_keywords }}">
    @endif

    <link rel="canonical" href="{{ url()->current() }}">

    @if (!empty($faviconUrl))
        <link rel="icon" href="{{ $faviconUrl }}">
        <link rel="apple-touch-icon" href="{{ $faviconUrl }}">
    @endif

    <meta property="og:site_name" content="{{ $tenant->name }}">
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
    <x-public.header :tenant="$tenant" />

    <main>
        {{ $slot }}
    </main>

    <x-public.footer :tenant="$tenant" />
</body>

</html>
