<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Shamaseen\Repository\Utility\Model as Model;

/**
 * Class Blog.
 */
class Blog extends Model
{
    /*
    * Fill in your fillables here
    */

    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'slug',
        'image',
    ];

    // Only fetch own posts in controller, but define relation for comments
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
