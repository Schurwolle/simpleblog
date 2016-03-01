<?php

namespace App\Repositories;

use App\article;
use App\User;


class ArticleRepository 
{


	public function showPublished()
	{
		return article::latest('published_at')->published()->paginate(5);
	}

	public function showUnpublished()
	{
		return article::latest('published_at')->unpublished()->paginate(5);
	}

	public function forUser(User $user)
	{
		return $user->articles()->latest('published_at')->published()->paginate(5);
	}

	public function forUserUnpublished($user)
	{
		return $user->articles()->latest('published_at')->unpublished()->paginate(5);
	}

	public function forTag($tag)
	{
		return $tag->articles()->latest('published_at')->published()->paginate(5);
	}

	public function forQuery($query)
	{
		return article::where('body', 'LIKE', '%'. $query. '%')->latest('published_at')->published()->paginate(5);
	}
}