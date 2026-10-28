<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
    ];

    /**
     * Kategori induk (beserta anaknya) untuk navigasi publik, diurutkan sesuai
     * `config('portal.nav')`; kategori yang tidak tercantum ditaruh di akhir.
     * Dimemo per request (cache Laravel 13 tidak menyimpan objek model).
     *
     * @return Collection<int, Category>
     */
    public static function navigation(): Collection
    {
        return once(function (): Collection {
            $order = array_keys(config('portal.nav', []));

            return static::with(['children' => fn ($query) => $query->orderBy('name')])
                ->whereNull('parent_id')
                ->get()
                ->sortBy(fn (Category $category): int => array_search($category->slug, $order, true) === false
                    ? PHP_INT_MAX
                    : array_search($category->slug, $order, true))
                ->values();
        });
    }

    /**
     * Label pendek untuk navigasi (mis. "Travel & Kuliner" menjadi "Travel").
     */
    public function navLabel(): string
    {
        return config("portal.nav.{$this->slug}", $this->name);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }
}
