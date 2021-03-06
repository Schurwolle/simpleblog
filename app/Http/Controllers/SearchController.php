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

        array_unshift($query_words, $query);
        $new_query_words = $this->sortWords($query_words);
        $string_words = $this->makeString($new_query_words);
        array_shift($query_words);

        $articles = $this->articles->forQuery($query, $query_words, $new_query_words);
        $num = $articles->count();


    	$comments = array();
    	foreach ($articles as $article)
        {   
            $article->title = $this->mark($string_words, $article->title);
            $article->body = $this->hasAllWords($query_words, $string_words, $article->body);
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
        return view('articles.headings.search', compact('articles', 'query', 'query_words', 'new_query_words', 'query_link', 'num', 'comments'));
    }

    public function show(article $article, $query)
    {
        $query = str_replace('%tačka%', '.', $query);
        $query = urldecode($query);
        $query_words = explode(" ", $query);
        array_unshift($query_words, $query);
        $new_query_words = $this->sortWords($query_words);
        $string_words = $this->makeString($new_query_words);
        array_shift($query_words);
        $article->title = $this->mark($string_words, $article->title);
        $article->body = $this->hasAllWords($query_words, $string_words, $article->body);
        $article->comments = $this->pickComments($article, $query_words, $string_words);
        foreach($query_words as &$word)
        {
            $word = preg_quote(htmlentities($word));
        }
        return redirect('articles/'.$article->slug)
                                    ->with('article', $article)
                                    ->with('query', $query)
                                    ->with('query_words', $query_words)
                                    ->with('new_query_words', $new_query_words);
    }   

    private function mark($string_words, $body, $comment = 0)
    {
        foreach($string_words as $string)
        {
            if ($comment == 0)
            {
                $body = $this->paintBody($string, $body);
            } else {
                $body = $this->paintComment($string, $body);
            }

            if(starts_with($string, "*(?<![a-zA-Z])") && strlen($string) > 29)
            {
                $word = substr($string, 14, strlen($string) - 28);
                
                if(strpos($body, "<span style='background-color:#FFFF00'>".$word."</span>") === false)
                {
                    $word = "*".preg_quote($word)."*i";
                    if ($comment == 0)
                    {
                        $body = $this->paintBody($word, $body);
                    } else {
                        $body = $this->paintComment($word, $body);
                    }
                }
            }
        }
        return $body;
    }
    private function paintBody($string, $body)
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
        return implode($exploded);
    }
    private function paintComment($string, $body)
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
        return implode($exploded);
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
                    $comment->body = $this->mark($string_words, $comment->body, 1);
                    if($comments != "null")
                    {   
                        if(str_word_count(strip_tags($comment->body)) > 60)
                        {
                            $comment->body = $this->findQueryInComment($comment->body, $query);
                        }
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
    private function findQueryInComment($body, $query)
    {
        $occurence = stripos($body, "<span style='background-color:#FFFF00'>".$query);
        if($occurence == FALSE)
        {
           $occurence = stripos($body, "<span style='background-color:#FFFF00'>");
        }
        $body = $this->cropComment($body, $occurence);
        return $body;
    }
    private function cropComment($body, $occurence)
    {
        $body1 = substr($body, 0, $occurence);
        $body = "...".substr($body, $occurence);
        if(!ends_with($body1, " "))
        {   
            if(strpos($body1, " ") === FALSE)
            {
                $body = $body1.substr($body, 3);
            } else {
                $body2 = substr($body1, 0, strlen($body1) - strlen(strrchr($body1, " ")));
                if(!ends_with($body2, "<span"))
                {
                    $body = "...".strrchr($body1, " ").substr($body, 3);
                }  
            }
        }
        return $body;
    }

    private function sortWords($query_words)
    {        
        foreach($query_words as $i => $word)
        {
            foreach($query_words as $j => $query)
            {
                if($i != 0 && $j != 0 && $word != $query)
                {
                    for($m = 1; $m <= strlen($query); $m++)
                    {
                        if(ends_with(strtolower($word), strtolower(substr($query, 0, $m))))
                        {
                            $query_words[] = $word.substr($query, $m);
                        }
                    }
                }
            }
        }

        usort($query_words, function($a, $b) {
                return strlen($b) - strlen($a);
            });

        return $query_words;
    }

    private function makeString($query_words)
    {
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

    private function hasAllWords($query_words, $string_words, $body)
    {
        $strings = $this->makeString($query_words);
        $ind = 0;
        for($i = 1; $i < count($strings); $i++)
        {
            if (preg_match($strings[$i], html_entity_decode($body, ENT_QUOTES)) || starts_with($strings[$i], "*(?<![a-zA-Z])"))
            {
                $ind += 1;
            }
        }
        if($ind == count($strings)-1)
        {
            return $this->mark($string_words, $this->decodeBody($body));
        }
        return $body;
    }
}
