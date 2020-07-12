<?php

namespace App\Repositories\Discussion;

use App\Repositories\Discussion\DiscussionInterface as DiscussionInterface;
use Auth;
use App\Models\DiscussionQuestions;
use App\Models\DiscussionAnswers;
use App\Models\SubModule;
use Carbon\Carbon;

class DiscussionRepository implements DiscussionInterface
{
    public $discussionQuestions;
    public $discussionAnswers;

    function __construct(DiscussionQuestions $discussionQuestions, DiscussionAnswers $discussionAnswers) {
        $this->discussionQuestions = $discussionQuestions;
		$this->discussionAnswers = $discussionAnswers;
    }   
    
    public function postDiscussionQuestion($value)
    {   
        $subModule = SubModule::where('slug',$value['sub_module'])->first();
        $this->discussionQuestions->sub_module_id = $subModule->id;
        $this->discussionQuestions->user_id = Auth::user()->id;
        $this->discussionQuestions->question = $value['question'];
        $this->discussionQuestions->user_name = $value['name'];
        $this->discussionQuestions->user_email = $value['email'];
        $this->discussionQuestions->slug = $this->generateRandomString();
        $this->discussionQuestions->save();
        return $this->discussionQuestions;
    } 

    public function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }       

    public function postDiscussionAnswer($value)
    {   
        $this->discussionAnswers->question_id = $value['question_id'];
        $this->discussionAnswers->user_id = Auth::user()->id;
        $this->discussionAnswers->answer = $value['answer'];
        $this->discussionAnswers->user_name = $value['name'];
        $this->discussionAnswers->user_email = $value['email'];
        $this->discussionAnswers->save();
        return $this->discussionAnswers;
    }  
}

?>