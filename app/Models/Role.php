<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    function user()
    {
        return $this->belongsTo('App\Models\User', 'role', 'tag');
    }
}
