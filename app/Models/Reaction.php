<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reaction extends Model
{
    protected $fillable = [
        'post_id',
        'type',
        'ip_address',
        'session_id',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
