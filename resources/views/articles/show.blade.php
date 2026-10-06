<x-app-layout :title="$article->title . ' - B.Today'" :description="Str::limit(strip_tags($article->content), 150)">
    <div class="max-w-4xl mx-auto">
        <!-- Breadcrumbs -->
        <nav class="flex items-center space-x-2 text-xs dark:text-gray-400 text-slate-500 mb-6">
            <a href="{{ route('home') }}" class="hover:text-amber-500">Beranda</a>
            <span>/</span>
            <a href="{{ route('categories.show', $article->category->slug) }}" class="hover:text-amber-500">{{ $article->category->name }}</a>
            <span>/</span>
            <span class="dark:text-gray-300 text-slate-700 truncate max-w-[200px]">{{ $article->title }}</span>
        </nav>

        <!-- Article Header -->
        <header class="mb-8">
            <span class="inline-block bg-amber-500 text-gray-950 font-extrabold text-xs px-3 py-1 rounded-md uppercase tracking-wider mb-3">
                {{ $article->category->name }}
            </span>
            <h1 class="text-2xl md:text-4xl font-black dark:text-white text-slate-900 leading-tight mb-4">
                {{ $article->title }}
            </h1>

            <!-- Byline & Metadata -->
            <div class="flex flex-wrap items-center justify-between gap-4 py-3 border-y dark:border-gray-800 border-gray-200 text-xs dark:text-gray-400 text-slate-500">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-amber-500/20 border border-amber-500/40 flex items-center justify-center font-bold text-amber-500">
                        {{ strtoupper(substr($article->guest_author_name ?: ($article->author->name ?? 'B'), 0, 1)) }}
                    </div>
                    <div>
                        <span class="block font-bold dark:text-gray-200 text-slate-800">
                            {{ $article->guest_author_name ? $article->guest_author_name : ($article->author->name ?? 'Redaksi B.Today') }}
                        </span>
                        @if($article->guest_author_instansi)
                            <span class="block text-[11px] text-amber-500">Penulis Publik ({{ $article->guest_author_instansi }})</span>
                        @else
                            <span class="block text-[11px] dark:text-gray-500 text-slate-400">Tim Redaksi B.Today</span>
                        @endif
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <span>📅 {{ $article->published_at ? $article->published_at->translatedFormat('l, d F Y H:i') : $article->created_at->format('d/m/Y') }} WIB</span>
                    <span>👁️ {{ number_format($article->views_count) }} views</span>
                </div>
            </div>
        </header>

        <!-- Social Share Bar -->
        <div class="flex items-center space-x-3 mb-6 text-xs font-semibold">
            <span class="dark:text-gray-400 text-slate-500">Bagikan:</span>
            <a href="https://api.whatsapp.com/send?text={{ urlencode($article->title . ' ' . url()->current()) }}" target="_blank" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition-colors flex items-center space-x-1">
                <span>WhatsApp</span>
            </a>
            <a href="https://twitter.com/intent/tweet?text={{ urlencode($article->title) }}&url={{ urlencode(url()->current()) }}" target="_blank" class="px-3 py-1.5 bg-sky-600 hover:bg-sky-700 text-white rounded-lg transition-colors flex items-center space-x-1">
                <span>Twitter / X</span>
            </a>
        </div>

        <!-- Featured Image -->
        @if($article->thumbnail_path)
            <div class="mb-8 rounded-2xl overflow-hidden border dark:border-gray-800 border-gray-200 shadow-2xl">
                <img src="{{ $article->thumbnail_path }}" alt="{{ $article->title }}" class="w-full h-auto max-h-[500px] object-cover">
            </div>
        @endif

        <!-- Main Body Content -->
        <article class="prose dark:prose-invert max-w-none dark:text-gray-200 text-slate-800 text-base leading-relaxed space-y-6">
            {!! $article->content !!}
        </article>

        <!-- In-Article Ad Slot -->
        <x-ad-slot :ad="$inArticleAd" placement="in_article" />

        <!-- Tags -->
        @if($article->tags->count())
            <div class="my-8 pt-6 border-t dark:border-gray-800 border-gray-200 flex flex-wrap items-center gap-2">
                <span class="text-xs font-bold dark:text-gray-400 text-slate-500 mr-2">Tags:</span>
                @foreach($article->tags as $tag)
                    <a href="{{ route('search', ['q' => $tag->name]) }}" class="text-xs dark:bg-gray-800 bg-slate-100 hover:bg-amber-500 hover:text-gray-950 dark:text-gray-300 text-slate-700 px-3 py-1 rounded-full transition-colors">
                        #{{ $tag->name }}
                    </a>
                @endforeach
            </div>
        @endif

        <!-- Related Articles -->
        @if($relatedArticles->count())
            <section class="mt-12 pt-8 border-t dark:border-gray-800 border-gray-200">
                <h3 class="text-lg font-black text-amber-500 mb-6">Artikel Terkait</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($relatedArticles as $relArticle)
                        <x-article-card :article="$relArticle" />
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</x-app-layout>
