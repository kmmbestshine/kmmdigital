<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Module\ModuleInterface as ModuleInterface;
use App\Repositories\Common\CommonInterface as CommonInterface;
use App\Http\Requests\MainModuleFormRequest;
use App\Http\Requests\SubModuleFormRequest;
use App\Http\Requests\SubModuleOverviewFormRequest;
use App\Http\Requests\SubModuleEligibilityFormRequest;
use App\Http\Requests\SubModuleSyllabusFormRequest;
use App\Http\Requests\SubModuleExamPatternFormRequest;
use App\Http\Requests\SubModuleHowToApplyFormRequest;
use App\Http\Requests\SubModuleStudyMaterialFormRequest;
use App\Models\MainModule;
use App\Models\SubModule;
use App\Models\Exam;

class ModuleController extends Controller
{

    /**
     * ModuleInterface instance
     *
     * @var Interface instance
     * @access protected
     */    
    protected $module;

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
    public function __construct(ModuleInterface $module, CommonInterface $common)
    {
        $this->middleware('admin', ['only' => ['mainModule','createMainModule']]);
        $this->module = $module;
        $this->common = $common;
    }   

    /**
     * Display main module details.
     *
     * @return \Illuminate\Http\Response
     */
    public function mainModule()
    {   
        $mainModules = $this->module->getMainModules();     
        return view('module.main.index',compact('mainModules'));
    }  

    /**
     * Display sub module details.
     *
     * @param string $main_module_slug
     * @return \Illuminate\Http\Response
     */
    public function getSubModules($main_module_slug)
    {   
        $result = $this->checkMainModuleModelExistBySlug($main_module_slug);
        if($result){
            $subModules = $this->module->getActivatedSubModulesByMainModule($main_module_slug); 
            $mainModule = $this->module->getMainModuleBySlug($main_module_slug); 
            return view('module.sub.list',compact('mainModule','subModules','main_module_slug'));
        }else{
            return redirect()->route('showMessages', ['msg' => '404']);
        }
    }    

    /**
     * Display sub module overview details.
     *
     * @param string $main_module_slug
     * @param string $sub_module_slug
     * @return \Illuminate\Http\Response
     */
    public function getSubModuleOverview($main_module_slug,$sub_module_slug)
    {   
        $result = $this->checkSubModuleModelExistBySlug($sub_module_slug);
        if($result){
            $mainModule = $this->module->getMainModuleBySlug($main_module_slug);
            $subModule = $this->module->getSubModuleBySlug($sub_module_slug);
            $overview = $this->module->getSubModuleOverview($sub_module_slug);
            return view('module.sub.details.overview',compact('mainModule','subModule','overview','main_module_slug','sub_module_slug'));
        }else{
            return redirect()->route('showMessages', ['msg' => '404']);
        }
    }        

    /**
     * Display sub module eligibility details.
     *
     * @param string $main_module_slug
     * @param string $sub_module_slug     
     * @return \Illuminate\Http\Response
     */
    public function getSubModuleEligibility($main_module_slug,$sub_module_slug)
    {   
        $result = $this->checkSubModuleModelExistBySlug($sub_module_slug);
        if($result){
            $mainModule = $this->module->getMainModuleBySlug($main_module_slug);
            $subModule = $this->module->getSubModuleBySlug($sub_module_slug);            
            $eligibility = $this->module->getSubModuleEligibility($sub_module_slug);
            return view('module.sub.details.eligibility',compact('mainModule','subModule','eligibility','main_module_slug','sub_module_slug'));
        }else{
            return redirect()->route('showMessages', ['msg' => '404']);
        }        
    }  

    /**
     * Display sub module syllabus details.
     *
     * @param string $main_module_slug
     * @param string $sub_module_slug     
     * @return \Illuminate\Http\Response
     */
    public function getSubModuleSyllabus($main_module_slug,$sub_module_slug)
    {   
        $result = $this->checkSubModuleModelExistBySlug($sub_module_slug);
        if($result){
            $mainModule = $this->module->getMainModuleBySlug($main_module_slug);
            $subModule = $this->module->getSubModuleBySlug($sub_module_slug);             
            $syllabus = $this->module->getSubModuleSyllabus($sub_module_slug);
            return view('module.sub.details.syllabus',compact('mainModule','subModule','syllabus','main_module_slug','sub_module_slug'));            
        }else{
            return redirect()->route('showMessages', ['msg' => '404']);
        }        
    }

    /**
     * Display sub module exam pattern details.
     *
     * @param string $main_module_slug
     * @param string $sub_module_slug     
     * @return \Illuminate\Http\Response
     */
    public function getSubModuleExamPattern($main_module_slug,$sub_module_slug)
    {   
        $result = $this->checkSubModuleModelExistBySlug($sub_module_slug);
        if($result){
            $mainModule = $this->module->getMainModuleBySlug($main_module_slug);
            $subModule = $this->module->getSubModuleBySlug($sub_module_slug);             
            $examPattern = $this->module->getSubModuleExamPattern($sub_module_slug);
            return view('module.sub.details.exam-pattern',compact('mainModule','subModule','examPattern','main_module_slug','sub_module_slug'));            
        }else{
            return redirect()->route('showMessages', ['msg' => '404']);
        }        
    }  

    /**
     * Display sub module how to apply details.
     *
     * @param string $main_module_slug
     * @param string $sub_module_slug     
     * @return \Illuminate\Http\Response
     */    
    public function getSubModuleHowToApply($main_module_slug,$sub_module_slug)
    {   
        $result = $this->checkSubModuleModelExistBySlug($sub_module_slug);
        if($result){
            $mainModule = $this->module->getMainModuleBySlug($main_module_slug);
            $subModule = $this->module->getSubModuleBySlug($sub_module_slug);             
            $apply = $this->module->getSubModuleHowToApply($sub_module_slug);
            return view('module.sub.details.how-to-apply',compact('mainModule','subModule','apply','main_module_slug','sub_module_slug'));            
        }else{
            return redirect()->route('showMessages', ['msg' => '404']);
        }        
    }

    /**
     * Display sub module discussion details.
     *
     * @param string $main_module_slug
     * @param string $sub_module_slug     
     * @return \Illuminate\Http\Response
     */ 
    public function getSubModuleDiscussion($main_module_slug,$sub_module_slug)
    {   
        $result = $this->checkSubModuleModelExistBySlug($sub_module_slug);
        if($result){
            $mainModule = $this->module->getMainModuleBySlug($main_module_slug);
            $subModule = $this->module->getSubModuleBySlug($sub_module_slug);             
            $discussionQuestions = $this->module->getDiscussionQuestions($sub_module_slug);       
            return view('module.sub.details.discuss',compact('mainModule','subModule','discussionQuestions','main_module_slug','sub_module_slug'));            
        }else{
            return redirect()->route('showMessages', ['msg' => '404']);
        }        
    } 

    /**
     * Show discussion question with answers.
     *
     * @param string $main_module_slug
     * @param string $sub_module_slug     
     * @param string $slug     
     * @return \Illuminate\Http\Response
     */ 
    public function showDiscussionQuestionWithAnswer($main_module_slug,$sub_module_slug,$slug)
    {   
        $mainModule = $this->module->getMainModuleBySlug($main_module_slug);
        $subModule = $this->module->getSubModuleBySlug($sub_module_slug);        
        $discussionQuestion = $this->module->getSingleDiscussionQuestion($slug);     
        $discussionAnswers = $this->module->getDiscussionAnswers($slug); 
        $relatedDiscussionQuestions = $this->module->getRelatedDiscussionQuestions($slug); 
        return view('module.sub.details.discuss-answer',compact('mainModule','subModule','discussionQuestion','discussionAnswers','relatedDiscussionQuestions','main_module_slug','sub_module_slug'));
    }    

    /**
     * Display sub module study material details.
     *
     * @param string $main_module_slug
     * @param string $sub_module_slug       
     * @return \Illuminate\Http\Response
     */
    public function getSubModuleStudyMaterial($main_module_slug,$sub_module_slug)
    {   
        $result = $this->checkSubModuleModelExistBySlug($sub_module_slug);
        if($result){
            $mainModule = $this->module->getMainModuleBySlug($main_module_slug);
            $subModule = $this->module->getSubModuleBySlug($sub_module_slug);             
            $studyMaterials = $this->module->getSubModuleStudyMaterial($sub_module_slug);
            return view('module.sub.details.study-material',compact('mainModule','subModule','studyMaterials','main_module_slug','sub_module_slug'));            
        }else{
            return redirect()->route('showMessages', ['msg' => '404']);
        }        
    } 

    /**
     * Display sub module model papers details.
     *
     * @param string $main_module_slug
     * @param string $sub_module_slug       
     * @return \Illuminate\Http\Response
     */
    public function getSubModuleModelPaper($main_module_slug,$sub_module_slug)
    {   
        $result = $this->checkSubModuleModelExistBySlug($sub_module_slug);
        if($result){
            $mainModule = $this->module->getMainModuleBySlug($main_module_slug);
            $subModule = $this->module->getSubModuleBySlug($sub_module_slug);             
            $sampleQuestions = $this->module->getSampleQuestionPapers($sub_module_slug);
            return view('module.sub.details.model-paper',compact('mainModule','subModule','sampleQuestions','main_module_slug','sub_module_slug'));            
        }else{
            return redirect()->route('showMessages', ['msg' => '404']);
        }         
    } 

    /**
     * Display sub module online test details.
     *
     * @param string $main_module_slug
     * @param string $sub_module_slug       
     * @return \Illuminate\Http\Response
     */
    public function getSubModuleOnlineTest($main_module_slug,$sub_module_slug)
    {   
        $result = $this->checkSubModuleModelExistBySlug($sub_module_slug);
        if($result){
            $mainModule = $this->module->getMainModuleBySlug($main_module_slug);
            $subModule = $this->module->getSubModuleBySlug($sub_module_slug);             
            $exams = $this->module->getExamsForOnlineTest($sub_module_slug);
            return view('module.sub.details.online-test',compact('mainModule','subModule','exams','main_module_slug','sub_module_slug'));            
        }else{
            return redirect()->route('showMessages', ['msg' => '404']);
        }        
    }  

    /**
     * Display sub module test results.
     *
     * @param string $main_module_slug
     * @param string $sub_module_slug       
     * @return \Illuminate\Http\Response
     */
    public function getSubModuleResult($main_module_slug,$sub_module_slug)
    {   
        $result = $this->checkSubModuleModelExistBySlug($sub_module_slug);
        if($result){
            $mainModule = $this->module->getMainModuleBySlug($main_module_slug);
            $subModule = $this->module->getSubModuleBySlug($sub_module_slug);             
            $attendedExams = $this->module->getResultsForOnlineTest($sub_module_slug);
            return view('module.sub.details.result',compact('mainModule','subModule','attendedExams','main_module_slug','sub_module_slug'));             
        }else{
            return redirect()->route('showMessages', ['msg' => '404']);
        }        
    }  
             
    /**
     * Display main module form.
     *      
     * @return \Illuminate\Http\Response
     */
    public function createMainModule()
    {   
        $states = $this->common->getAllStates();
        $boards = $this->common->getAllBoards(); 
        $mediums = $this->common->getAllMediums();         
        return view('module.main.create',compact('states','boards','mediums'));
    } 

    /**
     * Store main module form data.
     * 
     * @param MainModuleFormRequest $request    
     * @return \Illuminate\Http\Response
     */
    public function postMainModule(MainModuleFormRequest $request)
    {   
        $value = $request->all();
        $data = $this->module->postMainModule($value); 
        return redirect()->route('mainModuleIndex')->with('postMainModuleSuccess', "Standard has been inserted successfully");  
    }  

    /**
     * Edit main module form data.
     * 
     * @param string $slug    
     * @return \Illuminate\Http\Response
     */
    public function editMainModule($slug)
    {   
        $result = $this->checkMainModuleModelExistBySlug($slug);
        if($result){
            $edit = $this->module->editMainModule($slug);             
            return view('module.main.edit',compact('edit')); 
        }else{
            return redirect()->route('showMessages', ['msg' => '404']);
        }
    }    

    /**
     * Update main module form data.
     * 
     * @param MainModuleFormRequest $request    
     * @param string $slug    
     * @return \Illuminate\Http\Response
     */
    public function updateMainModule(MainModuleFormRequest $request,$slug)
    {   
        $value = $request->all();
        $data = $this->module->updateMainModule($value,$slug); 
        return redirect()->route('mainModuleIndex')->with('updateMainModuleSuccess', "Standard has been updated successfully");  
    }  

    /**
     * Activate main module.
     *  
     * @param string $slug    
     * @return \Illuminate\Http\Response
     */
    public function activateMainModule($slug)
    {   
        $data = $this->module->activateMainModule($slug); 
        return redirect()->route('mainModuleIndex')->with('activateMainModuleSuccess', "Standard has been activated successfully");  
    }  

    /**
     * Deactivate main module.
     *  
     * @param string $slug    
     * @return \Illuminate\Http\Response
     */
    public function deactivateMainModule($slug)
    {   
        $data = $this->module->deactivateMainModule($slug); 
        return redirect()->route('mainModuleIndex')->with('deactivateMainModuleSuccess', "Standard has been deactivated successfully");  
    }  

    /**
     * Delete main module.
     *  
     * @param string $slug    
     * @return \Illuminate\Http\Response
     */
    public function deleteMainModule($slug)
    {   
        $data = $this->module->deleteMainModule($slug); 
        if($data == "success"){
            return redirect()->route('mainModuleIndex')->with('deleteMainModuleSuccess', "Standard has been deleted successfully");            
        }else{
            return redirect()->route('mainModuleIndex')->with('deleteMainModuleAlert', "Can't delete parent standard");
        }          
    }   

    /**
     * Display sub module list.
     *     
     * @return \Illuminate\Http\Response
     */
    public function subModule()
    {   
        $subModules = $this->module->getSubModules();  
        $moduleDetails = ['overview','eligibility','syllabus','exam-pattern','apply','study-material'];
        return view('module.sub.index',compact('subModules','moduleDetails'));
    }  

    /**
     * Show sub module form.
     *     
     * @return \Illuminate\Http\Response
     */
    public function createSubModule()
    {   
        $states = $this->common->getAllStates();
        $boards = $this->common->getAllBoards(); 
        $mediums = $this->common->getAllMediums();        
        $mainModules = $this->module->getActivatedMainModules(); 
        return view('module.sub.create',compact('states','boards','mediums','mainModules'));
    } 

    /**
     * Store sub module form data.
     *     
     * @param SubModuleFormRequest $request     
     * @return \Illuminate\Http\Response
     */
    public function postSubModule(SubModuleFormRequest $request)
    {   
        $value = $request->all();
        $data = $this->module->postSubModule($value); 
        return redirect()->route('subModuleIndex')->with('postSubModuleSuccess', "Subject has been inserted successfully");  
    }  

    /**
     * Edit sub module record.
     *     
     * @param string $slug     
     * @return \Illuminate\Http\Response
     */
    public function editSubModule($slug)
    {   
        $result = $this->checkSubModuleModelExistBySlug($slug);
        if($result){
            $edit = $this->module->editSubModule($slug); 
            $mainModule = $this->module->getMainModuleByID($edit->main_module_id);             
            return view('module.sub.edit',compact('edit','mainModule'));
        }else{
            return redirect()->route('showMessages', ['msg' => '404']);
        } 
    }    

    /**
     * Update sub module record.
     *     
     * @param SubModuleFormRequest $request     
     * @param string $slug     
     * @return \Illuminate\Http\Response
     */
    public function updateSubModule(SubModuleFormRequest $request,$slug)
    {   
        $value = $request->all();
        $data = $this->module->updateSubModule($value,$slug); 
        return redirect()->route('subModuleIndex')->with('updateSubModuleSuccess', "Subject has been updated successfully");  
    }  

    /**
     * Activate sub module.
     *     
     * @param string $slug       
     * @return \Illuminate\Http\Response
     */
    public function activateSubModule($slug)
    {   
        $data = $this->module->activateSubModule($slug); 
        return redirect()->route('subModuleIndex')->with('activateSubModuleSuccess', "Subject has been activated successfully");  
    }  

    /**
     * Deactivate sub module.
     *     
     * @param string $slug       
     * @return \Illuminate\Http\Response
     */
    public function deactivateSubModule($slug)
    {   
        $data = $this->module->deactivateSubModule($slug); 
        return redirect()->route('subModuleIndex')->with('deactivateSubModuleSuccess', "Subject has been deactivated successfully");  
    }  

    /**
     * Delete sub module.
     *     
     * @param string $slug       
     * @return \Illuminate\Http\Response
     */
    public function deleteSubModule($slug)
    {   
        $data = $this->module->deleteSubModule($slug); 
        if($data == "success"){
            return redirect()->route('subModuleIndex')->with('deleteSubModuleSuccess', "Subject has been deleted successfully");            
        }else{
            return redirect()->route('subModuleIndex')->with('deleteSubModuleAlert', "Can't delete parent subject");
        }          
    }  

    /**
     * Show sub module overview form.
     *     
     * @param string $sub_module_slug       
     * @return \Illuminate\Http\Response
     */
    public function createSubModuleOverview($sub_module_slug)
    {   
        $result = $this->checkSubModuleModelExistBySlug($sub_module_slug);
        if($result){
            $overview = $this->module->getSubModuleOverview($sub_module_slug);
            return view('module.sub.details.form.overview',compact('overview','sub_module_slug')); 
        }else{
            return redirect()->route('showMessages', ['msg' => '404']);
        }        
    }

    /**
     * Store sub module overview form data.
     *     
     * @param SubModuleOverviewFormRequest $request       
     * @return \Illuminate\Http\Response
     */
    public function postSubModuleOverview(SubModuleOverviewFormRequest $request)
    {   
        $value = $request->all();
        $data = $this->module->postSubModuleOverview($value); 
        return redirect()->route('subModuleIndex')->with('subModuleDetailsCURDSuccess', $data);        
    }     

    /**
     * Show sub module eligibility form.
     *     
     * @param string $sub_module_slug       
     * @return \Illuminate\Http\Response
     */
    public function createSubModuleEligibility($sub_module_slug)
    {   
        $result = $this->checkSubModuleModelExistBySlug($sub_module_slug);
        if($result){
            $eligibility = $this->module->getSubModuleEligibility($sub_module_slug);
            return view('module.sub.details.form.eligibility',compact('eligibility','sub_module_slug')); 
        }else{
            return redirect()->route('showMessages', ['msg' => '404']);
        }         
    }

    /**
     * Store sub module eligibility form data.
     *     
     * @param SubModuleEligibilityFormRequest $request       
     * @return \Illuminate\Http\Response
     */
    public function postSubModuleEligibility(SubModuleEligibilityFormRequest $request)
    {   
        $value = $request->all();
        $data = $this->module->postSubModuleEligibility($value); 
        return redirect()->route('subModuleIndex')->with('subModuleDetailsCURDSuccess', $data);         
    }     

    /**
     * Show sub module syllabus form.
     *     
     * @param string $sub_module_slug       
     * @return \Illuminate\Http\Response
     */
    public function createSubModuleSyllabus($sub_module_slug)
    {   
        $result = $this->checkSubModuleModelExistBySlug($sub_module_slug);
        if($result){
            $syllabus = $this->module->getSubModuleSyllabus($sub_module_slug);
            return view('module.sub.details.form.syllabus',compact('syllabus','sub_module_slug')); 
        }else{
            return redirect()->route('showMessages', ['msg' => '404']);
        }        
    }

    /**
     * Store sub module syllabus form data.
     *     
     * @param SubModuleSyllabusFormRequest $request       
     * @return \Illuminate\Http\Response
     */
    public function postSubModuleSyllabus(SubModuleSyllabusFormRequest $request)
    {   
        $value = $request->all();
        $data = $this->module->postSubModuleSyllabus($value); 
        return redirect()->route('subModuleIndex')->with('subModuleDetailsCURDSuccess', $data);
    } 
    
    /**
     * Show sub module exam pattern form.
     *     
     * @param string $sub_module_slug       
     * @return \Illuminate\Http\Response
     */
    public function createSubModuleExamPattern($sub_module_slug)
    {   
        $result = $this->checkSubModuleModelExistBySlug($sub_module_slug);
        if($result){
            $examPattern = $this->module->getSubModuleExamPattern($sub_module_slug);
            return view('module.sub.details.form.exam-pattern',compact('examPattern','sub_module_slug')); 
        }else{
            return redirect()->route('showMessages', ['msg' => '404']);
        }        
    }

    /**
     * Store sub module exam pattern form data.
     *     
     * @param SubModuleSyllabusFormRequest $request       
     * @return \Illuminate\Http\Response
     */
    public function postSubModuleExamPattern(SubModuleExamPatternFormRequest $request)
    {   
        $value = $request->all();
        $data = $this->module->postSubModuleExamPattern($value); 
        return redirect()->route('subModuleIndex')->with('subModuleDetailsCURDSuccess', $data);
    }     

    /**
     * Show sub module how to apply form.
     *     
     * @param string $sub_module_slug       
     * @return \Illuminate\Http\Response
     */
    public function createSubModuleHowToApply($sub_module_slug)
    {   
        $result = $this->checkSubModuleModelExistBySlug($sub_module_slug);
        if($result){
            $apply = $this->module->getSubModuleHowToApply($sub_module_slug);
            return view('module.sub.details.form.how-to-apply',compact('apply','sub_module_slug')); 
        }else{
            return redirect()->route('showMessages', ['msg' => '404']);
        }         
    }

    /**
     * Store sub module how to apply form data.
     *     
     * @param SubModuleHowToApplyFormRequest $request       
     * @return \Illuminate\Http\Response
     */
    public function postSubModuleHowToApply(SubModuleHowToApplyFormRequest $request)
    {   
        $value = $request->all();
        $data = $this->module->postSubModuleHowToApply($value); 
        return redirect()->route('subModuleIndex')->with('subModuleDetailsCURDSuccess', $data);
    }

    /**
     * Show sub module study material form.
     *     
     * @param string $sub_module_slug       
     * @return \Illuminate\Http\Response
     */
    public function createSubModuleStudyMaterial($sub_module_slug)
    {   
        $result = $this->checkSubModuleModelExistBySlug($sub_module_slug);
        if($result){
            $studyMaterials = $this->module->getSubModuleStudyMaterial($sub_module_slug);
            return view('module.sub.details.form.study-material',compact('studyMaterials','sub_module_slug')); 
        }else{
            return redirect()->route('showMessages', ['msg' => '404']);
        }         
    }

    /**
     * Store sub module study material form data.
     *     
     * @param SubModuleStudyMaterialFormRequest $request       
     * @return \Illuminate\Http\Response
     */
    public function postSubModuleStudyMaterial(SubModuleStudyMaterialFormRequest $request)
    {   
        $value = $request->all();
        $data = $this->module->postSubModuleStudyMaterial($value); 
        return redirect()->route('subModuleIndex')->with('subModuleDetailsCURDSuccess', $data);
    }

    /**
     * Update sub module study material detail.
     *     
     * @param Request $request       
     * @return \Illuminate\Http\Response
     */
    public function updateSubModuleStudyMaterial(Request $request)
    {   
        $value = $request->all();
        $data = $this->module->updateSubModuleStudyMaterial($value); 
        return redirect()->route('subModuleIndex')->with('subModuleDetailsCURDSuccess', $data);
    }          

    /**
     * Check main module model exist by slug.
     *     
     * @param string $slug       
     * @return boolean
     */
    protected function checkMainModuleModelExistBySlug($slug)
    {       
        $mainModule = MainModule::where('slug',$slug)->first();
        return (isset($mainModule)) ? true : false ;
    } 

    /**
     * Check sub module model exist by slug.
     *     
     * @param string $slug       
     * @return boolean
     */
    protected function checkSubModuleModelExistBySlug($slug)
    {    
        $subModule = SubModule::where('slug',$slug)->first();
        return (isset($subModule)) ? true : false ;
    }               
}
   