<?php

namespace App\Repositories;


class CommentRepository
{

	public function forArticle($article)
	{
		return $article->comments()->latest('created_at')->get();
	}

}