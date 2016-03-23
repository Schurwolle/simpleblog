<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\article;
use App\Repositories\ArticleRepository;

class SearchController extends Controller
{

	protected $articles;

	public function __construct(ArticleRepository $articles)
	{
		$this->middleware('auth');

		$this->articles = $articles;
	}

    public function search(Request $request)
    {
    	$query = $request->input('search');

    	$articles = $this->articles->forQuery($query);

    	$num = $articles->count();
    	
    	foreach ($articles as $article){

    		$article->body = preg_replace("/".$query."/i", "<span style='background-color:#FFFF00'>\$0</span>", strip_tags($article->body));

    		if(strpos($article->body, "<span style='background-color:#FFFF00'>")) 
    		{
	    		$article->body = substr($article->body, strpos($article->body, "<span style='background-color:#FFFF00'>"));

	    		$article->body = "..." .$article->body;
	    	}

    		$article->title = preg_replace("/".$query."/i", "<span style='background-color:#FFFF00'>\$0</span>", $article->title);
    	}

    	return view('articles.headings.search', compact('articles', 'query', 'num'));
    }
}
