<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class UserRouteAccess
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
        if(Auth::User()->roles->first()->name == "User"){
            return $next($request);
        }else{
            return redirect()->route('showMessages', ['msg' => '403']);
        }
    }
}
