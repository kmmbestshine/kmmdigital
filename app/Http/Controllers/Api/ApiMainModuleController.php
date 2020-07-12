<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\MainModule;

class ApiMainModuleController extends Controller
{
    
    /**
     * Get activated main modules.
     *
     * @return array $mainModules
     */		
   	public function getActivatedMainModules()
    {   
        $mainModules = MainModule::where('status',1)->get();       
        return response()->json($mainModules);
    }    
}
