<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Advertisement;
use App\Models\GuestSubmission;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $stats = [
            'total_articles' => Article::count(),
            'published_articles' => Article::where('status', 'published')->count(),
            'draft_articles' => Article::where('status', 'draft')->count(),
            'pending_reviews' => Article::where('status', 'pending_review')->count(),
            'pending_submissions' => GuestSubmission::where('status', 'pending')->count(),
            'active_ads' => Advertisement::where('is_active', true)->count(),
        ];

        $latestSubmissions = GuestSubmission::latest()->take(5)->get();
        $latestArticles = Article::with(['category', 'author'])->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'latestSubmissions', 'latestArticles'));
    }
}
