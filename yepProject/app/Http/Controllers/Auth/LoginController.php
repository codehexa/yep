<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
//use http\Env\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function authenticated(Request $request,$user){
        //$credentials = $request->only('email', 'password',);
        $credentials = ['email' => $request->get('email'), 'password' => $request->get('password'), 'live'=>'Y'];

        if (Auth::attempt($credentials)) {
            // Authentication passed...
            $user = Auth::user();
            $user->last_login = now();
            $user->save();
            return redirect()->intended('home');
        } else {
            Auth::logout();
        }
    }

    protected function loggedOut(Request $request) {
        return redirect('/');
    }
}
