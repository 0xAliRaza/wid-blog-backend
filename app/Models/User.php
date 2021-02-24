<?php

namespace App\Models;

use App\Models\Role;
use App\Models\UserRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use Sluggable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'role_id', 'roleModel', 'email_verified_at', 'email', 'created_at', 'updated_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The attributes that should be appended.
     *
     * @var array
     */
    protected $appends = [
        'role'
    ];

    protected $with = ['roleModel'];



    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }


    function setRoleAttribute(Role $val)
    {
        $this->attributes['role_id'] = $val->id;
        $this->roleModel->slug = $val->slug;
    }
    function getRoleAttribute()
    {
        return $this->roleModel->slug;
    }

    function roleModel()
    {
        return $this->belongsTo('App\Models\Role', 'role_id', 'id');
    }

    function isSuperAdmin(): bool
    {
        return $this->role === UserRoles::SuperAdmin;
    }

    function isAdmin(): bool
    {
        return $this->role === UserRoles::Admin;
    }

    function isWriter(): bool
    {
        return $this->role === UserRoles::Writer;
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
                'source' => 'name'
            ]
        ];
    }
}
