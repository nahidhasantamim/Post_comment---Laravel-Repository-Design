<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Shamaseen\Repository\Utility\Model as Model;

/**
 * Class Comment.
 */
class Comment extends Model
{
    /*
    * Fill in your fillables here
    */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'blog_id',
        'body',
    ];

    public function blog(): BelongsTo
    {
        return $this->belongsTo(Blog::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
