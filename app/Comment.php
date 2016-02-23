<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
	protected $guarded =  [];

    public function article()
    {
    	return $this->belongsTo('App\article');
    }

    public function user()
    {
    	return $this->belongsTo('App\User');
    }
}
