<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Mark;
use App\Models\Exam;
use App\Models\SubModule;
use App\Models\Payment;
use App\Models\PlanUsageDetail;
use App\Models\SubscribePlanMaster;
use Carbon\Carbon;

class ApiPaymentController extends Controller
{
    // public function upgradePlan(Request $request)
    // {   
    //  $value = $request->all();
    //     $subscribePlanObj = SubscribePlanMaster::find($value['subscribe_id']);
    //     $user_id = $value['user_id'];

    //     // check offer applicable
    //     if($subscribePlanObj->offer_applicable == 1){
    //         $today_date = date('d-m-Y');        
    //         $start_date = $subscribePlanObj->offer_start_date;
    //         $end_date = $subscribePlanObj->offer_end_date;
    //         // Convert to timestamp
    //         $start_ts = strtotime($start_date);
    //         $end_ts = strtotime($end_date);
    //         $today_ts = strtotime($today_date);

    //         // Check that user date is between start & end
    //         if(($today_ts >= $start_ts) && ($today_ts <= $end_ts)){
    //             $offer_amount = $subscribePlanObj->offer_percentage / 100 * $subscribePlanObj->amount;
    //             $plan_amount = $subscribePlanObj->amount - $offer_amount;
    //         }else{
    //             $plan_amount = $subscribePlanObj->amount;
    //         }
    //     }else{
    //         $plan_amount = $subscribePlanObj->amount;
    //     }
        
    //     // paymnent gate for sending here

    //     $paymentObj = new Payment();
    //     $paymentObj->user_id = $user_id;
    //     $paymentObj->plan_name = $subscribePlanObj->name;
    //     $paymentObj->plan_amount = $subscribePlanObj->amount;
    //     $paymentObj->payment_date = Carbon::now();
    //     $paymentObj->save();
    //     $this->updatePlan($subscribePlanObj,$paymentObj);
    //     return response()->json("plan upgraded successfully");
    // }

    // public function updatePlan($subscribePlanObj,$paymentObj)
    // {   
    //     $plan = PlanUsageDetail::where('user_id',$paymentObj->user_id)->first();
    //     $plan->plan_name = $subscribePlanObj->name;
    //     $plan->plan_limit = $subscribePlanObj->limit;
    //     $plan->days_duration = $subscribePlanObj->duration;
    //     $plan->subscribe_date = Carbon::now();
    //     $plan->update();
    // }

    /**
     *  Upgrade plan.
     *
     * @param Request $request
     * @return string $result
     */
    public function upgradePlan(Request $request)
    {   
        $value = $request->all();
        $subscribePlanObj = SubscribePlanMaster::find($value['subscribe_id']);
        $user_id = $value['user_id'];
        // check offer applicable
        if($subscribePlanObj->offer_applicable == 1){
            $today_date = date('d-m-Y');        
            $start_date = $subscribePlanObj->offer_start_date;
            $end_date = $subscribePlanObj->offer_end_date;
            // Convert to timestamp
            $start_ts = strtotime($start_date);
            $end_ts = strtotime($end_date);
            $today_ts = strtotime($today_date);

            // Check that user date is between start & end
            if(($today_ts >= $start_ts) && ($today_ts <= $end_ts)){
                $offer_amount = $subscribePlanObj->offer_percentage / 100 * $subscribePlanObj->amount;
                $plan_amount = $subscribePlanObj->amount - $offer_amount;
            }else{
                $plan_amount = $subscribePlanObj->amount;
            }
        }else{
            $plan_amount = $subscribePlanObj->amount;
        }
 
        // paymnent gate for sending here
        $paymentObj = new Payment();
        $paymentObj->user_id = $user_id;
        $paymentObj->main_module_id = $subscribePlanObj->main_module_id;
        $paymentObj->plan_name = $subscribePlanObj->name;
        $paymentObj->plan_amount = $plan_amount;
        $paymentObj->payment_date = Carbon::now();
        $paymentObj->save();
        $this->updatePlanUsage($subscribePlanObj,$paymentObj);
        return response()->json("plan upgraded successfully");
    }

    /**
     *  Upgrade plan usage.
     *
     * @param object $subscribePlanObj
     * @param object $paymentObj
     * @return void
     */
    public function updatePlanUsage($subscribePlanObj,$paymentObj)
    {   
        $plan = PlanUsageDetail::where('user_id',$paymentObj->user_id)->where('main_module_id',$subscribePlanObj->main_module_id)->first();
        if($plan){
            $plan->main_module_id = $subscribePlanObj->main_module_id;
            $plan->plan_name = $subscribePlanObj->name;
            $plan->plan_limit = $subscribePlanObj->limit;
            $plan->days_duration = $subscribePlanObj->duration;
            $plan->subscribe_date = Carbon::now();
            $plan->update();
        }else{
            $planUsageDetailObj = new PlanUsageDetail();
            $planUsageDetailObj->user_id = $paymentObj->user_id;
            $planUsageDetailObj->main_module_id = $subscribePlanObj->main_module_id;
            $planUsageDetailObj->plan_name = $subscribePlanObj->name;
            $planUsageDetailObj->plan_limit = $subscribePlanObj->limit;
            $planUsageDetailObj->days_duration = $subscribePlanObj->duration;
            $planUsageDetailObj->subscribe_date = Carbon::now();
            $planUsageDetailObj->save();
        }
    }      

    /**
     *  Validate plan.
     *
     * @param Request $request
     * @return string "allow"
     */
    public function validatePlan(Request $request)
    {   
        return response()->json("allow");
        // $value = $request->all();
        // $user_id = $value['user_id'];        
        // $exam_id = $value['exam_id'];        
        // // check plan upgrade
        // // check user is in free trial count
        // $exam = Exam::find($exam_id);
        // $subModule = SubModule::where('id',$exam->sub_module_id)->first();   
        // $userIsInSubscribePlanCount = PlanUsageDetail::where('user_id',$user_id)->where('main_module_id',$subModule->main_module_id)->count();     
        // if($userIsInSubscribePlanCount == 1){
        //     $plan = PlanUsageDetail::where('user_id',$user_id)->where('main_module_id',$subModule->main_module_id)->first(); 
        //     $now = time();
        //     $subscribe_date = strtotime($plan->subscribe_date);
        //     $datediff = $now - $subscribe_date;

        //     $days = floor($datediff / (60 * 60 * 24));

        //     if($plan->plan_limit > 0 && $plan->days_duration > $days){
        //         $examCount = Mark::where('exam_id',$exam->id)->where('user_id',$user_id)->count();
        //         if($examCount == 0){
        //             return response()->json("allow");
        //         }else{                
        //             return response()->json("already taken this exam");
        //         }
        //     }else{
        //         return response()->json("upgrade your plan");
        //     } 
        // }else{         
        //     $plan = PlanUsageDetail::where('user_id',$user_id)->where('plan_name',"Free Trial")->first();
        //     $now = time();
        //     $subscribe_date = strtotime($plan->subscribe_date);
        //     $datediff = $now - $subscribe_date;

        //     $days = floor($datediff / (60 * 60 * 24));

        //     if($plan->plan_limit > 0 && $plan->days_duration > $days){
        //         $examCount = Mark::where('exam_id',$exam_id)->where('user_id',$user_id)->count();
        //         if($examCount == 0){
        //             return response()->json("allow");
        //         }else{                
        //             return response()->json("already taken this exam");
        //         }
        //     }else{
        //         return response()->json("upgrade your plan");
        //     }                             
        // }         
    }      
}
