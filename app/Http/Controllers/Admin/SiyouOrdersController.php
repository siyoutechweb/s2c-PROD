<?php namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AuthController;
use App\Models\SiyouProduct;
use App\Models\SiyouOrder;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Auth;
use Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
class SiyouOrdersController extends Controller {
    public function __construct()
    {
        $this->middleware('auth:api',['except'=>['addSiyouOrder','getCustomerStatus','createCustomer']]);
    }

    public function getCustomerStatus(Request $request)
    {
     
    $a=  Auth::check();
    if($a) {
        $user = AuthController::meme();
        if($user->role_id==1) {
            $response['code']=1;
            $response['msg']="";
            $response['data'] = 'logged in shop owner';
        }
        else {
            $response['code']=2;
            $response['msg']="";
            $response['data'] = 'user not shop owner';
        }
    }else {
        $response['code']=3;
        $response['msg']="";
        $response['data'] = 'email or contact input are required';
    }
	return response()->json($response);
        
    
    }


    public function addSiyouOrder(Request $request)
    {
        $result= $this->getCustomerStatus($request);
        $code = $result->getData()->code;
        if($code===1) {

        } else if($code===2){
           return response()->json(['code'=>0,'msg'=>'fail','data'=>'the user is not a shopOwner!! method not allowed']); 
        }else {
            if(!$request->filled('email','contact')) {
                return response()->json(['code'=>0,'msg'=>'fail','data'=>'email and contact required']);
            }
            $email = $request->input('email');
            $contact = $request->input('contact');
            $user = User::where('email',$email)->orWhere('contact',$contact)->first();
            if(!$user) {

                $customer = Customer::where('email',$email)->orWhere('contact',$contact)->first();
                if(!$customer) {
                    $tmp = $this->createCustomer($request);
                    if($tmp->getData()->code ===0) {
                        return response()->json(['code'=>0,'msg'=>'fail','data'=>'error while creating new customer']);
                    } 
                }

            }else {
                if($user->role_id!=1) {
                    return response()->json(['code'=>0,'msg'=>'fail','data'=>' email and contact already exist the user is not a shopOwner!! method not allowed']); 
                } else {

                }

            }
        }
    

    }
    public function CreateCustomer(Request $request)
    {   
        //new customer credentials
        $customer = new Customer();
        $customer->first_name = $request->input('first_name');
        $customer->last_name=$request->input('last_name');
        $customer->email = $request->input('email');
        $customer->contact = $request->input('contact');
        $customer->address = $request->input('address');
        $customer->phone_num2 = $request->input('phone_num2');
        if($customer->save()) {
            return response()->json(['code'=>1,"msg"=>'success']);
        }else {
            return response()->json(['code'=>0,'msg'=>'fail']);
        }

    }
}
