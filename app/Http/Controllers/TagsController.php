<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Tag;
use Carbon\Carbon;
use App\Repositories\ArticleRepository;

class TagsController extends Controller
{
	protected $articles;


	public function __construct(ArticleRepository $articles)
	{
		$this->articles = $articles;
	}



    public function show(Tag $tag)
    {
    	$articles = $this->articles->forTag($tag);

    	return view('articles.headings.tags', compact('articles', 'tag'));
    }
}
