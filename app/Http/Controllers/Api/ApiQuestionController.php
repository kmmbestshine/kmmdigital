<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Question;
use App\Models\PlanUsageDetail;
use App\Models\Mark;
use App\Models\Exam;
use App\Models\SubModule;
use Hash;
use JWTAuth;
use Carbon\Carbon;

class ApiQuestionController extends Controller
{

    /**
     *  Get all questions. 
     *
     * @param int $user_id
     * @return array $questions
     */    
    public function getAllQuestions($exam_id)
    {   
        $questions = Question::where('exam_id',$exam_id)->get();
        return response()->json($questions);
    }

    /**
     *  Validate questions and answer. 
     *
     * @param Request $request
     * @return array $array
     */
    public function validateQuestionAndAnswer(Request $request)
    {   
        $input = $request->all();
        $array = $input['array'];
        $exam_id = $input['exam_id'];
        $user_id = $input['user_id'];
        
        $mark = 0;
        $total_questions = Question::where('exam_id',$exam_id)->count();
        $attended_questions = 0;
        $not_attended_questions = 0;
        $negative_mark = 0;        

        // foreach ($array as $key => $value) {
        //     $question = Question::find($value['id']);

        //     if(isset($value['checked'])){
        //         if($question->answer == $value['checked']){
        //             $mark = $mark + 4;
        //         }else{
        //             $mark = $mark - 1;
        //             $negative_mark++;
        //         }
        //         $attended_questions++;
        //     }
        // }

        // if($mark >= 550){
        //     $remarks = "Excellent Score";
        // }elseif($mark >=300 && $mark <= 549){
        //     $remarks = "Good Score";
        // }else{
        //     $remarks = "Poor Score";
        // }

        foreach ($array as $key => $value) {
            $question = Question::find($value['id']);

            if(isset($value['checked'])){
                if($question->answer == $value['checked']){
                    $mark++;
                }else{
                    $negative_mark++;
                }
                $attended_questions++;
            }
        }

        if($mark >= 80){
            $remarks = "Excellent Score";
        }elseif($mark >=50 && $mark <= 79){
            $remarks = "Good Score";
        }else{
            $remarks = "Poor Score";
        }         

        $not_attended_questions = $total_questions - $attended_questions;

        $markCount = Mark::where('exam_id',$exam_id)->where('user_id',$user_id)->count();
        if($markCount == 0){
            $markObj = new Mark();
            $markObj->exam_id = $exam_id;
            $markObj->user_id = $user_id;
            $markObj->total_questions = $total_questions;
            $markObj->attended_questions = $attended_questions;
            $markObj->not_attended_questions = $not_attended_questions;        
            $markObj->mark = $mark;
            $markObj->negative_mark = $negative_mark;
            $markObj->remarks = $remarks;
            $markObj->written_date = Carbon::now();
            $markObj->save(); 
        }else{
            $markObj = Mark::where('exam_id',$exam_id)->where('user_id',$user_id)->first();
            $markObj->total_questions = $total_questions;
            $markObj->attended_questions = $attended_questions;
            $markObj->not_attended_questions = $not_attended_questions;        
            $markObj->mark = $mark;
            $markObj->negative_mark = $negative_mark;
            $markObj->remarks = $remarks;
            $markObj->written_date = Carbon::now(); 
            $markObj->update();            
        }      
        $this->updatePlan($user_id,$exam_id);
        return response()->json($array);
    } 

    /**
     *  Update plan. 
     *
     * @param int $user_id
     * @param int $exam_id
     * @return void
     */
    public function updatePlan($user_id,$exam_id)
    {   
        $exam = Exam::find($exam_id);
        $subModule = SubModule::where('id',$exam->sub_module_id)->first();        
        $userIsInSubscribePlanCount = PlanUsageDetail::where('user_id',$user_id)->where('main_module_id',$subModule->main_module_id)->count();     
        if($userIsInSubscribePlanCount == 1){
            $plan = PlanUsageDetail::where('user_id',$user_id)->where('main_module_id',$subModule->main_module_id)->first();               
            $limit = $plan->plan_limit - 1;
            $plan->plan_limit = $limit;
            $plan->update();                       
        }else{   
            $plan = PlanUsageDetail::where('user_id',$user_id)->where('plan_name',"Free Trial")->first();
            $limit = $plan->plan_limit - 1;
            $plan->plan_limit = $limit;
            $plan->update();                                   
        }        
    }    
}
