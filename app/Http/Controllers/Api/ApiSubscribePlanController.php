
<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SubscribePlanMaster;

class ApiSubscribePlanController extends Controller
{
    
    /**
     *  Get all subscribe plans. 
     *
     * @return array $plans
     */    
   	public function getAllSubscribePlans()
    {   
        $plans = SubscribePlanMaster::get();
        return response()->json($plans);

		// $result = [];
		// $plans = SubscribePlanMaster::get();    
		// foreach ($plans as $key => $plan) {
		// 	$result[$plan->name] = $plan;
		// }
		// return response()->json($result);          
    }    
}
