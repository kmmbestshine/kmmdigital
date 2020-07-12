<?php

namespace App\Repositories\Chart;

use App\Repositories\Chart\ChartInterface as ChartInterface;
use Auth;
use App\Models\SubModule;
use App\Models\Mark;
use Charts;

class ChartRepository implements ChartInterface
{   
    public $colorMaster;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->colorMaster = ['#2196F3', '#F44336', '#FFC107', '#008000', '#5F9EA0', '00FFFF', '#DEB887', '#A52A2A', '#6495ED', '#DC143C', '#008B8B', '#B8860B', '#006400', '#ADFF2F', '#FF69B4'];
    }   
    
    public function prepareChart()
    {  	
        $subModuleNameArray = [];      
        $colorArray = [];      
        $subModules = SubModule::where('main_module_id',Auth::user()->module_id)->select('id','name')->get();
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

        $chart = Charts::create('bar', 'highcharts')
        ->title("Progress Details")
        ->elementLabel("Subject/Percentage")
        ->dimensions(1000, 500)
        ->colors($colorArray)
        ->labels($subModuleNameArray)
        ->values($percentageArray);

        return $chart;
    }         
}

?>