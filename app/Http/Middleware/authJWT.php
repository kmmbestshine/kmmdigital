<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Exception;

class authJWT
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
        $token = JWTAuth::getToken();
        try {
            $user = JWTAuth::toUser($token);
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return response()->json(['error'=>'Token-Invalid']);
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return response()->json(['error'=>'Token-Expired']);
            }else{
                return response()->json(['error'=>'Something-Wrong']);
            }
        }
        return $next($request);
    }
}
