<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Models\Exam;
use App\Models\Mark;
use App\Models\PlanUsageDetail;
use App\Models\SubModule;

class CheckDuplicateUsersInExam
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {      
        if(Auth::User()->roles->first()->name == "Admin"){
            return $next($request);
        }else{
            // check plan upgrade
            $sub_module_slug = $request->route()->parameter('sub_module_slug');
            $exam_slug = $request->route()->parameter('exam_slug');
            $exam = Exam::where('slug',$exam_slug)->first();
            $user_id = Auth::user()->id;
            $subModule = SubModule::where('id',$exam->sub_module_id)->first();   
            $userIsInSubscribePlanCount = PlanUsageDetail::where('user_id',$user_id)->where('main_module_id',$subModule->main_module_id)->count();     

            if($userIsInSubscribePlanCount == 1){
                $plan = PlanUsageDetail::where('user_id',$user_id)->where('main_module_id',$subModule->main_module_id)->first(); 
                $now = time();
                $subscribe_date = strtotime($plan->subscribe_date);
                $datediff = $now - $subscribe_date;

                $days = floor($datediff / (60 * 60 * 24));

                if($plan->plan_limit > 0 && $plan->days_duration > $days){
                    $examCount = Mark::where('exam_id',$exam->id)->where('user_id',$user_id)->count();
                    if($examCount == 0){
                        return $next($request);
                    }else{                
                        return redirect()->route('showMessages', ['msg' => '2']);
                    }
                }else{
                    return redirect()->route('showMessages', ['msg' => '1']);
                } 
            }else{         
                $plan = PlanUsageDetail::where('user_id',$user_id)->where('plan_name',"Free Trial")->first();
                if($plan){
                    $now = time();
                    $subscribe_date = strtotime($plan->subscribe_date);
                    $datediff = $now - $subscribe_date;

                    $days = floor($datediff / (60 * 60 * 24));

                    if($plan->plan_limit > 0 && $plan->days_duration > $days){
                        $examCount = Mark::where('exam_id',$exam_id)->where('user_id',$user_id)->count();
                        if($examCount == 0){
                            return $next($request);
                        }else{                
                            return redirect()->route('showMessages', ['msg' => '2']);
                        }
                    }else{
                        return redirect()->route('showMessages', ['msg' => '1']);
                    } 
                }else{
                    return redirect()->route('showMessages', ['msg' => '1']);
                }                            
            }               
        }
        return $next($request);
    }

    // public function handle($request, Closure $next)
    // {      
    //     if(Auth::User()->roles->first()->name == "Admin"){
    //         return $next($request);
    //     }else{
    //         $sub_module_slug = $request->route()->parameter('sub_module_slug');
    //         // check plan upgrade
    //         $plan = PlanUsageDetail::where('user_id',Auth::user()->id)->first();
    //         $now = time();
    //         $subscribe_date = strtotime($plan->subscribe_date);
    //         $datediff = $now - $subscribe_date;

    //         $days = floor($datediff / (60 * 60 * 24));

    //         if($plan->plan_limit > 0 && $plan->days_duration > $days){
    //             $exam_id = $request->exam_id;
    //             $examCount = Mark::where('exam_id',$exam_id)->where('user_id',Auth::user()->id)->count();
    //             if($examCount == 0){
    //                 return $next($request);
    //             }else{                
    //                 return redirect()->route('showMessages', ['msg' => '2']);
    //             }
    //         }else{
    //             return redirect()->route('showMessages', ['msg' => '1']); 
    //         }
    //     }

    //     return $next($request);
    // }    
}
