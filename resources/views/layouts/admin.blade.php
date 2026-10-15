<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    @include('partials.admin-head', ['title' => $title ?? 'CMS Redaksi Panel - B.Today'])
</head>
<body class="bg-gray-950 text-gray-100 min-h-screen flex flex-col font-sans">
    <!-- Admin Header -->
    <header class="bg-gray-900 border-b border-gray-800 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center space-x-6">
                <a href="{{ route('admin.dashboard') }}" class="text-xl font-black text-white">
                    B.<span class="text-amber-500">TODAY</span> <span class="text-xs bg-amber-500/10 text-amber-400 border border-amber-500/30 px-2 py-0.5 rounded font-mono ml-2">CMS Admin</span>
                </a>
                <!-- Shortcut "Lihat Portal Publik" -->
                <a href="{{ route('home') }}" target="_blank" class="inline-flex items-center space-x-1 text-xs text-gray-400 hover:text-amber-400 bg-gray-800/60 hover:bg-gray-800 px-3 py-1.5 rounded-lg border border-gray-700/60 transition-colors">
                    <span>🌐 Lihat Portal Publik</span>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                </a>
            </div>

            <div class="flex items-center space-x-4">
                <div class="text-right text-xs">
                    <span class="block font-bold text-gray-200">{{ Auth::user()->name ?? 'Super Admin' }}</span>
                    <span class="block text-[10px] text-amber-400 uppercase font-mono">{{ Auth::user()->role ?? 'super_admin' }}</span>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-3 py-1.5 bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/30 text-xs font-bold rounded-lg transition-colors">
                        🚪 Logout
                    </button>
                </form>
            </div>
        </div>
    </header>

    <div class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 grid grid-cols-1 lg:grid-cols-5 gap-8">
        <!-- Sidebar Navigation -->
        <aside class="lg:col-span-1 space-y-2">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-bold {{ request()->routeIs('admin.dashboard') ? 'bg-amber-500 text-gray-950 shadow-lg' : 'text-gray-300 hover:bg-gray-900' }}">
                <span>📊 Dashboard</span>
            </a>
            <a href="{{ route('admin.articles.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-bold {{ request()->routeIs('admin.articles.*') ? 'bg-amber-500 text-gray-950 shadow-lg' : 'text-gray-300 hover:bg-gray-900' }}">
                <span>📰 Manajemen Artikel</span>
            </a>
            <a href="{{ route('admin.submissions.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-bold relative {{ request()->routeIs('admin.submissions.*') ? 'bg-amber-500 text-gray-950 shadow-lg' : 'text-gray-300 hover:bg-gray-900' }}">
                <span>Kiriman Publik</span>
                @php $pendingCount = \App\Models\GuestSubmission::where('status', 'pending')->count(); @endphp
                @if($pendingCount > 0)
                    <span class="ml-auto bg-amber-400 text-gray-950 text-[10px] font-black px-2 py-0.5 rounded-full">{{ $pendingCount }}</span>
                @endif
            </a>
            <a href="{{ route('admin.ads.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-bold {{ request()->routeIs('admin.ads.*') ? 'bg-amber-500 text-gray-950 shadow-lg' : 'text-gray-300 hover:bg-gray-900' }}">
                <span>📢 Slot Iklan</span>
            </a>
            <a href="{{ route('admin.categories.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-bold {{ request()->routeIs('admin.categories.*') ? 'bg-amber-500 text-gray-950 shadow-lg' : 'text-gray-300 hover:bg-gray-900' }}">
                <span>📁 Kategori</span>
            </a>
        </aside>

        <!-- Main Content Area -->
        <main class="lg:col-span-4 space-y-6">
            <!-- Contextual Banner Notification -->
            @if(session('success'))
                <div class="p-4 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 rounded-xl text-xs font-semibold flex items-center justify-between shadow">
                    <span>✅ {{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-rose-500/10 border border-rose-500/30 text-rose-400 rounded-xl text-xs font-semibold flex items-center justify-between shadow">
                    <span>⚠️ {{ session('error') }}</span>
                </div>
            @endif

            {{ $slot }}
        </main>
    </div>
</body>
</html>
