<?php namespace App\Http\Controllers\User;

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Models\cachier;
use App\Models\Supplier;
use App\Models\Chain;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
/*
SIYOU THECH Tunisia
Author: Habiba Boujmil
*/

class UsersController extends Controller {


    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /* get shop's Manager list
     - Parameters: 'token'
       Accessible for : ShopOwner
    */
  public function getManagersList(Request $request)
    {
        $shop_owner = AuthController::me();
        $chain_id = $request->input('chain_id');
        if(!$chain_id ||empty($chain_id)) {
            $chain_id = '';
        }
        $managerList=$shop_owner->ShopOwnerManager($chain_id)->get();
        if($managerList) {
	    if($chain_id!='') {
                $managerList =array_values( array_filter($managerList->toArray(),function($query) use($chain_id) {
                    
                    return $query['chain_id']==$chain_id;
                }));
	            }else {$managerList = $managerList->toArray();}
	     foreach($managerList as $Manager) {
		 if($Manager['chain_id']) 
                {
	
                    $arr = array('chain_name'=> Chain::where('id',$Manager['chain_id'])->first()->chain_name);
                    $Manager = array_push($Manager, $arr);                }
              
            }

            $response['code'] = 1;
            $response['msg'] = 'success';
            $response['data'] = $managerList;
        }else{
            $response['code'] = 0;
            $response['msg'] = 'fail';
            $response['data'] = 'no data found';
        }

        return response()->json($response, 200);

    }
    public function getManagersList1(Request $request)
    {
        $shop_owner = AuthController::me();
        $chain_id = $request->input('chain_id');
        if(!$chain_id ||empty($chain_id)) {
            $chain_id = '';
        }
        $managerList=$shop_owner->ShopOwnerManager($chain_id)->get();

        if($managerList) {
	    
	    if($chain_id!='') {
                $managerList =array_values( array_filter($managerList->toArray(),function($query) use($chain_id) {
                   
                    return $query['chain_id']==$chain_id;

                }));
                $managerList = (object)$managerList;
	            }
	     foreach($managerList as $Manager) {
           
		$Manager = (object) $Manager;
		
		 if($Manager->chain_id) 
                 {
  
                   $Manager->chain_name =Chain::where('id',$Manager->chain_id)->first()->chain_name;
              
                 }
              
            }

            $response['code'] = 1;
            $response['msg'] = 'success';
            $response['data'] = $managerList;
        }else{
            $response['code'] = 0;
            $response['msg'] = 'fail';
            $response['data'] = 'no data found';
        }

        return response()->json($response, 200);

    }
 public function getOperatorsList(Request $request)
    {
        $shop_owner = AuthController::me();
        $operatorsList=$shop_owner->ShopOwnerOperator()->get();
	$chain_id = $request->input('chain_id');
        if($operatorsList) {
	   if($chain_id!='') {
                $operatorsList =array_values( array_filter($operatorsList->toArray(),function($query) use($chain_id) {
                return $query['chain_id']==$chain_id;
                }));
            }
            $response['code'] = 1;
            $response['msg'] = 'success';
            $response['data'] = $operatorsList;
        }else{
            $response['code'] = 0;
            $response['msg'] = 'fail';
            $response['data'] = 'no data found';
        }
        return response()->json($response, 200);


    }   public function getsuppliers()
    {
        $categories = Supplier::all();
        return response()->json($categories);
    }
    public function addSuppliersToShop(Request $request)
    {
        $categories = $request->input('suppliers');
        $store_id = $request->input('store_id');

        foreach($categories as $category) {
            DB::table('shop_category')->insert(['shop_id'=>$store_id,'supplier_id'=>$category]);
        }
        return response()->json(['code'=>'1','msg'=>'Suppliers selected']);
    }
    /* get shop's cachier list
     - Parameters: 'token'
       Accessible for : ShopOwner
    */
        public function getCachiersList(Request $request)
    {
        $shop_owner = AuthController::meme();
        $chain_id =  $request->input('chain_id');
        $userchain=$shop_owner->chain_id;
        $name = $request->input('name');
        $cachierList=Cachier::where('store_id',$shop_owner->store_id)
	->where('hidden',0)
        ->when($chain_id != '', function ($query) use ($chain_id) {
            $query->where('chain_id',$chain_id);})
        ->when($userchain != '', function ($query) use ($userchain) {
                $query->where('chain_id',$userchain);})
        ->when($name != '', function ($query) use ($name) {
                    $query->where('first_name','like',$name)->orWhere('last_name','like',$name);})
            ->get()->toArray();
        
         return response()->json($cachierList, 200);

    }
    /* get manager from his email
     - Parameters: 'token','manager_email'
       Accessible for : ShopOwner
    */
    public function getManagerByEmail(Request $request)
    {
        $email = $request->input('email');
        $Manager = User::where('email', $email)
            ->whereHas('role', function ($query) {
                $query->where('name', 'ShopManager')->distinct();
            })->get();
        return response()->json($Manager, 200);
    }

    /* get cachier from his email
     - Parameters: 'token','cachier_email'
       Accessible for : ShopOwner
    */
    public function getCachierByEmail(Request $request)
    {
        $email = $request->input('email');
        // $shopsIds = $request->input('shopsIds');
        $cachier = User::where('email', $email)
            ->whereHas('role', function ($query) {
                $query->where('name', 'cachier')->distinct();
            })->get();
        return response()->json($cachier, 200);
    }

    public function getMenu(Request $request)
    {
        $shop_owner = AuthController::me();
        $chains= $shop_owner->shop->chains()->pluck('id');

        $menu = db::table('menu')->whereIn('chain_id', $chains)->get();
       
        return response()->json($menu,200);
    }

    public function updateUser(Request $request)
    {
        $user = AuthController::me();
        $user->first_name= $request->input('first_name');
        $user->last_name= $request->input('last_name');
        // $user->email= $request->input('email');
        $user->contact= $request->input('contact');
        $user->birthday= $request->input('birthday');
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
        // return $request->file('profil_img');
        // if ($request->hasFile('profil_img')) {
        //     return $request->file('profil_img');
        //     $isImageDeleted = Storage::disk('public')->delete('profile_img/' . $user->profile_img_name);
        //     if ($isImageDeleted) {
        //         $image->delete();
        //     }
        //     $path = $request->file('profil_img')->store('profile_img','public');
        //     $fileUrl = Storage::url($path);
        //     $user->img_url = $fileUrl;
        //     $user->img_name = basename($path);
        // }
        // if ($request->hasFile('cover_img')) {
        //     $isImageDeleted = Storage::disk('public')->delete('cover_img/' . $user->cover_img_name);
        //     if ($isImageDeleted) {
        //         $image->delete();
        //     }
        //     $path = $request->file('cover_img')->store('cover_img','public');
        //     $fileUrl = Storage::url($path);
        //     $user->cover_img_url = $fileUrl;
        //     $user->cover_img_name = basename($path);
        // }
        $user->save();
        $response['code']=1;
        $response['msg']='';
        $response['data']=$user;
        return response()->json($response,200);
    }

public function getCurrentUser() {
        $user = AuthController::me();
        //id, user_id, chain_id, b2s_products, purchased, my_wishlist, invalid_orders, valid_orders, paid_orders, add_product, product_list, affect_discount, discounted_products_list, member_list, level_list, shop_managers_list, add_new_shop_manager, 
        //store_list, add_new_store, suppliers_list, inventory, stock_management, my_account, created_at, updated_at, warehouse, 
        //funds_by_cash, funds_by_card, funds_by_check, statistics, update_product, add_quick_purchase, purchase_order, siyou_suppliers,
        // my_suppliers, siyou_categories, my_category, promotion_history, promotion_list, discount_history, sales_funds, accounts_payable,
        // payment_methods, shop_operators_list, add_shop_operator, shop_cashiers_list, add_shop_cashier, advertisement, inventory_management,
        // warehouse_management, returned_goods, b2s_order_management, discount_list, inventory_history
        $user->menu = DB::table('menu')->where('user_id',$user->id)->first();
        if(!$user->menu) {
            $user->menu=[
                "user_id"=>$user->id,
                "chain_id"=>null,
                "b2s_products"=>1,
                "purchased"=>1,
                "my_wishlist"=>1,
                "invalid_orders"=>1,
                "valid_orders"=>1,
                "paid_orders"=>1,
                "s2c_orders_list"=>1,
                "add_product"=>1,
                "product_list"=>1,
                "affect_discount"=>1,
                "discounted_products_list"=>1,
                "member_list"=>1,
                "level_list"=>1,
                "shop_managers_list"=>1,
                "add_new_shop_manager"=>1,
                "store_list"=>1,
                "add_new_store"=>1,
                "suppliers_list"=>1,
                "inventory"=>1,
                "stock_management"=>1,
                "my_account"=>1,
                "warehouse"=>1,
                "funds_by_cash"=>1,
                "funds_by_card"=>1,
                "funds_by_check"=>1,
                "statistics"=>1,
                "update_product"=>1,
                "add_quick_purchase"=>1,
                "purchase_order"=>1,
                "siyou_suppliers"=>1,
                "my_suppliers"=>1,
                "siyou_categories"=>1,
                "my_category"=>1,
                "promotion_history"=>1,
                "promotion_list"=>1,
                "discount_history"=>1,
                "sales_funds"=>1,
                "accounts_payable"=>1,
                "payment_methods"=>1,
                "shop_operators_list"=>1,
                "add_shop_operator"=>1,
                "shop_cashiers_list"=>1,
                "add_shop_cashier"=>1,
                "advertisement"=>1,
                "inventory_management"=>1,
                "warehouse_management"=>1,
                "returned_goods"=>1,
                "warehouse_management"=>1,
                "b2s_order_management"=>1,
                "discount_list"=>1,
                "inventory_history"=>1,
                "B2s"=>1,
                "s2c"=>1
                

            ];
        }
        return response()->json($user);
    }

public function getManagersList2(Request $request)
    {
        $shop_owner = AuthController::meme();
        $chain_id = $request->input('chain_id');
        if(!$chain_id ||empty($chain_id)) {
            $chain_id = '';
        }
	$managers=User::where('shop_owner_id',$shop_owner->id)->where('role_id',2)->with('chain')->when($chain_id!='',function($query)use($chain_id) {

	$query->where('chain_id',$chain_id);
	})->get();
	$response['code'] = 1;
	$response['msg'] = "";
	$response['data'] = $managers;
return response()->json($response);
        
    }


}
