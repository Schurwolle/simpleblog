<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use App\Repositories\TagRepository;
use App\Repositories\UserRepository;
use App\Repositories\ArticleRepository;

class AdminController extends Controller
{
	protected $tags;
	protected $users;
	protected $articles;

	public function __construct(TagRepository $tags, UserRepository $users, ArticleRepository $articles)
	{
		$this->authorize('adminAuth', Auth::user());

		$this->tags  	 = $tags;
		$this->users 	 = $users;
		$this->articles  = $articles;
	}


    public function showTags()
    {
    	$tags = $this->tags->showSorted();

    	return view('tags', compact('tags'));
    }

    public function showUsers()
    {
        if(session()->has('user'))
        {
            $users = $this->users->showExcept(session('user'));
        } else {
            $users = $this->users->showAll();
        }

        return view('users', compact('users'));
    }

    public function showUnpublished()
    {       
        $articles = $this->articles->showUnpublished();

        return view('articles.headings.unpublished', compact('articles'));
    }
}
