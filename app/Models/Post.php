<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /**
     * Get the associated type model
     *
     * @return App\Models\Type
     */
    function type()
    {
        return $this->belongsTo('App\Models\Type');
    }

    /**
     * Get the associated user model
     *
     * @return App\Models\User
     */
    function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
