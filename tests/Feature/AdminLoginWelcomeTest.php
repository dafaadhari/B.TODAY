<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use App\Models\GuestSubmission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminLoginWelcomeTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create(['name' => 'Admin Satu', 'email' => 'admin1@btoday.com', 'password' => bcrypt('password-rahasia')]);
    }

    public function test_welcome_notice_appears_only_on_the_first_page_after_login(): void
    {
        $category = Category::create(['name' => 'Nasional', 'slug' => 'nasional']);
        Article::create([
            'title' => 'Menunggu Review',
            'slug' => 'menunggu-review',
            'category_id' => $category->id,
            'author_id' => $this->admin->id,
            'content' => '<p>Isi.</p>',
            'status' => 'pending_review',
        ]);
        GuestSubmission::create([
            'name' => 'Budi',
            'email' => 'budi@example.com',
            'title' => 'Opini Warga',
            'content' => 'Isi opini warga yang cukup panjang untuk diuji.',
            'status' => 'pending',
        ]);

        $this->followingRedirects()
            ->post(route('login.store'), ['email' => 'admin1@btoday.com', 'password' => 'password-rahasia'])
            ->assertOk()
            ->assertSee('Selamat datang kembali, Admin Satu')
            ->assertSee('1 artikel menunggu review')
            ->assertSee('1 kiriman publik belum ditinjau');

        $this->get(route('admin.dashboard'))
            ->assertOk()
            ->assertDontSee('Selamat datang kembali');
    }

    public function test_welcome_notice_follows_the_intended_page(): void
    {
        $this->get(route('admin.articles.index'))->assertRedirect(route('login'));

        $this->followingRedirects()
            ->post(route('login.store'), ['email' => 'admin1@btoday.com', 'password' => 'password-rahasia'])
            ->assertOk()
            ->assertSee('Manajemen Artikel Redaksi')
            ->assertSee('Tidak ada artikel atau kiriman yang menunggu ditinjau.');
    }

    public function test_account_menu_no_longer_duplicates_the_site_link(): void
    {
        $this->actingAs($this->admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Lihat Situs')
            ->assertDontSee('Lihat Situs Publik');
    }
}
