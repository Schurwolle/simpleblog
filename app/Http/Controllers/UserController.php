<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\Repositories\ArticleRepository;
use Auth;

class UserController extends Controller
{
    protected $articles;


    public function __construct(ArticleRepository $articles)
    {
        $this->articles = $articles;
    }



    public function showPosts(User $user)
    {
        $articles = $this->articles->forUser($user);

        return view('articles.users', compact('articles'));
    }

    public function showProfile(User $user)
    {

        return view('articles.profile', compact('user'));
    }

    public function unpublished(User $user)
    {
        $this->authorize('userAuth', $user);

            $articles = $this->articles->forUserUnpublished($user);

            return view('articles.unpublished', compact('articles'));

    }

    public function delete(User $user)
    {

        $this->authorize('userAuth', $user);

            $user->delete();
            if(Auth::user()->isAdmin())
            {
                \Session::flash('flash_message', 'The profile has been deleted!');
                return redirect ('articles');
            }else
            {
                \Session::flash('flash_message', 'Your profile has been deleted!');
                return redirect('login');
            }

    }
}
