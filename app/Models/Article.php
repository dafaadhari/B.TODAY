<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use InvalidArgumentException;

class Article extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'author_id',
        'category_id',
        'title',
        'slug',
        'content',
        'thumbnail_path',
        'guest_author_name',
        'guest_author_instansi',
        'reviewed_by',
        'reviewed_at',
        'views_count',
        'status',
        'published_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
        'published_at' => 'datetime',
        'views_count' => 'integer',
    ];

    protected static function booted(): void
    {
        static::saving(function (Article $article) {
            // Guard: Status published hanya boleh jika reviewed_by sudah terisi
            if ($article->status === 'published' && !$article->reviewed_by) {
                throw new InvalidArgumentException('Artikel tidak dapat dipublikasikan tanpa peninjau (reviewed_by).');
            }

            // Guard self-review: reviewed_by tidak boleh sama dengan author_id
            if ($article->status === 'published' && $article->author_id && $article->reviewed_by === $article->author_id) {
                throw new InvalidArgumentException('Admin tidak dapat menyetujui/memublikasikan artikel buatannya sendiri.');
            }
        });
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'article_tag');
    }

    public function media(): HasMany
    {
        return $this->hasMany(Media::class);
    }
}
