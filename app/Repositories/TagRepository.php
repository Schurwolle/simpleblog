<?php

namespace App\Repositories;

use App\Tag;

class TagRepository
{
	public function lists()
	{
		return Tag::lists('name', 'id'); 
	}

	public function showSorted()
	{
		$tags = Tag::get();

        $tagsSorted = $tags->sortByDesc(function ($tag, $key){
                return count($tag->articles);
        });

        return $tagsSorted;
    }
}