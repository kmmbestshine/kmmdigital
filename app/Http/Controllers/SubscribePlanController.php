<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\SubscribePlan\SubscribePlanInterface as SubscribePlanInterface;
use App\Http\Requests\SubscribePlanFormRequest;

class SubscribePlanController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(SubscribePlanInterface $subscribePlan)
    {
        $this->middleware('admin', ['only' => ['index','create','post']]);
        $this->subscribePlan = $subscribePlan;
    }    


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $subscribePlans = $this->subscribePlan->getSubscribePlans();
        return view('subscribe-plan.index',compact('subscribePlans'));         
    }   

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $mainModules = $this->subscribePlan->getActivatedMainModules();
        return view('subscribe-plan.create',compact('mainModules'));         
    } 

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function post(SubscribePlanFormRequest $request)
    {   
        $value = $request->all();
        $data = $this->subscribePlan->store($value);
        return redirect()->route('subscribePlanIndex')->with('postSubscribePlanSuccess', $data);       
    } 

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteSubscribePlan($id)
    {   
        $data = $this->subscribePlan->delete($id);
        return redirect()->back()->with('deleteSubscribePlanSuccess', $data);
    } 
}
