<?php

namespace App\Models;

use DOMDocument;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
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
            if ($article->status === 'published' && ! $article->reviewed_by) {
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

    /**
     * Pecah isi artikel menjadi dua bagian setelah paragraf ke-N, untuk menyisipkan
     * iklan native (PRD 4C.3). Bila paragraf kurang dari N, bagian kedua kosong
     * sehingga iklan tampil setelah isi.
     *
     * @return array{0: string, 1: string}
     */
    public function contentSegments(int $afterParagraph = 3): array
    {
        $html = (string) $this->content;

        if (trim($html) === '') {
            return ['', ''];
        }

        $previousErrorSetting = libxml_use_internal_errors(true);
        $document = new DOMDocument;
        $document->loadHTML('<?xml encoding="UTF-8"><div>'.$html.'</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();
        libxml_use_internal_errors($previousErrorSetting);

        $root = $document->getElementsByTagName('div')->item(0);

        if ($root === null) {
            return [$html, ''];
        }

        $before = '';
        $after = '';
        $paragraphCount = 0;

        foreach ($root->childNodes as $node) {
            if ($paragraphCount >= $afterParagraph) {
                $after .= $document->saveHTML($node);

                continue;
            }

            $before .= $document->saveHTML($node);

            if ($node->nodeName === 'p') {
                $paragraphCount++;
            }
        }

        return trim($after) === '' ? [$html, ''] : [$before, $after];
    }

    /**
     * Isi artikel sebagai teks polos satu baris (tanpa tag & entity HTML).
     */
    protected function plainText(): Attribute
    {
        return Attribute::get(function (): string {
            $text = html_entity_decode(strip_tags((string) $this->content), ENT_QUOTES | ENT_HTML5, 'UTF-8');

            return trim(preg_replace('/\s+/u', ' ', $text));
        })->shouldCache();
    }

    /**
     * Ringkasan ±160 karakter untuk kartu, hero, dan meta description.
     */
    protected function excerpt(): Attribute
    {
        return Attribute::get(fn (): string => Str::limit($this->plain_text, 160))->shouldCache();
    }

    /**
     * Perkiraan waktu baca dalam menit (±200 kata per menit).
     */
    protected function readMinutes(): Attribute
    {
        return Attribute::get(fn (): int => max(1, (int) ceil(str_word_count($this->plain_text) / 200)))->shouldCache();
    }

    /**
     * Jumlah pembaca ringkas berformat Indonesia, mis. "980", "1,2 rb", "3,4 jt".
     */
    protected function viewsLabel(): Attribute
    {
        return Attribute::get(function (): string {
            $views = (int) $this->views_count;

            return match (true) {
                $views >= 1_000_000 => number_format($views / 1_000_000, 1, ',', '.').' jt',
                $views >= 1_000 => number_format($views / 1_000, 1, ',', '.').' rb',
                default => number_format($views, 0, ',', '.'),
            };
        });
    }

    /**
     * Apakah artikel berasal dari kiriman tulisan publik (penulis tanpa akun).
     */
    protected function isGuestContribution(): Attribute
    {
        return Attribute::get(fn (): bool => filled($this->guest_author_name));
    }

    /**
     * Nama yang tampil di byline: penulis tamu, penulis staf, atau nama redaksi.
     */
    protected function bylineName(): Attribute
    {
        return Attribute::get(function (): string {
            if ($this->is_guest_contribution) {
                return $this->guest_author_name;
            }

            return $this->author?->name ?? 'Redaksi '.config('portal.name');
        });
    }

    /**
     * Keterangan di bawah nama byline: "Kontributor · Instansi" atau "Redaksi B.Today".
     */
    protected function bylineLabel(): Attribute
    {
        return Attribute::get(function (): string {
            if (! $this->is_guest_contribution) {
                return 'Redaksi '.config('portal.name');
            }

            return filled($this->guest_author_instansi)
                ? 'Kontributor · '.$this->guest_author_instansi
                : 'Kontributor';
        });
    }

    /**
     * Inisial (maks. 2 huruf) untuk avatar byline.
     */
    protected function bylineInitials(): Attribute
    {
        return Attribute::get(function (): string {
            return Str::of($this->byline_name)
                ->explode(' ')
                ->filter()
                ->take(2)
                ->map(fn (string $word): string => Str::upper(Str::substr($word, 0, 1)))
                ->implode('');
        });
    }
}
