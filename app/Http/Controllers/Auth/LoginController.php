<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Events\UserLogOut;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Notifications\UserLogIn;
use App\User;

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
    protected $redirectTo = 'admin/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            $this->username() => 'required', 'password' => 'required', //'g-recaptcha-response' => 'required|captcha',
        ]);
    }

    public function logout(Request $request)
    {
        auth()->user()->cerrarVentanilla();

        event(new UserLogOut(auth()->user()->name));

        $this->guard()->logout();

        $request->session()->flush();

        $request->session()->regenerate();

        return redirect('/');
    }

    protected function authenticated(Request $request, $user)
    {
        if($user->lock_session === 'yes'){
            $this->guard()->logout();

            $request->session()->flush();

            $request->session()->regenerate();

            return redirect('/')->withErrors(['No tiene permitido ingresar al sitio.']);
        }
        event(new \App\Events\UserLogIn($user->name));
    }
}
