<x-app-layout>
    <!-- Leaderboard Ad Banner -->
    <x-ad-slot :ad="$leaderboardAd" placement="leaderboard" />

    <!-- Hero Section Split Layout -->
    @if($heroArticle)
        <section class="mb-12">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-stretch dark:bg-gray-900/80 bg-white border dark:border-gray-800 border-gray-200/80 rounded-2xl overflow-hidden shadow-2xl">
                <!-- Main Featured Hero Left (2 cols) -->
                <div class="lg:col-span-2 relative min-h-[380px] lg:min-h-[460px] bg-gray-950 flex flex-col justify-end p-6 lg:p-10 group overflow-hidden">
                    <img src="{{ $heroArticle->thumbnail_path ?: 'https://images.unsplash.com/photo-1585829365295-ab7cd400c167?w=1200&h=800&fit=crop' }}" alt="{{ $heroArticle->title }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-60">
                    <div class="absolute inset-0 bg-gradient-to-t from-gray-950 via-gray-950/40 to-transparent"></div>
                    <div class="relative z-10">
                        <span class="inline-block bg-amber-500 text-gray-950 font-extrabold text-xs px-3 py-1 rounded-md uppercase tracking-wider mb-3">
                            {{ $heroArticle->category->name ?? 'Utama' }}
                        </span>
                        <h1 class="text-2xl lg:text-4xl font-black text-white group-hover:text-amber-400 leading-tight transition-colors mb-3">
                            <a href="{{ route('articles.show', $heroArticle->slug) }}">{{ $heroArticle->title }}</a>
                        </h1>
                        <p class="text-gray-300 text-sm line-clamp-2 mb-4 leading-relaxed max-w-2xl">
                            {{ Str::limit(strip_tags($heroArticle->content), 160) }}
                        </p>
                        <div class="flex items-center space-x-4 text-xs text-gray-400">
                            <span>{{ $heroArticle->guest_author_name ? $heroArticle->guest_author_name . ' (' . ($heroArticle->guest_author_instansi ?: 'Publik') . ')' : ($heroArticle->author->name ?? 'Redaksi B.Today') }}</span>
                            <span>•</span>
                            <span>{{ $heroArticle->published_at ? $heroArticle->published_at->format('d M Y') : '' }}</span>
                            <span>•</span>
                            <span>👁️ {{ number_format($heroArticle->views_count) }} views</span>
                        </div>
                    </div>
                </div>

                <!-- Trending / Featured Sidebar Right (1 col) -->
                <div class="p-6 flex flex-col justify-between dark:bg-gray-900/60 bg-slate-50 border-t lg:border-t-0 lg:border-l dark:border-gray-800 border-gray-200">
                    <div>
                        <h3 class="text-xs font-extrabold uppercase tracking-widest text-amber-500 mb-4 pb-2 border-b dark:border-gray-800 border-gray-200 flex items-center justify-between">
                            <span>🔥 Berita Terpopuler</span>
                            <span class="w-2 h-2 rounded-full bg-amber-500 animate-ping"></span>
                        </h3>
                        <div class="space-y-4">
                            @foreach($popularArticles as $index => $pArticle)
                                <div class="flex items-start space-x-3 group">
                                    <span class="text-2xl font-black text-amber-500/70 group-hover:text-amber-500 shrink-0 w-6">0{{ $index + 1 }}</span>
                                    <div>
                                        <h4 class="text-xs font-bold dark:text-gray-200 text-slate-800 group-hover:text-amber-500 leading-snug transition-colors line-clamp-2">
                                            <a href="{{ route('articles.show', $pArticle->slug) }}">{{ $pArticle->title }}</a>
                                        </h4>
                                        <span class="text-[10px] dark:text-gray-500 text-slate-400 mt-1 block">{{ $pArticle->category->name ?? 'Berita' }} • {{ number_format($pArticle->views_count) }} views</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Main Grid Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Article Feed (2 Cols) -->
        <div class="lg:col-span-2 space-y-8">
            <div>
                <h2 class="text-xl font-black dark:text-white text-slate-900 mb-6 pb-2 border-b-2 border-amber-500 inline-block">
                    Berita Terbaru
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($latestArticles as $article)
                        <x-article-card :article="$article" />
                    @endforeach
                </div>
            </div>

            <!-- Focus Section: Banten Today -->
            @if(isset($bantenArticles) && $bantenArticles->count())
                <div class="mt-12 pt-8 border-t dark:border-gray-800 border-gray-200">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-black text-amber-500 flex items-center space-x-2">
                            <span>🏛️ Banten Today</span>
                        </h2>
                        <a href="{{ route('categories.show', 'banten') }}" class="text-xs font-bold dark:text-gray-400 text-slate-600 hover:text-amber-500 transition-colors">
                            Lihat Semua Banten →
                        </a>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($bantenArticles as $bArticle)
                            <x-article-card :article="$bArticle" layout="horizontal" />
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar Right (1 Col) -->
        <aside class="space-y-8">
            <x-ad-slot :ad="$sidebarAd" placement="sidebar" />

            <!-- Kirim Tulisan Widget Banner -->
            <div class="p-6 bg-gradient-to-br dark:from-amber-500/10 dark:via-gray-900 dark:to-gray-900 from-amber-50 via-white to-white border border-amber-500/30 rounded-2xl text-center shadow-xl">
                <h3 class="text-base font-bold dark:text-white text-slate-900 mb-2">Punya Gagasan atau Berita?</h3>
                <p class="text-xs dark:text-gray-400 text-slate-600 mb-4 leading-relaxed">
                    Kirim tulisan Anda tanpa perlu mendaftar akun. Karya yang disetujui akan ditayangkan di portal B.Today.
                </p>
                <a href="{{ route('kirim-tulisan.create') }}" class="inline-block w-full py-2.5 px-4 bg-amber-500 hover:bg-amber-600 text-gray-950 font-extrabold text-xs uppercase tracking-wider rounded-xl transition-all shadow-lg">
                    Kirim Tulisan Sekarang
                </a>
            </div>
        </aside>
    </div>
</x-app-layout>
