<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    function posts()
    {
        return $this->hasMany('App\Models\Post');
    }
}
