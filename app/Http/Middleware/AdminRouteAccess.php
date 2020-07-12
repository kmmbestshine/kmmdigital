<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class AdminRouteAccess
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
            return redirect()->route('showMessages', ['msg' => '403']);
        }
    }
}
