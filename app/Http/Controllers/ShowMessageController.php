<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShowMessageController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    	$this->array = ['1'=>'Upgrade Your Pricing Plan','2'=>'You Already Taken This Exam','400'=>'Bad Request','404'=>'Page Not Found','403'=>'Forbidden Access Denied','500'=>'Internel Server Error'];
    }   

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showMessage($msg_id)
    {   
        if($msg_id=="100001"){
            $message=" This Exam already written. ";
            return view('message.message',compact('message'));
        }

        if (array_key_exists($msg_id, $this->array)) {
            $message = $this->array[$msg_id]; 
        }else{
            $message = "Someting Went Wrong";
        }  
            
        return view('message.message',compact('message'));  
    }
}
