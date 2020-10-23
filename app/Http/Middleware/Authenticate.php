<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use App\Http\Controllers\AuthController;

class Authenticate
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next,$guard = null)
    {
       
        if ($this->auth->guard($guard)->guest()) {
            $response = array();
            $response['code']= 0;
            $response['msg']='';
            $response['data']='Unauthorized';
            return response()->json($response);
        }
        // elseif (AuthController::me()->token !== $request->token )
        // {
        //     $response = array();
        //     $response['code']= 0;
        //     $response['msg']='2';
        //     $response['data']='Token expired. You need to relogin';
        //     return response()->json($response);
        // }

       else return $next($request);
    }
}
