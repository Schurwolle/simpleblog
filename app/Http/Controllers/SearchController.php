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
            $string = strstr($query, "/") ? "#" : "/";

            if(strstr($query, '['))
            {
                $subStrings = explode('[', $query);
                
                for($i = 0; $i < count($subStrings); $i++)
                {
                    $string .= $subStrings[$i]."\[";
                }
                $string = substr($string, 0, strlen($string)-2);
            } else {

    		    $string .= $query;
            }

            $string .= strstr($query, "/") ? "#i" : "/i";

            $article->body = preg_replace($string, "<span style='background-color:#FFFF00'>\$0</span>", strip_tags(html_entity_decode($article->body, ENT_QUOTES)));

            $article->title = preg_replace($string, "<span style='background-color:#FFFF00'>\$0</span>", strip_tags(html_entity_decode($article->title, ENT_QUOTES)));

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
