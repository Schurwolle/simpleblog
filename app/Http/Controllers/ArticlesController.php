<?php

namespace App\Http\Controllers;


use Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\article;
use App\Http\Requests\ArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Tag;
use App\User;
use Carbon\Carbon;

class ArticlesController extends Controller
{	
	public function __construct()
	{
		$this->middleware('auth');
	}

    public function index()
    {	

    	$articles = article::latest('published_at')->published()->paginate(5);

    	return view('articles.articles', compact('articles'));
    }


    public function show(article $article)
    {
        if ($article->published_at > Carbon::now() && Auth::id() != $article->user_id)
        {
            return redirect('articles');
        }else
        {
            $comments = $article->comments()->latest('created_at')->get();

    	    return view('articles.show', compact('article', 'comments'));
        }
    }

    public function create()
    {

        $tags = Tag::lists('name', 'id'); 

    	return view('articles.create', compact('tags'));
    }

    public function store(ArticleRequest $request)
    {

    	$article = Auth::user()->articles()->create($request->all());

        $this->syncTags($article, $request);

        if ($request->hasFile('image')) 
        {
        
            $destinationPath = 'pictures/';
            $fileName = $article->id;
            

            $request->file('image')->move($destinationPath, $fileName);
        }
   	
    	\Session::flash('flash_message', 'Your article has been created!');

    	return redirect('articles');
    }

    public function edit(article $article)
    {
        if (Auth::id() == $article->user_id)
        {
    	   $tags = Tag::lists('name', 'id');

    	   return view('articles.edit', compact('article', 'tags'));
        } else
        {
            return redirect('articles');
        }

    }

    public function update(article $article, UpdateArticleRequest $request)
    {

    	$article->update($request->all());
        
        $this->syncTags($article, $request);

        if ($request->hasFile('image')) 
        {
        
            $destinationPath = 'pictures/';
            $fileName = $article->id;
            

            $request->file('image')->move($destinationPath, $fileName);
        }

        \Session::flash('flash_message', 'Your article has been updated!');

    	return redirect('articles');
    }

    public function delete(article $article)
    {
        if(Auth::id() == $article->user_id)
        {
            $article->delete();
            \Session::flash('flash_message', 'Your article has been deleted!');
        }

        return redirect('articles');
    }


    private function syncTags($article, $request)
    {
        if ( ! $request->has('tag_list'))
        {
            $article->tags()->detach();
            return;
        }

        $allTagIds = array();

        foreach ($request->tag_list as $tagId)
        {
            if (substr($tagId, 0, 4) == 'new:')
            {
                $newTag = Tag::create(['name' => substr($tagId, 4)]);
                $allTagIds[] = $newTag->id;
                continue;
            }
            $allTagIds[] = $tagId;
        }

        $article->tags()->sync($allTagIds);
    }


}


