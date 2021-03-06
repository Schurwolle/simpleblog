<?php

namespace App\Http\Controllers\Auth;


use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Socialite;
use Auth;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectAfterLogout = '/login';
    protected $redirectTo = '/articles';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255|unique:users,name',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    public function redirectToFacebook()
    {
        return Socialite::with('facebook')->redirect();
    }

    public function getFacebook()
    {

        $data = Socialite::with('facebook')->user();
        $user = User::where('facebook_id', $data->id)->first();

        if(!is_null($user))
        {
            Auth::login($user);

        } else {

            $user = User::where('email', $data->email)->first();

            if(!is_null($user)) 
            {
                Auth::login($user);

                $user->facebook_id = $data->id;
                $user->save();

            } else {
                
                $user = new User();
                $user->name = $data->user['first_name'].' '.$data->user['last_name'];
                $user->email = $data->email;
                $user->facebook_id = $data->id;
                $user->save();

                Auth::login($user);

            } 
        }

        \Session::flash('flash_message', 'You are logged in!');

        return redirect('articles');
    }
}
