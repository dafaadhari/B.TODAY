@php
    $siteName = config('portal.name');
    $metaTitle = $title ?? $siteName.' - Berita Terkini Banten & Nasional';
    $metaDescription = $description ?? 'Portal berita independen menyajikan berita terkini Nasional, Banten, Olahraga, Teknologi, dan Gaya Hidup.';
    $metaImage = $image ?? asset('images/og-default.png');
    $metaUrl = $canonical ?? url()->current();
@endphp

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="color-scheme" content="dark light">
<meta name="theme-color" content="#050505">

{{-- Tema diterapkan sebelum halaman dirender supaya tidak ada kedipan tema salah (PRD 9.4). --}}
<script>
    (function () {
        var dark = true;
        try {
            dark = localStorage.getItem('theme') !== 'light';
        } catch (error) {}
        var root = document.documentElement;
        root.classList.toggle('dark', dark);
        root.classList.toggle('light', !dark);
        if (!dark) {
            document.querySelector('meta[name="theme-color"]').setAttribute('content', '#fafaf9');
        }
    })();
</script>

<title>{{ $metaTitle }}</title>
<meta name="description" content="{{ $metaDescription }}">
@if (! empty($robots))
    <meta name="robots" content="{{ $robots }}">
@endif
<link rel="canonical" href="{{ $metaUrl }}">

<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:locale" content="id_ID">
<meta property="og:type" content="{{ $type ?? 'website' }}">
<meta property="og:url" content="{{ $metaUrl }}">
<meta property="og:title" content="{{ $metaTitle }}">
<meta property="og:description" content="{{ $metaDescription }}">
<meta property="og:image" content="{{ $metaImage }}">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $metaTitle }}">
<meta name="twitter:description" content="{{ $metaDescription }}">
<meta name="twitter:image" content="{{ $metaImage }}">

<script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'NewsMediaOrganization',
        'name' => $siteName,
        'url' => url('/'),
        'logo' => asset('images/og-default.png'),
        'email' => config('portal.office.email_redaksi'),
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}
</script>

@fonts
@vite(['resources/css/app.css', 'resources/js/app.js'])
