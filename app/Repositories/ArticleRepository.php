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

	public function forQuery($query, $query_words)
	{
		$allArticles = article::published()->get();
		$articles = collect();
		$words_num = count($query_words);
		foreach($allArticles as $article)
		{
			$ind = 0;
			foreach($query_words as $word)
			{
				if(stristr(strip_tags(html_entity_decode($article->body, ENT_QUOTES)), $word) || stristr($article->title, $word))
				{
					$ind += 1;
				}
			}
			if ($ind == $words_num)
			{
				$articles[] = $article;
				continue;
			}
			if($article->tags->contains('name', strtolower(implode($query_words))))
			{
				$articles[] = $article;
				continue;
			}
			foreach($query_words as $word)
			{
				if($article->tags->contains('name', strtolower($word))) 
				{
					$articles[] = $article;
					continue(2);
				} 
			}
			foreach($article->comments as $comment)
			{
				$ind = 0;
				foreach($query_words as $word)
				{
					if(stristr($comment->body, $word))
					{
						$ind +=1;
					}
				}
				if($ind == $words_num)
				{
					$articles[] = $article;
					continue(2);
				}
			}	
		}

		$articlesSorted = $articles->sortByDesc(function($article, $key) use ($query_words, $query){
			$count = substr_count(strtolower($article->title), strtolower($query)) * 1000000;
			$count += substr_count(strtolower(strip_tags(html_entity_decode($article->body))), strtolower($query)) * 10000;
			if($article->tags->contains('name', strtolower(implode($query_words))))
			{
				$count += 10000;
			}
			
			foreach($query_words as $word)
			{
				$count += substr_count(strtolower($article->title), strtolower($word)) * 1000 + substr_count(strtolower(strip_tags(html_entity_decode($article->body))), strtolower($word));
				if($article->tags->contains('name', strtolower($word)))
				{
					$count += 10;
				}
				foreach($article->comments as $comment)
				{
					$count += substr_count(strtolower($comment->body), strtolower($word));
				}
			}
			return $count;
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