<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class PublicRedesignTest extends TestCase
{
    use RefreshDatabase;

    private function publishedArticle(Category $category, string $title, array $attributes = []): Article
    {
        $author = User::create(['name' => 'Penulis '.$title, 'email' => md5($title).'@btoday.com', 'password' => bcrypt('password')]);
        $reviewer = User::create(['name' => 'Reviewer '.$title, 'email' => 'r'.md5($title).'@btoday.com', 'password' => bcrypt('password')]);

        return Article::create(array_merge([
            'title' => $title,
            'slug' => str($title)->slug()->toString(),
            'category_id' => $category->id,
            'author_id' => $author->id,
            'reviewed_by' => $reviewer->id,
            'reviewed_at' => now(),
            'content' => '<p>Isi berita '.$title.'.</p>',
            'status' => 'published',
            'published_at' => now(),
        ], $attributes));
    }

    public function test_search_by_parent_category_includes_child_regions(): void
    {
        $banten = Category::create(['name' => 'Banten', 'slug' => 'banten']);
        $cilegon = Category::create(['name' => 'Cilegon Today', 'slug' => 'cilegon-today', 'parent_id' => $banten->id]);
        $sport = Category::create(['name' => 'Olahraga', 'slug' => 'olahraga']);

        $this->publishedArticle($cilegon, 'Pelabuhan Merak Ramai');
        $this->publishedArticle($sport, 'Timnas Menang Telak');

        $this->get('/cari?category_id='.$banten->id)
            ->assertOk()
            ->assertSee('Pelabuhan Merak Ramai')
            ->assertDontSee('Timnas Menang Telak');
    }

    public function test_search_date_range_filter_excludes_older_articles(): void
    {
        $category = Category::create(['name' => 'Nasional', 'slug' => 'nasional']);

        $this->publishedArticle($category, 'Berita Pekan Ini');
        $this->publishedArticle($category, 'Berita Bulan Lalu', ['published_at' => Carbon::now()->subDays(40)]);

        $this->get('/cari?rentang=7-hari')
            ->assertOk()
            ->assertSee('Berita Pekan Ini')
            ->assertDontSee('Berita Bulan Lalu');
    }

    public function test_guest_contribution_shows_contributor_label_on_article_page(): void
    {
        $category = Category::create(['name' => 'Serang Today', 'slug' => 'serang-today']);
        $article = $this->publishedArticle($category, 'Batik Banten Mendunia', [
            'author_id' => null,
            'guest_author_name' => 'Ahmad Fauzi',
            'guest_author_instansi' => null,
        ]);

        $this->get('/berita/'.$article->slug)
            ->assertOk()
            ->assertSee('Ahmad Fauzi')
            ->assertSee('Kontributor')
            ->assertDontSee('Tim Redaksi');
    }

    public function test_missing_page_keeps_404_status_and_recommends_popular_articles(): void
    {
        $category = Category::create(['name' => 'Teknologi', 'slug' => 'teknologi']);
        $this->publishedArticle($category, 'Startup Lokal Melesat', ['views_count' => 999]);

        $this->get('/berita/artikel-yang-sudah-dihapus')
            ->assertNotFound()
            ->assertSee('Halaman Tidak Ditemukan')
            ->assertSee('Startup Lokal Melesat');
    }

    public function test_home_page_renders_trending_ticker(): void
    {
        $category = Category::create(['name' => 'Nasional', 'slug' => 'nasional']);
        $this->publishedArticle($category, 'Sidang Paripurna Memanas');

        $this->get('/')
            ->assertOk()
            ->assertSee('Berita trending')
            ->assertSee('Sidang Paripurna Memanas');
    }
}
