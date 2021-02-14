<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use Sluggable;


    protected $attributes = ['html' => null, 'custom_excerpt' => null, 'featured' => 0, 'featured_image' => null, 'published_at' => null, 'page' => false];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'featured' => 'boolean', 'published' => 'boolean', 'page' => 'boolean'
    ];


    /**
     * The attributes that should be appended to model.
     *
     * @var array
     */
    protected $appends = [
        'published', 'meta_title', 'meta_description', 'first_tag'
    ];




    /**
     * The attributes that should be hidden.
     *
     * @var array
     */
    protected $hidden = [
        'type', 'type_id', 'meta', 'user_id'
    ];



    /**
     * The relationships that should eager load.
     *
     * @var array
     */
    protected $with = [
        'user'
    ];


    /**
     * The columns that should be mutated to date type.
     *
     * @var array
     */
    protected $dates = ['published_at'];





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

    /**
     * Get the associated user(author) model
     *
     * @return App\Models\User
     */
    function author()
    {
        return $this->belongsTo('App\Models\User', 'author', 'id');
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


    function getFirstTagAttribute()
    {
        return $this->tags()->oldest()->first();
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

    function isPage(): bool
    {
        return $this->page;
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
