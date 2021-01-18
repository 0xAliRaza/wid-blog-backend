<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;


    public function before($user, $ability)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
    }


    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {

        if ($user->isAdmin() || $user->isWriter()) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Post  $post
     * @return mixed
     */
    public function update(User $user, Post $post)
    {

        // denying if post is owned by superadmin
        if ($post->user->isSuperAdmin() && !$user->isSuperAdmin()) {
            return false;
        }

        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isWriter()) {
            return $user->id === $post->user_id;
        }


        return false;
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
        // denying if post is owned by superadmin
        if ($post->user->isSuperAdmin() && !$user->isSuperAdmin()) {
            return false;
        }

        if ($user->isAdmin()) {
            return true;
        }

        // writer can only delete their post
        if ($user->isWriter()) {
            return $user->id === $post->user_id;
        }

        return false;
    }

    /**
     * Determine whether the user can view model.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function view(User $user, Post $post)
    {

        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isWriter()) {
            return $user->id === $post->user_id;
        }

        return false;
    }

    /**
     * Determine whether the user can index model.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function index(User $user)
    {

        if ($user->isAdmin() || $user->isWriter()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can index all models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function indexAll(User $user)
    {

        if ($user->isAdmin()) {
            return true;
        }

        return false;
    }
}
