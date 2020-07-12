<?php

namespace App\Repositories\Payment;

use Auth;
use App\Models\Payment;
use App\Models\PlanUsageDetail;
use App\Models\SubscribePlanMaster;
use Carbon\Carbon;

class PaymentRepository implements PaymentInterface
{
    public $payment;
    public $plan;

    function __construct(SubscribePlanMaster $subscribePlan, Payment $payment, PlanUsageDetail $plan) {
        $this->subscribePlan = $subscribePlan;
        $this->payment = $payment;
		$this->plan = $plan;
    }

    public function getSubscribePlans($value)
    {  
        if(isset($value['module_type'])){            
            $subscribePlans = $this->subscribePlan::where('main_module_id',$value['module_type'])->get();
        }else{
            $subscribePlans = [];
        }   
        return $subscribePlans;
    }    

    public function store($value)
    {   
        $subscribePlanObj = $this->subscribePlan::find($value['subscribe_id']);

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

        $this->payment->user_id = Auth::user()->id;
        $this->payment->main_module_id = $subscribePlanObj->main_module_id;
        $this->payment->plan_name = $subscribePlanObj->name;
        $this->payment->plan_amount = $plan_amount;
        $this->payment->payment_date = Carbon::now();
        $this->payment->save();
        $this->updatePlanUsage($subscribePlanObj);
        return $this->payment;
    }

    public function updatePlanUsage($subscribePlanObj)
    {   
        // upgrade from free trail to paid services
        $firstTimeUpdatePlanCount = $this->plan::where('user_id',Auth::user()->id)->where('plan_name',"Free Trial")->count();
        if($firstTimeUpdatePlanCount == 1){
            $plan = $this->plan::where('user_id',Auth::user()->id)->where('plan_name',"Free Trial")->first();
            $plan->main_module_id = $subscribePlanObj->main_module_id;
            $plan->plan_name = $subscribePlanObj->name;
            $plan->plan_limit = $subscribePlanObj->limit;
            $plan->days_duration = $subscribePlanObj->duration;
            $plan->subscribe_date = Carbon::now();
            $plan->update();
        }else{
            $plan = $this->plan::where('user_id',Auth::user()->id)->where('main_module_id',$subscribePlanObj->main_module_id)->first();
            if($plan){
                $plan->main_module_id = $subscribePlanObj->main_module_id;
                $plan->plan_name = $subscribePlanObj->name;
                $plan->plan_limit = $subscribePlanObj->limit;
                $plan->days_duration = $subscribePlanObj->duration;
                $plan->subscribe_date = Carbon::now();
                $plan->update();
            }else{
                $this->plan->user_id = Auth::user()->id;
                $this->plan->main_module_id = $subscribePlanObj->main_module_id;
                $this->plan->plan_name = $subscribePlanObj->name;
                $this->plan->plan_limit = $subscribePlanObj->limit;
                $this->plan->days_duration = $subscribePlanObj->duration;
                $this->plan->subscribe_date = Carbon::now();
                $this->plan->save();
            }
        }
    }    
}

?>