<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class News extends Model
{
    use HasFactory;

    protected $table = 'news';

    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'featured_image',
        'category',
        'tags',
        'author_id',
        'status',
        'featured',
        'views',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'featured'     => 'boolean',
            'views'        => 'integer',
            'published_at' => 'datetime',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function scopePublished(Builder $q): Builder
    {
        return $q->where('status', 'published')->whereNotNull('published_at');
    }

    public function scopeFeatured(Builder $q): Builder
    {
        return $q->where('featured', true);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
