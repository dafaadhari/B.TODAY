@props(['article', 'layout' => 'vertical'])

@if($layout === 'horizontal')
    <article class="group flex gap-4 dark:bg-gray-900/80 bg-white border dark:border-gray-800 border-gray-200/80 rounded-xl p-3 hover:border-amber-500/50 transition-all shadow-sm">
        <a href="{{ route('articles.show', $article->slug) }}" class="shrink-0 w-28 h-20 overflow-hidden rounded-lg bg-gray-100 dark:bg-gray-950">
            <img src="{{ $article->thumbnail_path ?: 'https://images.unsplash.com/photo-1585829365295-ab7cd400c167?w=400&h=300&fit=crop' }}" alt="{{ $article->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
        </a>
        <div class="flex flex-col justify-between flex-1">
            <div>
                <a href="{{ route('categories.show', $article->category->slug ?? 'umum') }}" class="text-xs font-semibold text-amber-500 hover:underline">
                    {{ $article->category->name ?? 'Umum' }}
                </a>
                <h3 class="text-sm font-bold dark:text-gray-100 text-slate-900 group-hover:text-amber-500 line-clamp-2 leading-snug transition-colors">
                    <a href="{{ route('articles.show', $article->slug) }}">{{ $article->title }}</a>
                </h3>
            </div>
            <div class="flex items-center justify-between text-[11px] dark:text-gray-400 text-slate-500 mt-2">
                <span>{{ $article->published_at ? $article->published_at->diffForHumans() : $article->created_at->diffForHumans() }}</span>
                <span>👁️ {{ number_format($article->views_count) }}</span>
            </div>
        </div>
    </article>
@else
    <article class="group dark:bg-gray-900/80 bg-white border dark:border-gray-800 border-gray-200/80 rounded-xl overflow-hidden hover:border-amber-500/50 transition-all flex flex-col h-full shadow-md">
        <a href="{{ route('articles.show', $article->slug) }}" class="relative block h-48 overflow-hidden bg-gray-100 dark:bg-gray-950">
            <img src="{{ $article->thumbnail_path ?: 'https://images.unsplash.com/photo-1585829365295-ab7cd400c167?w=800&h=450&fit=crop' }}" alt="{{ $article->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
            <span class="absolute top-3 left-3 bg-amber-500 text-gray-950 text-xs font-extrabold px-2.5 py-1 rounded-md shadow">
                {{ $article->category->name ?? 'Umum' }}
            </span>
        </a>
        <div class="p-5 flex flex-col justify-between flex-1">
            <div>
                <h3 class="text-base font-bold dark:text-gray-100 text-slate-900 group-hover:text-amber-500 line-clamp-2 leading-snug transition-colors mb-2">
                    <a href="{{ route('articles.show', $article->slug) }}">{{ $article->title }}</a>
                </h3>
                <p class="text-xs dark:text-gray-400 text-slate-600 line-clamp-2 leading-relaxed mb-4">
                    {{ Str::limit(strip_tags($article->content), 110) }}
                </p>
            </div>

            <div class="pt-3 border-t dark:border-gray-800/80 border-gray-100 flex items-center justify-between text-xs dark:text-gray-400 text-slate-500">
                <span class="truncate max-w-[150px]">
                    {{ $article->guest_author_name ? $article->guest_author_name . ' (' . ($article->guest_author_instansi ?: 'Publik') . ')' : ($article->author->name ?? 'Redaksi B.Today') }}
                </span>
                <span class="shrink-0 text-slate-400 dark:text-gray-500">
                    {{ $article->published_at ? $article->published_at->format('d M Y') : '' }}
                </span>
            </div>
        </div>
    </article>
@endif
