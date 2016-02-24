<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use Auth;

class UserController extends Controller
{
    public function showPosts(User $user)
    {

        $articles = $user->articles()->latest('published_at')->published()->paginate(5);

        return view('articles.users', compact('articles'));
    }

    public function showProfile(User $user)
    {

        return view('articles.profile', compact('user'));
    }

    public function unpublished(User $user)
    {
        $this->authorize('userAuth', $user);

            $articles = $user->articles()->latest('published_at')->unpublished()->paginate(5);

            return view('articles.unpublished', compact('articles'));

    }

    public function delete(User $user)
    {

        $this->authorize('userAuth', $user);

            $user->delete();
            \Session::flash('flash_message', 'Your profile has been deleted!');
            return redirect('login');
        

    }
}
