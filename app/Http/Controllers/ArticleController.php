<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function show(Request $request, string $slug)
    {
        $article = Article::with(['category', 'author', 'reviewer', 'tags'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // Anti-spam views counter (per session per article limit)
        $sessionKey = 'viewed_article_' . $article->id;
        if (! $request->session()->has($sessionKey)) {
            $article->increment('views_count');
            $request->session()->put($sessionKey, now()->toDateTimeString());
        }

        // Related articles
        $relatedArticles = Article::with(['category'])
            ->where('status', 'published')
            ->where('category_id', $article->category_id)
            ->where('id', '!=', $article->id)
            ->latest('published_at')
            ->take(3)
            ->get();

        // Popular articles sidebar
        $popularArticles = Article::where('status', 'published')
            ->orderByDesc('views_count')
            ->take(5)
            ->get();

        // In-article ad
        $inArticleAd = Advertisement::where('placement', 'in_article')
            ->where('is_active', true)
            ->first();

        if ($inArticleAd) {
            $inArticleAd->increment('impressions_count');
        }

        // Sidebar ad
        $sidebarAd = Advertisement::where('placement', 'sidebar')
            ->where('is_active', true)
            ->first();

        if ($sidebarAd) {
            $sidebarAd->increment('impressions_count');
        }

        return view('articles.show', compact('article', 'relatedArticles', 'popularArticles', 'inArticleAd', 'sidebarAd'));
    }
}
