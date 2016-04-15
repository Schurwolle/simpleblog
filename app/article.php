<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class article extends Model
{
    protected $fillable = ['title', 'slug', 'body', 'published_at'];

    protected $dates = ['published_at'];

    public function scopePublished($query)
    {
    	$query->where('published_at', '<=', Carbon::now());
    }

    public function scopeUnpublished($query)
    {
        $query->where('published_at', '>', Carbon::now());
    }

    public function setPublishedAtAttribute($date)
    {
    	$this->attributes['published_at'] = Carbon::createFromFormat('Y-m-d', $date);
    }

    public function getPublishedAtAttribute($date)
    {
        return (new Carbon($date))->format('Y-m-d');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function favoritedBy()
    {
        return $this->belongsToMany('App\User', 'favorites')->withTimestamps();
    }

    public function tags()
    {
        return $this->belongsToMany('App\Tag')->withTimestamps();
    }

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    public function hasCommentFromUser($user_id)
    {
        return $this->comments()->where('user_id', $user_id)->count() > 0;
    }

    public function getTagListAttribute()
    {
        return $this->tags->lists('id')->all();
    }
}
