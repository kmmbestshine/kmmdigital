<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\SubModule;

class ApiSubModuleController extends Controller
{

    /**
     *  Get activated sub modules by user id. 
     *
     * @param int $user_id
     * @return array $subModules
     */	
   	public function getActivatedSubModulesByUser($user_id)
    {   
    	$user = User::find($user_id);
        $subModules = SubModule::where('main_module_id',$user->module_id)->where('status',1)->get();       
        return response()->json($subModules);
    }        
}
