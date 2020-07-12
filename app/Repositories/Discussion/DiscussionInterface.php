<?php 

namespace App\Repositories\Discussion;

interface DiscussionInterface {

    public function postDiscussionQuestion($value);   

    public function postDiscussionAnswer($value);        
}

?>