<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class AdminCategoryTest extends TestCase
{
    use RefreshDatabase;

    private const MOBILE_AGENT = 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.0 Mobile/15E148 Safari/604.1';

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create(['name' => 'Admin Satu', 'email' => 'admin1@btoday.com', 'password' => bcrypt('password')]);
    }

    private function createCategory(string $name, ?Category $parent = null): Category
    {
        return Category::create(['name' => $name, 'slug' => str($name)->slug()->toString(), 'parent_id' => $parent?->id]);
    }

    public function test_categories_are_paginated_per_main_category_in_menu_order(): void
    {
        foreach (['Zona Hijau', 'Otomotif', 'Agenda', 'Nasional', 'Cuaca', 'Komunitas'] as $name) {
            $this->createCategory($name);
        }
        $banten = $this->createCategory('Banten');
        $this->createCategory('Serang Today', $banten);
        $this->createCategory('Lebak Today', $banten);

        $this->actingAs($this->admin)->withHeader('User-Agent', self::MOBILE_AGENT)
            ->get(route('admin.categories.index'))
            ->assertOk()
            ->assertViewHas('categories', function (LengthAwarePaginator $categories): bool {
                return $categories->perPage() === 5
                    && $categories->total() === 7
                    && $categories->pluck('slug')->all() === ['nasional', 'banten', 'otomotif', 'agenda', 'cuaca']
                    && $categories[1]->children->pluck('name')->all() === ['Lebak Today', 'Serang Today'];
            })
            ->assertViewHas('stats', ['total' => 9, 'main' => 7, 'sub' => 2, 'empty' => 8])
            ->assertSee('dari 2');
    }

    public function test_store_generates_slug_from_name_and_rejects_duplicates(): void
    {
        $this->actingAs($this->admin)->from(route('admin.categories.index'))
            ->post(route('admin.categories.store'), ['name' => 'Ekonomi Kreatif', 'slug' => ''])
            ->assertRedirect(route('admin.categories.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('categories', ['name' => 'Ekonomi Kreatif', 'slug' => 'ekonomi-kreatif', 'parent_id' => null]);

        $this->actingAs($this->admin)->from(route('admin.categories.index'))
            ->post(route('admin.categories.store'), ['name' => 'Ekonomi Kreatif'])
            ->assertRedirect(route('admin.categories.index'))
            ->assertSessionHasErrorsIn('category', [
                'name' => 'Nama kategori ini sudah dipakai.',
                'slug' => 'Slug ini sudah dipakai kategori lain.',
            ]);

        $this->assertSame(1, Category::count());
    }

    public function test_update_changes_name_slug_and_parent(): void
    {
        $banten = $this->createCategory('Banten');
        $serang = $this->createCategory('Serang');

        $this->actingAs($this->admin)->from(route('admin.categories.index'))
            ->put(route('admin.categories.update', $serang), ['name' => 'Serang Today', 'slug' => 'Serang Today', 'parent_id' => $banten->id])
            ->assertSessionHasNoErrors()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('categories', ['id' => $serang->id, 'name' => 'Serang Today', 'slug' => 'serang-today', 'parent_id' => $banten->id]);
    }

    public function test_update_rejects_parents_that_would_break_the_two_level_tree(): void
    {
        $banten = $this->createCategory('Banten');
        $serang = $this->createCategory('Serang Today', $banten);
        $nasional = $this->createCategory('Nasional');

        $this->actingAs($this->admin)->from(route('admin.categories.index'))
            ->put(route('admin.categories.update', $nasional), ['name' => 'Nasional', 'parent_id' => $nasional->id])
            ->assertSessionHasErrorsIn('category', ['parent_id' => 'Kategori tidak bisa menjadi induk dirinya sendiri.']);

        $this->actingAs($this->admin)->from(route('admin.categories.index'))
            ->put(route('admin.categories.update', $nasional), ['name' => 'Nasional', 'parent_id' => $serang->id])
            ->assertSessionHasErrorsIn('category', ['parent_id' => 'Induk harus berupa kategori utama.']);

        $this->actingAs($this->admin)->from(route('admin.categories.index'))
            ->put(route('admin.categories.update', $banten), ['name' => 'Banten', 'parent_id' => $nasional->id])
            ->assertSessionHasErrorsIn('category', ['parent_id' => 'Kategori ini punya sub-kategori, jadi harus tetap menjadi kategori utama.']);

        $this->assertNull($nasional->fresh()->parent_id);
        $this->assertNull($banten->fresh()->parent_id);
    }

    public function test_destroy_is_blocked_while_category_has_articles_or_sub_categories(): void
    {
        $otomotif = $this->createCategory('Otomotif');
        Article::create([
            'title' => 'Draf Otomotif',
            'slug' => 'draf-otomotif',
            'category_id' => $otomotif->id,
            'author_id' => $this->admin->id,
            'content' => '<p>Isi draf.</p>',
            'status' => 'draft',
        ]);
        $banten = $this->createCategory('Banten');
        $this->createCategory('Serang Today', $banten);
        $empty = $this->createCategory('Kosong');

        $this->actingAs($this->admin)->from(route('admin.categories.index'))
            ->delete(route('admin.categories.destroy', $otomotif))
            ->assertSessionHas('error', 'Kategori "Otomotif" belum bisa dihapus. Masih berisi 1 artikel. Pindahkan atau hapus isinya terlebih dulu.');

        $this->actingAs($this->admin)->from(route('admin.categories.index'))
            ->delete(route('admin.categories.destroy', $banten))
            ->assertSessionHas('error');

        $this->actingAs($this->admin)->from(route('admin.categories.index'))
            ->delete(route('admin.categories.destroy', $empty))
            ->assertSessionHas('success');

        $this->assertModelExists($otomotif);
        $this->assertDatabaseHas('articles', ['slug' => 'draf-otomotif']);
        $this->assertModelExists($banten);
        $this->assertModelMissing($empty);
    }
}
