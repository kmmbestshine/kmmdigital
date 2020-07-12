<?php

namespace App\Repositories\SubscribePlan;

use App\Repositories\SubscribePlan\SubscribePlanInterface as SubscribePlanInterface;
use App\Models\SubscribePlanMaster;
use App\Models\MainModule;

class SubscribePlanRepository implements SubscribePlanInterface
{
    public $subscribePlan;
    public $mainModule;

    function __construct(SubscribePlanMaster $subscribePlan, MainModule $mainModule) {
        $this->subscribePlan = $subscribePlan;
        $this->mainModule = $mainModule;
    }

    public function getSubscribePlans()
    {  
        $subscribePlans = $this->subscribePlan::with('mainmodule')->get();
        return $subscribePlans;
    }

    public function getActivatedMainModules()
    {  
        $mainModules = $this->mainModule::where('status',1)->get();
        return $mainModules;
    }

    public function store($value)
    {   
        $this->subscribePlan->main_module_id = $value['module_type'];
        $this->subscribePlan->name = $value['name'];
        $this->subscribePlan->amount = $value['amount'];
        $this->subscribePlan->duration = $value['time_duration'];
        $this->subscribePlan->limit = $value['limit'];
        $offer_applicable = (isset($value['offer_applicable'])) ? 1 : 0;
        $this->subscribePlan->offer_applicable = $offer_applicable;
        if($offer_applicable == 1){
            $this->subscribePlan->offer_percentage = $value['offer_percentage'];
            $this->subscribePlan->offer_start_date = $value['offer_start_date'];
            $this->subscribePlan->offer_end_date = $value['offer_end_date'];
        }
        $this->subscribePlan->save();
        return "Subscribe plan has been inserted successfully";
    }  

    public function delete($id)
    {   
        $plan = $this->subscribePlan::find($id);
        $plan->delete();
        return "Subscribe plan has been deleted successfully";
    }  
}

?>