<?php

namespace App\Repositories\Module;

use App\Repositories\Module\ModuleInterface as ModuleInterface;
use Auth;
use App\Models\MainModule;
use App\Models\SubModule;
use App\Models\DiscussionQuestions;
use App\Models\DiscussionAnswers;
use App\Models\SampleQuestionPaper;
use App\Models\Exam;
use App\Models\Mark;
use App\Models\SubModuleOverview;
use App\Models\SubModuleEligibility;
use App\Models\SubModuleSyllabus;
use App\Models\SubModuleExamPattern;
use App\Models\SubModuleHowToApply;
use App\Models\SubModuleStudyMaterial;
use Carbon\Carbon;

class ModuleRepository implements ModuleInterface
{

    /**
     * MainModule instance
     *
     * @var model instance
     * @access protected
     */    
    protected $mainModule; 

    /**
     * SubModule instance
     *
     * @var model instance
     * @access protected
     */    
    protected $subModule; 

    /**
     * SubModuleOverview instance
     *
     * @var model instance
     * @access protected
     */    
    protected $subModuleOverview;

    /**
     * SubModuleEligibility instance
     *
     * @var model instance
     * @access protected
     */    
    protected $subModuleEligibility;      

    /**
     * SubModuleSyllabus instance
     *
     * @var model instance
     * @access protected
     */    
    protected $subModuleSyllabus;

    /**
     * SubModuleExamPattern instance
     *
     * @var model instance
     * @access protected
     */    
    protected $subModuleExamPattern;

    /**
     * SubModuleHowToApply instance
     *
     * @var model instance
     * @access protected
     */    
    protected $subModuleHowToApply;

    /**
     * SubModuleStudyMaterial instance
     *
     * @var model instance
     * @access protected
     */    
    protected $subModuleStudyMaterial;

    /**
     * DiscussionQuestions instance
     *
     * @var model instance
     * @access protected
     */    
    protected $discussionQuestions;

    /**
     * DiscussionAnswers instance
     *
     * @var model instance
     * @access protected
     */    
    protected $discussionAnswers;

    /**
     * SampleQuestionPaper instance
     *
     * @var model instance
     * @access protected
     */    
    protected $questionPaper;

    /**
     * Exam instance
     *
     * @var model instance
     * @access protected
     */    
    protected $exam;

    /**
     * Mark instance
     *
     * @var model instance
     * @access protected
     */    
    protected $mark;

    function __construct(MainModule $mainModule, SubModule $subModule, SubModuleOverview $subModuleOverview, SubModuleEligibility $subModuleEligibility, SubModuleSyllabus $subModuleSyllabus, SubModuleExamPattern $subModuleExamPattern, SubModuleHowToApply $subModuleHowToApply, SubModuleStudyMaterial $subModuleStudyMaterial, DiscussionQuestions $discussionQuestions, DiscussionAnswers $discussionAnswers, SampleQuestionPaper $questionPaper, Exam $exam, Mark $mark) {
        $this->mainModule = $mainModule;
        $this->subModule = $subModule;
        $this->subModuleOverview = $subModuleOverview;
        $this->subModuleEligibility = $subModuleEligibility;
        $this->subModuleSyllabus = $subModuleSyllabus;
        $this->subModuleExamPattern = $subModuleExamPattern;
        $this->subModuleHowToApply = $subModuleHowToApply;
        $this->subModuleStudyMaterial = $subModuleStudyMaterial;
        $this->discussionQuestions = $discussionQuestions;
        $this->discussionAnswers = $discussionAnswers;
        $this->questionPaper = $questionPaper;
        $this->exam = $exam;
        $this->mark = $mark;
    }       

    /**
     * Get main module details.
     *
     * @return array $mainModules
     */
    public function getMainModules()
    {
        $mainModules = $this->mainModule::with('state','board','medium')->get();
        return $mainModules;
    }

    /**
     * Get activated main module details.
     *
     * @return array $mainModules
     */
    public function getActivatedMainModules()
    {
        $mainModules = $this->mainModule::where('status',1)->get();
        return $mainModules;
    }   

    /**
     * Get activated sub module details.
     *
     * @return array $subModules
     */
    public function getActivatedSubModulesForUsers()
    {   
        $subModules = $this->subModule::with('mainmodule')->where('main_module_id',Auth::user()->module_id)->where('status',1)->get();
        return $subModules;
    }       

    /**
     * Get main module by id.
     *
     * @param int $main_module_id
     * @return object $mainModule
     */
    public function getMainModuleByID($main_module_id)
    {
        $mainModule = $this->mainModule::find($main_module_id);
        return $mainModule;
    } 

    /**
     * Get activated sub modules by main module.
     *
     * @param string $main_module_slug
     * @return array $subModules
     */
    public function getActivatedSubModulesByMainModule($main_module_slug)
    {   
        $mainModule = $this->mainModule::where('slug',$main_module_slug)->first();
        $subModules = $this->subModule::where('main_module_id',$mainModule->id)->where('status',1)->get();
        return $subModules;
    }

    /**
     * Get main module by slug.
     *
     * @param string $main_module_slug
     * @return object $mainModule
     */
    public function getMainModuleBySlug($main_module_slug)
    {   
        $mainModule = $this->mainModule::where('slug',$main_module_slug)->first();
        return $mainModule;
    }    

    /**
     * Get discussion questions.
     *
     * @param string $sub_module_slug
     * @return array $discussionQuestions
     */
    public function getDiscussionQuestions($sub_module_slug)
    {   
        $subModule = $this->subModule::where('slug',$sub_module_slug)->first();
        $discussionQuestions = $this->discussionQuestions::where('sub_module_id',$subModule->id)->orderBy('created_at','DESC')->paginate(5);
        return $discussionQuestions;        
    }

    /**
     * Get single discussion question.
     *
     * @param string $slug
     * @return object $discussionQuestion
     */
    public function getSingleDiscussionQuestion($slug)
    {
        $discussionQuestion = $this->discussionQuestions::where('slug',$slug)->first();
        return $discussionQuestion;
    }   

    /**
     * Get discussion answers.
     *
     * @param string $slug
     * @return array $discussionAnswers
     */
    public function getDiscussionAnswers($slug)
    {   
        $discussionQuestion = $this->discussionQuestions::where('slug',$slug)->first();
        $discussionAnswers = $this->discussionAnswers::where('question_id',$discussionQuestion->id)->get();
        return $discussionAnswers;
    }     

    /**
     * Get related discussion questions.
     *
     * @param string $slug
     * @return array $relatedDiscussionQuestions
     */
    public function getRelatedDiscussionQuestions($slug)
    {   
        $discussionQuestion = $this->discussionQuestions::where('slug',$slug)->first();
        $relatedDiscussionQuestions = $this->discussionQuestions::where('id','!=',$discussionQuestion->id)->orderBy('created_at','DESC')->paginate(5);
        return $relatedDiscussionQuestions;
    }     

    /**
     * Get sample question papers.
     *
     * @param string $sub_module_slug
     * @return array $sampleQuestions
     */
    public function getSampleQuestionPapers($sub_module_slug)
    {   
        $subModule = $this->subModule::where('slug',$sub_module_slug)->first();
        $sampleQuestions = $this->questionPaper::where('sub_module_id',$subModule->id)->get();
        return $sampleQuestions;        
    } 

    /**
     * Get exam for online test.
     *
     * @param string $sub_module_slug
     * @return array $exams
     */
    public function getExamsForOnlineTest($sub_module_slug)
    {   
        $subModule = $this->subModule::where('slug',$sub_module_slug)->first();
        $exams = $this->exam::where('sub_module_id',$subModule->id)->where('status',1)->paginate(5);
        return $exams;
    } 

    /**
     * Get results for online test.
     *
     * @param string $sub_module_slug
     * @return array $attendedExams
     */
    public function getResultsForOnlineTest($sub_module_slug)
    {   
        $subModule = $this->subModule::where('slug',$sub_module_slug)->first();
        $totalExams = $this->exam::where('sub_module_id',$subModule->id)->where('status',1)->get();
        $marks = $this->mark::where('user_id',Auth::user()->id)->get();
        $markExamArray = [];
        $finalArray = [];
        foreach ($marks as $key => $mark) {
            $markExamArray[] = $mark->exam_id;
        }
        foreach ($totalExams as $key => $exam) {
            if(in_array($exam->id, $markExamArray)){
                $finalArray[] = $exam->id;
            }
        }        
        $attendedExams = $this->exam::whereIn('id',$finalArray)->paginate(5);        
        return $attendedExams;
    } 

    /**
     * Store main module form data.
     *
     * @param array $value
     * @return object $mainModule
     */               
    public function postMainModule($value)
    {   
        $this->mainModule->state_id = $value['state'];
        $this->mainModule->board_id = $value['board_type'];
        $this->mainModule->medium_id = $value['medium'];
        $this->mainModule->name = $value['main_module_name'];
        $this->mainModule->status = 1;
        $this->mainModule->save();
        return $this->mainModule;
    } 

    /**
     * Edit main module.
     *
     * @param string $slug
     * @return object $edit
     */ 
    public function editMainModule($slug)
    {   
        $edit = $this->mainModule::where('slug',$slug)->first();
        return $edit;
    }

    /**
     * Update main module.
     *
     * @param array $value
     * @param string $slug
     * @return object $update
     */ 
    public function updateMainModule($value,$slug)
    {
        $update = $this->mainModule::where('slug',$slug)->first();
        $update->name = $value['main_module_name'];
        $update->update();
        return $update;
    }

    /**
     * Activate main module.
     *
     * @param string $slug
     * @return object $obj
     */ 
    public function activateMainModule($slug)
    {
        $obj = $this->mainModule::where('slug',$slug)->first();
        $obj->status = 1;
        $obj->update();
        return $obj;
    }

    /**
     * Deactivate main module.
     *
     * @param string $slug
     * @return object $obj
     */ 
    public function deactivateMainModule($slug)
    {
        $obj = $this->mainModule::where('slug',$slug)->first();
        $obj->status = 0;
        $obj->update();
        return $obj;
    }

    /**
     * Delete main module.
     *
     * @param string $slug
     * @return string "success" | "error"
     */
    public function deleteMainModule($slug)
    {   
        $mainModule = $this->mainModule::where('slug',$slug)->first();
        $parentMainModuleCount = $this->subModule::where('main_module_id',$mainModule->id)->count();
        if($parentMainModuleCount == 0){
            $delete = $this->mainModule::where('slug',$slug)->first();
            $delete->delete();
            // return $delete;
            return "success";
        }else{
            return "error";
        }        
    }   

    /**
     * Get sub modules.
     *
     * @return array $subModules
     */
    public function getSubModules()
    {
        $subModules = $this->subModule::with('mainmodule')->get();
        return $subModules;
    }

    /**
     * Get sub module by slug.
     *
     * @param string $sub_module_slug
     * @return object $subModule
     */
    public function getSubModuleBySlug($sub_module_slug)
    {
        $subModule = $this->subModule::where('slug',$sub_module_slug)->first();
        return $subModule;
    }

    /**
     * Store sub module by slug.
     *
     * @param array $value
     * @return object $subModule
     */
    public function postSubModule($value)
    {   
        $this->subModule->main_module_id = $value['module_type'];
        $this->subModule->name = $value['sub_module_name'];
        $this->subModule->status = 1;
        $this->subModule->save();
        return $this->subModule;
    } 

    /**
     * Edit sub module.
     *
     * @param string $slug
     * @return object $edit
     */
    public function editSubModule($slug)
    {
        $edit = $this->subModule::where('slug',$slug)->first();
        return $edit;
    }

    /**
     * Update sub module.
     *
     * @param array $value
     * @param string $slug
     * @return object $update
     */
    public function updateSubModule($value,$slug)
    {   
        $update = $this->subModule::where('slug',$slug)->first();
        $update->name = $value['sub_module_name'];
        $update->update();
        return $update;
    }

    /**
     * Activate sub module.
     *
     * @param string $slug
     * @return object $obj
     */
    public function activateSubModule($slug)
    {
        $obj = $this->subModule::where('slug',$slug)->first();
        $obj->status = 1;
        $obj->update();
        return $obj;
    }

    /**
     * Deactivate sub module.
     *
     * @param string $slug
     * @return object $obj
     */
    public function deactivateSubModule($slug)
    {
        $obj = $this->subModule::where('slug',$slug)->first();
        $obj->status = 0;
        $obj->update();
        return $obj;
    }

    /**
     * Delete sub module.
     *
     * @param string $slug
     * @return string "success" | "error"
     */
    public function deleteSubModule($slug)
    {   
        $subModule = $this->subModule::where('slug',$slug)->first();
        $parentSubModuleCount = $this->exam::where('sub_module_id',$subModule->id)->count();
        if($parentSubModuleCount == 0){
            $delete = $this->subModule::where('slug',$slug)->first();
            $delete->delete();
            // return $delete;
            return "success";
        }else{
            return "error";
        }         
    } 

    /**
     * Store sub module form data.
     *
     * @param array $value
     * @return string $data
     */
    public function postSubModuleOverview($value)
    {   
        $subModule = $this->subModule::where('slug',$value['sub_module'])->first();
        $alreadyExistCount = $this->subModuleOverview::where('sub_module_id',$subModule->id)->count();
        if($alreadyExistCount == 0){
            $this->subModuleOverview->sub_module_id = $subModule->id;
            $this->subModuleOverview->content = $value['content'];
            $this->subModuleOverview->save();
            $data = "Overview has been inserted successfully";
        }else{
            $subModuleOverview = $this->subModuleOverview::where('sub_module_id',$subModule->id)->first();
            $subModuleOverview->content = $value['content'];
            $subModuleOverview->update();
            $data = "Overview has been updated successfully";
        }
        return $data;
    } 

    /**
     * Get sub module overview.
     *
     * @param string $sub_module_slug
     * @return object $subModuleOverview
     */
    public function getSubModuleOverview($sub_module_slug)
    {   
        $subModule = $this->subModule::where('slug',$sub_module_slug)->first();
        $subModuleOverview = $this->subModuleOverview::where('sub_module_id',$subModule->id)->first();
        return $subModuleOverview;
    }

    /**
     * Post sub module eligibility form data.
     *
     * @param array $value
     * @return string $data
     */
    public function postSubModuleEligibility($value)
    {   
        $subModule = $this->subModule::where('slug',$value['sub_module'])->first();
        $alreadyExistCount = $this->subModuleEligibility::where('sub_module_id',$subModule->id)->count();
        if($alreadyExistCount == 0){
            $this->subModuleEligibility->sub_module_id = $subModule->id;
            $this->subModuleEligibility->content = $value['content'];
            $this->subModuleEligibility->save();
            $data = "Eligibility has been inserted successfully";
        }else{
            $subModuleEligibility = $this->subModuleEligibility::where('sub_module_id',$subModule->id)->first();
            $subModuleEligibility->content = $value['content'];
            $subModuleEligibility->update();
            $data = "Eligibility has been updated successfully";
        }
        return $data;
    } 

    /**
     * Get sub module eligibility.
     *
     * @param string $sub_module_slug
     * @return object $subModuleEligibility
     */
    public function getSubModuleEligibility($sub_module_slug)
    {   
        $subModule = $this->subModule::where('slug',$sub_module_slug)->first();
        $subModuleEligibility = $this->subModuleEligibility::where('sub_module_id',$subModule->id)->first();
        return $subModuleEligibility;
    } 

    /**
     * Store sub module syllabus form data.
     *
     * @param array $value
     * @return string $data
     */
    public function postSubModuleSyllabus($value)
    {   
        $subModule = $this->subModule::where('slug',$value['sub_module'])->first();
        $alreadyExistCount = $this->subModuleSyllabus::where('sub_module_id',$subModule->id)->count();
        if($alreadyExistCount == 0){
            $this->subModuleSyllabus->sub_module_id = $subModule->id;
            $this->subModuleSyllabus->content = $value['content'];
            $this->subModuleSyllabus->save();
            $data = "Syllabus has been inserted successfully";
        }else{
            $subModuleSyllabus = $this->subModuleSyllabus::where('sub_module_id',$subModule->id)->first();
            $subModuleSyllabus->content = $value['content'];
            $subModuleSyllabus->update();
            $data = "Syllabus has been updated successfully";
        }
        return $data;
    } 

    /**
     * Get sub module syllabus.
     *
     * @param string $sub_module_slug
     * @return object $subModuleSyllabus
     */
    public function getSubModuleSyllabus($sub_module_slug)
    {       
        $subModule = $this->subModule::where('slug',$sub_module_slug)->first();
        $subModuleSyllabus = $this->subModuleSyllabus::where('sub_module_id',$subModule->id)->first();
        return $subModuleSyllabus;
    }

    /**
     * Store sub module exam pattern.
     *
     * @param array $value
     * @return string $data
     */
    public function postSubModuleExamPattern($value)
    {   
        $subModule = $this->subModule::where('slug',$value['sub_module'])->first();
        $alreadyExistCount = $this->subModuleExamPattern::where('sub_module_id',$subModule->id)->count();
        if($alreadyExistCount == 0){
            $this->subModuleExamPattern->sub_module_id = $subModule->id;
            $this->subModuleExamPattern->content = $value['content'];
            $this->subModuleExamPattern->save();
            $data = "Exam Pattern has been inserted successfully";
        }else{
            $subModuleExamPattern = $this->subModuleExamPattern::where('sub_module_id',$subModule->id)->first();
            $subModuleExamPattern->content = $value['content'];
            $subModuleExamPattern->update();
            $data = "Exam Pattern has been updated successfully";
        }
        return $data;
    } 

    /**
     * Get sub module exam pattern.
     *
     * @param string $sub_module_slug
     * @return object $subModuleExamPattern
     */
    public function getSubModuleExamPattern($sub_module_slug)
    {       
        $subModule = $this->subModule::where('slug',$sub_module_slug)->first();
        $subModuleExamPattern = $this->subModuleExamPattern::where('sub_module_id',$subModule->id)->first();
        return $subModuleExamPattern;
    }  

    /**
     * Post sub module how to apply.
     *
     * @param array $value
     * @return string $data
     */
    public function postSubModuleHowToApply($value)
    {   
        $subModule = $this->subModule::where('slug',$value['sub_module'])->first();
        $alreadyExistCount = $this->subModuleHowToApply::where('sub_module_id',$subModule->id)->count();
        if($alreadyExistCount == 0){
            $this->subModuleHowToApply->sub_module_id = $subModule->id;
            $this->subModuleHowToApply->content = $value['content'];
            $this->subModuleHowToApply->save();
            $data = "How To Apply Pattern has been inserted successfully";
        }else{
            $subModuleHowToApply = $this->subModuleHowToApply::where('sub_module_id',$subModule->id)->first();
            $subModuleHowToApply->content = $value['content'];
            $subModuleHowToApply->update();
            $data = "How To Apply Pattern has been updated successfully";
        }
        return $data;
    } 

    /**
     * Get sub module how to apply.
     *
     * @param string $sub_module_slug
     * @return object $subModuleHowToApply
     */
    public function getSubModuleHowToApply($sub_module_slug)
    {       
        $subModule = $this->subModule::where('slug',$sub_module_slug)->first();
        $subModuleHowToApply = $this->subModuleHowToApply::where('sub_module_id',$subModule->id)->first();
        return $subModuleHowToApply;
    } 

    /**
     * Store sub module study material.
     *
     * @param array $value
     * @return string $data
     */
    public function postSubModuleStudyMaterial($value)
    {   
        $subModule = $this->subModule::where('slug',$value['sub_module'])->first();
        $json = json_decode($value['angular_value'],true);
        foreach ($json as $key => $val) {
            $subModuleStudyMaterial = new SubModuleStudyMaterial();
            $subModuleStudyMaterial->sub_module_id = $subModule->id;
            $subModuleStudyMaterial->name = $val["Name"];
            $subModuleStudyMaterial->url = $val["Url"];
            $subModuleStudyMaterial->save();            
        }
        $data = "Study material has been inserted successfully";
        return $data;
    } 

    /**
     * Get sub module study material.
     *
     * @param string $sub_module_slug
     * @return array $subModuleStudyMaterial
     */
    public function getSubModuleStudyMaterial($sub_module_slug)
    {       
        $subModule = $this->subModule::where('slug',$sub_module_slug)->first();
        $subModuleStudyMaterial = $this->subModuleStudyMaterial::where('sub_module_id',$subModule->id)->get();
        return $subModuleStudyMaterial;
    }

    /**
     * Update sub module study material.
     *
     * @param array $value
     * @return string $data
     */
    public function updateSubModuleStudyMaterial($value)
    {   
        $IDArray = [];
        foreach($value as $key=> $val){
            $id = substr($key, strpos($key, "_") + 1);
            if(is_numeric($id)){
                if (!in_array($id, $IDArray)) {
                    array_push($IDArray, $id); 
                }                   
            }
        }

        foreach ($IDArray as $k => $v) {
            $subModuleStudyMaterial = $this->subModuleStudyMaterial::find($v);
            if(!empty($subModuleStudyMaterial)){
                $subject_name = $value['subject_name_'.$v];
                $url = $value['url_'.$v];
                if(!empty($subject_name) && !empty($subject_name)){
                    $subModuleStudyMaterial->name = $subject_name;
                    $subModuleStudyMaterial->url = $url;
                    $subModuleStudyMaterial->update();
                } 
            }           
        }        
        $data = "Study material has been updated successfully";
        return $data;
    }                                   
}

?>