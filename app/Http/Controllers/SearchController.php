<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Pilihan filter rentang waktu: nilai query string => [label, jumlah hari ke belakang].
     *
     * @var array<string, array{0: string, 1: int|null}>
     */
    public const DATE_RANGES = [
        '' => ['Semua waktu', null],
        'hari-ini' => ['Hari ini', 0],
        '7-hari' => ['7 hari terakhir', 7],
        '30-hari' => ['30 hari terakhir', 30],
    ];

    /**
     * @var array<string, string>
     */
    public const SORTS = [
        '' => 'Terbaru',
        'populer' => 'Terpopuler',
    ];

    public function __invoke(Request $request)
    {
        $query = $request->input('q');
        $categoryId = $request->input('category_id');
        $dateRange = array_key_exists((string) $request->input('rentang'), self::DATE_RANGES) ? (string) $request->input('rentang') : '';
        $sort = array_key_exists((string) $request->input('urut'), self::SORTS) ? (string) $request->input('urut') : '';

        $articlesQuery = Article::with(['category', 'author'])
            ->where('status', 'published');

        if ($query) {
            $articlesQuery->where(function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                    ->orWhere('content', 'LIKE', "%{$query}%");
            });
        }

        // Kategori induk (mis. Banten) mencakup seluruh wilayah anaknya
        $selectedCategory = $categoryId ? Category::with('children')->find($categoryId) : null;
        if ($selectedCategory) {
            $articlesQuery->whereIn('category_id', $selectedCategory->children->pluck('id')->push($selectedCategory->id));
        }

        $days = self::DATE_RANGES[$dateRange][1];
        if ($days !== null) {
            $articlesQuery->where('published_at', '>=', now('Asia/Jakarta')->subDays($days)->startOfDay()->utc());
        }

        $sort === 'populer'
            ? $articlesQuery->orderByDesc('views_count')
            : $articlesQuery->latest('published_at');

        $articles = $articlesQuery->paginate(10)->withQueryString();
        $categories = Category::with(['children' => fn ($q) => $q->orderBy('name')])
            ->whereNull('parent_id')
            ->orderBy('name')
            ->get();

        return view('search', compact('articles', 'categories', 'query', 'categoryId', 'selectedCategory', 'dateRange', 'sort'));
    }
}
