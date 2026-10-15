<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;

class StaticPageController extends Controller
{
    public function pasangIklan()
    {
        // Angka nyata dari database, menggantikan statistik fiktif di demo (PRD 9.7)
        $stats = [
            'articles' => Article::where('status', 'published')->count(),
            'channels' => Category::whereNull('parent_id')->count(),
            'regions' => Category::whereHas('parent', fn ($q) => $q->where('slug', 'banten'))->count(),
        ];

        return view('pasang-iklan', compact('stats'));
    }

    public function redaksi()
    {
        return view('redaksi');
    }
}
