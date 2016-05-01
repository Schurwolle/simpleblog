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
		$allArticles = article::published()->get();
		$articles = collect();
		foreach($allArticles as $article)
		{
			if(stristr(strip_tags(html_entity_decode($article->body)), $query) || stristr($article->title, $query))
			{
				$articles[] = $article;
			}
		}

		$articlesSorted = $articles->sortByDesc(function($article, $key) use ($query){
			return substr_count(strtolower(strip_tags(html_entity_decode($article->body))), strtolower($query));
		});
		return $articlesSorted;

	}

	public function showSorted()
	{
		$articles = article::published()->get();

		$articlesSorted = $articles->sortByDesc(function ($article, $key){
            return $article->visits;
        });

        return $articlesSorted->slice(0,10);
	}

	public function showLatest($article = 0)
	{
		return article::latest('published_at')->published()->where('id', '!=', $article)->first();
	}

	public function showExcept($article)
	{
		return article::latest('published_at')->published()->where('id', '!=', $article)->paginate(5);
	}
}