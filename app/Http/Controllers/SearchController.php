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
    	
    	foreach ($articles as $article)
        {   
            if(strstr($query, "/"))
            {
                $article->body = preg_replace("#".$query."#i", "<span style='background-color:#FFFF00'>\$0</span>", strip_tags(html_entity_decode($article->body, ENT_QUOTES)));

                $article->title = preg_replace("#".$query."#i", "<span style='background-color:#FFFF00'>\$0</span>", $article->title);
            } else {
    		    $article->body = preg_replace("/".$query."/i", "<span style='background-color:#FFFF00'>\$0</span>", strip_tags(html_entity_decode($article->body, ENT_QUOTES),'<h2><h3><h4><h5>'));
                
                $article->title = preg_replace("/".$query."/i", "<span style='background-color:#FFFF00'>\$0</span>", $article->title);
            }
    		if(strpos($article->body, "<span style='background-color:#FFFF00'>")) 
    		{
                if(strlen($article->body) - strpos($article->body, $query) < 300)
                {
                    $article->body = substr($article->body, strpos($article->body, "<span style='background-color:#FFFF00'>") - 300);
                } else {
	    		    $article->body = substr($article->body, strpos($article->body, "<span style='background-color:#FFFF00'>"));
                }

                if(strlen($article->body) > 300)
                {
                    $article->body = "..." .$article->body;
                }
	    	}
    	}

    	return view('articles.headings.search', compact('articles', 'query', 'num'));
    }
}
