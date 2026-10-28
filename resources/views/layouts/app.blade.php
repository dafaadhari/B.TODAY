<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    @include('partials.meta', [
        'title' => $title,
        'description' => $description,
        'image' => $image,
        'type' => $type,
        'robots' => $robots,
        'canonical' => $canonical,
    ])
    @stack('head')
</head>
<body class="flex min-h-screen flex-col font-sans" x-data="{ menuOpen: false }" @keydown.escape.window="menuOpen = false">
    <a href="#konten"
        class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-[60] focus:rounded-lg focus:bg-brand focus:px-4 focus:py-2.5 focus:text-sm focus:font-bold focus:text-on-brand">
        Langsung ke konten
    </a>

    <header class="sticky top-0 z-50 border-b border-line bg-glass backdrop-blur-md">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between gap-2 px-4 min-[360px]:gap-4">
            <a href="{{ route('home') }}" class="flex shrink-0 items-center gap-2 sm:gap-3" aria-label="{{ config('portal.name') }} — Beranda">
                <span class="flex size-10 items-center justify-center rounded bg-brand text-xl font-extrabold text-on-brand shadow-[0_0_15px_rgba(251,191,36,0.3)]" aria-hidden="true">B</span>
                <span class="hidden text-lg font-extrabold tracking-tight text-fg-strong min-[320px]:inline min-[360px]:text-xl sm:text-2xl" aria-hidden="true">B<span class="text-accent">.</span>TODAY</span>
            </a>

            <x-category-nav :categories="$navCategories" class="hidden xl:block" />

            <div class="flex shrink-0 items-center gap-0.5 min-[360px]:gap-1 sm:gap-2">
                <a href="{{ route('search') }}"
                    class="inline-flex size-10 items-center justify-center rounded-lg text-fg-muted transition-colors hover:bg-raised hover:text-fg-strong min-[360px]:size-11">
                    <span class="sr-only">Cari berita</span>
                    <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-6-6m2-5a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                    </svg>
                </a>
                <x-theme-toggle />
                <a href="{{ route('pasang-iklan') }}"
                    class="ml-2 hidden rounded-full bg-pill px-4 py-2 text-xs font-bold tracking-widest text-on-pill uppercase transition-colors hover:bg-brand hover:text-on-brand md:inline-block">
                    Pasang Iklan
                </a>
                <button type="button" @click="menuOpen = ! menuOpen" :aria-expanded="menuOpen.toString()" aria-controls="menu-utama"
                    class="inline-flex size-10 items-center justify-center rounded-lg text-fg-muted transition-colors hover:bg-raised hover:text-fg-strong min-[360px]:size-11 xl:hidden">
                    <span class="sr-only">Menu</span>
                    <svg x-show="! menuOpen" class="size-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-show="menuOpen" x-cloak class="size-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <x-mobile-nav :categories="$navCategories" />
    </header>

    @if ($trending?->isNotEmpty())
        <x-ticker :articles="$trending" />
    @endif

    <main id="konten" class="flex-1">
        {{ $slot }}
    </main>

    <footer class="mt-16 border-t border-line bg-footer">
        <div class="mx-auto max-w-7xl px-4 py-12">
            <div class="grid grid-cols-1 gap-10 sm:grid-cols-2 lg:grid-cols-4">
                <div>
                    <a href="{{ route('home') }}" class="text-2xl font-extrabold tracking-tight text-fg-strong">B<span class="text-accent">.</span>TODAY</a>
                    <p class="mt-3 text-sm leading-relaxed text-fg-muted">{{ config('portal.tagline') }}</p>
                </div>

                <nav aria-labelledby="footer-navigasi">
                    <h2 id="footer-navigasi" class="mb-4 text-xs font-bold tracking-widest text-fg-muted uppercase">Navigasi</h2>
                    <ul class="space-y-1 text-sm">
                        @foreach ($navCategories->take(6) as $category)
                            <li>
                                <a href="{{ route('categories.show', $category->slug) }}" class="inline-flex min-h-8 items-center text-fg-muted transition-colors hover:text-fg-strong">
                                    {{ $category->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </nav>

                <nav aria-labelledby="footer-perusahaan">
                    <h2 id="footer-perusahaan" class="mb-4 text-xs font-bold tracking-widest text-fg-muted uppercase">Perusahaan</h2>
                    <ul class="space-y-1 text-sm">
                        <li><a href="{{ route('redaksi') }}" class="inline-flex min-h-8 items-center text-fg-muted transition-colors hover:text-fg-strong">Tentang Redaksi</a></li>
                        <li><a href="{{ route('redaksi') }}#pedoman-media-siber" class="inline-flex min-h-8 items-center text-fg-muted transition-colors hover:text-fg-strong">Pedoman Media Siber</a></li>
                        <li><a href="{{ route('kirim-tulisan.create') }}" class="inline-flex min-h-8 items-center text-fg-muted transition-colors hover:text-fg-strong">Kirim Tulisan</a></li>
                        <li><a href="{{ route('pasang-iklan') }}" class="inline-flex min-h-8 items-center text-fg-muted transition-colors hover:text-fg-strong">Pasang Iklan</a></li>
                    </ul>
                </nav>

                <div>
                    <h2 class="mb-4 text-xs font-bold tracking-widest text-fg-muted uppercase">Kontak</h2>
                    <ul class="space-y-1 text-sm">
                        <li>
                            <a href="mailto:{{ config('portal.office.email_redaksi') }}" class="inline-flex min-h-8 items-center break-all text-fg-muted transition-colors hover:text-fg-strong">
                                {{ config('portal.office.email_redaksi') }}
                            </a>
                        </li>
                        <li>
                            <a href="mailto:{{ config('portal.office.email_iklan') }}" class="inline-flex min-h-8 items-center break-all text-fg-muted transition-colors hover:text-fg-strong">
                                {{ config('portal.office.email_iklan') }}
                            </a>
                        </li>
                        @if (config('portal.office.whatsapp'))
                            <li>
                                <a href="https://wa.me/{{ config('portal.office.whatsapp') }}" target="_blank" rel="noopener" class="inline-flex min-h-8 items-center text-fg-muted transition-colors hover:text-fg-strong">
                                    WhatsApp Redaksi
                                </a>
                            </li>
                        @endif
                        <li class="pt-1 leading-relaxed text-fg-muted">{{ config('portal.office.address') }}</li>
                    </ul>
                </div>
            </div>

            <div class="mt-10 border-t border-line pt-6 text-xs text-fg-muted">
                &copy; {{ now()->year }} {{ config('portal.name') }}. Hak cipta dilindungi undang-undang.
            </div>
        </div>
    </footer>
</body>
</html>
