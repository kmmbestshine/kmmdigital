<?php

namespace App\Repositories\Exam;

use App\Repositories\Exam\ExamInterface as ExamInterface;
use App\Models\Question;
use App\Models\Exam;
use App\Models\MainModule;
use App\Models\SubModule;
use App\Models\StateMaster;
use App\Models\LanguageMaster;
use App\Models\BoardMaster;

class ExamRepository implements ExamInterface
{

    /**
     * Exam instance
     *
     * @var model instance
     * @access protected
     */    
    protected $exam;

    function __construct(Exam $exam) {
        $this->exam = $exam;
    }

    /**
     * Get exams.
     *
     * @return array $exams
     */
    public function getExams()
    {   
        $exams = $this->exam::with('submodule')->get();
        return $exams;
    } 

    /**
     * Get activated main modules.
     *
     * @return array $mainModules
     */
    public function getActivatedMainModules()
    {   
        $mainModules = MainModule::where('status',1)->get();
        return $mainModules;
    }  

    /**
     * Ajax sub modules by main modules.
     *
     * @return array $subModules
     */
    public function ajaxSubModulesByMainModules($value)
    {   
        $subModules = SubModule::where('status',1)->where('main_module_id',$value['main_id'])->get();
        return $subModules;
    }     

    /**
     * Get sub modules by id.
     *
     * @param int $sub_module_id
     * @return object $subModule
     */
    public function getSubModuleByID($sub_module_id)
    {   
        $subModule = SubModule::find($sub_module_id);
        return $subModule;
    }       

    /**
     * Store exam form data.
     *
     * @param array $value
     * @return string
     */
    public function store($value)
    {   
        $this->exam->sub_module_id = $value['sub_module_type'];
        $this->exam->name = $value['exam_name'];
        $this->exam->time_duration = gmdate("H:i:s", $value['exam_duration']);
        $this->exam->exam_date = $value['exam_date'];
        $this->exam->status = 0;
        $this->exam->save();
        return "Exam type has been inserted successfully";
    }

    /**
     * Edit exam record.
     *
     * @param string $slug
     * @return object $exam
     */
    public function edit($slug)
    {   
        $exam = $this->exam::where('slug',$slug)->first();
        return $exam;
    } 

    /**
     * Update exam record.
     *
     * @param array $value
     * @param string $slug
     * @return string
     */
    public function update($value,$slug)
    {   
        $exam = $this->exam::where('slug',$slug)->first();
        $exam->name = $value['exam_name'];
        $exam->time_duration = gmdate("H:i:s", $value['exam_duration']);
        $exam->exam_date = $value['exam_date'];
        $exam->update();
        return "Exam type has been updated successfully";
    } 

    /**
     * Activate exam record.
     *
     * @param string $slug
     * @return string
     */
    public function activate($slug)
    {   
        $exam = $this->exam::where('slug',$slug)->first();
        $exam->status = 1;
        $exam->update();
        return "Exam type has been activated successfully";
    }  

    /**
     * Deactivate exam record.
     *
     * @param string $slug
     * @return string
     */
    public function deactivate($slug)
    {   
        $exam = $this->exam::where('slug',$slug)->first();
        $exam->status = 0;
        $exam->update();
        return "Exam type has been deactivated successfully";
    }  

    /**
     * Delete exam record.
     *
     * @param string $slug
     * @return string
     */
    public function delete($slug)
    {   
        $obj = $this->exam::where('slug',$slug)->first();
        $parentExamCount = Question::where('exam_id',$obj->id)->count();  
        if($parentExamCount == 0){
            $exam = $this->exam::where('slug',$slug)->first();
            $exam->delete();
            return "success";
        }else{
            return "error";
        }
    }     
}

?>