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

	public function forUserFavorited($user)
	{
		return $user->favorites()->latest('published_at')->published()->paginate(5);
	}

	public function forTag($tag)
	{
		return $tag->articles()->latest('published_at')->published()->paginate(5);
	}

	public function forQuery($query)
	{
		return article::where('body', 'LIKE', '%'. $query. '%')->orWhere('title', 'LIKE', '%'. $query. '%')->latest('published_at')->published()->get();
	}

	public function showSorted()
	{
		$articles = article::get();

		$articlesSorted = $articles->sortByDesc(function ($article, $key){
            return count($article->comments);
        });

        return $articlesSorted->slice(0,10);
	}

	public function showLatest()
	{
		return article::latest('published_at')->published()->first();
	}
}