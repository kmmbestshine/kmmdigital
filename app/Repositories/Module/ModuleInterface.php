<?php 

namespace App\Repositories\Module;

interface ModuleInterface {
    
    /**
     * Get main modules.
     *
     */
    public function getMainModules();  
    
    /**
     * Get activated main modules.
     *
     */    
    public function getActivatedMainModules(); 

    /**
     * Get activated sub modules for users.
     *
     */    
    public function getActivatedSubModulesForUsers(); 

    /**
     * Get main modules by id.
     *
     * @param int $main_module_id
     */  
    public function getMainModuleByID($main_module_id); 

    /**
     * Get activated sub modules by main modules.
     *
     * @param string $main_module_slug
     */  
    public function getActivatedSubModulesByMainModule($main_module_slug); 

    /**
     * Get discussion questions.
     *
     * @param string $sub_module_slug
     */     
    public function getDiscussionQuestions($sub_module_slug);

    /**
     * Get single discussion question by slug.
     *
     * @param string $slug
     */  
    public function getSingleDiscussionQuestion($slug);

    /**
     * Get answers for discussion questions by slug.
     *
     * @param string $slug
     */ 
    public function getDiscussionAnswers($slug);   

    /**
     * Get related discussion questions by slug.
     *
     * @param string $slug
     */       
    public function getRelatedDiscussionQuestions($slug);     

    /**
     * Get sample question papers by sub_module_slug.
     *
     * @param string $sub_module_slug
     */    
    public function getSampleQuestionPapers($sub_module_slug);

    /**
     * Get exams for online test by sub_module_slug.
     *
     * @param string $sub_module_slug
     */     
    public function getExamsForOnlineTest($sub_module_slug); 

    /**
     * Get results for online test by sub_module_slug.
     *
     * @param string $sub_module_slug
     */     
    public function getResultsForOnlineTest($sub_module_slug); 

    /**
     * Store main module form data.
     *
     * @param array $value
     */
    public function postMainModule($value);       

    /**
     * Edit main module record.
     *
     * @param string $slug
     */
    public function editMainModule($slug);     

    /**
     * Update main module record.
     *
     * @param array $value
     * @param string $slug
     */
    public function updateMainModule($value,$slug); 

    /**
     * Activate main module.
     *
     * @param string $slug
     */
    public function activateMainModule($slug);         

    /**
     * Deactivate main module.
     *
     * @param string $slug
     */
    public function deactivateMainModule($slug);         

    /**
     * Delete main module.
     *
     * @param string $slug
     */
    public function deleteMainModule($slug);    

    /**
     * Get sub modules.
     *
     */
    public function getSubModules();  

    /**
     * Get main module by slug.
     *
     * @param string $main_module_slug
     */
    public function getMainModuleBySlug($main_module_slug);  

    /**
     * Get sub module by slug.
     *
     * @param string $sub_module_slug
     */
    public function getSubModuleBySlug($sub_module_slug);

    /**
     * Store sub module form data.
     *
     * @param array $value
     */
    public function postSubModule($value);       

    /**
     * Edit sub module record.
     *
     * @param string $slug
     */
    public function editSubModule($slug);     

    /**
     * Update sub module record.
     *
     * @param array $value
     * @param string $slug
     */
    public function updateSubModule($value,$slug); 

    /**
     * Activate sub module.
     *
     * @param string $slug
     */
    public function activateSubModule($slug);         

    /**
     * Deactivate sub module.
     *
     * @param string $slug
     */
    public function deactivateSubModule($slug);         

    /**
     * Delete sub module.
     *
     * @param string $slug
     */
    public function deleteSubModule($slug);

    /**
     * Store sub module overview details.
     *
     * @param array $value
     */
    public function postSubModuleOverview($value);  

    /**
     * Get sub module overview details.
     *
     * @param string $sub_module_slug
     */
    public function getSubModuleOverview($sub_module_slug);

    /**
     * Store sub module eligibility details.
     *
     * @param array $value
     */
    public function postSubModuleEligibility($value); 

    /**
     * Get sub module eligibility details.
     *
     * @param string $sub_module_slug
     */
    public function getSubModuleEligibility($sub_module_slug);

    /**
     * Store sub module syllabus details.
     *
     * @param array $value
     */
    public function postSubModuleSyllabus($value); 

    /**
     * Get sub module syllabus details.
     *
     * @param string $sub_module_slug
     */             
    public function getSubModuleSyllabus($sub_module_slug);

    /**
     * Store sub module exam pattern details.
     *
     * @param array $value
     */  
    public function postSubModuleExamPattern($value); 

    /**
     * Get sub module exam pattern details.
     *
     * @param string $sub_module_slug
     */             
    public function getSubModuleExamPattern($sub_module_slug);

    /**
     * Store sub module how to apply details.
     *
     * @param array $value
     */ 
    public function postSubModuleHowToApply($value); 

    /**
     * Get sub module how to apply details.
     *
     * @param string $sub_module_slug
     */             
    public function getSubModuleHowToApply($sub_module_slug);

    /**
     * Store sub module study material details.
     *
     * @param array $value
     */
    public function postSubModuleStudyMaterial($value); 

    /**
     * Get sub module study material details.
     *
     * @param string $sub_module_slug
     */    
    public function getSubModuleStudyMaterial($sub_module_slug);    

    /**
     * Update sub module study material details.
     *
     * @param array $value
     */             
    public function updateSubModuleStudyMaterial($value);      
}
    
?>