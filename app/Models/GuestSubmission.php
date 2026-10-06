<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuestSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'reviewed_by',
        'article_id',
        'name',
        'instansi',
        'email',
        'title',
        'content',
        'status',
        'rejection_reason',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }
}
