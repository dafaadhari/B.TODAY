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
            return static::with(['children' => fn ($query) => $query->orderBy('name')])
                ->whereNull('parent_id')
                ->get()
                ->sortBy(fn (Category $category): int => $category->navigationPosition())
                ->values();
        });
    }

    /**
     * Posisi kategori pada urutan `config('portal.nav')`; yang tidak tercantum di akhir.
     */
    public function navigationPosition(): int
    {
        $position = array_search($this->slug, array_keys(config('portal.nav', [])), true);

        return $position === false ? PHP_INT_MAX : $position;
    }

    /**
     * Label pendek untuk navigasi (mis. "Travel & Kuliner" menjadi "Travel").
     */
    public function navLabel(): string
    {
        return config("portal.nav.{$this->slug}", $this->name);
    }

    /**
     * Alasan kategori belum boleh dihapus, atau null bila kosong. Menghapus kategori
     * ikut menghapus artikelnya (cascade) dan melepas sub-kategorinya menjadi kategori utama.
     */
    public function deletionBlocker(): ?string
    {
        $articleCount = $this->articles_count ?? $this->articles()->count();
        $childCount = $this->children_count ?? $this->children()->count();

        $contents = array_filter([
            $articleCount > 0 ? "{$articleCount} artikel" : null,
            $childCount > 0 ? "{$childCount} sub-kategori" : null,
        ]);

        return $contents === [] ? null : 'Masih berisi '.implode(' dan ', $contents).'.';
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
