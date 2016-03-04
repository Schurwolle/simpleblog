<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Tag;
use App\Repositories\ArticleRepository;
use Auth;

class TagsController extends Controller
{
	protected $articles;


	public function __construct(ArticleRepository $articles)
	{
		$this->middleware('auth');
		
		$this->articles = $articles;
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

	    	\Session::flash('flash_message', 'The tag has been deleted!');
	        
	        return redirect('tags');
    }
}
