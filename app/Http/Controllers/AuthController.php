<?php

namespace App\Http\Controllers;

use App\Http\Controllers\User\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\user;
use App\Models\License;
use App\Models\Shop;
use App\Models\Chain;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{

    // const MODEL = "App\AuthController";

    // use RESTActions;

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','testlogin']]);
    }

    public function test() {
        return response()->json(["msg" => "test ok !!"]);
    }
    public function login(Request $request)
    {
        
        //$credentials = $request->only(['email', 'password']);
        //var_dump($request->headers->all());
        $credentials = $this->credentials($request);
        //echo $credentials;
        if (!$token = Auth::guard('api')->claims(['email' => $request->input('email')])->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        

        return $this->respondWithToken($request,$token);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken(Request $request,$token)
    {

        JWTAuth::setToken($token);
        $user = JWTAuth::toUser($token);
        $userData = User::whereEmail($user['email'])->with('role')->first();
        //echo $userData;
        if($userData->role_id==1||$userData->role_id==2||$userData->role_id==3) {
        $license=License::where('shop_owner_id',$userData->shop_owner_id)->orWhere('shop_owner_id',$userData->id)->first();
        if(!$license) {
            return response()->json(['code'=>0,'msg'=>'no licence found']);
        }
        if($license->finish_date<date('Y-m-d')) {
            return response()->json(['code'=>0,'msg'=>'licence expired']);
        }
        }
        if($userData->activated_account===0) {
            return response()->json(['code'=>0,'msg'=>'account not activated']);
        }
        $user_menu = db::table('menu')->where('user_id',$userData->id)->get();
        $userData->token=$token;
        $userData->save();
       
        $shop=$user->shop()->value('id');
	 if($user->chain_id) {
           // echo $user->chain_id;
            $shop = Chain::find($user->chain_id)->store_id;
        }
	if($shop) {
            $store_name = Shop::find($shop)->store_name;
        } else {$store_name=null;}
        if(empty($shop))
        {
            $userData->store_id = null;
        }
        //$origin =$request->headers->all();
      //var_dump ($origin);
        $userData->store_id =$shop;
	if($store_name) {
	 $userData->store_name =$store_name; }
        //$userData['url'] =$origin["user-agent"];
        $userData->user_menu =$user_menu;
        // $userData->access_token =$token;
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => $userData,
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60
        ]);
    }



    // public static function me() {
    //     if (!$user = JWTAuth::parseToken()->authenticate()) {
    //         return response()->json(["msg" => 'user_not_found'], 404);
    //     }
    //     //added for testing purposes
    //     // $tmp = JWTAuth::getPayload()->get('email');
    //     // echo $tmp.'this is a temporary variable from auth controller';
    //     $email = JWTAuth::getPayload()->get('email');
    //     $user = User::where('email','=',$email)->first();
    //     //echo $user;

    //     return $user;
    // }
    public static function me() {
        if (!$user = JWTAuth::parseToken()->authenticate()) {
            return response()->json(["msg" => 'user_not_found'], 404);
        }
         $email = JWTAuth::getPayload()->get('email');
        // $user = user::where('email',$email)->first();
        $phone_num1 = JWTAuth::getPayload()->get('contact');
        $user = User::where('email','=',$email)->where('contact', $phone_num1)->first();
       
        return $user;
    }
  public static function meme() {
        if (!$user = JWTAuth::parseToken()->authenticate()) {
            return response()->json(["msg" => 'user_not_found'], 404);
        }
        $email = JWTAuth::getPayload()->get('email');
        $phone_num1 = JWTAuth::getPayload()->get('contact');
        $user = User::where('email','=',$email)->where('contact', $phone_num1)->first();
	$shop=$user->shop()->value('id');
        if($user->chain_id) {
                      $shop = Chain::find($user->chain_id)->store_id;
           
        }

        if(empty($shop))
        {
            $user->store_id = null;
        }
        $user->store_id = $shop;
        return $user;
    }

    public function credentials(Request $request) {
        //echo $request->input('email');
        if(is_numeric($request->input('email'))){

            return ['contact'=>$request->input('email'),'password'=>$request->input('password')];
        }
        elseif (filter_var($request->input('email'), FILTER_VALIDATE_EMAIL)) {
            //echo $request->input('email');
            //echo echo $request->input('password');
            return ['email' => $request->input('email'), 'password'=>$request->input('password')];
        }
        return 'no data provided';
    }

    public function infos() {
        return response()->json(['msg' => 'Data ok!']);
    }


    public function getBDAccess(Request $request) {

        $dbAccess= DB::table('db_settings')->first();
        return response()->json($dbAccess) ;
    }

    public function testlogin(Request $request)
    {
      $credentials = $request->only(['email', 'password']);
      if (!$token = Auth::guard('api')->claims(['email' => $request->input('email')])->attempt($credentials)) {
          return response()->json(['error' => 'Unauthorized'], 401);
      }

      return $this->respondWithToken($request,$token);
    }
}
