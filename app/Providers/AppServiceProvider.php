<?php

namespace App\Providers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View as ViewInstance;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.app', function (ViewInstance $view): void {
            $view->with('navCategories', Category::navigation());
        });

        View::composer(['errors::404', 'errors.404'], function (ViewInstance $view): void {
            $view->with('popularArticles', rescue(
                fn () => Article::with('category')
                    ->where('status', 'published')
                    ->orderByDesc('views_count')
                    ->take(4)
                    ->get(),
                collect(),
                report: false,
            ));
        });
    }
}
