<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Tag;
use App\Repositories\ArticleRepository;
use App\Repositories\TagRepository;
use Auth;

class TagsController extends Controller
{
	protected $articles;
	protected $tags;

	public function __construct(ArticleRepository $articles, TagRepository $tags)
	{
		$this->middleware('auth');
		
		$this->articles = $articles;
		$this->tags 	= $tags;
	}

    public function store(Request $request)
    {
        $tag = Tag::create(['name' => strtolower($request->name)]);

        return $tag;
    }


    public function show(Tag $tag)
    {
    	$articles = $this->articles->forTag($tag);

    	return view('articles.headings.tags', compact('articles', 'tag'));
    }

    public function destroy(Tag $tag)
    {
    	$this->authorize('adminAuth', Auth::user());

	    	$tag->delete();	        
    }

    public function update(Tag $tag, Request $request)
    {
    	$this->authorize('adminAuth', Auth::user());

    		$tag->update(['name' => strtolower($request->name)]);

    		return $tag->name;
    }
}
