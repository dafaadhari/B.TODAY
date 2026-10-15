@props([
    'title' => 'B.Today - Portal Berita Modern & Terpercaya',
    'description' => 'Portal berita independen menyajikan berita terkini Nasional, Banten, Olahraga, Teknologi, dan Gaya Hidup.',
    'image' => asset('images/og-default.jpg'),
    'type' => 'website',
])

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- Synchronous Theme Setup (Prevents FOUC / dual-mode flash on page navigation) -->
<script>
    (function() {
        try {
            var isAdmin = window.location.pathname.startsWith('/admin');
            if (isAdmin) {
                document.documentElement.classList.add('dark');
                document.documentElement.classList.remove('light');
            } else {
                var stored = localStorage.getItem('theme');
                if (stored === 'light') {
                    document.documentElement.classList.remove('dark');
                    document.documentElement.classList.add('light');
                } else {
                    document.documentElement.classList.add('dark');
                    document.documentElement.classList.remove('light');
                }
            }
        } catch (e) {
            document.documentElement.classList.add('dark');
        }
    })();
</script>

<title>{{ $title }}</title>
<meta name="description" content="{{ $description }}">

<!-- OpenGraph / Facebook -->
<meta property="og:type" content="{{ $type }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:image" content="{{ $image }}">

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:url" content="{{ url()->current() }}">
<meta name="twitter:title" content="{{ $title }}">
<meta name="twitter:description" content="{{ $description }}">
<meta name="twitter:image" content="{{ $image }}">

<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">

<!-- Tailwind CSS & Alpine.js -->
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        darkMode: 'class',
        theme: {
            extend: {
                colors: {
                    brand: {
                        50: '#fff7ed',
                        500: '#f97316',
                        600: '#ea580c',
                    }
                },
                keyframes: {
                    ticker: {
                        '0%': { transform: 'translateX(0)' },
                        '100%': { transform: 'translateX(-50%)' },
                    }
                },
                animation: {
                    ticker: 'ticker 20s linear infinite',
                }
            }
        }
    }
</script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<style>
    [x-cloak] {
        display: none !important;
    }

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .glass-nav {
        background: rgba(15, 23, 42, 0.75);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
    }
    .light .glass-nav {
        background: rgba(255, 255, 255, 0.85);
    }

    /* Smooth color transition ONLY when toggling theme, not during page load or route navigation */
    .theme-transitioning,
    .theme-transitioning *,
    .theme-transitioning *::before,
    .theme-transitioning *::after {
        transition: background-color 300ms cubic-bezier(0.4, 0, 0.2, 1),
                    border-color 300ms cubic-bezier(0.4, 0, 0.2, 1),
                    color 300ms cubic-bezier(0.4, 0, 0.2, 1),
                    fill 300ms cubic-bezier(0.4, 0, 0.2, 1),
                    stroke 300ms cubic-bezier(0.4, 0, 0.2, 1),
                    box-shadow 300ms cubic-bezier(0.4, 0, 0.2, 1) !important;
        transition-delay: 0s !important;
    }
</style>
