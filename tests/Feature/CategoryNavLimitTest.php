<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryNavLimitTest extends TestCase
{
    use RefreshDatabase;

    private function createMainCategories(int $count): void
    {
        $names = ['Nasional', 'Jabodetabek', 'Banten', 'Budaya', 'Travel & Kuliner', 'Hukum & Kriminal', 'Olahraga', 'Teknologi', 'Otomotif', 'Internasional', 'Ekonomi'];

        foreach (array_slice($names, 0, $count) as $name) {
            Category::create(['name' => $name, 'slug' => str($name)->slug()->toString()]);
        }
    }

    public function test_categories_beyond_the_limit_move_into_lainnya(): void
    {
        $this->createMainCategories(10);

        $html = (string) $this->blade('<x-category-nav :categories="$categories" />', ['categories' => Category::navigation()]);
        [$mainMenu, $moreMenu] = explode('id="menu-lainnya"', $html);

        $this->assertStringContainsString('Teknologi', $mainMenu);
        $this->assertStringNotContainsString('Otomotif', $mainMenu);
        $this->assertStringContainsString('Otomotif', $moreMenu);
        $this->assertStringContainsString('Internasional', $moreMenu);
    }

    public function test_lainnya_is_hidden_while_categories_fit(): void
    {
        $this->createMainCategories(8);

        $this->blade('<x-category-nav :categories="$categories" />', ['categories' => Category::navigation()])
            ->assertSee('Teknologi')
            ->assertDontSee('menu-lainnya', false);
    }
}
