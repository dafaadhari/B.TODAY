<x-admin-layout title="Dashboard Redaksi - B.Today">
    <!-- Page Header (sapaan setelah login tampil sebagai notifikasi di layout) -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-white">Dashboard</h1>
            <p class="text-xs text-gray-400">{{ now()->locale('id')->timezone('Asia/Jakarta')->translatedFormat('l, j F Y') }}</p>
        </div>
        <a href="{{ route('admin.articles.create') }}" class="inline-flex items-center justify-center gap-1.5 py-3 px-6 bg-amber-500 hover:bg-amber-600 text-gray-950 font-black text-xs uppercase tracking-wider rounded-xl transition-all shadow-lg shrink-0">
            <x-heroicon-o-plus class="w-4 h-4" aria-hidden="true" />
            Tambah Berita Baru
        </a>
    </div>

    <!-- Summary Stats Cards Grid -->
    <div class="grid grid-cols-3 sm:grid-cols-2 lg:grid-cols-3 gap-2 sm:gap-4">
        <x-stat-card title="Total Artikel" :value="$stats['total_articles']" icon="newspaper" color="amber" />
        <x-stat-card title="Artikel Terbit" :value="$stats['published_articles']" icon="check-circle" color="emerald" />
        <x-stat-card title="Draf" :value="$stats['draft_articles']" icon="pencil-square" color="blue" />
        <x-stat-card title="Menunggu Review" :value="$stats['pending_reviews']" icon="clock" color="amber" />
        <x-stat-card title="Kiriman Pending" :value="$stats['pending_submissions']" icon="inbox-arrow-down" color="rose" />
        <x-stat-card title="Iklan Aktif" :value="$stats['active_ads']" icon="megaphone" color="emerald" />
    </div>

    <!-- Recent Tables Split -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Pending Submissions -->
        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6 shadow-xl">
            <div class="flex items-center justify-between gap-3 mb-4">
                <h3 class="text-sm font-bold text-white">Antrean Kiriman Publik Terbaru</h3>
                <a href="{{ route('admin.submissions.index') }}" class="shrink-0 whitespace-nowrap text-xs text-amber-400 hover:underline">Lihat Semua →</a>
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
            <div class="flex items-center justify-between gap-3 mb-4">
                <h3 class="text-sm font-bold text-white">Artikel Redaksi Terbaru</h3>
                <a href="{{ route('admin.articles.index') }}" class="shrink-0 whitespace-nowrap text-xs text-amber-400 hover:underline">Lihat Semua →</a>
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
