<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function show(Request $request, string $slug)
    {
        $article = Article::with(['category.parent', 'author', 'reviewer', 'tags'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // Anti-spam views counter (per session per article limit)
        $sessionKey = 'viewed_article_'.$article->id;
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
            ->take(4)
            ->get();

        // Popular articles sidebar ("Baca Juga"), tanpa artikel yang sedang dibaca
        $popularArticles = Article::where('status', 'published')
            ->where('id', '!=', $article->id)
            ->orderByDesc('views_count')
            ->take(4)
            ->get();

        // Isi artikel dipecah setelah paragraf ke-3 untuk iklan native (PRD 4C.3)
        [$contentBeforeAd, $contentAfterAd] = $article->contentSegments();

        return view('articles.show', compact('article', 'relatedArticles', 'popularArticles', 'contentBeforeAd', 'contentAfterAd'));
    }
}
