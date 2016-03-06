<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'facebook_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function articles()
    {
        return $this->hasMany('App\article');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    public function isAdmin()
    {
        return $this->admin;
    }
}

