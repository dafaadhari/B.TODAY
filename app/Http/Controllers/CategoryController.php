<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show(Request $request, string $slug)
    {
        $category = Category::with(['children', 'parent'])->where('slug', $slug)->firstOrFail();

        $categoryIds = [$category->id];
        if ($category->children->isNotEmpty()) {
            $categoryIds = array_merge($categoryIds, $category->children->pluck('id')->toArray());
        }

        $articles = Article::with(['category', 'author'])
            ->where('status', 'published')
            ->whereIn('category_id', $categoryIds)
            ->latest('published_at')
            ->paginate(9);

        // Sidebar ad
        $sidebarAd = Advertisement::where('placement', 'sidebar')
            ->where('is_active', true)
            ->first();

        if ($sidebarAd) {
            $sidebarAd->increment('impressions_count');
        }

        return view('categories.show', compact('category', 'articles', 'sidebarAd'));
    }
}
