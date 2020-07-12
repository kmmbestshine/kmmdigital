<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordFormRequest;
use App\Repositories\User\UserInterface as UserInterface;

class ChangePasswordController extends Controller
{	

    /**
     * @var declaration
     */   
    protected $user;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    /**
     *  Show change password form.
     *
     */
    public function showChangePasswordForm()
    {	
        return view('auth.passwords.change');        
    }

    /**
     *  Store change password form data.
     *
     * @param  array  $data
     * @return Redirect
     */
    public function changePassword(ChangePasswordFormRequest $request)
    {	
    	$value = $request->all();
    	$result = $this->user->changePassword($value); 
    	if($result == "success"){
    		return redirect()->back()->with('changePasswordSuccess', "Password changed successfully");
    	}else{
    		return redirect()->back()->with('changePasswordFailure', "Please enter correct current password");
    	}    
    }    
}
