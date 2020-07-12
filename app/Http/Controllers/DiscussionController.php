<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Discussion\DiscussionInterface as DiscussionInterface;
use App\Http\Requests\DiscussionQuestionFormRequest;
use App\Http\Requests\DiscussionAnswerFormRequest;

class DiscussionController extends Controller
{
    
    /**
     * DiscussionInterface instance
     *
     * @var Interface instance
     * @access protected
     */    
    protected $discussion; 

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(DiscussionInterface $discussion)
    {
        $this->discussion = $discussion;
        $this->middleware('user', ['only' => ['takeExam']]);
    }

    /**
     * Store discussion question.
     *
     * @param DiscussionQuestionFormRequest $request
     * @return Redirect
     */
    public function postDiscussionQuestion(DiscussionQuestionFormRequest $request)
    {   
        $value = $request->all();
        $msg = $this->discussion->postDiscussionQuestion($value); 
        return redirect()->back()->with('postDiscussionQuestionSuccess', "Question has been posted successfully");
    } 

    /**
     * Store discussion answer.
     *
     * @param DiscussionAnswerFormRequest $request
     * @return Redirect
     */
    public function postDiscussionAnswer(DiscussionAnswerFormRequest $request)
    {   
        $value = $request->all();
        $msg = $this->discussion->postDiscussionAnswer($value); 
        return redirect()->back()->with('postDiscussionAnswerSuccess', "Answer has been posted successfully");
    }         
}
