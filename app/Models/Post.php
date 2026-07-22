<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    protected $fillable = [

        'title',
        'content',
        'image',

        'status',

        'is_featured',

        'reading_time',

        'views',

    ];

    protected $casts = [

        'is_featured' => 'boolean',

    ];

    /**
     * Calculate reading time
     */
    public static function calculateReadingTime($content)
    {
        $words = str_word_count(strip_tags($content));

        return max(1, ceil($words / 200));
    }

    /**
     * Featured Posts Scope
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Published Posts Scope
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Draft Posts Scope
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Popular Posts Scope
     */
    public function scopePopular($query)
    {
        return $query->orderByDesc('views');
    }
}
