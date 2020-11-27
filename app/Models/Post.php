<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use Sluggable;

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

    function isEmpty()
    {
        return empty($this->getAttribute('id'));
    }

    function tags()
    {
        return $this->belongsToMany('App\Models\Tag');
    }

    function meta()
    {
        return $this->hasOne('App\Models\PostMeta');
    }


    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

}
