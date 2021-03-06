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
use App\Jobs\Delete;


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

        return view('articles.headings.userarticles', compact('articles', 'user'));
    }

    public function showProfile(User $user)
    {

        return view('articles.profile', compact('user'));
    }

    public function unpublished(User $user)
    {
        $this->authorize('userAuth', $user);

            $articles = $this->articles->forUserUnpublished($user);

            return view('articles.headings.unpublishedbyuser', compact('articles', 'user'));

    }

    public function favorites(User $user)
    {
        $articles = $this->articles->forUserFavorited($user);

        return view('articles.headings.favorited', compact('articles', 'user'));
    }

    public function delete(User $user)
    {
        $job = (new Delete($user, $user->name));
        $this->dispatch($job);

        if(Auth::user()->isAdmin() && Auth::user() != $user)
        {
            \Session::flash('flash_message', 'The profile has been deleted!');
            return redirect ('users')->with('user', $user->id);
        }else
        {
            Auth::logout();
            \Session::flash('flash_message', 'Your profile has been deleted!');
            return redirect('register');
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
        $inputs = array('newAvatar' => $request->newAvatar);
        $rules = array('newAvatar' => 'required');

        $validator = Validator::make($inputs, $rules);

        if($validator->fails())
        {
            return redirect($user->name.'/avatar')->withErrors($validator);
        }

        $mask = glob('pictures/cropper/croppedthumb'.$user->name.'*');
        if(!empty($mask))
        {
            $photo = $mask[0];
            $fileName = $user->name;

            $manager = new ImageManager();
            $image = $manager->make($photo)->save('pictures/'.$fileName);

            \Session::flash('flash_message', 'Your avatar has been updated!');

            return redirect ($user->name.'/profile');
        }
       
    }
}
