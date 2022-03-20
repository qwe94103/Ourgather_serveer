<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class JWTMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $message= '';
        try{
            JWTAuth::parseToken()->authenticate();
            return $next($request);
        }catch(\Tymon\JWTAuth\Exceptions\TokenExpiredException $e){
            $message = 'token expired';
        }catch(\Tymon\JWTAuth\Exceptions\TokenInvalidException $e){
            $message ='Invalid token';
        }catch(\Tymon\JWTAuth\Exceptions\JWTException $e){
            $message = 'provide token';
        }
        return response()->json([
            'success'=>false,
            'message'=>$message
        ]);
    }
}
