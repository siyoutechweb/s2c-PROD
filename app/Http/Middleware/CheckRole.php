<?php

namespace App\Http\Middleware;

use Closure;
// use JWTAuth;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\AuthController;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ... $roles)
    {
      

        $user = AuthController::me();

        foreach($roles as $role) {
       
        if($user->hasRole($role))
            return $next($request);
        }

        return response()->json(["msg" => "Insufficient permissions"], 401);
    }
}

