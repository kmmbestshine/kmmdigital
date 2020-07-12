<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use App\Repositories\User\UserInterface as UserInterface;
use App\Repositories\Common\CommonInterface as CommonInterface;
use App\Http\Requests\RegistrationFormRequest;
use Auth;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * UserInterface instance
     *
     * @var Interface instance
     * @access protected
     */    
    protected $user;

    /**
     * CommonInterface instance
     *
     * @var Interface instance
     * @access protected
     */    
    protected $common;        

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserInterface $user, CommonInterface $common)
    {
        $this->middleware('guest');
        $this->user = $user;
        $this->common = $common;
    }

    /**
     *  Show registration form.
     */
    public function showRegistrationForm()
    {   
        $states = $this->common->getAllStates();
        $boards = $this->common->getAllBoards(); 
        $mediums = $this->common->getAllMediums(); 
        $mainModules = $this->user->getActivatedMainModules();
        return view('auth.register',compact('states','mediums','boards','mainModules'));        
    }

    /**
     * Store registration form data
     *
     * @param  RegistrationFormRequest  $request
     * @return Redirect
     */
    public function storeRegistrationFormData(RegistrationFormRequest $request)
    {   
        $value = $request->all();
        $obj = $this->user->store($value);
        Auth::login($obj);
        return redirect()->intended('dashboard')->with('registerSuccess', 'New user has been registred successfully');
    }
}
