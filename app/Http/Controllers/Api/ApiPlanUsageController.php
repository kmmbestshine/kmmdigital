<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PlanUsageDetail;
use App\Models\MainModule;

class ApiPlanUsageController extends Controller
{
    // public function getPlanUsage($user_id)
    // {   
    // 	$result = [];
    //     $usages = PlanUsageDetail::where('user_id',$user_id)->get();
    //     foreach ($usages as $key => $usage) {
    //     	if($usage->main_module_id){
    //     		$mainModule = MainModule::find($usage->main_module_id);
    //     		$temp['name'] = $mainModule->name;
    //     		$temp['plan_name'] = $usage->plan_name;
    //     		$temp['plan_limit'] = $usage->plan_limit;
    //             $temp['days_duration'] = $usage->days_duration;
    //             list($year, $month, $day) = array_values(date_parse($usage->subscribe_date));
    //             $date = strtotime($day.'-'.$month.'-'.$year);
    //             $date = strtotime("+$usage->days_duration day", $date);
    //             $expire_date = date('d-m-Y', $date);       
    //             $temp['expire_date'] = $expire_date;
    //     	}else{
    //     		$temp['name'] = "Free Trial";
    //     		$temp['plan_name'] = $usage->plan_name;
    //     		$temp['plan_limit'] = $usage->plan_limit;
    //             $temp['days_duration'] = $usage->days_duration;
    //             list($year, $month, $day) = array_values(date_parse($usage->subscribe_date));
    //             $date = strtotime($day.'-'.$month.'-'.$year);
    //             $date = strtotime("+$usage->days_duration day", $date);
    //             $expire_date = date('d-m-Y', $date);       
    //             $temp['expire_date'] = $expire_date;
    //     	}
    //         array_push($result, $temp);
    //     }
    //     return response()->json($result);
    // }

    /**
     *  Get plan usage. 
     *
     * @param int $user_id
     * @return array $result
     */
    public function getPlanUsage($user_id)
    {   
        $result = [];
        $usage = PlanUsageDetail::where('user_id',$user_id)->first();
        $mainModule = MainModule::find($usage->main_module_id);
        $result['name'] = $mainModule->name;
        $result['plan_name'] = $usage->plan_name;
        $result['plan_limit'] = "UNLIMITED";
        $result['days_duration'] = "UNLIMITED";     
        $result['expire_date'] = "-";        
        // $result['plan_limit'] = $usage->plan_limit;
        // $result['days_duration'] = $usage->days_duration;
        // list($year, $month, $day) = array_values(date_parse($usage->subscribe_date));
        // $date = strtotime($day.'-'.$month.'-'.$year);
        // $date = strtotime("+$usage->days_duration day", $date);
        // $expire_date = date('d-m-Y', $date);       
        // $result['expire_date'] = $expire_date;
        return response()->json($result);
    }    
}
