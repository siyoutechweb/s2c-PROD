<?php namespace App\Http\Controllers\User;

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Models\cachier;
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
	//var_dump($managerList);
//echo gettype($managerList);
        if($managerList) {
	    if($chain_id!='') {
                $managerList =array_values( array_filter($managerList->toArray(),function($query) use($chain_id) {
                    //stristr
                    return $query['chain_id']==$chain_id;
                }));
	            }else {$managerList = $managerList->toArray();}
	     foreach($managerList as $Manager) {
		//echo gettype($Manager);
               //echo $Manager['chain_id'];
		 if($Manager['chain_id']) 
                {
			//echo $Manager['chain_id'];
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
                    //stristr
                    return $query['chain_id']==$chain_id;

                }));
                $managerList = (object)$managerList;
	            }
	     foreach($managerList as $Manager) {
            //echo gettype($Manager);
$Manager = (object) $Manager;
echo $Manager->chain_id;
		 if($Manager->chain_id) 
                {
                    //echo $Manager->chain_id;
                   $Manager->chain_name =Chain::where('id',$Manager->chain_id)->first()->chain_name;
                   //array_push($Manager, $arr);               
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
                    //stristr
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
        $shop_owner = AuthController::me();
        //echo $shop_owner->shop->id;
        // $cachierList=$shop_owner->ShopOwnerCachier()->get();
        $cachierList=Cachier::where('store_id',$shop_owner->shop->id)->get();
        if($request->has('chain_id')) {
            $chain_id =  $request->input('chain_id');
            $cachierList =array_values( array_filter($cachierList->toArray(),function($query) use($chain_id) {
                //stristr
                return $query['chain_id']==$chain_id;
            }));
        }
else {$cachierList = $cachierList->toArray();}
        if($request->has('name') && !empty($request->input('name') )) {
            $name = $request->input('name');
            $cachierList =array_values( array_filter($cachierList,function($query) use($name ) {
               //return strpos($query['first_name'],$name) ||strpos($query['last_name'],$name);
		return is_numeric(strpos($query['first_name'],$name)) ||is_numeric(strpos($query['last_name'],$name));
                //return $query['name ']==$name ;
            }));
        }

        //echo gettype($cachierList);
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
        return response()->json($user);
    }

}
