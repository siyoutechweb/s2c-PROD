<?php

namespace App\Http\Middleware;

use Closure;
// use JWTAuth;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\AuthController;

class CheckAccount
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
      

        $user = AuthController::me();

      
       
        if($user->activated_account == 1)
        {
            return $next($request);
        }
           
        $response = array();
        $response['code']=0;
        $response['msg']="1";
        $response['data']='Account not validated by Admin';
        return response()->json($response);
    }
}