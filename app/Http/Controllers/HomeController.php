<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke(Request $request)
    {
        // Articles query for published articles
        $publishedQuery = Article::with(['category', 'author'])
            ->where('status', 'published')
            ->whereNotNull('published_at');

        // Hero article (latest published)
        $heroArticle = (clone $publishedQuery)
            ->latest('published_at')
            ->first();

        // Latest articles grid (excluding hero)
        $latestArticles = (clone $publishedQuery)
            ->when($heroArticle, fn ($q) => $q->where('id', '!=', $heroArticle->id))
            ->latest('published_at')
            ->take(6)
            ->get();

        // Popular articles (most viewed)
        $popularArticles = (clone $publishedQuery)
            ->orderByDesc('views_count')
            ->take(5)
            ->get();

        // Trending topics ticker
        $trendingArticles = (clone $publishedQuery)
            ->latest('published_at')
            ->take(5)
            ->get();

        // Banten regional news (parent category Banten), tanpa artikel yang sudah tampil di hero/terbaru
        $bantenCategory = Category::with(['children' => fn ($q) => $q->orderBy('name')])
            ->where('slug', 'banten')
            ->first();
        $bantenCategoryIds = $bantenCategory
            ? $bantenCategory->children->pluck('id')->push($bantenCategory->id)->all()
            : [];
        $shownArticleIds = $latestArticles->pluck('id')->when($heroArticle, fn ($ids) => $ids->push($heroArticle->id))->all();

        $bantenArticles = Article::with(['category'])
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->whereIn('category_id', $bantenCategoryIds)
            ->whereNotIn('id', $shownArticleIds)
            ->latest('published_at')
            ->take(4)
            ->get();

        // Topik pilihan: tag yang dipakai artikel terbit
        $featuredTags = Tag::whereHas('articles', fn ($q) => $q->where('status', 'published'))
            ->withCount(['articles' => fn ($q) => $q->where('status', 'published')])
            ->orderByDesc('articles_count')
            ->take(8)
            ->get();

        return view('home', compact(
            'heroArticle',
            'latestArticles',
            'popularArticles',
            'trendingArticles',
            'bantenCategory',
            'bantenArticles',
            'featuredTags',
        ));
    }
}
