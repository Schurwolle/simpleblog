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

            $exploded = preg_split("/(<|>)/", html_entity_decode($article->body, ENT_QUOTES), null, PREG_SPLIT_DELIM_CAPTURE);

            $exploded[0] = preg_replace($string, "<span style='background-color:#FFFF00'>\$0</span>", $exploded[0]);

            for($i = 1; $i < count($exploded); $i++)
            {
                if ($exploded[$i] != "<" && $exploded[$i] != ">" && ($exploded[$i-1] != "<" || $exploded[$i+1] != ">"))
                {
                    $exploded[$i] = preg_replace($string, "<span style='background-color:#FFFF00'>\$0</span>", $exploded[$i]);
                }
            }
            $article->body = "";
            for($i = 0; $i < count($exploded); $i++)
            {
                if(strstr($exploded[$i], "<span style='background-color:#FFFF00'>"))
                {
                    for($j = 1; $j < $i-3; $j++)
                    {   
                        if($exploded[$j] != "<" && $exploded[$j] != ">" && $exploded[$j] != "" && ($exploded[$j-1] != "<" || $exploded[$j+1] != ">"))
                        {
                            $article->body = "...";
                            break;
                        }
                    }
                    $limit = $i;
                    for($m = 1, $n = 2; $n < $i; $m += 4, $n += 4)
                    {
                        if($i > $m && $exploded[$i+$n] != "/".explode(" ", $exploded[$i-$n])[0])
                        {
                            break;
                        }
                        $limit = $i-$n-1;
                    }
                    for($j = 0; $j < $limit; $j++)
                    {
                        unset($exploded[$j]);
                    }
                    $article->body .= implode($exploded);

                    if (strpos($article->body, "<span style='background-color:#FFFF00'>") > 300)
                    {
                        $article->body = "...".substr($article->body, strpos($article->body, "<span style='background-color:#FFFF00'>"));
                    }
                    break;
                }
            }
            

            $article->title = preg_replace($string, "<span style='background-color:#FFFF00'>\$0</span>", $article->title);

    		// if(strpos($article->body, "<span style='background-color:#FFFF00'>")) 
    		// {
      //           if(strlen($article->body) - strpos($article->body, "<span style='background-color:#FFFF00'>") < 300)
      //           {
      //               if(strpos($article->body, "<span style='background-color:#FFFF00'>") - 300 > 0) 
      //               {
      //                   $article->body = substr($article->body, strpos($article->body, "<span style='background-color:#FFFF00'>") - 300);

      //                   $article->body = "..." .$article->body;
      //               }
      //           } else {

	    	// 	    $article->body = substr($article->body, strpos($article->body, "<span style='background-color:#FFFF00'>"));

      //               $article->body = "..." .$article->body;
      //           }
	    	// }
    	}

    	return view('articles.headings.search', compact('articles', 'query', 'num'));
    }
}
