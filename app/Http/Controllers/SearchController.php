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
    	// $articles = article::where('body', 'LIKE', '%'. $query. '%')->latest('published_at')->published()->paginate(5);

    	return view('articles.headings.search', compact('articles', 'query'));
    }
}
