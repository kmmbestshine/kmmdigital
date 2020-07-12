<?php

namespace App\Repositories\Question;

use App\Repositories\Question\QuestionInterface as QuestionInterface;
use Auth;
use App\Models\Question;
use App\Models\Exam;
use App\Models\Mark;
use Carbon\Carbon;
use App\Models\PlanUsageDetail;
use App\Models\SampleQuestionPaper;
use App\Models\MainModule;
use App\Models\SubModule;
use Event;
use App\Events\OnlineTestResultAlert;
use App\Events\NewExamNotificationAlert;
use File;

class QuestionRepository implements QuestionInterface
{
    public $question;

    function __construct(Question $question, Exam $exam, Mark $mark, PlanUsageDetail $plan, SampleQuestionPaper $questionPaper) {
        $this->question = $question;
        $this->exam = $exam;
        $this->mark = $mark;
        $this->plan = $plan;
        $this->questionPaper = $questionPaper;
    }

    public function getExams()
    {   
        $examIDArray = [];
        $questions = $this->question::get();
        foreach($questions as $key=> $question){
            if (!in_array($question->exam_id, $examIDArray)) {
                array_push($examIDArray, $question->exam_id); 
            }                   
        } 

        $exams = $this->exam::whereNotIn('id', $examIDArray)->where('status',1)->get();
        return $exams;
    }

    public function getSampleQuestionPapers()
    {   
        $sampleQuestions = $this->questionPaper::with('submodule')->get();
        return $sampleQuestions;
    }

    public function getActivatedSubModules()
    {   
        $subModules = SubModule::where('status',1)->get();
        return $subModules;
    }    

    public function postSampleQuestion($request)
    {   
        $this->questionPaper->sub_module_id = $request['module_type'];
        $this->questionPaper->title = $request['title'];
        $randomString = $this->generateRandomString();
        $fileName = $randomString . '.' . $request->file('file')->getClientOriginalExtension();

        $destinationPath = base_path() . '/public/sample-question-papers/';

        if(!file_exists($destinationPath)){
            File::makeDirectory($destinationPath);
        } 

        $request->file('file')->move($destinationPath, $fileName);
        $this->questionPaper->file = $fileName;
        $this->questionPaper->save();
        return $this->questionPaper;
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

    public function getSingleExam($exam_slug)
    {   
        $exam = $this->exam::where('slug',$exam_slug)->first();
        return $exam;
    }    

    public function viewQuestions($exam_slug)
    {   
        $exam = $this->exam::where('slug',$exam_slug)->first();
        $questions = $this->question::where('exam_id',$exam->id)->inRandomOrder()->get();
        return $questions;
    }

    public function viewQuestionsForUser($request,$exam_slug)
    {   
        $input = $request->all();
        if(isset($input['checkedAnswers'])){
            $questionIDArray = [];  
            $checkedAnswers = $input['checkedAnswers'];
            if(count($checkedAnswers) > 0){
                foreach($checkedAnswers as $checkedAnswer){
                    $questionIDArray[] = $checkedAnswer['question_id'];     
                }
            }
            $request->session()->put('questionIDArray',$questionIDArray);
        }
        $exam = $this->exam::where('slug',$exam_slug)->first();
        if($request->session()->has('questionIDArray')){
            $questionIDArray = $request->session()->get('questionIDArray');
            $questions = $this->question::where('exam_id',$exam->id)->whereNotIN('id',$questionIDArray)->inRandomOrder()->take(1)->get();
        }else{
            $questions = $this->question::where('exam_id',$exam->id)->inRandomOrder()->take(1)->get();
        }
        return $questions;
    }

    public function totalQuestions($exam_slug)
    {   
        $exam = $this->exam::where('slug',$exam_slug)->first();
        $totalQuestions = $this->question::where('exam_id',$exam->id)->count();
        return $totalQuestions;
    }

    public function getSubjectTitles($exam_slug)
    {   
        $array = [];
        $exam = $this->exam::where('slug',$exam_slug)->first();
        $questions = $this->question::where('exam_id',$exam->id)->get();
        foreach ($questions as $key => $question) {
            if (!in_array($question->subject, $array)) {
                array_push($array, $question->subject); 
            }   
        }
        return $array;
    }

    public function getMark($exam_slug)
    {   
        $exam = $this->exam::where('slug',$exam_slug)->first();
        $mark = Mark::where('exam_id',$exam->id)->where('user_id',Auth::user()->id)->first();
        return $mark;
    }

    public function postAnswers($input,$exam_slug)
    {   
        $checkedAnswers = json_decode($input['checkedAnswers'],true);       
        $exam = $this->exam::where('slug',$exam_slug)->first();
        $mark = 0;
        $questionIDArray = [];
        $selectedAnswers = [];
        $total_questions = $this->question::where('exam_id',$exam->id)->count();
        $attended_questions = 0;
        $not_attended_questions = 0;
        $negative_mark = 0;

        if(count($checkedAnswers) > 0){
            foreach($checkedAnswers as $checkedAnswer){
                $attended_questions++; 
                $question = $this->question::find($checkedAnswer['question_id']);
                $answer = $checkedAnswer['answer'];
                $selectedAnswers[$checkedAnswer['question_id']] = $answer;
                if($question->answer == $answer){
                    $mark++;
                }else{
                    $negative_mark++;
                }            
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
        $json_encode = json_encode($selectedAnswers);

        $markCount = $this->mark::where('exam_id',$exam->id)->where('user_id',Auth::user()->id)->count();
        if($markCount == 0){
            $this->mark->sub_module_id = $exam->sub_module_id;
            $this->mark->exam_id = $exam->id;
            $this->mark->user_id = Auth::user()->id;
            $this->mark->total_questions = $total_questions;
            $this->mark->attended_questions = $attended_questions;
            $this->mark->not_attended_questions = $not_attended_questions;
            $this->mark->selected_answers = $json_encode;
            $this->mark->mark = $mark;
            $this->mark->negative_mark = $negative_mark;
            $this->mark->remarks = $remarks;
            $this->mark->written_date = Carbon::now();
            $this->mark->save();            
            $this->updatePlan($exam->id);
            Event::fire(new OnlineTestResultAlert($this->mark));
            return $this->mark;
        }else{                
            $markObj = $this->mark::where('exam_id',$exam->id)->where('user_id',Auth::user()->id)->first();
            $markObj->total_questions = $total_questions;
            $markObj->attended_questions = $attended_questions;
            $markObj->not_attended_questions = $not_attended_questions;
            $markObj->selected_answers = $json_encode;
            $markObj->mark = $mark;
            $markObj->negative_mark = $negative_mark;
            $markObj->remarks = $remarks;
            $markObj->written_date = Carbon::now();   
            $markObj->update(); 
            $this->updatePlan($exam->id);
            Event::fire(new OnlineTestResultAlert($markObj));
            return $markObj;                   
        }     
    }  

    public function updatePlan($exam_id)
    {   
        $exam = $this->exam::find($exam_id);
        $subModule = SubModule::where('id',$exam->sub_module_id)->first();        
        $userIsInSubscribePlanCount = PlanUsageDetail::where('user_id',Auth::user()->id)->where('main_module_id',$subModule->main_module_id)->count();     
        if($userIsInSubscribePlanCount == 1){
            $plan = $this->plan::where('user_id',Auth::user()->id)->where('main_module_id',$subModule->main_module_id)->first();               
            $limit = $plan->plan_limit - 1;
            $plan->plan_limit = $limit;
            $plan->update();                       
        }else{   
            $plan = $this->plan::where('user_id',Auth::user()->id)->where('plan_name',"Free Trial")->first();
            $limit = $plan->plan_limit - 1;
            $plan->plan_limit = $limit;
            $plan->update();                                   
        }        
    }

    // public function updatePlan()
    // {   
    //     $plan = $this->plan::where('user_id',Auth::user()->id)->first();
    //     $limit = $plan->plan_limit - 1;
    //     $plan->plan_limit = $limit;
    //     $plan->update();
    // }          

    // http://www.walkswithme.net/reading-images-from-excel-sheet-using-phpexcel
    public function store($value)
    {   
        $exam_id = $value['exam_type'];
        $Accounts = array();
        $objPHPExcel = \PHPExcel_IOFactory::load($value['file']);
        $obj = $objPHPExcel->getActiveSheet();        

        if ($obj->getCellByColumnAndRow(0, 1)->getValue() != 'Subject' &&
                $obj->getCellByColumnAndRow(1, 1)->getValue() != 'Question' &&
                $obj->getCellByColumnAndRow(2, 1)->getValue() != 'Option_A' &&
                $obj->getCellByColumnAndRow(3, 1)->getValue() != 'Option_B' &&
                $obj->getCellByColumnAndRow(4, 1)->getValue() != 'Option_C' &&
                $obj->getCellByColumnAndRow(5, 1)->getValue() != 'Option_D' &&
                $obj->getCellByColumnAndRow(6, 1)->getValue() != 'Answer' &&
                $obj->getCellByColumnAndRow(7, 1)->getValue() != 'Answer_Description' &&
                $obj->getCellByColumnAndRow(8, 1)->getValue() != 'Question_Image' &&
                $obj->getCellByColumnAndRow(9, 1)->getValue() != 'Question_Image1' &&
                $obj->getCellByColumnAndRow(10, 1)->getValue() != 'Question_Image2') {
            $msg['error'] = 'Data is not according to format';
            return $msg;
        }

        $rows = $obj->getHighestRow();

        $row = 1;

        $Iterator = 0;
        $ins_count=0;

        for (((($obj->getCellByColumnAndRow(0, $row)->getValue()) == 'Subject') ? $row = 2 : $row = 1); $row <= $rows; ++$row) {

            $Accounts[$Iterator] = array(
                'Subject' => $obj->getCellByColumnAndRow(0, $row)->getValue(),
                'Question' => $obj->getCellByColumnAndRow(1, $row)->getValue(),
                'Option_A' => $obj->getCellByColumnAndRow(2, $row)->getValue(),
                'Option_B' => $obj->getCellByColumnAndRow(3, $row)->getValue(),
                'Option_C' => $obj->getCellByColumnAndRow(4, $row)->getValue(),
                'Option_D' => $obj->getCellByColumnAndRow(5, $row)->getValue(),
                'Answer' => $obj->getCellByColumnAndRow(6, $row)->getValue(),
                'Answer_Description' => $obj->getCellByColumnAndRow(7, $row)->getValue(),
                // 'Question_Image' => $obj->getCellByColumnAndRow(7, $row)->getValue()
                'Question_Image' => $this->imagesFromExcel($objPHPExcel,$row,8),
                'Question_Image1' => $this->imagesFromExcel($objPHPExcel,$row,9),
                'Question_Image2' => $this->imagesFromExcel($objPHPExcel,$row,10)
            );

            foreach ($Accounts as $key => $value) {
                
                if ($value['Subject'] == '' && $value['Question'] == '' && $value['Option_A'] == '' && $value['Option_B'] == '' && $value['Option_C'] == '' && $value['Option_D'] == '' && $value['Answer'] == '') {
                    unset($Accounts[$key]);
                    unset($value);
                    break;
                }
                $not_mandatary = array('Answer_Description','Question_Image','Question_Image1','Question_Image2');
                foreach($value as $keys => $val){
                    if(!in_array($keys, $not_mandatary)){
                        if(empty($val)){
                            if($ins_count!=0){
                                //$pre_row=$key-1;
                                $msg['error'] = 'At Row : ' . $row .' '. $keys .' required';
                            }else{
                                $msg['error'] = 'At Row : ' . $row .' '. $keys . ' required';
                            }
                            return $msg;
                        }
                    }
                }
            }

            foreach ($Accounts as $key => $val) {
                $pre_row=$key--;
                $questionModel = new Question();
                $questionModel->exam_id = $exam_id;
                $questionModel->subject = $val['Subject'];
                $questionModel->question = $val['Question'];
                $questionModel->option_a = $val['Option_A'];
                $questionModel->option_b = $val['Option_B'];
                $questionModel->option_c = $val['Option_C'];
                $questionModel->option_d = $val['Option_D'];
                $questionModel->answer = $val['Answer'];
                $questionModel->answer_description = $val['Answer_Description'];
                $destinationPath = base_path() . '/public/question-images/';
                if(!file_exists($destinationPath)){
                    File::makeDirectory($destinationPath);
                } 
                if(!empty($val['Question_Image'])){
                    $path = $val['Question_Image']['path'];
                    $des = $val['Question_Image']['des'];
                    $arr = explode('.',$des);
                    $ext = end($arr);   
                    $randomString = $this->generateRandomString();
                    $filename = $randomString . '.' . $ext;            
                    copy($path, $destinationPath . $filename);
                    $questionModel->image = $filename;
                }

                if(!empty($val['Question_Image1'])){
                    $path = $val['Question_Image1']['path'];
                    $des = $val['Question_Image1']['des'];
                    $arr = explode('.',$des);
                    $ext = end($arr);   
                    $randomString = $this->generateRandomString();
                    $filename = $randomString . '.' . $ext;            
                    copy($path, $destinationPath . $filename);
                    $questionModel->image1 = $filename;
                } 

                if(!empty($val['Question_Image2'])){
                    $path = $val['Question_Image2']['path'];
                    $des = $val['Question_Image2']['des'];
                    $arr = explode('.',$des);
                    $ext = end($arr);   
                    $randomString = $this->generateRandomString();
                    $filename = $randomString . '.' . $ext;            
                    copy($path, $destinationPath . $filename);
                    $questionModel->image2 = $filename;
                }                                          
                $questionModel->save();
 
                $ins_count++;
            }
        }
        Event::fire(new NewExamNotificationAlert($exam_id));
        $msg['success'] = 'Question has been imported successfully';
        return $msg;
    }

    public function imagesFromExcel($objPHPExcel,$row,$no){
        foreach ($objPHPExcel->getActiveSheet()->getDrawingCollection() as $drawing) {
            if ($drawing instanceof \PHPExcel_Worksheet_Drawing) {
                $cellID = $drawing->getCoordinates();
                if($cellID == \PHPExcel_Cell::stringFromColumnIndex($no).$row){
                    $result = array(
                        'path' => $drawing->getPath(),
                        'des' => $drawing->getDescription(),
                    );
                    return $result;
                }
            }else{
                return null;
            }
        }        
    }
}




            // foreach ($objPHPExcel->getActiveSheet()->getDrawingCollection() as $drawing) {
            //     if ($drawing instanceof \PHPExcel_Worksheet_MemoryDrawing) {
            //         $cellID = $drawing->getCoordinates();
            //         if($cellID == \PHPExcel_Cell::stringFromColumnIndex(7).$row){
            //             ob_start();
            //             call_user_func(
            //                 $drawing->getRenderingFunction(),
            //                 $drawing->getImageResource()
            //             );
            //             $imageContents = ob_get_contents();
            //             ob_end_clean();

            //             dd($imageContents);
            //             $filetype = $drawing->getMimeType();
            //             $filename = md5(microtime());                   

            //             switch ($filetype) {

            //                 case 'image/gif':
            //                     $image = imagecreatefromstring($imageContents);
            //                     imagegif($image, "/var/www/social/uploads/i/$filename.gif", 100);
            //                     $new_file = "$filename.gif";
            //                     break;

            //                 case 'image/jpeg':
            //                     $image = imagecreatefromstring($imageContents);
            //                     imagejpeg($image, "/var/www/social/uploads/i/$filename.jpeg", 100);
            //                     $new_file = "$filename.jpeg";
            //                     break;

            //                 case 'image/png':
            //                     $image = imagecreatefromstring($imageContents);
            //                     imagepng($image, "/var/www/social/uploads/i/$filename.png", 100);
            //                     $new_file = "$filename.png";
            //                     break;

            //                 default:
            //                     continue 2;

            //             }

            //             // Add our image location to the array
            //             ${'Accounts'}[${'Iterator'}]['image'] = array('link'=>'http://IMAGECDN/'.$new_file, 'type'=>$filetype, 'name'=>$new_file, 'size'=>filesize('/'.$new_file));
            //         }
            //     }else{
            //         dd('dsd');
            //     }
            // }

?>

