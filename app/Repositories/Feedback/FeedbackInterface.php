<?php 

namespace App\Repositories\Feedback;

interface FeedbackInterface {

    public function getFeedbacks();   

    public function sendFeedback($value);     

    public function sendFeedbackToDevelopmentTeam($id);     
}

?>