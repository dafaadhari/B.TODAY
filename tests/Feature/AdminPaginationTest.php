<?php

namespace Tests\Feature;

use App\Models\Advertisement;
use App\Models\Article;
use App\Models\Category;
use App\Models\GuestSubmission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class AdminPaginationTest extends TestCase
{
    use RefreshDatabase;

    private const MOBILE_AGENT = 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.0 Mobile/15E148 Safari/604.1';

    private const DESKTOP_AGENT = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Safari/537.36';

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create(['name' => 'Admin Satu', 'email' => 'admin1@btoday.com', 'password' => bcrypt('password')]);
    }

    private function createDraftArticles(int $count): void
    {
        $category = Category::create(['name' => 'Nasional', 'slug' => 'nasional']);

        foreach (range(1, $count) as $number) {
            Article::create([
                'title' => "Draf Berita {$number}",
                'slug' => "draf-berita-{$number}",
                'category_id' => $category->id,
                'author_id' => $this->admin->id,
                'content' => '<p>Isi draf.</p>',
                'status' => 'draft',
            ]);
        }
    }

    public function test_articles_show_five_rows_on_mobile_and_ten_on_desktop(): void
    {
        $this->createDraftArticles(12);

        $this->actingAs($this->admin)->withHeader('User-Agent', self::MOBILE_AGENT)
            ->get(route('admin.articles.index'))
            ->assertOk()
            ->assertViewHas('articles', fn (LengthAwarePaginator $articles) => $articles->perPage() === 5)
            ->assertSee('Hal 1');

        $this->actingAs($this->admin)->withHeader('User-Agent', self::DESKTOP_AGENT)
            ->get(route('admin.articles.index'))
            ->assertOk()
            ->assertViewHas('articles', fn (LengthAwarePaginator $articles) => $articles->perPage() === 10);
    }

    public function test_per_page_choice_is_respected_and_invalid_values_fall_back_to_default(): void
    {
        $this->createDraftArticles(12);

        $this->actingAs($this->admin)->withHeader('User-Agent', self::MOBILE_AGENT)
            ->get(route('admin.articles.index', ['per_halaman' => 25]))
            ->assertViewHas('articles', fn (LengthAwarePaginator $articles) => $articles->perPage() === 25);

        $this->actingAs($this->admin)->withHeader('User-Agent', self::DESKTOP_AGENT)
            ->get(route('admin.articles.index', ['per_halaman' => 7]))
            ->assertViewHas('articles', fn (LengthAwarePaginator $articles) => $articles->perPage() === 10);
    }

    public function test_filters_and_per_page_are_kept_in_pagination_links(): void
    {
        $this->createDraftArticles(12);

        $this->actingAs($this->admin)
            ->get(route('admin.articles.index', ['status' => 'draft', 'per_halaman' => 5]))
            ->assertViewHas('articles', function (LengthAwarePaginator $articles): bool {
                $nextPage = (string) $articles->nextPageUrl();

                return str_contains($nextPage, 'status=draft') && str_contains($nextPage, 'per_halaman=5');
            });
    }

    public function test_ads_and_submissions_tables_are_paginated(): void
    {
        foreach (range(1, 6) as $number) {
            Advertisement::create([
                'title' => "Banner {$number}",
                'image_url' => 'https://example.com/banner.jpg',
                'redirect_url' => 'https://example.com',
                'placement' => 'sidebar',
                'is_active' => true,
            ]);
            GuestSubmission::create([
                'name' => "Penulis {$number}",
                'email' => "penulis{$number}@example.com",
                'title' => "Opini {$number}",
                'content' => 'Isi opini warga yang cukup panjang untuk diuji.',
                'status' => 'pending',
            ]);
        }

        $this->actingAs($this->admin)->withHeader('User-Agent', self::MOBILE_AGENT)
            ->get(route('admin.ads.index'))
            ->assertOk()
            ->assertViewHas('ads', fn (LengthAwarePaginator $ads) => $ads->perPage() === 5 && $ads->total() === 6)
            ->assertSee('dari 2');

        $this->actingAs($this->admin)->withHeader('User-Agent', self::MOBILE_AGENT)
            ->get(route('admin.submissions.index'))
            ->assertOk()
            ->assertViewHas('submissions', fn (LengthAwarePaginator $submissions) => $submissions->perPage() === 5);
    }
}
