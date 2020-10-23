<?php

namespace App\Http\Middleware;

use Closure;
// use JWTAuth;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\AuthController;

class Permission
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
      
      $url =  $request->get('referrer');
        $user = AuthController::me();
        if(!DB::table('menu')->find($user->id) ) {
            return $next($request);
        } else{
            $permissions = DB::table('menu')->where('user_id',$user->id)->first();
            
        }
        if(isset($request->token)) {
            $user = AuthController::me();
        if(!(DB::table('menu')->find($user->id)) ) {
            return $next($request);
        } else{
            $permissions = DB::table('menu')->where('user_id',$user->id)->first();
            if(isset($permissions[$url]) && $permissions[$url]==0) {
                // do something
                return response()->json(["msg" => "Insufficient permissions"], 401);
            } else {
                return $next($request);
            }
        }

        }else {
            return $next($request);
        }
        // foreach($permissions as $permission) {
       
        // if($user->hasRole($role))
        //     return $next($request);
        // }

        return response()->json(["msg" => "Insufficient permissions"], 401);
    }
}
