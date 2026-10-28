<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryController extends Controller
{
    /**
     * Dipaginasi per kategori utama agar sub-kategori tidak terpisah dari induknya.
     */
    public function index(Request $request): View
    {
        $mainCategories = Category::whereNull('parent_id')
            ->withCount(['articles', 'children'])
            ->orderBy('name')
            ->get()
            ->sortBy(fn (Category $category): int => $category->navigationPosition())
            ->values();

        $perPage = $this->adminPerPage($request);
        $page = LengthAwarePaginator::resolveCurrentPage();

        $categories = new LengthAwarePaginator(
            $mainCategories->forPage($page, $perPage)->load([
                'children' => fn ($query) => $query->withCount(['articles', 'children'])->orderBy('name'),
            ]),
            $mainCategories->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()],
        );

        $subCategoryCount = Category::whereNotNull('parent_id')->count();

        $stats = [
            'total' => $mainCategories->count() + $subCategoryCount,
            'main' => $mainCategories->count(),
            'sub' => $subCategoryCount,
            'empty' => Category::doesntHave('articles')->doesntHave('children')->count(),
        ];

        return view('admin.categories.index', compact('categories', 'mainCategories', 'stats'));
    }

    public function store(CategoryRequest $request): RedirectResponse
    {
        $category = Category::create($request->validated());

        return redirect()->back()->with('success', "Kategori \"{$category->name}\" berhasil ditambahkan.");
    }

    public function update(CategoryRequest $request, Category $category): RedirectResponse
    {
        $category->update($request->validated());

        return redirect()->back()->with('success', "Kategori \"{$category->name}\" berhasil diperbarui.");
    }

    public function destroy(Category $category): RedirectResponse
    {
        $blocker = $category->deletionBlocker();

        if ($blocker !== null) {
            return redirect()->back()->with('error', "Kategori \"{$category->name}\" belum bisa dihapus. {$blocker} Pindahkan atau hapus isinya terlebih dulu.");
        }

        $category->delete();

        return redirect()->back()->with('success', "Kategori \"{$category->name}\" berhasil dihapus.");
    }
}
