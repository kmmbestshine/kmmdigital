<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Repositories\User\UserInterface as UserInterface;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserInterface $user)
    {
        $this->middleware('auth');
        $this->user = $user;
    }    


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index(Request $request)
    {   
        $value = $request->all();
        //dd($value);
        $users = \DB::table('users')->where('id','!=',Auth::user()->id)->where('module_id',$value['module_type'])->get();
        //$users1 = \DB::table('users')->where('id','!=',Auth::user()->id)->get();
         
         //dd($value,$users,$users1,$value['module_type']);
        return view('user.index',compact('users'));         
    }  
    public function userIndexmainmodule()
    {   
        $main_module=\DB::table('main_module')->get();
        //dd($main_module);
        return view('user.userIndexmainmodule',compact('main_module'));         
    }  

    public function userDelete(Request $request)
    {   
        $value = $request->all();
        $document_id=\DB::table('users')->where('id', $value['user_id'])->delete();
        
        $msg['success'] = 'Successfully  deleted Requested user';
        //dd($msg);
        return \Redirect::back()->withInput($msg);
             
    }  

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function usersSearch(Request $request)
    {   
        $value = $request->all();
        $users = $this->user->usersSearch($value);
        return view('user.index',compact('users'));         
    }          
}
