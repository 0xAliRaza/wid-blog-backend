<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;



    public function before($user, $ability)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can index all models.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function index(User $user)
    {
        if($user->isAdmin() || $user->isWriter()) {
            return true;
        };
        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\User  $model
     * @return bool
     */
    public function view(User $user, User $model)
    {


        if ($user->isAdmin() && !$model->isSuperAdmin()) {
            return true;
        }
        if ($user->isWriter() && $model->isWriter()) {
            return $user->id === $model->id;
        }
        return false;
    }

    /**
     * Determine whether the user can store models.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function store(User $user, User $model)
    {
        return $user->isAdmin() && !$model->isSuperAdmin();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\User  $model
     * @return bool
     */
    public function update(User $user, User $model)
    {
        if ($user->isAdmin() && !$model->isSuperAdmin()) {
            return true;
        }
        if ($user->isWriter() && $model->isWriter()) {
            return $user->id === $model->id;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\User  $model
     * @return bool
     */
    public function delete(User $user, User $model)
    {
        if ($user->isAdmin() && !$model->isSuperAdmin()) {
            return true;
        }
        if ($user->isWriter() && $model->isWriter()) {
            return $user->id === $model->id;
        }
        return false;
    }
}
