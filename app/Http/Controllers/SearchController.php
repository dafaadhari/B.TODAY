<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $query = $request->input('q');
        $categoryId = $request->input('category_id');

        $articlesQuery = Article::with(['category', 'author'])
            ->where('status', 'published');

        if ($query) {
            $articlesQuery->where(function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('content', 'LIKE', "%{$query}%");
            });
        }

        if ($categoryId) {
            $articlesQuery->where('category_id', $categoryId);
        }

        $articles = $articlesQuery->latest('published_at')->paginate(9)->withQueryString();
        $categories = Category::all();

        return view('search', compact('articles', 'categories', 'query', 'categoryId'));
    }
}
