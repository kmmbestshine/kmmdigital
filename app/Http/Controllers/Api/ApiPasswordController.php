<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Hash;

class ApiPasswordController extends Controller
{

    /**
     * Change user password.
     *
     * @param Request $request
     * @return string $result
     */    
    public function changePassword(Request $request)
    {   
    	$value = $request->all();
    	$userObj = User::find($value['user_id']);
    	$current_password = $userObj->password;           
		if(Hash::check($value['current_password'], $current_password))
		{           
			$userObj->password = Hash::make($value['new_password']);;
			$userObj->update(); 
			$result = "Password changed successfully";
		}else{            
			$result = "Please enter correct current password"; 
		}
		return response()->json($result);          
    } 

    /**
     *  Forgot password.
     *
     * @param Request $request
     * @return string $result
     */
    public function forgotPassword(Request $request)
    {   
    	$value = $request->all();
        dd($value);
        $emailCount = User::where('email',$value['email'])->count();
    	if($emailCount == 1){
    		// $result = "Email found";
    		return redirect()->route('password.email', ['Request' => $request]);
    	}else{
    		$result = "Email not found"; 
    	}
		return response()->json($result);          
    }    
}
