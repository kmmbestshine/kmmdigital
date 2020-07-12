<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ImportQuestionFormRequest;
use App\Http\Requests\SampleQuestionPaperUploadFormRequest;
use App\Repositories\Question\QuestionInterface as QuestionInterface;
use App\Repositories\Common\CommonInterface as CommonInterface;
use Auth;
use App\Models\Question;
use App\Models\Exam;

class QuestionController extends Controller
{

    /**
     * QuestionInterface instance
     *
     * @var Interface instance
     * @access protected
     */    
    protected $question; 

    /**
     * CommonInterface instance
     *
     * @var Interface instance
     * @access protected
     */    
    protected $common;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(QuestionInterface $question, CommonInterface $common)
    {
        $this->middleware('admin', ['only' => ['index', 'importQuestions']]);
        $this->middleware('user', ['only' => ['postAnswers']]);
        $this->question = $question;
        $this->common = $common;        
        // $this->middleware('check-duplicate', ['only' => ['viewQuestions']]);
    }   
     
    /**
     * Display a listing of the exams.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $states = $this->common->getAllStates();
        $boards = $this->common->getAllBoards(); 
        $mediums = $this->common->getAllMediums();              
        $exams = $this->question->getExams();
        return view('question.index',compact('states','boards','mediums','exams'));  
    }    

    /**
     * Show the form for creating a new resource for questions.
     *
     * @return \Illuminate\Http\Response
     */
    public function importQuestions(ImportQuestionFormRequest $request)
    {
        $value = $request->all();

        $msg = $this->question->store($value);

        if(isset($msg['error'])){            
            return redirect()->back()->with('error', $msg['error']);
        }

        if(isset($msg['success'])){            
            return redirect()->back()->with('success', $msg['success']);
        }        
    }

    /**
     * View Questions.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewQuestions($sub_module_slug,$exam_slug)
    {   
        $result = $this->checkQuestionModelExistByExamSlug($exam_slug);
        if($result){
            $questions = $this->question->viewQuestions($exam_slug);  
            $subjectTitles = $this->question->getSubjectTitles($exam_slug);   
            $exam = $this->question->getSingleExam($exam_slug);
            if(Auth::User()->roles->first()->name == "Admin"){
                return view('question.admin.view',compact('questions','subjectTitles','exam'));         
            }else{
                return view('question.user.view',compact('questions','subjectTitles','exam'));
            }
        }else{
            return redirect()->route('showMessages', ['msg' => '404']);
        }
    }  

    /**
     * View Questions.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewQuestionsForUser(Request $request,$sub_module_slug,$exam_slug)
    {   
        $exam = Exam::where('slug',$exam_slug)->first();
 
        $attended = \DB::table('marks')
            ->where('exam_id',$exam->id)
            ->where('user_id',Auth::User()->id )
            ->first();
        if($attended){
            return redirect()->route('showMessages', ['msg' => '100001']);
        }
        $request->session()->forget('questionIDArray');
        $result = $this->checkQuestionModelExistByExamSlug($exam_slug);
        if($result){
            $totalQuestions = $this->question->totalQuestions($exam_slug);  
            $questions = $this->question->viewQuestionsForUser($request,$exam_slug);  
            $subjectTitles = $this->question->getSubjectTitles($exam_slug);   
            $exam = $this->question->getSingleExam($exam_slug);
            // if ($request->ajax()) {
            //     return view('question.user.load', ['totalQuestions' => $totalQuestions,'questions' => $questions,'subjectTitles' => $subjectTitles,'exam' => $exam])->render();  
            // }                   
            return view('question.user.index',compact('totalQuestions','questions','subjectTitles','exam','exam_slug'));
        }else{
            return redirect()->route('showMessages', ['msg' => '404']);
        }
    }     

    /**
     * Ajax call.
     *
     * @return \Illuminate\Http\Response
     */
    public function ajaxCall(Request $request)
    {   
        $input = $request->all();
        $exam_slug = $input['exam_slug'];
        $totalQuestions = $this->question->totalQuestions($exam_slug);  
        $questions = $this->question->viewQuestionsForUser($request,$exam_slug);  
        $subjectTitles = $this->question->getSubjectTitles($exam_slug);   
        $exam = $this->question->getSingleExam($exam_slug);
        return view('question.user.load', ['totalQuestions' => $totalQuestions,'questions' => $questions,'subjectTitles' => $subjectTitles,'exam' => $exam])->render();                 
    }    

    /**
     * Post Answers.
     *
     * @return \Illuminate\Http\Response
     */
    public function postAnswers(Request $request,$exam_slug)
    {   
        $input = $request->all();
        $mark = $this->question->postAnswers($input,$exam_slug);  
        return redirect()->route('showResults', ['exam_slug' => $exam_slug]);            
    }  

    /**
     * Show Result.
     *
     * @return \Illuminate\Http\Response
     */
    public function showResults($exam_slug)
    {   
        $result = $this->checkQuestionModelExistByExamSlug($exam_slug); 
        if($result){
            $questions = $this->question->viewQuestions($exam_slug);  
            $subjectTitles = $this->question->getSubjectTitles($exam_slug);  
            $mark = $this->question->getMark($exam_slug); 
            return view('question.user.result',compact('questions','subjectTitles','mark'));
        }else{
            return redirect()->route('showMessages', ['msg' => '404']);
        }                         
    }

    /**
     * Display a listing of the sample questions.
     *
     * @return \Illuminate\Http\Response
     */
    public function sampleQuestionIndex()
    {   
        $sampleQuestions = $this->question->getSampleQuestionPapers();
        return view('question.sample-questions.index',compact('sampleQuestions'));  
    } 

    /**
     * Display a listing of the sample questions.
     *
     * @return \Illuminate\Http\Response
     */
    public function sampleQuestionCreate()
    {   
        $subModules = $this->question->getActivatedSubModules();
        return view('question.sample-questions.create',compact('subModules'));  
    }     

    /**
     * Display a listing of the sample questions.
     *
     * @return \Illuminate\Http\Response
     */
    public function postSampleQuestion(SampleQuestionPaperUploadFormRequest $request)
    {   
        $paper = $this->question->postSampleQuestion($request);
        return redirect()->route('sampleQuestionIndex')->with('postSampleQuestionPaperSuccess', 'Sample question papers has been uploaded successfully.');
    }

    protected function checkQuestionModelExistByExamSlug($exam_slug)
    {   
        $exam = Exam::where('slug',$exam_slug)->first();
        return (isset($exam)) ? true : false ;
        // $questions = Question::where('exam_id',$exam->id)->get(); 
        // return (count($questions) > 0) ? true : false ;
    }
}
