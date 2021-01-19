<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use Sluggable;


    protected $attributes = ['html' => null, 'custom_excerpt' => null, 'featured' => 0, 'featured_image' => null];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'featured' => 'boolean', 'published' => 'boolean'
    ];


    /**
     * The attributes that should be appended to model.
     *
     * @var array
     */
    protected $appends = [
        'published', 'meta_title', 'meta_description'
    ];




    /**
     * The attributes that should be hidden.
     *
     * @var array
     */
    protected $hidden = [
        'type', 'type_id', 'meta', 'user'
    ];






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


    function getMetaTitleAttribute()
    {
        return !empty($this->meta) ? $this->meta->title : null;
    }

    function getMetaDescriptionAttribute()
    {
        return !empty($this->meta) ? $this->meta->description : null;
    }

    function getPublishedAttribute()
    {
        return $this->isPublished();
    }

    function isPublished(): bool
    {
        return !empty($this->type->tag) && $this->type->tag === "published";
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
