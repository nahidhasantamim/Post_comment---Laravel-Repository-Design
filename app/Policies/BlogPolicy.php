<?php

namespace App\Policies;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BlogPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability, $model)
    {
        if (is_string($model)) {
            return true;
        }
        return null;
    }


    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Blog $blog): bool
    {
        return $user->id === $blog->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Blog $blog): bool
    {
        return $user->id === $blog->user_id;
    }

    public function delete(User $user, Blog $blog): bool
    {
        return $user->id === $blog->user_id;
    }
}
