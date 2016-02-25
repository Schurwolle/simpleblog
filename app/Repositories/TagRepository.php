<?php

namespace App\Repositories;

use App\Tag;

class TagRepository
{
	public function lists()
	{
		return Tag::lists('name', 'id'); 
	}
}