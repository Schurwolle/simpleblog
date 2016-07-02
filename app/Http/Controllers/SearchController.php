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
    	$query = preg_replace('/ {2,}/',' ', trim($request->input('search')));
        $query_words = explode(" ", $query);

    	$articles = $this->articles->forQuery($query, $query_words);
    	$num = $articles->count();

        array_unshift($query_words, $query);
        $string_words = $this->makeString($query_words);
        array_shift($query_words);

    	$comments = array();
    	foreach ($articles as $article)
        {   
            $article->title = $this->mark($string_words, $article->title);
            $article->body = $this->hasAllWords($string_words, $article->body);
            $article->body = $this->findQuery($article->body, $query);
            $comments = $this->pickComments($article, $query_words, $string_words, $comments, $query);
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
        $query_link = urlencode($query);
        $query_link = str_replace('.', '%tačka%', $query_link);
        foreach($query_words as &$word)
        {
            $word = preg_quote(htmlentities($word));
        }
        return view('articles.headings.search', compact('articles', 'query', 'query_words', 'query_link', 'num', 'comments'));
    }

    public function show(article $article, $query)
    {
        $query = str_replace('%tačka%', '.', $query);
        $query = urldecode($query);
        $query_words = explode(" ", $query);
        array_unshift($query_words, $query);
        $string_words = $this->makeString($query_words);
        array_shift($query_words);
        $article->title = $this->mark($string_words, $article->title);
        $article->body = $this->hasAllWords($string_words, $article->body);
        $article->comments = $this->pickComments($article, $query_words, $string_words);
        foreach($query_words as &$word)
        {
            $word = preg_quote(htmlentities($word));
        }
        return redirect('articles/'.$article->slug)
                                    ->with('article', $article)
                                    ->with('query', $query)
                                    ->with('query_words', $query_words);
    }   

    private function mark($string_words, $body)
    {
        foreach($string_words as $string)
        {
            $exploded = preg_split("/(<|>)/", $body, null, PREG_SPLIT_DELIM_CAPTURE);

            for($i = 0; $i < count($exploded); $i++)
            {
                if($exploded[$i] != "<" && $exploded[$i] != ">")
                {
                    if ($i == 0 || ($exploded[$i-1] != "<" || isset($exploded[$i+1]) && $exploded[$i+1] != ">"))
                    {
                        if($i == 0 || $i == 1 || $exploded[$i-2] != "span style='background-color:#FFFF00'")
                        {
                            $exploded[$i] = preg_replace($string, "<span style='background-color:#FFFF00'>\$0</span>", $exploded[$i]);
                        }
                    }
                }
            }
            $body = implode($exploded);
        }
        return $body;
    }

    private function findQuery($body, $query)
    {
        $exploded = preg_split("/(<|>)/", $body, null, PREG_SPLIT_DELIM_CAPTURE);
        for($i = 0; $i < count($exploded); $i++)
        {
            if($exploded[$i] == "span style='background-color:#FFFF00'" && strcasecmp($exploded[$i+2], $query) == 0)
            {
                $body = $this->cropBody($exploded, $i);
                return $body;
            }
        }
        for($i = 0; $i < count($exploded); $i++)
        {
            if($exploded[$i] == "span style='background-color:#FFFF00'")
            {
                $body = $this->cropBody($exploded, $i);
                return $body;
            }
        }
        return $body;
    }
    private function cropBody($exploded, $i)
    {
            $body = "";
            $x = $i+2;
            for($m = 1, $n = 2; $n < $x; $m += 4, $n += 4)
            {
                if($x < $m || $x+$n > count($exploded) || $exploded[$x+$n] != "/".explode(" ", $exploded[$x-$n])[0] || str_word_count($exploded[$x-$n+2]) > 70)
                {
                    if(str_word_count($exploded[$x-$n+2]) > 70)
                    {
                        $n-=4;
                    }
                    break;
                }
            }
            $limit = $x-$n;
            for($j = 1; $j < $limit; $j++)
            {
                if($exploded[$j] != "<" && $exploded[$j] != ">" && $exploded[$j] != "" && ($exploded[$j-1] != "<" ||     $exploded[$j+1] != ">"))
                {
                    $body = "...";
                    break;
                }
            }
            if ($body == "...")
            {
                $num_of_words = 0;
                while($num_of_words < 80 && $limit > 0)
                {
                    if(str_word_count($exploded[$limit-1]) > 70)
                    {
                        break;
                    }
                    $num_of_words = 0;
                    for($k = $limit; $k < count($exploded); $k++)
                    {
                        if($exploded[$k] != "<" && $exploded[$k] != ">")
                        {
                            $num_of_words = $num_of_words + str_word_count($exploded[$k]);
                        }
                    }
                    $limit--;
                }
                for($j = 0; $j < $limit; $j++)
                {
                    unset($exploded[$j]);
                }
                if($exploded[$limit] == ">") 
                {
                    unset($exploded[$limit]);
                }
            }
            $body .= implode($exploded);

            // if (strpos($body, "<span style='background-color:#FFFF00'>") > 300)
            // {
            //     $body = "...".substr($body, strpos($body, "<span style='background-color:#FFFF00'>"));
            // }
            return $body;
    }

    private function pickComments($article, $query_words, $string_words, $comments = "null", $query = "null")
    {
        foreach($article->comments->sortByDesc('created_at') as $comment)
            {   
                $ind = 0;
                foreach($query_words as $word)
                {
                    if(stristr($comment->body, $word))
                    {
                        $ind += 1;
                    }
                }
                if($ind == count($query_words))
                {
                    $comment->body = $this->markComments($string_words, $comment->body);
                    if($comments != "null")
                    {
                        $comment->body = $this->cropComment($comment->body, $query);
                        $comments[$article->id][] = $comment;
                    }                        
                }
            }
        if($comments != "null")
        {
            return $comments;
        }
        return $article->comments->sortByDesc('created_at');
    }

    private function markComments($string_words, $body)
    {

        foreach($string_words as $string)
        {
            $exploded = preg_split("/(<span style='background-color:#FFFF00'>|<\/span>)/", $body, null, PREG_SPLIT_DELIM_CAPTURE);

            for($i = 0; $i < count($exploded); $i++)
            {
                if($exploded[$i] != "<span style='background-color:#FFFF00'>")
                {
                    if($i == 0 || $exploded[$i-1] != "<span style='background-color:#FFFF00'>")
                    {
                        if($i == 0 || $i == 1 || $exploded[$i] != "</span>" || ($exploded[$i] == "</span>" && $exploded[$i-2] != "<span style='background-color:#FFFF00'>"))
                        {
                            $exploded[$i] = preg_replace($string, "<span style='background-color:#FFFF00'>\$0</span>", $exploded[$i]);
                        }
                    }
                }
            }
            $body = implode($exploded);
        }
        return $body;
    }
    private function cropComment($body, $query)
    {
        if (str_word_count(strip_tags($body)) > 60)
        {
            if(stripos($body, "<span style='background-color:#FFFF00'>".$query))
            {
                $body = "...".substr($body, stripos($body, "<span style='background-color:#FFFF00'>".$query));
            } else if(stripos($body, "<span style='background-color:#FFFF00'>")) {
                $body = "...".substr($body, stripos($body, "<span style='background-color:#FFFF00'>"));
            }
        }
        return $body;
    }

    private function makeString($query_words)
    {
        usort($query_words, function($a, $b) {
                return strlen($b) - strlen($a);
            });
        foreach($query_words as $word)
        {
            if(strlen($word) <= 3)
            {
                $string_words[] = "*(?<![a-zA-Z])".preg_quote($word)."(?![a-zA-Z])*i";
            } else {
                $string_words[] = "*".preg_quote($word)."*i";
            }
        }
        return $string_words;
    }

    private function decodeBody($body)
    {
        $list = get_html_translation_table(HTML_ENTITIES);
        unset($list['<'], $list['>']);
        $list["'"] = "&#39;";
        $find = array_values($list);
        $replace = array_keys($list);

        return (str_replace($find, $replace, $body));
    }

    private function hasAllWords($string_words, $body)
    {
        $ind = 0;
        for($i = 1; $i < count($string_words); $i++)
        {
            if (preg_match($string_words[$i], html_entity_decode($body, ENT_QUOTES)))
            {
                $ind += 1;
            }
        }
        if($ind == count($string_words)-1)
        {
            return $this->mark($string_words, $this->decodeBody($body));
        }
        return $body;
    }
}
