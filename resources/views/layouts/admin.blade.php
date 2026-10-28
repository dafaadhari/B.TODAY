@php
    $adminUser = Auth::user();
    $adminName = $adminUser->name ?? 'Redaksi';
    $adminRole = Str::headline($adminUser->role ?? 'super_admin');
    $adminInitials = $adminUser?->initials ?? 'R';

    $pendingCount = \App\Models\GuestSubmission::where('status', 'pending')->count();
    $navItems = [
        ['route' => 'admin.dashboard', 'active' => 'admin.dashboard', 'icon' => 'squares-2x2', 'label' => 'Dashboard'],
        ['route' => 'admin.articles.index', 'active' => 'admin.articles.*', 'icon' => 'newspaper', 'label' => 'Manajemen Artikel'],
        ['route' => 'admin.submissions.index', 'active' => 'admin.submissions.*', 'icon' => 'inbox-arrow-down', 'label' => 'Kiriman Publik', 'badge' => $pendingCount],
        ['route' => 'admin.ads.index', 'active' => 'admin.ads.*', 'icon' => 'megaphone', 'label' => 'Slot Iklan'],
        ['route' => 'admin.categories.index', 'active' => 'admin.categories.*', 'icon' => 'folder', 'label' => 'Kategori'],
    ];
@endphp
<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    @include('partials.admin-head', ['title' => $title ?? 'CMS Redaksi Panel - B.Today'])

    {{-- Terapkan status sidebar ciut sebelum render agar tidak berkedip saat pindah halaman. --}}
    <script>
        try {
            if (localStorage.getItem('admin-sidebar') === 'collapsed') {
                document.documentElement.classList.add('sidebar-collapsed');
            }
        } catch (error) {}
    </script>
    <style>
        /*
         * Ciut/buka sidebar desktop: hanya lebar yang dianimasikan. Label tidak pernah
         * terlipat (nowrap + dipotong sidebar) dan ikon tidak berpindah, jadi item menu
         * tidak naik-turun. Label memudar: muncul setelah sidebar melebar, hilang lebih dulu
         * saat diciutkan; opacity 0 tetap terbaca pembaca layar.
         */
        @media (min-width: 1024px) {
            aside.admin-sidebar { overflow-x: hidden; transition: width 250ms cubic-bezier(0.4, 0, 0.2, 1); }
            .sidebar-collapsed .admin-sidebar { width: 4.5rem; }

            .admin-nav-label, .admin-nav-badge { transition: opacity 150ms ease 100ms; }
            .sidebar-collapsed .admin-nav-label,
            .sidebar-collapsed .admin-nav-badge { opacity: 0; transition-duration: 100ms; transition-delay: 0ms; }

            .admin-nav-dot { opacity: 0; transition: opacity 150ms ease; }
            .sidebar-collapsed .admin-nav-dot { opacity: 1; transition-delay: 150ms; }

            .admin-nav-heading::after {
                content: ''; position: absolute; left: 0.75rem; top: calc(50% - 0.25rem);
                width: 1.5rem; height: 1px; background: rgb(55 65 81); opacity: 0; transition: opacity 150ms ease;
            }
            .sidebar-collapsed .admin-nav-heading::after { opacity: 1; transition-delay: 100ms; }
        }

        /* Bilah hitung mundur sapaan setelah login; durasinya sama dengan timer 5 detik di Alpine. */
        @keyframes admin-toast-countdown { from { transform: scaleX(1); } to { transform: scaleX(0); } }
        .admin-toast-countdown { transform-origin: left; animation: admin-toast-countdown 5s linear forwards; }

        @media (prefers-reduced-motion: reduce) {
            aside.admin-sidebar, .admin-nav-label, .admin-nav-badge, .admin-nav-dot, .admin-nav-heading::after { transition: none !important; }
        }
    </style>
</head>
<body class="bg-gray-950 text-gray-100 min-h-screen flex flex-col font-sans"
    x-data="{
        mobileNavOpen: false,
        sidebarCollapsed: document.documentElement.classList.contains('sidebar-collapsed'),
        isDesktop() {
            return window.matchMedia('(min-width: 1024px)').matches;
        },
        toggleNavigation() {
            if (! this.isDesktop()) {
                this.mobileNavOpen = ! this.mobileNavOpen;

                return;
            }

            this.sidebarCollapsed = ! this.sidebarCollapsed;
            document.documentElement.classList.toggle('sidebar-collapsed', this.sidebarCollapsed);
            try { localStorage.setItem('admin-sidebar', this.sidebarCollapsed ? 'collapsed' : 'expanded'); } catch (error) {}
        },
        navigationExpanded() {
            return this.isDesktop() ? ! this.sidebarCollapsed : this.mobileNavOpen;
        },
    }"
    @keydown.escape.window="mobileNavOpen = false">

    <!-- Header admin: hamburger + logo di kiri, tautan situs + menu akun di kanan -->
    <header class="sticky top-0 z-50 h-16 bg-gray-900/95 backdrop-blur border-b border-gray-800">
        <div class="h-full flex items-center justify-between gap-3 px-3 sm:px-4">
            <div class="flex items-center gap-2 sm:gap-3 min-w-0">
                <button type="button" @click="toggleNavigation()" :aria-expanded="navigationExpanded().toString()" aria-controls="admin-sidebar"
                    :title="mobileNavOpen ? 'Tutup menu' : 'Buka/tutup menu'"
                    class="inline-flex w-10 h-10 shrink-0 items-center justify-center rounded-lg text-gray-300 hover:bg-gray-800 hover:text-white transition-colors">
                    <span x-show="! mobileNavOpen"><x-heroicon-o-bars-3 class="w-6 h-6" aria-hidden="true" /></span>
                    <span x-show="mobileNavOpen" x-cloak><x-heroicon-o-x-mark class="w-6 h-6" aria-hidden="true" /></span>
                    <span class="sr-only">Buka atau tutup menu</span>
                </button>

                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 min-w-0">
                    <span class="flex w-8 h-8 shrink-0 items-center justify-center rounded bg-amber-400 text-base font-extrabold text-black" aria-hidden="true">B</span>
                    <span class="text-lg font-extrabold tracking-tight text-white whitespace-nowrap">B<span class="text-amber-400">.</span>TODAY</span>
                    <span class="hidden sm:inline-block rounded border border-gray-700 px-1.5 py-0.5 text-[10px] font-bold uppercase tracking-widest text-gray-400">CMS</span>
                </a>
            </div>

            <div class="flex items-center gap-1 sm:gap-2 shrink-0">
                <a href="{{ route('home') }}" target="_blank" rel="noopener" title="Lihat situs publik"
                    class="inline-flex h-10 items-center gap-2 rounded-lg px-2.5 text-sm font-semibold text-gray-300 hover:bg-gray-800 hover:text-white transition-colors">
                    <x-heroicon-o-arrow-top-right-on-square class="w-5 h-5" aria-hidden="true" />
                    <span class="hidden md:inline">Lihat Situs</span>
                </a>

                <span class="hidden sm:block w-px h-6 bg-gray-800" aria-hidden="true"></span>

                <!-- Menu akun -->
                <div class="relative" x-data="{ open: false }" @click.outside="open = false" @keydown.escape.stop="open = false">
                    <button type="button" @click="open = ! open" :aria-expanded="open.toString()" aria-controls="menu-akun"
                        class="flex h-10 items-center gap-2.5 rounded-lg pl-1 pr-1 sm:pr-2 hover:bg-gray-800 transition-colors">
                        <span class="flex w-8 h-8 items-center justify-center rounded-full bg-gradient-to-br from-amber-400 to-amber-600 text-xs font-bold text-black" aria-hidden="true">{{ $adminInitials }}</span>
                        <span class="hidden sm:block text-left leading-tight">
                            <span class="block text-sm font-bold text-gray-100">{{ $adminName }}</span>
                            <span class="block text-[11px] text-gray-400">{{ $adminRole }}</span>
                        </span>
                        <span class="hidden sm:block transition-transform" :class="open && 'rotate-180'">
                            <x-heroicon-o-chevron-down class="w-4 h-4 text-gray-400" aria-hidden="true" />
                        </span>
                        <span class="sr-only">Menu akun {{ $adminName }}</span>
                    </button>

                    <div id="menu-akun" x-show="open" x-cloak x-transition.origin.top.right
                        class="absolute right-0 top-full mt-2 w-60 rounded-xl border border-gray-800 bg-gray-900 py-2 shadow-2xl">
                        <div class="px-4 py-2 border-b border-gray-800">
                            <p class="text-sm font-bold text-gray-100 truncate">{{ $adminName }}</p>
                            <p class="text-xs text-gray-400 truncate">{{ $adminUser->email ?? '' }}</p>
                        </div>
                        <a href="{{ route('admin.profile.edit') }}" @if (request()->routeIs('admin.profile.*')) aria-current="page" @endif
                            class="mt-1 flex items-center gap-3 px-4 py-2.5 text-sm text-gray-300 hover:bg-gray-800 hover:text-white">
                            <x-heroicon-o-user-circle class="w-5 h-5 text-gray-400" aria-hidden="true" />
                            Profil Saya
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="border-t border-gray-800 mt-1 pt-1">
                            @csrf
                            <button type="submit" class="flex w-full items-center gap-3 px-4 py-2.5 text-sm text-rose-400 hover:bg-rose-500/10">
                                <x-heroicon-o-arrow-right-start-on-rectangle class="w-5 h-5" aria-hidden="true" />
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    @if (session('welcome'))
        @php
            $welcome = session('welcome');
            $welcomeItems = array_values(array_filter([
                $welcome['pending_reviews'] > 0
                    ? ['url' => route('admin.articles.index', ['status' => 'pending_review']), 'label' => "{$welcome['pending_reviews']} artikel menunggu review"]
                    : null,
                $welcome['pending_submissions'] > 0
                    ? ['url' => route('admin.submissions.index'), 'label' => "{$welcome['pending_submissions']} kiriman publik belum ditinjau"]
                    : null,
            ]));
        @endphp
        <!-- Sapaan setelah login: melayang di bawah (tidak menggeser konten atau menutupi aksi di atas), hilang sendiri setelah 5 detik, tertahan saat disentuh -->
        <div x-data="{
                visible: false,
                paused: false,
                remaining: 5000,
                startedAt: 0,
                timer: null,
                init() {
                    this.$nextTick(() => { this.visible = true; this.run(); });
                },
                run() {
                    this.startedAt = Date.now();
                    this.timer = setTimeout(() => this.visible = false, Math.max(this.remaining, 0));
                },
                pause() {
                    if (this.paused) { return; }
                    this.paused = true;
                    clearTimeout(this.timer);
                    this.remaining -= Date.now() - this.startedAt;
                },
                resume() {
                    if (! this.paused) { return; }
                    this.paused = false;
                    this.run();
                },
                dismiss() {
                    clearTimeout(this.timer);
                    this.visible = false;
                },
            }"
            x-show="visible" x-cloak
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            @mouseenter="pause()" @mouseleave="resume()" @focusin="pause()" @focusout="resume()"
            role="status" aria-live="polite"
            class="fixed inset-x-4 bottom-4 z-[60] overflow-hidden rounded-xl border border-amber-500/30 bg-gray-900 shadow-2xl sm:inset-x-auto sm:bottom-6 sm:right-6 sm:w-96">
            <div class="flex items-start gap-3 p-4">
                <span class="flex w-9 h-9 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-amber-400 to-amber-600 text-xs font-bold text-black" aria-hidden="true">{{ $adminInitials }}</span>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-bold text-white">Selamat datang kembali, {{ $adminName }}</p>
                    @if ($welcomeItems === [])
                        <p class="mt-0.5 text-xs text-gray-400">Tidak ada artikel atau kiriman yang menunggu ditinjau.</p>
                    @else
                        <ul class="mt-1 space-y-0.5 text-xs">
                            @foreach ($welcomeItems as $welcomeItem)
                                <li><a href="{{ $welcomeItem['url'] }}" class="text-amber-400 hover:underline">{{ $welcomeItem['label'] }}</a></li>
                            @endforeach
                        </ul>
                    @endif
                </div>
                <button type="button" @click="dismiss()" class="relative -m-1 inline-flex w-8 h-8 shrink-0 items-center justify-center rounded-lg text-gray-400 transition-colors hover:bg-gray-800 hover:text-white">
                    <x-heroicon-o-x-mark class="w-4 h-4" aria-hidden="true" />
                    <span class="sr-only">Tutup sapaan</span>
                </button>
            </div>
            <div class="admin-toast-countdown h-0.5 bg-amber-400 motion-reduce:hidden" :style="{ animationPlayState: paused ? 'paused' : 'running' }" aria-hidden="true"></div>
        </div>
    @endif

    <!-- Latar gelap di belakang menu (HP/tablet) -->
    <div x-show="mobileNavOpen" x-cloak x-transition.opacity @click="mobileNavOpen = false" class="fixed inset-x-0 top-16 bottom-0 z-40 bg-black/60 lg:hidden" aria-hidden="true"></div>

    <div class="flex flex-1">
        <!-- Sidebar: panel geser di HP/tablet, bisa diciutkan jadi ikon di desktop lewat tombol hamburger -->
        <aside id="admin-sidebar" aria-label="Menu admin"
            class="admin-sidebar fixed top-16 bottom-0 left-0 z-40 w-72 -translate-x-full invisible overflow-y-auto bg-gray-950 border-r border-gray-800 px-3 py-4
                   transition-[transform,visibility] duration-200 ease-out motion-reduce:transition-none
                   lg:visible lg:translate-x-0 lg:sticky lg:top-16 lg:bottom-auto lg:z-auto lg:h-[calc(100vh-4rem)] lg:w-64 lg:shrink-0"
            :class="mobileNavOpen && '!translate-x-0 !visible'">
            <div class="admin-nav-heading relative">
                <p class="admin-nav-label whitespace-nowrap pl-3.5 pb-2 text-[11px] font-bold uppercase tracking-widest text-gray-500">Menu</p>
            </div>
            <nav class="space-y-1">
                @foreach ($navItems as $item)
                    @php $isActive = request()->routeIs($item['active']); @endphp
                    <a href="{{ route($item['route']) }}" title="{{ $item['label'] }}" @if ($isActive) aria-current="page" @endif
                        class="admin-nav-link relative flex items-center gap-3 pl-3.5 pr-4 py-2.5 rounded-lg text-sm font-semibold transition-colors {{ $isActive ? 'bg-amber-500 text-gray-950' : 'text-gray-300 hover:bg-gray-900 hover:text-white' }}">
                        <x-dynamic-component :component="'heroicon-o-'.$item['icon']" class="w-5 h-5 shrink-0" aria-hidden="true" />
                        <span class="admin-nav-label whitespace-nowrap">{{ $item['label'] }}</span>
                        @if (! empty($item['badge']))
                            <span class="admin-nav-badge ml-auto bg-amber-400 text-gray-950 text-[10px] font-black px-2 py-0.5 rounded-full">
                                {{ $item['badge'] }}<span class="sr-only"> menunggu</span>
                            </span>
                            {{-- Penanda di pojok ikon saat sidebar diciutkan. --}}
                            <span class="admin-nav-dot absolute left-7 top-2 hidden h-2 w-2 rounded-full bg-amber-400 ring-2 ring-gray-950 lg:block" aria-hidden="true"></span>
                        @endif
                    </a>
                @endforeach
            </nav>
        </aside>

        <div class="flex-1 min-w-0">
            <main class="mx-auto w-full max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
                @if(session('success'))
                    <div role="status" class="p-4 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 rounded-xl text-xs font-semibold flex items-center gap-2 shadow">
                        <x-heroicon-o-check-circle class="w-5 h-5 shrink-0" aria-hidden="true" />
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div role="alert" class="p-4 bg-rose-500/10 border border-rose-500/30 text-rose-400 rounded-xl text-xs font-semibold flex items-center gap-2 shadow">
                        <x-heroicon-o-exclamation-triangle class="w-5 h-5 shrink-0" aria-hidden="true" />
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
