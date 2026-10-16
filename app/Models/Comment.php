<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    protected $fillable = [
        'post_id',
        'parent_id',
        'author_name',
        'author_email',
        'content',
        'status',
        'is_admin',
    ];

    protected $casts = [
        'is_admin' => 'boolean',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    /**
     * Approved nested replies
     */
    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id')
            ->where('status', 'approved')
            ->oldest();
    }

    public function allReplies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Gravatar avatar URL helper
     */
    public function avatarUrl(int $size = 80): string
    {
        $hash = md5(strtolower(trim($this->author_email)));
        return "https://www.gravatar.com/avatar/{$hash}?s={$size}&d=identicon";
    }
}
