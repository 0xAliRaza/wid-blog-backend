<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    function user()
    {
        return $this->hasOne(User::class);
    }
}
