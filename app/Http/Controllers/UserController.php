<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use Auth;

class UserController extends Controller
{
    public function showPosts($name)
    {
        $user = User::where('name', '=', $name)->first();

        $articles = $user->articles()->latest('published_at')->published()->paginate(5);

        return view('articles.users', compact('articles'));
    }

    public function showProfile($name)
    {
        $user = User::where('name', '=', $name)->first();

        return view('articles.profile', compact('user'));
    }

    public function unpublished($name)
    {
        $user = User::where('name', '=', $name)->first();

        if (Auth::id() == $user->id) 
        {
        $articles = $user->articles()->latest('published_at')->unpublished()->paginate(5);

        return view('articles.unpublished', compact('articles'));
        } else
        {
            return redirect('articles');
        }
    }

    public function delete($name)
    {
        $user = User::where('name', '=', $name)->first();

        if (Auth::id() == $user->id) 
        {
            $user->delete();
            \Session::flash('flash_message', 'Your profile has been deleted!');
            return redirect('login');
        }

        return redirect('articles');

    }
}
