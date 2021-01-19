<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostMeta extends Model
{
    protected $fillable = [
        'title', 'description'
    ];
    function post()
    {
        return $this->hasOne('App\Models\Post');
    }
}
