<?php

namespace App\Repositories\Feedback;

use App\Repositories\Feedback\FeedbackInterface as FeedbackInterface;
use Auth;
use App\Models\Feedback;
use Carbon\Carbon;

class FeedbackRepository implements FeedbackInterface
{
    public $feedback;

    function __construct(Feedback $feedback) {
        $this->feedback = $feedback;
    }

    public function getFeedbacks()
    {   
        $feedbacks = $this->feedback::latest('created_at')->paginate(5);
        return $feedbacks;
    }       
    
    public function sendFeedback($value)
    {  	
    	$this->feedback->name = $value["name"];
    	$this->feedback->email = $value["email"];
    	$this->feedback->content = $value["feedback"];
    	$this->feedback->save();
        return "success";
    }

    public function sendFeedbackToDevelopmentTeam($id)
    {   
        // send sms and email to development team
        return "Feedback has been forwarded to development team";
    }  
}

?>