<?php

namespace App\Policies;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TagPolicy
{
    use HandlesAuthorization;



    public function before($user, $ability)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
    }


    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        if ($user->isAdmin()) {
            return true;
        }
        if ($user->isWriter()) {
            return true;
        }
        return false;
    }


    /**
     * Determine whether the user can change models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function change(User $user)
    {
        if ($user->isAdmin()) {
            return true;
        }
        return false;
    }
}
