<?php namespace App\Http\Controllers\User;

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Models\License;
use App\Models\Chain;
use App\Models\cachier;
use Carbon\Carbon;
use Exception;
use Hamcrest\Type\IsNumeric;
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
    {	$shop_owner = AuthController::meme();
        $licence = License::where('shop_owner_id',$shop_owner->id)->first();
        if(!$licence) {
            return response()->json(['code'=>0,'msg'=>'you do not have license']);
        }
        $managers=User::where('shop_owner_id',$shop_owner->id)->where('role_id',2)->where('activated_account',1)->count();
        if($managers>=$licence->max_managers) {
            return response()->json(['code'=>0,'msg'=>'reached maximum managers allowed for this account']);
        }
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
        $chain1=Chain::find($chain_id);
        if(isset($chain1->manager_id) && isset($chain1->manager2_id)&& isset($chain1->manager3_id)) {
            return response()->json(['code'=>0,"msg"=>'max 3 managers for a chain',"data"=>'max 3 managers for a chain!! you nedd to deactivate a manager first']);
        }
        $birthday = $request->input('birthday');
        $hide_cost_price = $request->input('hide_cost_price',0);
        if($hide_cost_price=='false') {$hide_cost_price=1;} else {$hide_cost_price=0;}
        if ($request->hasFile('manager_image')) {
            $path = $request->file('manager_image')->store('managers', 'public');
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
        $user->first_name = $first_name;
        $user->last_name = $last_name;
        $user->email = $email;
        $user->contact = $contact;
        $user->birthday = $birthday;
        $user->profile_img_url = $img_url;
        $user->profile_img_name = $img_name;
        $user->chain_id = $chain_id;
        $user->hide_cost_price = $hide_cost_price;
        $user->activated_account=1;
        $user->password = Hash::make($password);
        $role = Role::where('name', 'ShopManager')->first();
        if($role->users()->save($user)){
	   DB::table('users')->where('role_id', '=', 2)->where('id', '!=', $user->id)->where('chain_id', '=', $chain_id)->update(array('chain_id' => null,'activated_account'=>0));
	   $chain=Chain::find($chain_id);
       if(!isset($chain->manager_id) && $chain->manager2_id!=$user->id && $chain->manager3_id!=$user->id) {
        $chain->manager_id=$user->id;
        }
    else if(!isset($chain->manager2_id) && $chain->manager_id!=$user->id && $chain->manager3_id!=$user->id) {
        $chain->manager2_id=$user->id;
        }
    else if(!isset($chain->manager3_id) && $chain->manager2_id!=$user->id && $chain->manager_id!=$user->id) {
        $chain->manager3_id=$user->id;
        }
	   $chain->save();
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
              'is_manager'=>1,
              'user_id'=>$user->id,
              'hidden'=>0,
              'created_at'=>Carbon::now(),
              'updated_at'=>Carbon::now()

            ]);
           $B2s = $request->input('B2s',1);
           if($B2s=="" ) {$B2s=1;}
            $s2c = $request->input('s2c',1);
            if($s2c=="" ) {$s2c=1;}
            $warehouse = $request->input('warehouse',1);
            if($warehouse=="" ) {$warehouse=1;}
            $add_quick_purchase = $request->input('add_quick_purchase',1);
            if($add_quick_purchase=="" ) {$add_quick_purchase=1;}
            $purchase_order = $request->input('purchase_order',1);
            if($purchase_order=="" ) {$purchase_order=1;}
            $siyou_suppliers = $request->input('siyou_suppliers',1);
            if($siyou_suppliers=="" ) {$siyou_suppliers=1;}
            $my_suppliers = $request->input('my_suppliers',1);
            if($my_suppliers=="" ) {$my_suppliers=1;}
            $siyou_categories = $request->input('siyou_categories',1);
            if($siyou_categories=="" ) {$siyou_categories=1;}
            $my_category = $request->input('my_category',1);
            if($my_category=="" ) {$my_category=1;}
            $promotion_history = $request->input('promotion_history',1);
            if($promotion_history=="" ) {$promotion_history=1;}
            $promotion_list = $request->input('promotion_list',1);
            if($promotion_list=="" ) {$promotion_list=1;}
            $discount_history = $request->input('discount_history',1);
            if($discount_history=="" ) {$discount_history=1;}
            $sales_funds = $request->input('sales_funds',1);
            if($sales_funds=="" ) {$sales_funds=1;}
            $accounts_payable = $request->input('accounts_payable',1);
            if($accounts_payable=="" ) {$accounts_payable=1;}
            $payment_methods = $request->input('payment_methods',1);
            if($payment_methods=="" ) {$payment_methods=1;}
            $shop_operators_list = $request->input('shop_operators_list',1);
            if($shop_operators_list=="" ) {$shop_operators_list=1;}
            $add_shop_operator = $request->input('add_shop_operator',1);
            if($add_shop_operator=="" ) {$add_shop_operator=1;}
            $shop_cashiers_list = $request->input('shop_cashiers_list',1);
            if($shop_cashiers_list=="" ) {$shop_cashiers_list=1;}
            $add_shop_cashier = $request->input('add_shop_cashier',1);
            if($add_shop_cashier=="" ) {$add_shop_cashier=1;}
            $advertisement = $request->input('advertisement',1);
            if($advertisement=="" ) {$advertisement=1;}
            $inventory_management = $request->input('inventory_management',1);
            if($inventory_management=="" ) {$inventory_management=1;}
            $warehouse_management = $request->input('warehouse_management',1);
            if($warehouse_management=="" ) {$warehouse_management=1;}
            $returned_goods = $request->input('returned_goods',1);
            if($returned_goods=="" ) {$returned_goods=1;}
            $add_discount = $request->input('add_discount',1);
            if($add_discount=="" ) {$add_discount=1;}
            $statistics = $request->input('statistics',1);
            if($statistics=="" ) {$statistics=1;}
            $b2s_order_management = $request->input('b2s_order_management',1);
            if($b2s_order_management=="" ) {$b2s_order_management=1;}
            $discount_list = $request->input('discount_list',1);
            if($discount_list=="" ) {$discount_list=1;}
            $inventory_history = $request->input('inventory_history',1);
            if($inventory_history=="" ) {$inventory_history=1;}
            $stock_management = $request->input('stock_management',1);
            if($stock_management=="" ) {$stock_management=1;}
            $member_list = $request->input('member_list',1);
            if($member_list=="" ) {$member_list=1;}
            $level_list = $request->input('level_list',1);
            if($level_list=="" ) {$level_list=1;}
            $b2s_products=$request->input('b2s_products',1);
            if($b2s_products=="" ) {$b2s_products=1;}
            $product_list=$request->input('product_list',1);
            if($product_list=="" ) {$product_list=1;}
            $add_product = $request->input('add_product',1);
            if($add_product=="" ) {$add_product=1;}
            $update_product = $request->input('update_product',1);
            if($update_product=="" ) {$update_product=1;}
            $s2c_orders_list=$request->input('s2c_orders_list',1);
            if($s2c_orders_list=="" ) {$s2c_orders_list=1;}
            $this->accessRights($user->id,$chain_id,$B2s,$s2c,$warehouse,$add_discount,$statistics,$update_product,$add_product,$add_quick_purchase,$purchase_order,$siyou_suppliers,$my_suppliers,$siyou_categories,$my_category,$promotion_history,$promotion_list,$discount_history,$sales_funds,$accounts_payable,$payment_methods,$shop_operators_list,$add_shop_operator,$shop_cashiers_list,$add_shop_cashier,$advertisement,$inventory_management,$warehouse_management,$returned_goods,$b2s_products,$b2s_order_management,$discount_list,$inventory_history,$stock_management,$member_list,$level_list,$s2c_orders_list,$product_list);
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
        $shop_owner = AuthController::meme();
        if($shop_owner->role_id==2) {
            $shop_owner_id= $shop_owner->shop_owner_id;
        } 
        else if($shop_owner->role_id==1) {
            $shop_owner_id= $shop_owner->id;
        } 
        $licence = License::where('shop_owner_id',$shop_owner->id)->orWhere('shop_owner_id',$shop_owner->shop_owner_id)->first();
        if(!$licence) {
            return response()->json(['code'=>0,'msg'=>'you do not have license']);
        }
        $operators=User::where('shop_owner_id',$shop_owner_id)->where('role_id',3)->where('activated_account',1)->count();
        if($operators>=$licence->max_operators) {
            return response()->json(['code'=>0,'msg'=>'reached maximum operators allowed for this account']);
        }
        $tmp =User::where('email',$request->input('email'))->orWhere('contact',$request->input('contact'))->first();
        if($tmp) {
            return response()->json(["msg" => "User already exists !!"]);
        }
	$chain_id = $request->input('chain_id');

        $first_name = $request->input('first_name');
        $last_name = $request->input('last_name');
        $email = $request->input('email');
        $password = $request->input('password');
        $contact = $request->input('contact');
        $birthday = $request->input('birthday');
        $hide_cost_price = $request->input('hide_cost_price',0);
        if($hide_cost_price=='false') {$hide_cost_price=1;} else {$hide_cost_price=0;}
        $shop_owner = AuthController::me();
        $user = new User();
        $user ->shop_owner_id=$shop_owner_id;
        $user->first_name = $first_name;
        $user->last_name = $last_name;
        $user->email = $email;
        $user->contact = $contact;
        $user->chain_id = $chain_id;
        $user->birthday = $birthday;
        $user->hide_cost_price = $hide_cost_price;
        $user->activated_account=1;
        $user->password = Hash::make($password);
        if ($request->hasFile('operator_image')) {
            $path = $request->file('operator_image')->store('operators', 'public');
            $fileUrl = Storage::url($path);
            $img_url = $fileUrl;
            $img_name = basename($path);

        }else 
	{
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

            $B2s = $request->input('B2s',1);
            if($B2s=="" ) {$B2s=1;}
            $s2c = $request->input('s2c',1);
            if($s2c=="" ) {$s2c=1;}
            $warehouse = $request->input('warehouse',1);
            if($warehouse=="" ) {$warehouse=1;}
            $update_product = $request->input('update_product',1);
            if($update_product=="" ) {$update_product=1;}
            $add_product = $request->input('add_product',1);
            if($add_product=="" ) {$add_product=1;}
            $add_quick_purchase = $request->input('add_quick_purchase',1);
            if($add_quick_purchase=="" ) {$add_quick_purchase=1;}
            $purchase_order = $request->input('purchase_order',1);
            if($purchase_order=="" ) {$purchase_order=1;}
            $siyou_suppliers = $request->input('siyou_suppliers',1);
            if($siyou_suppliers=="" ) {$siyou_suppliers=1;}
            $my_suppliers = $request->input('my_suppliers',1);
            if($my_suppliers=="" ) {$my_suppliers=1;}
            $siyou_categories = $request->input('siyou_categories',1);
            if($siyou_categories=="" ) {$siyou_categories=1;}
            $my_category = $request->input('my_category',1);
            if($my_category=="" ) {$my_category=1;}
            $promotion_history = $request->input('promotion_history',1);
            if($promotion_history=="" ) {$promotion_history=1;}
            $promotion_list = $request->input('promotion_list',1);
            if($promotion_list=="" ) {$promotion_list=1;}
            $discount_history = $request->input('discount_history',1);
            if($discount_history=="" ) {$discount_history=1;}
            $sales_funds = $request->input('sales_funds',1);
            if($sales_funds=="" ) {$sales_funds=1;}
            $accounts_payable = $request->input('accounts_payable',1);
            if($accounts_payable=="" ) {$accounts_payable=1;}
            $payment_methods = $request->input('payment_methods',1);
            if($payment_methods=="" ) {$payment_methods=1;}
            $shop_operators_list = $request->input('shop_operators_list',1);
            if($shop_operators_list=="" ) {$shop_operators_list=1;}
            $add_shop_operator = $request->input('add_shop_operator',1);
            if($add_shop_operator=="" ) {$add_shop_operator=1;}
            $shop_cashiers_list = $request->input('shop_cashiers_list',1);
            if($shop_cashiers_list=="" ) {$shop_cashiers_list=1;}
            $add_shop_cashier = $request->input('add_shop_cashier',1);
            if($add_shop_cashier=="" ) {$add_shop_cashier=1;}
            $advertisement = $request->input('advertisement',1);
            if($advertisement=="" ) {$advertisement=1;}
            $inventory_management = $request->input('inventory_management',1);
            if($inventory_management=="" ) {$inventory_management=1;}
            $warehouse_management = $request->input('warehouse_management',1);
            if($warehouse_management=="" ) {$warehouse_management=1;}
            $returned_goods = $request->input('returned_goods',1);
            if($returned_goods=="" ) {$returned_goods=1;}
            $b2s_order_management = $request->input('b2s_order_management',1);
            if($b2s_order_management=="" ) {$b2s_order_management=1;}
            $discount_list = $request->input('discount_list',1);
            if($discount_list=="" ) {$discount_list=1;}
            $inventory_history = $request->input('inventory_history',1);
            if($inventory_history=="" ) {$inventory_history=1;}
            $stock_management = $request->input('stock_management',1);
            if($stock_management=="" ) {$stock_management=1;}
            $member_list = $request->input('member_list',1);
            if($member_list=="" ) {$member_list=1;}
            $level_list = $request->input('level_list',1);
            if($level_list=="" ) {$level_list=1;}
            $b2s_products=$request->input('b2s_products',1);
            if($b2s_products=="" ) {$b2s_products=1;}
            $product_list=$request->input('product_list',1);
            if($product_list=="" ) {$product_list=1;}
            $s2c_orders_list=$request->input('s2c_orders_list',1);
            if($s2c_orders_list=="" ) {$s2c_orders_list=1;}
            $add_discount=$request->input('add_discount',1);
            if($add_discount=="" ) {$add_discount=1;}
            $statistics = $request->input('statistics',1);
            if($statistics=="" ) {$statistics=1;}
            $this->accessRights($user->id,$chain_id,$B2s,$s2c,$warehouse,$add_discount,$statistics,$update_product,$add_product,$add_quick_purchase,$purchase_order,$siyou_suppliers,$my_suppliers,$siyou_categories,$my_category,$promotion_history,$promotion_list,$discount_history,$sales_funds,$accounts_payable,$payment_methods,$shop_operators_list,$add_shop_operator,$shop_cashiers_list,$add_shop_cashier,$advertisement,$inventory_management,$warehouse_management,$returned_goods,$b2s_products,$b2s_order_management,$discount_list,$inventory_history,$stock_management,$member_list,$level_list,$s2c_orders_list,$product_list);
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
        $shop_owner = AuthController::meme();
        $licence = License::where('shop_owner_id',$shop_owner->id)->orWhere('shop_owner_id',$shop_owner->shop_owner_id)->first();
        if(!$licence) {
            return response()->json(['code'=>0,'msg'=>'you do not have license']);
        }
        $cachiers=cachier::where('store_id',$shop_owner->store_id)->where('is_active',1)->count();
        if($cachiers>=$licence->max_cachiers) {
            return response()->json(['code'=>0,'msg'=>'reached maximum active  cashiers allowed for this account']);
        }
        $store_id = $shop_owner->shop->id;
        if ($request->hasFile('cachier_image')) {
            $path = $request->file('cachier_image')->store('cachiers', 'public');
            $fileUrl = Storage::url($path);
            $img_url = $fileUrl;
           $img_name = basename($path);

        }else {
            $img_url = null;
            $img_name = null;
        }

        try {
        $user= DB::table('cachiers')->insert([
	     'first_name' => $first_name, 
             'last_name'=>$last_name,
             'contact' => $contact,
             'password' => $password,
             'chain_id' => $chain_id,
             'store_id' => $store_id,
             'img_name'=> $img_name,
             'img_url'=>$img_url,
             'is_active' => $is_active,
         'hidden'=>0,
         'created_at'=>Carbon::now(),
         'updated_at'=>Carbon::now()
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
    public function accessRights($user_id,$chain_id,$B2s,$s2c,$warehouse,$add_discount,$statistics,$update_product,$add_product,$add_quick_purchase,$purchase_order,$siyou_suppliers,$my_suppliers,$siyou_categories,$my_category,$promotion_history,$promotion_list,$discount_history,$sales_funds,$accounts_payable,$payment_methods,$shop_operators_list,$add_shop_operator,$shop_cashiers_list,$add_shop_cashier,$advertisement,$inventory_management,$warehouse_management,$returned_goods,$b2s_products,$b2s_order_management,$discount_list,$inventory_history,$stock_management,$member_list,$level_list,$s2c_orders_list,$product_list) 
    {
        if($B2s ==="true" ||$B2s ==="1") {$B2s=1;}
        if($s2c==="true" ||$s2c ==="1") {$s2c=1;}
        if($warehouse==="true" ||$warehouse ==="1") {$warehouse=1;}
        if($add_discount==="true"||$add_discount ==="1") {$add_discount=1;}
        if($product_list==="true"||$product_list ==="1") {$product_list=1;}
        if($update_product==="true"||$update_product ==="1") {$update_product=1;}
        if($add_product==="true"||$B2s ==="1") {$add_product=1;}
        if($statistics==="true"||$statistics ==="1") {$statistics=1;}
        if($add_quick_purchase==="true"||$add_quick_purchase ==="1") {$add_quick_purchase=1;}
        if($stock_management==="true"||$stock_management ==="1") {$stock_management=1;}
        if($purchase_order==="true"||$purchase_order ==="1") {$purchase_order=1;}
        if($siyou_suppliers==="true"||$siyou_suppliers ==="1") {$siyou_suppliers=1;}
        if($my_suppliers==="true"||$my_suppliers ==="1") {$my_suppliers=1;}
        if($siyou_categories==="true"||$siyou_categories ==="1") {$siyou_categories=1;}
        if($my_category==="true"||$my_category ==="1") {$my_category=1;}
        if($promotion_history==="true"||$promotion_history ==="1") {$promotion_history=1;}
        if($promotion_list==="true"||$promotion_list ==="1") {$promotion_list=1;}
        if($discount_list==="true"||$discount_list ==="1") {$discount_list=1;}
        if($discount_history==="true"||$discount_history ==="1") {$discount_history=1;}
        if($sales_funds==="true"||$sales_funds ==="1") {$sales_funds=1;}
        if($accounts_payable==="true"||$accounts_payable ==="1") {$accounts_payable=1;}
        if($payment_methods==="true"||$payment_methods ==="1") {$payment_methods=1;}
        if($shop_operators_list==="true"||$shop_operators_list ==="1") {$shop_operators_list=1;}
        if($add_shop_operator==="true"||$add_shop_operator ==="1") {$add_shop_operator=1;}
        if($shop_cashiers_list==="true"||$shop_cashiers_list ==="true") {$shop_cashiers_list=1;}
        if($add_shop_cashier==="true"||$add_shop_cashier ==="1") {$add_shop_cashier=1;}
        if($advertisement==="true"||$advertisement ==="1") {$advertisement=1;}
        if($inventory_management==="true"||$inventory_management ==="1") {$inventory_management=1;}
        if($inventory_history==="true"||$inventory_history ==="1") {$inventory_history=1;}
        if($warehouse_management==="true"||$warehouse_management ==="1") {$warehouse_management=1;}
        if($returned_goods==="true"||$returned_goods ==="1") {$returned_goods=1;}
        if($b2s_products==="true"||$b2s_products ==="1") {$b2s_products=1;}
        if($member_list==="true"||$member_list ==="1") {$member_list=1;}
        if($level_list==="true"||$level_list ==="1") {$level_list=1;}
        if($s2c_orders_list==="true"||$s2c_orders_list ==="1") {$s2c_orders_list=1;}
        $arr=[];
        $arr['user_id']=$user_id;
        $arr['chain_id']=$chain_id;
        //for groupment
        $arr['B2s'] = $B2s===1?1:0;
        $arr['s2c'] = $s2c===1 ?1:0;
        $arr['warehouse'] =$warehouse===1?1:0;
        //b2b
        $arr['b2s_products'] = $B2s===1 && $b2s_products===1?1:0;
        $arr['purchased'] = $B2s===1 && $b2s_products===1?1:0;
        $arr['my_wishlist'] = $B2s==1 && $b2s_products===1?1:0;
        $arr['b2s_order_management']=$B2s===1 && $b2s_order_management===1?1:0;
        $arr['invalid_orders'] = $B2s===1 && $b2s_order_management===1?1:0;
        $arr['valid_orders'] = $B2s===1 && $b2s_order_management===1?1:0;
        $arr['paid_orders'] = $B2s===1 && $b2s_order_management===1?1:0;


        
        $arr['add_product'] = $s2c===1 && $add_product===1?1:0;
        $arr['update_product'] = $s2c===1 && $update_product===1?1:0;
        $arr['product_list'] = $s2c===1 && $product_list===1?1:0;
        $arr['affect_discount'] =$s2c===1 && $add_discount===1?1:0;
        $arr['discounted_products_list'] = $s2c===1?1:0;
        $arr['member_list'] = $s2c===1 && $member_list===1?1:0;
        $arr['level_list'] = $s2c===1 && $level_list===1?1:0;
        $arr['s2c_orders_list'] = $s2c===1 && $s2c_orders_list===1?1:0;
        $arr['shop_managers_list'] = 0;
        $arr['store_list'] = 0;
        $arr['add_new_store'] = 0;
        $arr['suppliers_list'] =$s2c===1 && $siyou_suppliers===1?1:0;
        $arr['inventory'] =$warehouse===1?1:0;
        $arr['stock_management'] =$warehouse===1 && $stock_management===1?1:0;
        //added fields
        

        $arr['inventory_management'] =$warehouse===1 && $inventory_management===1?1:0;
        $arr['inventory_history'] =$warehouse===1 && $inventory_history===1?1:0;
        $arr['returned_goods'] =$warehouse===1  &&  $returned_goods===1?1:0;
        $arr['advertisement'] =$s2c===1  &&  $advertisement===1?1:0;
        $arr['funds_by_cash'] =$warehouse===1?1:0;
        $arr['funds_by_card'] =$warehouse===1?1:0;
        $arr['funds_by_check'] =$warehouse===1?1:0;
        $arr['statistics'] =$statistics===1?1:0;

        $arr['my_account'] = 1;
        $arr['add_quick_purchase'] =$B2s==1&&$add_quick_purchase==1?1:0;
        $arr['purchase_order'] =$purchase_order==1?1:0;
        $arr['siyou_suppliers'] =$s2c==1&&$siyou_suppliers==1?1:0;
        $arr['my_suppliers'] =$s2c==1&&$my_suppliers==1?1:0;
        $arr['siyou_categories'] =$s2c==1&&$siyou_categories==1?1:0;
        $arr['my_category'] =$s2c==1&&$my_category==1?1:0;
        $arr['promotion_history'] =$s2c==1&&$promotion_history==1?1:0;
        $arr['promotion_list'] =$s2c==1&&$promotion_list==1?1:0;
        $arr['discount_history'] =$s2c==1&&$discount_history==1?1:0;
        $arr['discount_list'] =$s2c==1&&$discount_list==1?1:0;
        $arr['sales_funds'] =$s2c==1&&$sales_funds==1?1:0;
        $arr['accounts_payable'] =$s2c==1&&$accounts_payable==1?1:0;
        $arr['payment_methods'] =$s2c==1&&$payment_methods==1?1:0;
        $arr['shop_operators_list'] =$s2c==1&&$shop_operators_list==1?1:0;
        $arr['add_shop_operator'] =$s2c==1&&$add_shop_operator==1?1:0;
        $arr['shop_cashiers_list'] =$s2c==1&&$shop_cashiers_list==1?1:0;
        $arr['add_shop_cashier'] =$s2c==1&&$add_shop_cashier==1?1:0;
        $arr['warehouse_management'] =$warehouse_management==1?1:0;
        $menu = DB::table('menu')->where('user_id',$user_id)->first();
        if(!$menu) {
            DB::table('menu')->insert($arr);
        } else {
            DB::table('menu')->where('user_id',$user_id)->update($arr);
        }
        
    }
    public function getManagerById(Request $request,$id) {
        $manager = User::find($id);
        if($manager && $manager->role_id==2) {
            $manager->access_rights=DB::table('menu')->where('user_id',$id)->first();
            $manager->cashier_data=DB::table('cachiers')->where('user_id',$id)->first();
            $response['code'] = 1;
            $response['msg'] = "success";
            $response['data'] = $manager;
        } 
	else 
	{
            $response['code'] = 0;
            $response['msg'] = "fail";
            $response['data'] = 'not found';
        }
        return $response;
    }
    public function updateShopManager(Request $request,$id)
    {
        $shop_owner=AuthController::meme();
        $first_name = $request->input('first_name');
        $last_name = $request->input('last_name');
        $email = $request->input('email');
        $password = $request->input('password');
        $contact = $request->input('contact');
        $chain_id = $request->input('chain_id');
        $chain1=Chain::find($chain_id);
        if(isset($chain1->manager_id) &&$chain1->manager_id!=$id && isset($chain1->manager2_id)&& isset($chain1->manager3_id) &&$chain1->manager2_id!=$id &&$chain1->manager3_id!=$id) {
            return response()->json(['code'=>0,"msg"=>'max 3 managers for a chain',"data"=>'max 3 managers for a chain!! you nedd to deactivate a manager first']);
        }
        $birthday = $request->input('birthday');
        $hide_cost_price = $request->input('hide_cost_price');
        if ($request->hasFile('manager_image')) {
            $path = $request->file('manager_image')->store('managers', 'public');
            $fileUrl = Storage::url($path);
            $img_url = $fileUrl;
           $img_name = basename($path);

        }        
	$shop_owner = AuthController::me();
        $user = User::find($id);
        if(!$user || $user->role_id!=2) {
            return response()->json(["code"=>0,"msg"=>"error  no user found"]);
        }
	if($user->email != $email) 
	{
	   $tmp =User::where('email',$request->input('email'))->first();
           if($tmp) {
            return response()->json(["code"=>0,"msg" => "User already exists !!"]);
        	}

	}
	if($user->contact != $contact) 
	{
	   $tmp =User::where('contact',$request->input('contact'))->first();
           if($tmp) {
            return response()->json(["code"=>0,"msg" => "User already exists !!"]);
        	}

	}
        if(is_numeric($hide_cost_price)) {
            $user->hide_cost_price=$hide_cost_price;
        }
        
        if($hide_cost_price=='false') {$hide_cost_price=1;} else {$hide_cost_price=0;}
        $user->hide_cost_price=$hide_cost_price;
        $user ->shop_owner_id=$shop_owner->id;
        $user->first_name = $first_name;
        $user->last_name = $last_name;
        //$user->email = $email;
        $user->contact = $contact;
        //$user->birthday = $birthday;
        $user->profile_img_url = isset($img_url)?$img_url:$user->profile_img_url;
        $user->profile_img_name = isset($img_name)?$img_url:$user->profile_img_name;
        $user->chain_id = $chain_id;
        $user->activated_account=1;
	if( isset($password) && !empty($password)) {
		$user->password = Hash::make($password);
	}
     
        $role = Role::where('name', 'ShopManager')->first();
        if($role->users()->save($user)){
	   DB::table('users')->where('role_id', '=', 2)->where('id', '!=', $user->id)->where('chain_id', '=', $chain_id)->update(array('chain_id' => null,'activated_account'=>0));
       $chain=Chain::find($chain_id);
       $previousManagerChain=Chain::where('manager_id',$id)->orWhere('manager2_id',$id)->orWhere('manager3_id',$id)->first();
       if($previousManagerChain && $previousManagerChain->manager_id==$id) {
        $previousManagerChain->manager_id=null;
       }
       if($previousManagerChain && $previousManagerChain->manager2_id==$id) {
        $previousManagerChain->manager2_id=null;
       }
       if($previousManagerChain && $previousManagerChain->manager3_id==$id) {
        $previousManagerChain->manager3_id=null;
       }
       if($previousManagerChain)  {
        $previousManagerChain->save();
       }
       
       if(!isset($chain->manager_id) && $chain->manager2_id != $id && $chain->manager3_id!=$id ) {
           $chain->manager_id=$user->id;
       }
       else if(!isset($chain->manager2_id) && $chain->manager_id!=$id && $chain->manager3_id!=$id) {
           $chain->manager2_id=$user->id;
       }
       else if(!isset($chain->manager3_id) && $chain->manager2_id!=$id && $chain->manager_id!=$id) {
           $chain->manager3_id=$user->id;
       }
	   $chain->save();

            $cachier_code = $request->input('cachier_code');
            if(isset($chain)) {
            DB::table('cachiers')->where('user_id',$user->id)->update([
                'first_name'=>$first_name,
                'last_name'=>$last_name,
                'contact'=>$contact,               
                'chain_id'=>$chain_id,
                'img_url'=>$user->profile_img_url,
                'img_name'=>$user->profile_img_name,
                'password'=>$cachier_code,
                'is_active'=>1,
                'is_manager'=>1,
              

            ]);}
            $B2s = $request->input('B2s',1);
            if($B2s=="" ) {$B2s=1;}
            $s2c = $request->input('s2c',1);
            if($s2c=="" ) {$s2c=1;}
        $add_discount= $request->input('add_discount',1);
        if($add_discount=="" ) {$add_discount=1;}
        $statistics= $request->input('statistics',1);
        if($statistics=="" ) {$statistics=1;}
        $add_quick_purchase = $request->input('add_quick_purchase',1);
        if($add_quick_purchase=="" ) {$add_quick_purchase=1;}
        $purchase_order = $request->input('purchase_order',1);
        if($purchase_order=="" ) {$purchase_order=1;}
        $siyou_suppliers = $request->input('siyou_suppliers',1);
        if($siyou_suppliers=="" ) {$siyou_suppliers=1;}
        $my_suppliers = $request->input('my_suppliers',1);
        if($my_suppliers=="" ) {$my_suppliers=1;}
        $siyou_categories = $request->input('siyou_categories',1);
        if($siyou_categories=="" ) {$siyou_categories=1;}
        $my_category = $request->input('my_category',1);
        if($my_category=="" ) {$my_category=1;}
        $promotion_history = $request->input('promotion_history',1);
        if($promotion_history=="" ) {$promotion_history=1;}
        $promotion_list = $request->input('promotion_list',1);
        if($promotion_list=="" ) {$promotion_list=1;}
        $discount_history = $request->input('discount_history',1);
        if($discount_history=="" ) {$discount_history=1;}
        $sales_funds = $request->input('sales_funds',1);
        if($sales_funds=="" ) {$sales_funds=1;}
        $accounts_payable = $request->input('accounts_payable',1);
        if($accounts_payable=="" ) {$accounts_payable=1;}
        $payment_methods = $request->input('payment_methods',1);
        if($payment_methods=="" ) {$payment_methods=1;}
        $shop_operators_list = $request->input('shop_operators_list',1);
        if($shop_operators_list=="" ) {$shop_operators_list=1;}
        $add_shop_operator = $request->input('add_shop_operator',1);
        if($add_shop_operator=="" ) {$add_shop_operator=1;}
        $shop_cashiers_list = $request->input('shop_cashiers_list',1);
        if($shop_cashiers_list=="" ) {$shop_cashiers_list=1;}
        $add_shop_cashier = $request->input('add_shop_cashier',1);
        if($add_shop_cashier=="" ) {$add_shop_cashier=1;}
        $advertisement = $request->input('advertisement',1);
        if($advertisement=="" ) {$advertisement=1;}
        $inventory_management = $request->input('inventory_management',1);
        if($inventory_management=="" ) {$inventory_management=1;}
        $warehouse_management = $request->input('warehouse_management',1);
        if($warehouse_management=="" ) {$warehouse_management=1;}
        $returned_goods = $request->input('returned_goods',1);
        if($returned_goods=="" ) {$returned_goods=1;}
        $warehouse = $request->input('warehouse',1);
        if($warehouse=="" ) {$warehouse=1;}
        $b2s_order_management = $request->input('b2s_order_management',1);
        if($b2s_order_management=="" ) {$b2s_order_management=1;}
        $discount_list = $request->input('discount_list',1);
        if($discount_list=="" ) {$discount_list=1;}
        $inventory_history = $request->input('inventory_history',1);
        if($inventory_history=="" ) {$inventory_history=1;}
        $stock_management = $request->input('stock_management',1);
        if($stock_management=="" ) {$stock_management=1;}
        $member_list = $request->input('member_list',1);
        if($member_list=="" ) {$member_list=1;}
        $level_list = $request->input('level_list',1);
        if($level_list=="" ) {$level_list=1;}
        $b2s_products=$request->input('b2s_products',1);
        if($b2s_products=="" ) {$b2s_products=1;}
        $s2c_orders_list=$request->input('s2c_orders_list',1);
        if($s2c_orders_list=="" ) {$s2c_orders_list=1;}
        $product_list = $request->input('product_list',1);
        if($product_list=="" ) {$product_list=1;}
        $add_product=$request->input('add_product',1);
        if($add_product=="" ) {$add_product=1;}
        $update_product=$request->input('update_product',1);
        if($update_product=="" ) {$update_product=1;}
        $this->accessRights($user->id,$chain_id,$B2s,$s2c,$warehouse,$add_discount,$statistics,$update_product,$add_product,$add_quick_purchase,$purchase_order,$siyou_suppliers,$my_suppliers,$siyou_categories,$my_category,$promotion_history,$promotion_list,$discount_history,$sales_funds,$accounts_payable,$payment_methods,$shop_operators_list,$add_shop_operator,$shop_cashiers_list,$add_shop_cashier,$advertisement,$inventory_management,$warehouse_management,$returned_goods,$b2s_products,$b2s_order_management,$discount_list,$inventory_history,$stock_management,$member_list,$level_list,$s2c_orders_list,$product_list);

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
            $path = $request->file('cachier_image')->store('cachiers', 'public');
            $fileUrl = Storage::url($path);
            $img_url = $fileUrl;
           $img_name = basename($path);

        }
	else 
	{
       	   $cachier = cachier::find($id);
       if(!$cachier) 
	{
	   return response()->json(['code'=>0,'msg'=>'cashier not found']);
	} 
else {$img_url = $cachier->img_url;
           $img_name = $cachier->img_name;
}
}
        try {
        $user= DB::table('cachiers')->where('id',$id)->update(
            ['first_name' => $first_name, 
            'last_name'=>$last_name,
            'contact' => $contact,
  	        'password'=>$password,
            'chain_id' => $chain_id,
            
            'img_name'=> $img_name,
            'img_url'=>$img_url,
            'is_active' => $is_active,
            'updated_at'=>Carbon::now()
             ]);
            $cashier=cachier::find($id);
            if($cashier->is_manager) {
                $manager = User::find($cashier->user_id);
                $this->desactivateManager($request,$cashier->user_id);
            }
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

	if($cachier->is_manager ==1){

            return response()->json(['code'=>0,'msg'=>'error while deleting']);

        }
	$cachier->hidden = 1;
	$cachier->is_active = 0;
        if($cachier->save()){
	
        return response()->json(['code'=>1,'msg'=>'cashier successfully deleted']);}
    }

public function updateOperator(Request $request,$id)
    {

        $first_name = $request->input('first_name');
        $last_name = $request->input('last_name');
        $email = $request->input('email');
        $password = $request->input('password');
        $hide_cost_price=$request->input('hide_cost_price');
        $contact = $request->input('contact');
        $chain_id = $request->input('chain_id');
        $birthday = $request->input('birthday');
        if ($request->hasFile('operator_image')) {
            $path = $request->file('operator_image')->store('operators', 'public');
            $fileUrl = Storage::url($path);
            $img_url = $fileUrl;
           $img_name = basename($path);

        }else {
            $img_url = null;
            $img_name = null;
        }
        $shop_owner = AuthController::me();
        $user = User::find($id);
        if(!$user || $user->role_id!=3) {
            return response()->json(["code"=>0,"msg"=>"error  no user found"]);
        }
        //$user ->shop_owner_id=$shop_owner->id;
        if(is_numeric($hide_cost_price)) {
            $user->hide_cost_price=$hide_cost_price;
        }
        if($hide_cost_price=='false') {$hide_cost_price=1;} else {$hide_cost_price=0;}
        $user->hide_cost_price=$hide_cost_price;
        
        if( isset($password) && !empty($password)) {
            $user->password = Hash::make($password);
        }
        
        $user->first_name = $first_name;
        $user->last_name = $last_name;

        $user->contact = $contact;
        $user->chain_id = $chain_id;
        $user->activated_account=$request->input('activated_account',1);
        
        $role = Role::where('name', 'operator')->first();
        if($role->users()->save($user)){
            
            $response = array();
            $response['code']=1;
            $response['msg']="";
            $response['data']=$user;
            
            
            $B2s = $request->input('B2s');
            if($B2s=="" ) {$B2s=1;}
            $s2c = $request->input('s2c');
            if($s2c=="" ) {$s2c=1;}
            $warehouse = $request->input('warehouse');
            if($warehouse=="" ) {$warehouse=1;}
            $update_product = $request->input('update_product');
            if($update_product=="" ) {$update_product=1;}
            $add_product = $request->input('add_product');
            if($add_product=="" ) {$add_product=1;}
            $add_quick_purchase = $request->input('add_quick_purchase');
            if($add_quick_purchase=="" ) {$add_quick_purchase=1;}
            $purchase_order = $request->input('purchase_order');
            if($purchase_order=="" ) {$purchase_order=1;}
            $siyou_suppliers = $request->input('siyou_suppliers');
            if($siyou_suppliers=="" ) {$siyou_suppliers=1;}
            $my_suppliers = $request->input('my_suppliers');
            if($my_suppliers=="" ) {$my_suppliers=1;}
            $siyou_categories = $request->input('siyou_categories');
            if($siyou_categories=="" ) {$siyou_categories=1;}
            $my_category = $request->input('my_category');
            if($my_category=="" ) {$my_category=1;}
            $promotion_history = $request->input('promotion_history');
            if($promotion_history=="" ) {$promotion_history=1;}
            $promotion_list = $request->input('promotion_list');
            if($promotion_list=="" ) {$promotion_list=1;}
            $discount_history = $request->input('discount_history');
            if($discount_history=="" ) {$discount_history=1;}
            $sales_funds = $request->input('sales_funds');
            if($sales_funds=="" ) {$sales_funds=1;}
            $accounts_payable = $request->input('accounts_payable');
            if($accounts_payable=="" ) {$accounts_payable=1;}
            $payment_methods = $request->input('payment_methods');
            if($payment_methods=="" ) {$payment_methods=1;}
            $shop_operators_list = $request->input('shop_operators_list');
            if($shop_operators_list=="" ) {$shop_operators_list=1;}
            $add_shop_operator = $request->input('add_shop_operator');
            if($add_shop_operator=="" ) {$add_shop_operator=1;}
            $shop_cashiers_list = $request->input('shop_cashiers_list');
            if($shop_cashiers_list=="" ) {$shop_cashiers_list=1;}
            $add_shop_cashier = $request->input('add_shop_cashier');
            if($add_shop_cashier=="" ) {$add_shop_cashier=1;}
            $advertisement = $request->input('advertisement');
            if($advertisement=="" ) {$advertisement=1;}
            $inventory_management = $request->input('inventory_management');
            if($inventory_management=="" ) {$inventory_management=1;}
            $warehouse_management = $request->input('warehouse_management');
            if($warehouse_management=="" ) {$warehouse_management=1;}
            $returned_goods = $request->input('returned_goods');
            if($returned_goods=="" ) {$returned_goods=1;}
            $b2s_order_management = $request->input('b2s_order_management');
            if($b2s_order_management=="" ) {$b2s_order_management=1;}
            $discount_list = $request->input('discount_list');
            if($discount_list=="" ) {$discount_list=1;}
            $inventory_history = $request->input('inventory_history');
            if($inventory_history=="" ) {$inventory_history=1;}
            $stock_management = $request->input('stock_management');
            if($stock_management=="" ) {$stock_management=1;}
            $member_list = $request->input('member_list');
            if($member_list=="" ) {$member_list=1;}
            $level_list = $request->input('level_list');
            if($level_list=="" ) {$level_list=1;}
            $b2s_products=$request->input('b2s_products');
            if($b2s_products=="" ) {$b2s_products=1;}
            $s2c_orders_list=$request->input('s2c_orders_list');
            if($s2c_orders_list=="" ) {$s2c_orders_list=1;}
            $product_list=$request->input('product_list');
            if($product_list=="" ) {$product_list=1;}
            $add_discount=$request->input('add_discount',1);
            if($add_discount=="" ) {$add_discount=1;}
            $statistics = $request->input('statistics',1);
            if($statistics=="" ) {$statistics=1;}
            $this->accessRights($user->id,$chain_id,$B2s,$s2c,$warehouse,$add_discount,$statistics,$update_product,$add_product,$add_quick_purchase,$purchase_order,$siyou_suppliers,$my_suppliers,$siyou_categories,$my_category,$promotion_history,$promotion_list,$discount_history,$sales_funds,$accounts_payable,$payment_methods,$shop_operators_list,$add_shop_operator,$shop_cashiers_list,$add_shop_cashier,$advertisement,$inventory_management,$warehouse_management,$returned_goods,$b2s_products,$b2s_order_management,$discount_list,$inventory_history,$stock_management,$member_list,$level_list,$s2c_orders_list,$product_list);
	        return response()->json($response);
        }
        $response = array();
        $response['code']=0;
        $response['msg']="3";
        $response['data']='Error while saving';
        return response()->json($response);
    }
    public function getOperatorById(Request $request,$id) {
        $operator = User::find($id);
        if($operator && $operator->role_id==3) {
            $operator->access_rights=DB::table('menu')->where('user_id',$id)->first();
            $response['code'] = 1;
            $response['msg'] = "success";
            $response['data'] = $operator;
        } else {
            $response['code'] = 0;
            $response['msg'] = "fail";
            $response['data'] = 'not found';
        }
        return response()->json($response);
    }
public function desactivateManager(Request $request,$id) {
    $shopOwner= AuthController::meme();
    $manager=User::where('shop_owner_id',$shopOwner->id)->where('role_id',2)->where('id',$id)->first();
    if(!$manager) {
        return response()->json(['code'=>0,'msg'=>'user not found','data'=>'']);
    }
    $manager->activated_account=0;
    $manager->chain_id=null;
    if($manager->save()) {
        $chain=Chain::where('manager_id',$id)->orWhere('manager2_id',$id)->orWhere('manager3_id',$id)->get();
        if(!$chain->count()){
            return response()->json(['code'=>0,'msg'=>'manager deactivated successfully','data'=>'manager deactivated successfully']);
        } else {
            $cashier = cachier::where('user_id',$manager->id)->first();
            if($cashier) {
                $cashier->is_active=0;
                $cashier->save();
            }

            foreach($chain as $ch) {
            if($ch->manager_id==$id) {
                $ch->manager_id=null;
            }
            else if($ch->manager2_id==$id) {
                $ch->manager2_id=null;
            }
            else if($ch->manager3_id==$id) {
                $ch->manager3_id=null;
            }
            $ch->save();
        }
        }
       
        return response()->json(['code'=>0,'msg'=>'manager deactivated successfully','data'=>'manager deactivated successfully']);
        
    } else {
        return response()->json(['code'=>0,'msg'=>'error while deactivating','data'=>'error while deactivating']);
    }

	
    }
    
    public function activateManager(Request $request,$id)
    {   
        $shop_owner=AuthController::meme();
        if($shop_owner->role_id!=1) {
            return response()->json(['code'=>0,'msg'=>'method not allowed']);
        }
        $chain_id= $request->input('chain_id');
        $chain= Chain::find($id);
        $manager=User::where('id',$id)->where('shop_owner_id',$shop_owner->id)->where('role_id',2)->first();
        if(!$manager) {
            return response()->json(['code'=>0,'msg'=> 'manager with id '.$id . 'not found']);
        }
        if($manager->activated_account==1  || isset($manager->chain_id)) {
            return response()->json(['code'=>0,'msg'=>'manager account is already activated']);
        }
        if(!$chain) {
            return response()->json(['code'=>0,'msg'=>'trying to associate a manager to not existing chain']);
        }
        if(isset($chain->manager_id) && isset($chain->manager2_id) && isset($chain->manager3_id)) {
            return response()->json(['code'=>0,'msg'=>'reached maximum managers associated to this chain !! nedd to deactivate  a manager related to this chain']);
        }
        $manager->activated_account=1;
        $manager->chain_id=$chain_id;

        if($manager->save) {
            if(!isset($chain->manager_id)) {
                $chain->manager_id=$id;
                $chain->save();
                return response()->json(['code'=>1,'msg'=>'manager activated successfully']);

            }
            if(!isset($chain->manager2_id)) {
                $chain->manager2_id=$id;
                $chain->save();
                return response()->json(['code'=>1,'msg'=>'manager activated successfully']);

            }
            if(!isset($chain->manager3_id)) {
                $chain->manager3_id=$id;
                $chain->save();
                return response()->json(['code'=>1,'msg'=>'manager activated successfully']);

            }
        }else {
            return response()->json(['code'=>1,'msg'=>'error while activating manager']);
        }

    }

}
