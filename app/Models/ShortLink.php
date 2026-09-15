<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShortLink extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'original_url',
        'short_code',
        'clicks_count',
        'last_visited_at'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
