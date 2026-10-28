<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show(Request $request, string $slug)
    {
        $category = Category::with([
            'children' => fn ($q) => $q->orderBy('name'),
            'parent.children' => fn ($q) => $q->orderBy('name'),
        ])->where('slug', $slug)->firstOrFail();

        $categoryIds = [$category->id];
        if ($category->children->isNotEmpty()) {
            $categoryIds = array_merge($categoryIds, $category->children->pluck('id')->toArray());
        }

        // 11 artikel + 1 sel iklan sidebar = 12 sel, sehingga grid 2 & 3 kolom selalu penuh
        $articles = Article::with(['category', 'author'])
            ->where('status', 'published')
            ->whereIn('category_id', $categoryIds)
            ->latest('published_at')
            ->paginate(11);

        $lastPublishedAt = Article::where('status', 'published')
            ->whereIn('category_id', $categoryIds)
            ->max('published_at');

        return view('categories.show', compact('category', 'articles', 'lastPublishedAt'));
    }
}
