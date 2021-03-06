<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use Sluggable;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'featured' => 'boolean', 'published' => 'boolean'
    ];


    protected $attributes = ['html' => null, 'custom_excerpt' => null, 'featured' => 0, 'featured_image' => null, 'published_at' => null];


    /**
     * The attributes that should be appended to model.
     *
     * @var array
     */
    protected $appends = [
        'meta_title', 'meta_description', 'first_tag'
    ];




    /**
     * The attributes that should be hidden.
     *
     * @var array
     */
    protected $hidden = [
        'meta', 'user_id'
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
        return $this->belongsTo('App\Models\User', 'author_id', 'id');
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
        $firstTag = $this->tags()->oldest()->first();
        !empty($firstTag) ? $firstTag->makeHidden(['id', 'created_at', 'updated_at']) : null;
        return $firstTag;
    }

    function getMetaTitleAttribute()
    {
        return !empty($this->meta) ? $this->meta->title : null;
    }

    function getMetaDescriptionAttribute()
    {
        return !empty($this->meta) ? $this->meta->description : null;
    }


    function isPublished(): bool
    {
        return $this->published;
    }

    function isPage(): bool
    {
        return $this->type === PostTypes::Page;
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
