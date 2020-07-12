<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Feedback\FeedbackInterface as FeedbackInterface;

class FeedbackController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(FeedbackInterface $feedback)
    {
        $this->feedback = $feedback;
        $this->middleware('admin', ['only' => ['index']]);
    }    

    /**
     * Display a feedback details.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        $feedbacks = $this->feedback->getFeedbacks();
        if ($request->ajax()) {
            return view('feedback.load', ['feedbacks' => $feedbacks])->render();  
        }        
        return view('feedback.index',compact('feedbacks'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendFeedback(Request $request)
    {   
    	$value = $request->all();
    	$data = $this->feedback->sendFeedback($value);
        return $data;
    }   

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendFeedbackToDevelopmentTeam($id)
    {   
        $data = $this->feedback->sendFeedbackToDevelopmentTeam($id);
        return redirect()->route('getFeedbacks')->with('sendFeedbackToDevelopmentTeamSuccess', $data);
    }      
}
