<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Http\Requests\LoginFormRequest;
use Auth;

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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     *  Show login form.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function showLoginForm()
    {
        return view('auth.login');        
    }

    /**
     * Authenticate login form data
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function authenticate(LoginFormRequest $request)
    {
        $value = $request->all();
        if (Auth::attempt(['email' => $value['email'], 'password' => $value['password']])) {
            // Authentication passed...
            return redirect()->intended('dashboard');
        }else{
            return redirect()->back()->with('loginError', 'Incorrect username or password');
        }        
    }

    /**
     * Validate login form data
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function logout()
    {
        Auth::logout();
        return redirect()->intended('/');        
    }    
}
