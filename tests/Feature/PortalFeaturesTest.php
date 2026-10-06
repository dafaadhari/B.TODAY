<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use App\Models\GuestSubmission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Tests\TestCase;

class PortalFeaturesTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_render_successfully(): void
    {
        $category = Category::create(['name' => 'Nasional', 'slug' => 'nasional']);
        $admin = User::create(['name' => 'Admin 1', 'email' => 'a1@btoday.com', 'password' => bcrypt('password')]);
        $reviewer = User::create(['name' => 'Admin 2', 'email' => 'a2@btoday.com', 'password' => bcrypt('password')]);

        Article::create([
            'title' => 'Berita Uji Coba',
            'slug' => 'berita-uji-coba',
            'category_id' => $category->id,
            'author_id' => $admin->id,
            'reviewed_by' => $reviewer->id,
            'reviewed_at' => now(),
            'content' => '<p>Konten berita uji coba publik.</p>',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $this->get('/')->assertStatus(200);
        $this->get('/berita/berita-uji-coba')->assertStatus(200);
        $this->get('/kategori/nasional')->assertStatus(200);
        $this->get('/kirim-tulisan')->assertStatus(200);
        $this->get('/cari?q=Berita')->assertStatus(200);
        $this->get('/pasang-iklan')->assertStatus(200);
        $this->get('/redaksi')->assertStatus(200);
    }

    public function test_guest_submission_store(): void
    {
        $response = $this->post('/kirim-tulisan', [
            'name' => 'Budi Santoso',
            'instansi' => 'Warga Serang',
            'email' => 'budi@gmail.com',
            'title' => 'Opini Pembangunan Infrastruktur Banten',
            'content' => 'Ini adalah konten tulisan opini dari warga mengenai pembangunan jalan raya di Banten.',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('guest_submissions', [
            'name' => 'Budi Santoso',
            'email' => 'budi@gmail.com',
            'status' => 'pending',
        ]);
    }

    public function test_self_review_guard_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $admin = User::create(['name' => 'Admin Self', 'email' => 'self@btoday.com', 'password' => bcrypt('password')]);
        $category = Category::create(['name' => 'Umum', 'slug' => 'umum']);

        Article::create([
            'title' => 'Self Approved Article',
            'slug' => 'self-approved',
            'category_id' => $category->id,
            'author_id' => $admin->id,
            'reviewed_by' => $admin->id, // Same author as reviewer!
            'content' => 'Konten uji coba self review.',
            'status' => 'published',
        ]);
    }
}
