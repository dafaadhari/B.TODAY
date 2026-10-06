<x-admin-layout title="Dashboard Redaksi - B.Today">
    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-amber-500/20 via-gray-900 to-gray-900 border border-amber-500/30 p-6 rounded-2xl flex items-center justify-between shadow-xl">
        <div>
            <span class="text-xs font-bold uppercase tracking-widest text-amber-400">CMS Portal Berita</span>
            <h1 class="text-2xl font-black text-white mt-1">Selamat Datang Kembali, {{ Auth::user()->name ?? 'Redaksi' }}! 👋</h1>
            <p class="text-xs text-gray-400 mt-1">Sistem manajemen konten redaksi B.Today berjalan optimal.</p>
        </div>
        <a href="{{ route('admin.articles.create') }}" class="py-2.5 px-5 bg-amber-500 hover:bg-amber-600 text-gray-950 font-black text-xs uppercase tracking-wider rounded-xl transition-all shadow-lg hidden sm:inline-block">
            + Tambah Berita Baru
        </a>
    </div>

    <!-- Summary Stats Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <x-stat-card title="Total Artikel" :value="$stats['total_articles']" icon="📰" color="amber" />
        <x-stat-card title="Artikel Terbit" :value="$stats['published_articles']" icon="✅" color="emerald" />
        <x-stat-card title="Draf Redaksi" :value="$stats['draft_articles']" icon="📝" color="blue" />
        <x-stat-card title="Menunggu Review Admin" :value="$stats['pending_reviews']" icon="⏳" color="amber" />
        <x-stat-card title="Kiriman Publik Pending" :value="$stats['pending_submissions']" icon="📥" color="rose" />
        <x-stat-card title="Iklan Aktif" :value="$stats['active_ads']" icon="📢" color="emerald" />
    </div>

    <!-- Recent Tables Split -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Pending Submissions -->
        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6 shadow-xl">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-white">Antrean Kiriman Publik Terbaru</h3>
                <a href="{{ route('admin.submissions.index') }}" class="text-xs text-amber-400 hover:underline">Lihat Semua →</a>
            </div>
            @if($latestSubmissions->count())
                <div class="space-y-3">
                    @foreach($latestSubmissions as $sub)
                        <div class="p-3 bg-gray-950 border border-gray-800 rounded-xl flex items-center justify-between text-xs">
                            <div class="truncate max-w-[200px]">
                                <span class="font-bold text-gray-200 block truncate">{{ $sub->title }}</span>
                                <span class="text-gray-500 text-[10px]">Oleh: {{ $sub->name }} ({{ $sub->instansi ?: 'Publik' }})</span>
                            </div>
                            <x-status-badge :status="$sub->status" />
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-xs text-gray-500 text-center py-4">Belum ada antrean kiriman publik.</p>
            @endif
        </div>

        <!-- Recent Articles -->
        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6 shadow-xl">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-white">Artikel Redaksi Terbaru</h3>
                <a href="{{ route('admin.articles.index') }}" class="text-xs text-amber-400 hover:underline">Lihat Semua →</a>
            </div>
            @if($latestArticles->count())
                <div class="space-y-3">
                    @foreach($latestArticles as $art)
                        <div class="p-3 bg-gray-950 border border-gray-800 rounded-xl flex items-center justify-between text-xs">
                            <div class="truncate max-w-[200px]">
                                <span class="font-bold text-gray-200 block truncate">{{ $art->title }}</span>
                                <span class="text-gray-500 text-[10px]">Kategori: {{ $art->category->name ?? 'Umum' }}</span>
                            </div>
                            <x-status-badge :status="$art->status" />
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-xs text-gray-500 text-center py-4">Belum ada artikel redaksi.</p>
            @endif
        </div>
    </div>
</x-admin-layout>
