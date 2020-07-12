<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Module\ModuleInterface as ModuleInterface;
use Auth;

class DashboardController extends Controller
{

    /**
     * ModuleInterface instance
     *
     * @var Interface instance
     * @access protected
     */    
    protected $module; 
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ModuleInterface $module)
    {
        $this->module = $module;
    }

    /**
     * Show the dashboard page.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {   
        if(Auth::User()->roles->first()->name == "Admin"){            
            $mainModules = $this->module->getActivatedMainModules(); 
            return view('dashboard.admin.dashboard',compact('mainModules'));
        }else{
            $subModules = $this->module->getActivatedSubModulesForUsers(); 
            return view('dashboard.user.dashboard',compact('subModules'));
        }
    }   
}
