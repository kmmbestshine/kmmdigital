<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Event;
use App\Events\ContactUsAlert;
use App\Models\MainModule;
use Redirect;

class CommonController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Show the about us page.
     *
     * @return \Illuminate\Http\Response
     */
    public function aboutUs()
    {   
        return view('common.about');
    }

    /**
     * Show the contact us page.
     *
     * @return \Illuminate\Http\Response
     */
   /* public function contactUs()
    {   
        return view('common.contact');
    }*/

    /**
     * Store contact us form data.
     *
     * @param Request $request
     * @return Redirect
     */
    /*public function postContactUs(Request $request)
    {   
        $value = $request->all();
        Event::fire(new ContactUsAlert($value));
        return redirect()->back()->with('contactUSSuccess', 'Thank you for contact with us.');
    } */   

    /**
     * Show the features page.
     *
     * @return \Illuminate\Http\Response
     */
    public function features()
    {   
        return view('common.features');
    }    

    /**
     * Show the privacy policy page.
     *
     * @return \Illuminate\Http\Response
     */
    public function privacyPolicy()
    {   
        return view('common.policy');
    }

    /**
     * Show the terms&condition page.
     *
     * @return \Illuminate\Http\Response
     */
    public function termsAndCondition()
    {   
        return view('common.terms');
    }

    /**
     *  Ajax to get all standards
     * @param  Request  $request
     * @return array $standards     
     */
    public function ajaxAllStandards(Request $request)
    {   
        $value = $request->all(); 
        $standards = MainModule::where('state_id',$value['state_id'])->where('board_id',$value['board_id'])->where('medium_id',$value['medium_id'])->where('status','1')->get();
        return $standards;   
    }        
}
