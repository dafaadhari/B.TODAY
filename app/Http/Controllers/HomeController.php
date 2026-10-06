<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\Article;
use App\Models\Category;
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
            ->take(8)
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

        // Banten regional news (parent category Banten)
        $bantenCategory = Category::where('slug', 'banten')->first();
        $bantenChildIds = $bantenCategory ? $bantenCategory->children()->pluck('id')->toArray() : [];
        $bantenArticles = Article::with(['category'])
            ->where('status', 'published')
            ->whereIn('category_id', array_merge([$bantenCategory?->id], $bantenChildIds))
            ->latest('published_at')
            ->take(4)
            ->get();

        // Leaderboard ad
        $leaderboardAd = Advertisement::where('placement', 'leaderboard')
            ->where('is_active', true)
            ->first();

        if ($leaderboardAd) {
            $leaderboardAd->increment('impressions_count');
        }

        // Sidebar ad
        $sidebarAd = Advertisement::where('placement', 'sidebar')
            ->where('is_active', true)
            ->first();

        if ($sidebarAd) {
            $sidebarAd->increment('impressions_count');
        }

        return view('home', compact(
            'heroArticle',
            'latestArticles',
            'popularArticles',
            'trendingArticles',
            'bantenArticles',
            'leaderboardAd',
            'sidebarAd'
        ));
    }
}
