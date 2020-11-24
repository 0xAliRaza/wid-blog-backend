<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['name', 'slug', 'description'];
    protected $hidden = ['pivot'];

    function isEmpty()
    {
        return empty($this->getAttribute('id'));
    }
}
