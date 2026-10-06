<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    @include('partials.meta', [
        'title' => $title ?? 'B.Today - Portal Berita Modern & Terpercaya',
        'description' => $description ?? 'Portal berita independen menyajikan berita terkini Nasional, Banten, Olahraga, Teknologi, dan Gaya Hidup.',
    ])
</head>
<body class="min-h-screen flex flex-col font-sans dark:bg-slate-950 dark:text-gray-100 bg-slate-50 text-slate-900" x-data="{ mobileMenuOpen: false }">

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('theme', {
                dark: document.documentElement.classList.contains('dark'),
                toggle() {
                    // Activate smooth transition class only during explicit user toggle
                    document.documentElement.classList.add('theme-transitioning');
                    this.dark = !this.dark;
                    if (this.dark) {
                        document.documentElement.classList.add('dark');
                        document.documentElement.classList.remove('light');
                        localStorage.setItem('theme', 'dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                        document.documentElement.classList.add('light');
                        localStorage.setItem('theme', 'light');
                    }
                    setTimeout(() => {
                        document.documentElement.classList.remove('theme-transitioning');
                    }, 350);
                }
            });
        });
    </script>

    <!-- Top Bar Ticker -->
    @if(isset($trendingArticles) && $trendingArticles->count())
        <div class="dark:bg-gray-900 bg-gray-100 border-b dark:border-gray-800 border-gray-200 text-xs py-2 px-4">
            <div class="max-w-7xl mx-auto flex items-center justify-between gap-4">
                <div class="flex items-center space-x-3 overflow-hidden mr-4">
                    <span class="bg-amber-500 text-gray-950 px-2 py-0.5 rounded font-extrabold tracking-wider uppercase shrink-0">TRENDING</span>
                    <div class="flex items-center space-x-6 overflow-hidden">
                        <div class="flex items-center space-x-6 animate-[ticker_20s_linear_infinite] whitespace-nowrap dark:text-gray-300 text-gray-700">
                        @foreach($trendingArticles as $tArticle)
                            <a href="{{ route('articles.show', $tArticle->slug) }}" class="hover:text-amber-500 shrink-0">
                                🔥 {{ $tArticle->title }}
                            </a>
                        @endforeach
                        </div>
                    </div>
                </div>
                <div class="hidden md:flex items-center space-x-4 dark:text-gray-400 text-gray-500 text-[11px] shrink-0">
                    <span>{{ now()->isoFormat('D MMMM YYYY') }}</span>
                </div>
            </div>
        </div>
    @endif

    <!-- Main Sticky Navbar -->
    <header class="sticky top-0 z-40 backdrop-blur-md dark:bg-slate-900/90 bg-white/90 border-b dark:border-gray-800/80 border-gray-200/80 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 gap-4">
                <!-- Branding Logo -->
                <a href="{{ route('home') }}" class="flex items-center space-x-2 shrink-0">
                    <span class="text-2xl font-black tracking-tight dark:text-white text-slate-900">B.<span class="text-amber-500">TODAY</span></span>
                </a>

                <!-- Desktop Nav Category Component -->
                @php
                    $categoriesNav = \App\Models\Category::with('children')
                        ->whereNull('parent_id')
                        ->orderByRaw("CASE WHEN slug = 'banten' THEN 0 ELSE id END")
                        ->get();
                @endphp
                <div class="hidden lg:block flex-1 min-w-0 mx-2">
                    <x-category-nav :categories="$categoriesNav" />
                </div>

                <!-- Right Tools (Kirim Tulisan, Search, Theme Toggle, Mobile Hamburger) -->
                <div class="flex items-center space-x-2 sm:space-x-3 shrink-0">
                    <!-- Tombol Kirim Tulisan bersanding di Right Tools dengan shrink-0 agar tidak pernah tertimpa -->
                    <a href="{{ route('kirim-tulisan.create') }}" class="hidden sm:inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold text-amber-500 bg-amber-500/10 border border-amber-500/30 hover:bg-amber-500 hover:text-gray-950 transition-colors shrink-0">
                        Kirim Tulisan
                    </a>

                    <a href="{{ route('search') }}" class="p-2 dark:text-gray-400 text-gray-600 hover:text-amber-500 transition-colors rounded-lg dark:bg-gray-800/40 bg-gray-100 border dark:border-gray-800 border-gray-200 shrink-0" title="Cari Berita">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </a>
                    <x-theme-toggle />

                    <!-- Mobile/Tablet Hamburger Button (< lg) -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="lg:hidden p-2 rounded-lg dark:bg-gray-800/40 bg-gray-100 border dark:border-gray-800 border-gray-200 dark:text-gray-300 text-gray-700 hover:text-amber-500 focus:outline-none shrink-0" title="Toggle Navigation Menu" aria-label="Menu Navigasi">
                        <svg x-cloak x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                        <svg x-cloak x-show="mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Drawer Navigation -->
        <div x-cloak 
             x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-200" 
             x-transition:enter-start="opacity-0 -translate-y-2" 
             x-transition:enter-end="opacity-100 translate-y-0" 
             x-transition:leave="transition ease-in duration-150" 
             x-transition:leave-start="opacity-100 translate-y-0" 
             x-transition:leave-end="opacity-0 -translate-y-2" 
             @click.away="mobileMenuOpen = false" 
             class="lg:hidden border-t dark:border-gray-800 border-gray-200 dark:bg-slate-900 bg-white px-4 pt-3 pb-6 space-y-2 shadow-2xl">
            <a href="{{ route('home') }}" class="block px-3 py-2 rounded-lg font-bold text-sm {{ request()->routeIs('home') ? 'bg-amber-500/10 text-amber-500' : 'dark:text-gray-200 text-gray-800 hover:text-amber-500' }}">Beranda</a>
            @foreach($categoriesNav as $cat)
                @if($cat->slug === 'banten' && $cat->children->count())
                    <div x-data="{ bantenOpen: {{ request()->is('kategori/banten*') ? 'true' : 'false' }} }" class="space-y-1">
                        <div class="flex items-center justify-between rounded-lg {{ request()->is('kategori/banten*') ? 'bg-amber-500/10' : '' }}">
                            <a href="{{ route('categories.show', $cat->slug) }}" class="flex-1 px-3 py-2 font-semibold text-sm {{ request()->is('kategori/banten*') ? 'text-amber-500 font-bold' : 'dark:text-gray-300 text-gray-700 hover:text-amber-500' }}">
                                {{ $cat->name }}
                            </a>
                            <button @click.stop="bantenOpen = !bantenOpen" type="button" class="p-2 text-amber-500 hover:bg-amber-500/10 rounded-r-lg" title="Buka Wilayah Banten" aria-label="Buka Wilayah Banten">
                                <svg class="w-4 h-4 transform transition-transform duration-200" :class="{ 'rotate-180': bantenOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                        </div>
                        <div x-show="bantenOpen" x-cloak class="pl-4 space-y-1 border-l-2 border-amber-500/40 my-1">
                            <a href="{{ route('categories.show', 'banten') }}" class="block px-3 py-1.5 text-xs font-bold text-amber-500 hover:underline">
                                Semua Berita Banten
                            </a>
                            @foreach($cat->children as $childCat)
                                <a href="{{ route('categories.show', $childCat->slug) }}" class="block px-3 py-1.5 text-xs {{ request()->is('kategori/'.$childCat->slug) ? 'text-amber-500 font-bold bg-amber-500/10 rounded' : 'text-gray-500 dark:text-gray-400 hover:text-amber-500' }}">
                                    {{ $childCat->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @else
                    <a href="{{ route('categories.show', $cat->slug) }}" class="block px-3 py-2 rounded-lg font-semibold text-sm {{ request()->is('kategori/'.$cat->slug) ? 'bg-amber-500/10 text-amber-500 font-bold' : 'dark:text-gray-300 text-gray-700 hover:text-amber-500' }}">
                        {{ $cat->name }}
                    </a>
                @endif
            @endforeach
            <div class="pt-3 border-t dark:border-gray-800 border-gray-200 flex flex-col space-y-2">
                <a href="{{ route('kirim-tulisan.create') }}" class="block w-full text-center py-2 px-4 rounded-xl bg-amber-500 text-gray-950 font-bold text-xs uppercase tracking-wider sm:hidden">
                    Kirim Tulisan Publik
                </a>
                <div class="flex items-center justify-around pt-1 text-xs">
                    <a href="{{ route('redaksi') }}" class="dark:text-gray-400 text-gray-600 hover:text-amber-500">
                        Dewan Redaksi
                    </a>
                    <span class="text-gray-400">•</span>
                    <a href="{{ route('pasang-iklan') }}" class="dark:text-gray-400 text-gray-600 hover:text-amber-500">
                        Pasang Iklan
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Content Slot -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="dark:bg-slate-900 bg-slate-900 border-t dark:border-gray-800 border-gray-800 text-gray-400 text-sm mt-12 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <span class="text-2xl font-black text-white">B.<span class="text-amber-500">TODAY</span></span>
                <p class="mt-3 text-xs leading-relaxed text-gray-400">
                    Portal berita independen &amp; terpercaya. Menyajikan jurnalisme berkualitas dengan integritas dan kecepatan tinggi.
                </p>
            </div>
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-amber-400 mb-3">Kategori Berita</h4>
                <ul class="space-y-2 text-xs">
                    <li><a href="{{ route('categories.show', 'nasional') }}" class="hover:text-white">Nasional</a></li>
                    <li><a href="{{ route('categories.show', 'banten') }}" class="hover:text-white">Banten Today</a></li>
                    <li><a href="{{ route('categories.show', 'olahraga') }}" class="hover:text-white">Olahraga</a></li>
                    <li><a href="{{ route('categories.show', 'teknologi') }}" class="hover:text-white">Teknologi</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-amber-400 mb-3">Layanan &amp; Info</h4>
                <ul class="space-y-2 text-xs">
                    <li><a href="{{ route('kirim-tulisan.create') }}" class="hover:text-white">Kirim Tulisan Publik</a></li>
                    <li><a href="{{ route('pasang-iklan') }}" class="hover:text-white">Pasang Iklan / Media Kit</a></li>
                    <li><a href="{{ route('redaksi') }}" class="hover:text-white">Pedoman Redaksi</a></li>
                    <li><a href="{{ route('login') }}" class="hover:text-white">Akses CMS Redaksi</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-amber-400 mb-3">Redaksi B.Today</h4>
                <p class="text-xs text-gray-400 leading-relaxed">
                    Jl. Raya Pandeglang-Serang No. 88, Banten.<br>
                    Email: redaksi@btoday.com<br>
                    Redaktur Pelaksana: Moch Dapa Adhari
                </p>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 pt-6 border-t border-gray-800 text-center text-xs text-gray-500">
            &copy; {{ date('Y') }} B.Today Portal Berita Modern. Hak Cipta Dilindungi Undang-Undang.
        </div>
    </footer>
</body>
</html>
