<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\Repositories\ArticleRepository;
use App\Repositories\UserRepository;
use Auth;
use Hash;
use Validator;
use Intervention\Image\ImageManager;


class UserController extends Controller
{
    protected $articles;


    public function __construct(ArticleRepository $articles)
    {
        $this->middleware('auth');
        
        $this->articles = $articles;
    }


    public function showPosts(User $user)
    {
        $articles = $this->articles->forUser($user);

        return view('articles.headings.userarticles', compact('articles'));
    }

    public function showProfile(User $user)
    {

        return view('articles.profile', compact('user'));
    }

    public function unpublished(User $user)
    {
        $this->authorize('userAuth', $user);

            $articles = $this->articles->forUserUnpublished($user);

            return view('articles.headings.unpublishedbyuser', compact('articles'));

    }

    public function delete(User $user)
    {

        $user->delete();
        if(file_exists('pictures/'.$user->name))
        {
            unlink('pictures/'.$user->name);
        }

        if(Auth::user()->isAdmin())
        {
            \Session::flash('flash_message', 'The profile has been deleted!');
            return redirect ('users');
        }else
        {
            \Session::flash('flash_message', 'Your profile has been deleted!');
            return redirect('login');
        }

    }

    public function changePassword(User $user)
    {
        $this->authorize('userAuth', $user);

            return view('changePassword', compact('user'));
    }

    public function updatePassword(User $user, Request $request)
    {

        $inputs = array('newPassword'     => $request->newPassword,
                        'confirmPassword' => $request->confirmPassword,);
          
        $rules = array('newPassword'     => 'required|min:6',
                       'confirmPassword' => 'required|same:newPassword'); 

        $validator = Validator::make($inputs, $rules);

        if ($validator->fails()) 
        {
            return redirect ($user->name.'/changepassword')->withErrors($validator);
        }

        if (Hash::check($request->oldPassword, $user->password)) 
        {
            $user->password = bcrypt($request->newPassword);
            $user->save();

            \Session::flash('flash_message', 'Your password has been changed!');

            return redirect ($user->name.'/profile');
        } else {

            \Session::flash('alert_message', 'Wrong current password!');

            return redirect ($user->name.'/changepassword');
        }
    }

    public function avatar(User $user)
    {
        $this->authorize('userAuth', $user);
            
            return view('avatar', compact('user'));
    }

    public function updateAvatar(Request $request, User $user)
    {
        $inputs = array('newAvatar' =>$request->newAvatar);
        $rules = array('newAvatar' => 'required');

        $validator = Validator::make($inputs, $rules);

        if($validator->fails())
        {
            return redirect($user->name.'/avatar')->withErrors($validator);
        }

        $photo = 'pictures/imagecropped'.$user->name;

        $fileName = $user->name;

        $manager = new ImageManager();
        $image = $manager->make($photo)->save('pictures/'.$fileName);
        unlink('pictures/image'.$user->name);
        unlink('pictures/imagecropped'.$user->name);

        \Session::flash('flash_message', 'Your avatar has been updated!');

        return redirect ($user->name.'/profile');
    }
}
