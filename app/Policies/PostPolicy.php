<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;


    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user, Request $request)
    {
        if ($user->id === (int) $request->user_id) {
            return true;
        }
        return $this->deny('You are not allowed to post as someone else.');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Post  $post
     * @return mixed
     */
    public function update(User $user, Post $post, Request $request)
    {
        if ($user->id === (int) $request->user_id && (int) $post->user_id === $user->id) {
            return true;
        }
        return $this->deny('You are not allowed to update someone else\'s post.');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Post  $post
     * @return mixed
     */
    public function delete(User $user, Post $post)
    {
        if ($user->id === $post->user_id) {
            return true;
        }
        return $this->deny('You are not allowed to delete someone else\'s post.');
    }
}
