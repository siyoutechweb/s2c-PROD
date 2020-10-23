<?php namespace App\Http\Controllers\User;

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Models\cachier;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
/*
SIYOU THECH Tunisia
Author: Habiba Boujmil
*/


class SignUpsController extends Controller {

 /* Sign us as ShopOwner 
     - Parameters:'first_name','last_name','email','password' 
 */
    public function addShopOwner(Request $request)
    {
        $first_name = $request->input('first_name');
        $last_name = $request->input('last_name');
        $email = $request->input('email');
        $password = $request->input('password');
        $contact = $request->input('contact');
        $birthday = $request->input('birthday');
        $user = new User();
        $user->first_name = $first_name;
        $user->last_name = $last_name;
        $user->email = $email;
        $user->contact = $contact;
        $user->birthday = $birthday;
        $user->password = Hash::make($password);
        $user->billing_first_name= $request->input('billing_first_name');
        $user->billing_last_name= $request->input('billing_last_name');
        $user->billing_company= $request->input('billing_company');
        $user->billing_address_1= $request->input('billing_address_1');
        $user->billing_address_2= $request->input('billing_address_2');
        $user->billing_country= $request->input('billing_country');
        $user->billing_state= $request->input('billing_state');
        $user->billing_city= $request->input('billing_city');
        $user->billing_postal_code= $request->input('billing_postal_code');
        $user->billing_email= $request->input('billing_email');
        $user->billing_phone= $request->input('billing_phone');
        $user->shipping_first_name= $request->input('shipping_first_name');
        $user->shipping_last_name= $request->input('shipping_last_name');
        $user->shipping_company= $request->input('shipping_company');
        $user->shipping_address_1= $request->input('shipping_address_1');
        $user->shipping_address_2= $request->input('shipping_address_2');
        $user->shipping_country= $request->input('shipping_country');
        $user->shipping_city= $request->input('shipping_city');
        $user->shipping_postal_code= $request->input('shipping_postal_code');
        $user->shipping_email= $request->input('shipping_email');
        $user->shipping_phone= $request->input('shipping_phone');
        $role = Role::where('name', 'Shop_Owner')->first();
        if($role->users()->save($user))
        {
            $roleB2B = DB::connection('B2S')->table('roles')
            ->where('name', '=', 'Shop_Owner')->value('id');
            $image = DB::connection('B2S')->table('users')->insert([
                            "first_name" => $first_name,
                            "last_name" => $last_name,
                            "email" => $email,
                            "password" => $user->password,
                            "role_id" => $roleB2B
                        ]);
            $response = array();
            $response['code']=1;
            $response['msg']="";
            $response['data']=$user;
            return response()->json($response);
        }
        $response = array();
        $response['code']=0;
        $response['msg']="3";
        $response['data']='Error while saving';
        return response()->json($response);
    }

    /* Creat shopManager account 
     - Parameters:'token','first_name','last_name','email','password' 
     Accessible for : ShopOwner
    */
    public function addShopManager(Request $request)
    {	
	 $tmp =User::where('email',$request->input('email'))->orWhere('contact',$request->input('contact'))->first();
        if($tmp) {
            return response()->json(["msg" => "User already exists !!"]);
        }

        $first_name = $request->input('first_name');
        $last_name = $request->input('last_name');
        $email = $request->input('email');
        $password = $request->input('password');
        $contact = $request->input('contact');
        $chain_id = $request->input('chain_id');
        $birthday = $request->input('birthday');
        if ($request->hasFile('manager_image')) {
            $path = $request->file('manager_image')->store('managers', 'google');
            $fileUrl = Storage::url($path);
            $img_url = $fileUrl;
           $img_name = basename($path);

        }else {
            $img_url = null;
            $img_name = null;
        }
        $shop_owner = AuthController::me();
        $user = new User();
        $user ->shop_owner_id=$shop_owner->id;
        //$user ->store_id=$request->store_id;
        $user->first_name = $first_name;
        $user->last_name = $last_name;
        $user->email = $email;
        $user->contact = $contact;
        $user->birthday = $birthday;
        $user->profile_img_url = $img_url;
        $user->profile_img_name = $img_name;
        $user->chain_id = $chain_id;
        $user->activated_account=1;
        $user->password = Hash::make($password);
        $role = Role::where('name', 'ShopManager')->first();
        if($role->users()->save($user)){
            $cachier_code = $request->input('cachier_code');
            DB::table('cachiers')->insert([
                'first_name'=>$first_name,
                'last_name'=>$last_name,
                'contact'=>$contact,
                'store_id'=>$request->input('store_id'),
                'chain_id'=>$chain_id,
                'img_url'=>$img_url,
                'img_name'=>$img_name,
                'password'=>$cachier_code,
                'is_active'=>1,
                'is_manager'=>1

            ]);
            $B2s = $request->input('B2s');
            $s2c = $request->input('s2c');
            $warehouse = $request->input('warehouse');
            $this->accessRights($user->id,$chain_id,$B2s,$s2c,$warehouse);

            $response = array();
            $response['code']=1;
            $response['msg']="";
            $response['data']=$user;
            return response()->json($response);
        }
        $response = array();
        $response['code']=0;
        $response['msg']="3";
        $response['data']='Error while saving';
        return response()->json($response);
    }

    /* Creat Cachier account 
     - Parameters:'token','first_name','last_name','email','password' 
     Accessible for : ShopOwner
    */
    public function addOperators(Request $request)
    {
         $tmp =User::where('email',$request->input('email'))->orWhere('contact',$request->input('contact'))->first();
        if($tmp) {
            return response()->json(["msg" => "User already exists !!"]);
        }
        $first_name = $request->input('first_name');
        $last_name = $request->input('last_name');
        $email = $request->input('email');
        $password = $request->input('password');
        $contact = $request->input('contact');
        $birthday = $request->input('birthday');
        $shop_owner = AuthController::me();
        $user = new User();
        $user ->shop_owner_id=$shop_owner->id;
        $user->first_name = $first_name;
        $user->last_name = $last_name;
        $user->email = $email;
        $user->contact = $contact;
        $user->birthday = $birthday;
        $user->activated_account=1;
        $user->password = Hash::make($password);
        if ($request->hasFile('operator_image')) {
            $path = $request->file('operator_image')->store('operators', 'google');
            $fileUrl = Storage::url($path);
            $img_url = $fileUrl;
           $img_name = basename($path);

        }else {
            $img_url = null;
            $img_name = null;
        }
        $user->profile_img_url = $img_url;
        $user->profile_img_name = $img_name;
        $role = Role::where('name', 'operator')->first();
        if($role->users()->save($user)){
            $response = array();
            $response['code']=1;
            $response['msg']="";
            $response['data']=$user;
            
            $B2s = $request->input('B2s');
            $s2c = $request->input('s2c');
            $warehouse = $request->input('warehouse');
            $this->accessRights($user->id,$chain_id,$B2s,$s2c,$warehouse);
		return response()->json($response);

        }
        $response = array();
        $response['code']=0;
        $response['msg']="3";
        $response['data']='Error while saving';
        return response()->json($response);
    }

    public function addCachiers(Request $request)
    {
        $first_name = $request->input('first_name');
        $last_name = $request->input('last_name');
        $contact = $request->input('contact');
        $password = $request->input('password');
        $chain_id = $request->input('chain_id');
        $is_active = $request->input('is_active');
        $shop_owner = AuthController::me();
        $store_id = $shop_owner->shop->id;
        if ($request->hasFile('cachier_image')) {
            $path = $request->file('cachier_image')->store('cachiers', 'google');
            $fileUrl = Storage::url($path);
            $img_url = $fileUrl;
           $img_name = basename($path);

        }else {
            $img_url = null;
            $img_name = null;
        }

        try {
        $user= DB::table('cachiers')->insert(
            ['first_name' => $first_name, 
            'last_name'=>$last_name,
             'contact' => $contact,
             'password' => $password,
             'chain_id' => $chain_id,
             'store_id' => $store_id,
             'img_name'=> $img_name,
             'img_url'=>$img_url,
             'is_active' => $is_active
             ]);
            $response['code']=1;
            $response['msg']="";
            $response['data']="cachier has been saved";
            return response()->json($response);
        } catch (Exception $e) {

            $response['code']=0;
            $response['msg']="3";
            $response['data']=$e->errorInfo[2];
            return response()->json($response);}
    }
    public function accessRights($user_id,$chain_id,$B2s,$s2c,$warehouse) 
    {
      ;
        $arr=[];
        $arr['user_id']=$user_id;
        $arr['chain_id']=$chain_id;
        $arr['products'] = $B2s==1?1:0;
        $arr['purchased'] = $B2s==1?1:0;
        $arr['my_wishlist'] = $B2s==1?1:0;
        $arr['invalid_orders'] = $B2s==1?1:0;
        $arr['valid_orders'] = $B2s==1?1:0;
        $arr['paid_orders'] = $B2s==1?1:0;
        $arr['add_product'] = $s2c==1?1:0;
        $arr['product_list'] = $s2c==1?1:0;
        $arr['affect_discount'] = $s2c==1?1:0;
        $arr['discounted_products_list'] = $s2c==1?1:0;
        $arr['member_list'] = $s2c==1?1:0;
        $arr['level_list'] = $s2c==1?1:0;
        $arr['shop_managers_list'] = 0;
        $arr['store_list'] = 0;
        $arr['add_new_store'] = 0;
        $arr['suppliers_list'] =$warehouse==1?1:0;
        $arr['inventory'] =$warehouse==1?1:0;
        $arr['stock_management'] =$warehouse==1?1:0;
        //added fields
        $arr['warehouse'] =$warehouse==1?1:0;
        $arr['funds_by_cash'] =$warehouse==1?1:0;
        $arr['funds_by_card'] =$warehouse==1?1:0;
        $arr['funds_by_check'] =$warehouse==1?1:0;

        $arr['my_account'] = 1;
        DB::table('menu')->insert($arr);
    }
public function getManagerById(Request $request,$id) {
        $manager = User::find($id);
        if($manager && $manager->role_id==2) {
            $response['code'] = 1;
            $response['msg'] = "success";
            $response['data'] = $manager;
        } else {
            $response['code'] = 0;
            $response['msg'] = "fail";
            $response['data'] = 'not found';
        }
        return $response;
    }
    public function updateShopManager(Request $request,$id)
    {

        $first_name = $request->input('first_name');
        $last_name = $request->input('last_name');
        $email = $request->input('email');
        $password = $request->input('password');
        $contact = $request->input('contact');
        $chain_id = $request->input('chain_id');
        $birthday = $request->input('birthday');
        if ($request->hasFile('manager_image')) {
            $path = $request->file('manager_image')->store('managers', 'google');
            $fileUrl = Storage::url($path);
            $img_url = $fileUrl;
           $img_name = basename($path);

        }else {
            $img_url = null;
            $img_name = null;
        }
        $shop_owner = AuthController::me();
        $user = User::find($id);
        if(!$user || $user->role_id!=2) {
            return response()->json(["code"=>0,"msg"=>"error  no user found"]);
        }
        $user ->shop_owner_id=$shop_owner->id;
        //$user ->store_id=$request->store_id;
        $user->first_name = $first_name;
        $user->last_name = $last_name;
        //$user->email = $email;
        $user->contact = $contact;
        //$user->birthday = $birthday;
        //$user->profile_img_url = $img_url;
        //$user->profile_img_name = $img_name;
        $user->chain_id = $chain_id;
        $user->activated_account=1;
        //$user->password = Hash::make($password);
        $role = Role::where('name', 'ShopManager')->first();
        if($role->users()->save($user)){
            $cachier_code = $request->input('cachier_code');
            DB::table('cachiers')->where('id',$user->id)->update([
                'first_name'=>$first_name,
                'last_name'=>$last_name,
                'contact'=>$contact,
                'store_id'=>$request->input('store_id'),
                'chain_id'=>$chain_id,
                'img_url'=>$img_url,
                'img_name'=>$img_name,
                'password'=>$cachier_code,
                'is_active'=>1,
                'is_manager'=>1

            ]);
            $B2s = $request->input('B2s');
            $s2c = $request->input('s2c');
            $warehouse = $request->input('warehouse');
            $this->accessRights($user->id,$chain_id,$B2s,$s2c,$warehouse);

            $response = array();
            $response['code']=1;
            $response['msg']="";
            $response['data']=$user;
            return response()->json($response);
        }
        $response = array();
        $response['code']=0;
        $response['msg']="3";
        $response['data']='Error while saving';
        return response()->json($response);
    }

public function getCachierById(Request $request,$id) {
        $cachier = cachier::find($id);
        if($cachier) {
            $response['code'] = 1;
            $response['msg'] = "success";
            $response['data'] = $cachier;
        } else {
            $response['code'] = 0;
            $response['msg'] = "fail";
            $response['data'] = 'no cashier found';
        }
        return $response;
    }
    public function updateCachiers(Request $request,$id)
    {
        $first_name = $request->input('first_name');
        $last_name = $request->input('last_name');
        $contact = $request->input('contact');
        $password = $request->input('password');
        $chain_id = $request->input('chain_id');
        $is_active = $request->input('is_active');
        $shop_owner = AuthController::me();
        $store_id = $shop_owner->shop->id;
        if ($request->hasFile('cachier_image')) {
            $path = $request->file('cachier_image')->store('cachiers', 'google');
            $fileUrl = Storage::url($path);
            $img_url = $fileUrl;
           $img_name = basename($path);

        }else {
            $img_url = null;
            $img_name = null;
        }

        try {
        $user= DB::table('cachiers')->where('id',$id)->update(
            ['first_name' => $first_name, 
            'last_name'=>$last_name,
             'contact' => $contact,
             'password' => $password,
             'chain_id' => $chain_id,
             'store_id' => $store_id,
             'img_name'=> $img_name,
             'img_url'=>$img_url,
             'is_active' => $is_active
             ]);
            $response['code']=1;
            $response['msg']="";
            $response['data']="cachier has been updated";
            return response()->json($response);
        } catch (Exception $e) {

            $response['code']=0;
            $response['msg']="3";
            $response['data']=$e->errorInfo[2];
            return response()->json($response);}
    }
public function deleteCachier(Request $request,$id) {
        $cachier = cachier::find($id);
        if(!$cachier) {
            return response()->json(['code'=>0,'msg'=>'error while deleting']);

        }
	if($cachier->is_manager ==0)
        $cachier->delete();
        return response()->json(['code'=>1,'msg'=>'caschier successfully deleted']);
    }

}
