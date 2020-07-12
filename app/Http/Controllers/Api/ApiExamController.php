<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Exam;
use App\Models\Mark;
use App\Models\SubModule;
use Hash;
use JWTAuth;
use DB;

class ApiExamController extends Controller
{

    /**
     * Get exam by id.
     *
     * @param int $exam_id
     * @return time format $min
     */
   	public function getExamByID($exam_id)
    {   
        $exam = Exam::find($exam_id);  
        $time = $exam->time_duration;
        $timesplit=explode(':',$time);
        $min=($timesplit[0]*60)+($timesplit[1])+($timesplit[2]>30?1:0);
        return response()->json($min);
        // return response()->json(['result' => $exams]);
    }

    /**
     * Get activated exams by sub module.
     *
     * @param int $sub_module
     * @return array $exams
     */	
   	public function getActivatedExamsBySubModule($sub_module)
    {   
        $exams = Exam::with('marks')->where('sub_module_id',$sub_module)->where('status',1)->get();       
        return response()->json($exams);
        // return response()->json(['result' => $exams]);
    }

    /**
     * Get exams with marks.
     *
     * @param int $user_id
     * @return array $result
     */	
    public function getExamsWithMarks($user_id)
    {    
        $subModuleIDArray = [];
		$result = [];
		$user = User::find($user_id);
		$subModules = SubModule::where('main_module_id',$user->module_id)->get();
		foreach ($subModules as $key => $value) {
			if(!in_array($value->id, $subModuleIDArray))
				$subModuleIDArray[] = $value->id;
		}
		$exams = Exam::where('status',1)->whereIn('sub_module_id',$subModuleIDArray)->get();

		// $result = [];
		// $exams = Exam::where('status',1)->where('sub_module_id',$sub_module_id)->get();    
		foreach ($exams as $key => $exam) {
			$arr = [];
			$arr['exam_name'] = $exam->name;
			$arr['time_duration'] = $exam->time_duration;
		    $mark = Mark::where('exam_id',$exam->id)->where('user_id',$user_id)->first();
		    if($mark){
		    	$arr['status'] = 1;
		    	$arr['total_questions'] = $mark->total_questions;
		    	$arr['attended_questions'] = $mark->attended_questions;
		    	$arr['not_attended_questions'] = $mark->not_attended_questions;
		    	$arr['mark'] = $mark->mark;
		    	$arr['negative_mark'] = $mark->negative_mark;
		    	$arr['remarks'] = $mark->remarks;
		    	$arr['written_date'] = $mark->written_date;
		    }else{
		    	$arr['status'] = 0;
		    	$arr['total_questions'] = "-";
		    	$arr['attended_questions'] = "-";
		    	$arr['not_attended_questions'] = "-";
		    	$arr['mark'] = "-";
		    	$arr['negative_mark'] = "-";
		    	$arr['remarks'] = "-";
		    	$arr['written_date'] = "-";
		    }
		    array_push($result,$arr);
		}         	
		           
        return response()->json($result);
    }
}
