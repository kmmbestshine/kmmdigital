<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SubModule;
use App\Models\SubModuleOverview;
use App\Models\SubModuleEligibility;
use App\Models\SubModuleSyllabus;
use App\Models\SubModuleExamPattern;
use App\Models\SubModuleHowToApply;
use App\Models\SampleQuestionPaper;
use App\Models\SubModuleStudyMaterial;

class ApiSubModuleDetailController extends Controller
{

    /**
     *  Get sub module overview. 
     *
     * @param int $sub_module
     * @return object $subModuleOverview
     */     
    public function getSubModuleOverview($sub_module)
    {   
        $subModule = SubModule::where('id',$sub_module)->first();
        $subModuleOverview = SubModuleOverview::where('sub_module_id',$subModule->id)->first();
        return response()->json($subModuleOverview);
    }

    /**
     *  Get sub module eligibility. 
     *
     * @param int $sub_module
     * @return object $subModuleEligibility
     */
    public function getSubModuleEligibility($sub_module)
    {   
        $subModule = SubModule::where('id',$sub_module)->first();
        $subModuleEligibility = SubModuleEligibility::where('sub_module_id',$subModule->id)->first();
        return response()->json($subModuleEligibility);
    }

    /**
     *  Get sub module syllabus. 
     *
     * @param int $sub_module
     * @return object $subModuleSyllabus
     */
    public function getSubModuleSyllabus($sub_module)
    {       
        $subModule = SubModule::where('id',$sub_module)->first();
        $subModuleSyllabus = SubModuleSyllabus::where('sub_module_id',$subModule->id)->first();
        return response()->json($subModuleSyllabus);
    }

    /**
     *  Get sub module exam pattern. 
     *
     * @param int $sub_module
     * @return object $subModuleExamPattern
     */
    public function getSubModuleExamPattern($sub_module)
    {       
        $subModule = SubModule::where('id',$sub_module)->first();
        $subModuleExamPattern = SubModuleExamPattern::where('sub_module_id',$subModule->id)->first();
        return response()->json($subModuleExamPattern);
    }

    /**
     *  Get sub module how to apply. 
     *
     * @param int $sub_module
     * @return object $subModuleHowToApply
     */
    public function getSubModuleHowToApply($sub_module)
    {       
        $subModule = SubModule::where('id',$sub_module)->first();
        $subModuleHowToApply = SubModuleHowToApply::where('sub_module_id',$subModule->id)->first();
        return response()->json($subModuleHowToApply);
    }

    /**
     *  Get sub module question papers. 
     *
     * @param int $sub_module
     * @return array $sampleQuestions
     */
    public function getSampleQuestionPapers($sub_module)
    {   
        $subModule = SubModule::where('id',$sub_module)->first();
        $sampleQuestions = SampleQuestionPaper::where('sub_module_id',$subModule->id)->get();
        return response()->json($sampleQuestions);        
    } 

    /**
     *  Get sub module study materials. 
     *
     * @param int $sub_module
     * @return array $studyMaterials
     */
    public function getStudyMaterials($sub_module)
    {   
        $subModule = SubModule::where('id',$sub_module)->first();
        $studyMaterials = SubModuleStudyMaterial::where('sub_module_id',$subModule->id)->get();
        return response()->json($studyMaterials);        
    }                                
}
