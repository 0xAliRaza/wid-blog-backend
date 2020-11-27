<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostMeta extends Model
{
    function post()
    {
        return $this->hasOne('App\Models\Post');
    }
}
