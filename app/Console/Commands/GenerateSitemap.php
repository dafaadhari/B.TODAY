<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generasi sitemap XML dinamis untuk seluruh artikel dan kategori B.Today';

    public function handle(): void
    {
        $sitemap = Sitemap::create()
            ->add(Url::create('/')->setPriority(1.0)->setChangeFrequency(Url::CHANGE_FREQUENCY_ALWAYS))
            ->add(Url::create('/kirim-tulisan')->setPriority(0.8))
            ->add(Url::create('/pasang-iklan')->setPriority(0.7))
            ->add(Url::create('/redaksi')->setPriority(0.6));

        // Add Categories
        Category::all()->each(function (Category $category) use ($sitemap) {
            $sitemap->add(Url::create("/kategori/{$category->slug}")->setPriority(0.8));
        });

        // Add Published Articles
        Article::where('status', 'published')->get()->each(function (Article $article) use ($sitemap) {
            $sitemap->add(
                Url::create("/berita/{$article->slug}")
                    ->setLastModificationDate($article->updated_at ?? now())
                    ->setPriority(0.9)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            );
        });

        $sitemap->writeToFile(public_path('sitemap.xml'));
        $this->info('Sitemap XML dinamis berhasil dibuat di public/sitemap.xml!');
    }
}
