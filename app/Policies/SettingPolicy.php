<?php

namespace App\Policies;

use App\Models\User;
use App\Setting;
use Illuminate\Auth\Access\HandlesAuthorization;

class SettingPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        if ($user->isAdmin() || $user->isSuperAdmin()) {
            return true;
        }
        return false;
    }
}
