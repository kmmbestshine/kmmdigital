<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\SubModule;
use App\Models\Mark;

class ApiChartController extends Controller
{

    /**
     * Define color master
     *
     * @var array $colorMaster
     * @access protected
     */    
    protected $colorMaster; 

    // public $colorMaster;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->colorMaster = ['#2196F3', '#F44336', '#FFC107', '#008000', '#5F9EA0', '00FFFF', '#DEB887', '#A52A2A', '#6495ED', '#DC143C', '#008B8B', '#B8860B', '#006400', '#ADFF2F', '#FF69B4'];
    }   

    /**
     * Get chart details.
     *
     * @param int $user_id
     * @return array $result
     */
    public function getChartDetails($user_id)
    {   
    	$result = [];
        $subModuleNameArray = [];      
        $colorArray = [];
        $userObj = User::find($user_id);     
        $subModules = SubModule::where('main_module_id',$userObj->module_id)->select('id','name')->get();
        foreach ($subModules as  $subModule) {
            $subModuleNameArray[] = $subModule->name;
            $marks = Mark::where('sub_module_id',$subModule->id)->select('total_questions','mark')->get();
            $percentage = 0;
            $totalMark = 0;
            $obtainedMark = 0;
            foreach ($marks as $mark) {
                $totalMark += $mark->total_questions;
                $obtainedMark += $mark->mark;
            }
            if((!empty($totalMark))&&(!empty($obtainedMark)))
            $percentage = $obtainedMark/$totalMark*100;
            $percentageArray[] = $percentage;
        }
        // marking color array
        for ($i=0; $i <=count($subModuleNameArray) ; $i++) { 
            $colorArray[] = $this->colorMaster[$i];    
        }

        $result['color'] = $colorArray;
        $result['name'] = $subModuleNameArray;
        $result['value'] = $percentageArray;

        return response()->json($result);
    }     
}
