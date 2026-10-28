<?php

namespace Tests\Feature;

use App\Models\Advertisement;
use App\Models\Article;
use App\Models\Category;
use App\Models\GuestSubmission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPagesRenderTest extends TestCase
{
    use RefreshDatabase;

    public function test_every_admin_page_renders_with_icons_and_without_emoji(): void
    {
        $admin = User::create(['name' => 'Admin Satu', 'email' => 'admin1@btoday.com', 'password' => bcrypt('password')]);
        $category = Category::create(['name' => 'Nasional', 'slug' => 'nasional']);
        $article = Article::create([
            'title' => 'Draf Berita Uji',
            'slug' => 'draf-berita-uji',
            'category_id' => $category->id,
            'author_id' => $admin->id,
            'content' => '<p>Isi draf berita uji coba.</p>',
            'status' => 'draft',
        ]);
        GuestSubmission::create([
            'name' => 'Budi',
            'email' => 'budi@example.com',
            'title' => 'Opini Warga',
            'content' => 'Isi opini warga yang cukup panjang untuk diuji.',
            'status' => 'pending',
        ]);
        Advertisement::create([
            'title' => 'Banner Uji',
            'image_url' => 'https://example.com/banner.jpg',
            'redirect_url' => 'https://example.com',
            'placement' => 'sidebar',
            'is_active' => true,
        ]);

        $pages = [
            route('admin.dashboard'),
            route('admin.articles.index'),
            route('admin.articles.create'),
            route('admin.articles.edit', $article),
            route('admin.submissions.index'),
            route('admin.ads.index'),
            route('admin.categories.index'),
            route('admin.profile.edit'),
        ];

        foreach ($pages as $page) {
            $html = $this->actingAs($admin)->get($page)->assertOk()->getContent();

            $this->assertStringContainsString('<svg', $html, "Ikon tidak dirender di {$page}");
            $this->assertDoesNotMatchRegularExpression('/\p{Extended_Pictographic}/u', $html, "Masih ada emoji di {$page}");
        }
    }

    public function test_login_page_renders_without_emoji(): void
    {
        $html = $this->get(route('login'))->assertOk()->getContent();

        $this->assertDoesNotMatchRegularExpression('/\p{Extended_Pictographic}/u', $html);
    }
}
