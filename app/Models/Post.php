<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Post extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'content',
        'image',
        'status',
        'is_featured',
        'reading_time',
        'views',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'views' => 'integer',
        'reading_time' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = static::generateUniqueSlug($post->title);
            }
            if (empty($post->reading_time) && !empty($post->content)) {
                $post->reading_time = static::calculateReadingTime($post->content);
            }
        });

        static::updating(function ($post) {
            if ($post->isDirty('title') && empty($post->slug)) {
                $post->slug = static::generateUniqueSlug($post->title, $post->id);
            }
            if ($post->isDirty('content')) {
                $post->reading_time = static::calculateReadingTime($post->content);
            }
        });
    }

    /**
     * All comments (both top-level and replies)
     */
    public function allComments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Generate unique slug from title
     */
    public static function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug ?: 'post';
        $count = 1;

        while (static::where('slug', $slug)->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = "{$baseSlug}-{$count}";
            $count++;
        }

        return $slug;
    }

    /**
     * Category Relationship (One-to-Many inverse)
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Tags Relationship (Many-to-Many)
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'post_tag')->withTimestamps();
    }

    /**
     * Top-level approved comments with nested replies
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)
            ->whereNull('parent_id')
            ->where('status', 'approved')
            ->latest();
    }

    /**
     * All comments count (approved top-level + replies)
     */
    public function allApprovedCommentsCount(): int
    {
        return $this->hasMany(Comment::class)
            ->where('status', 'approved')
            ->count();
    }

    /**
     * Reactions Relationship
     */
    public function reactions(): HasMany
    {
        return $this->hasMany(Reaction::class);
    }

    public function reactionCount(string $type): int
    {
        return $this->reactions()->where('type', $type)->count();
    }

    public function totalReactionsCount(): int
    {
        return $this->reactions()->count();
    }

    public function hasUserReacted(string $type, ?string $sessionId = null): bool
    {
        if (!$sessionId) {
            return false;
        }

        return $this->reactions()
            ->where('type', $type)
            ->where('session_id', $sessionId)
            ->exists();
    }

    /**
     * Calculate reading time in minutes
     */
    public static function calculateReadingTime($content)
    {
        $words = str_word_count(strip_tags($content));
        return max(1, (int) ceil($words / 200));
    }

    /**
     * Scopes
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopePopular($query)
    {
        return $query->orderByDesc('views');
    }

    /**
     * Trending Algorithm: views + (reactions * 5) + (comments * 3)
     */
    public function scopeTrending($query)
    {
        return $query->published()
            ->withCount(['reactions', 'comments'])
            ->orderByRaw('(views + (reactions_count * 5) + (comments_count * 3)) DESC');
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeByTag($query, $tagId)
    {
        return $query->whereHas('tags', function ($q) use ($tagId) {
            $q->where('tags.id', $tagId);
        });
    }
}
