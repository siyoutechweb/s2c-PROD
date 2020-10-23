<?php namespace App\Http\Controllers\Store;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Shop;
use App\Models\Chain;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

/*
SIYOU THECH Tunisia
Author: Habiba Boujmil
ERROR MSG
* 1：parameters missing, in data field indicate whuch parameter is missing
* 2：token expired or forced to logout, take to relogin
* 3：error while saving
* 4: error while deleting
*/

class ShopsController extends Controller {

    public function __construct()
    {
        $this->middleware('auth:api');
    }
    
     /* Add store API
     - Parameters:'store_name','store_name_en','store_name_it','store_area','store_domain',
     'store_adress','contact','store_longitude','store_latitude'
     'opening_hour','closure_hour','store_ip','store_is_selfsupport',store_logo
     
    */
    public function createStore(Request $request)
    {
        $shop_owner = AuthController::me();
        $shop=$shop_owner->shop()->get();
        if ($shop==[]) {
        $shop= new shop();
        $shop->store_name = $request->input('store_name');
        $shop->store_name_en = $request->input('store_name_en');
        $shop->store_name_it = $request->input('store_name_it');
        $shop->store_area = $request->input('store_area');
        $shop->store_domain = $request->input('store_domain');
        $shop->store_adress = $request->input('store_adress');
        $shop->contact = $request->input('contact');
        $shop->store_longitude = $request->input('store_longitude'); 
        $shop->store_latitude = $request->input('store_latitude');
        $shop->opening_hour = $request->input('opening_hour');
        $shop->closure_hour = $request->input('closure_hour');
        $shop->store_ip = $request->input('store_ip');
        $shop->store_is_selfsupport = $request->input('store_is_selfsupport');
        $shop->shop_owner_id = $shop_owner->id;
        if ($request->hasFile('store_logo')) 
        {
            $path = $request->file('store_logo')->store('logos', 'public');
            $fileUrl = Storage::url($path);
            $product->store_logo = $fileUrl;
        }
        
        if($shop->save())
        {
            return response()->json(['msg' => 'Store has been added'], 200);
        }
        return response()->json(['msg' => 'Error while saving'], 500);
    }
    return response()->json(['msg' => 'Shop Owner has a store'], 500);
        
    }

     /* get store API
     - Parameters:'token','store_id'
     Accessible for : ShopOwner
    */
    public function getStore(Request $request)
    {
        $shop_owner = AuthController::me();
        
        $store_id=$request->query('store_id');
        $store_id=$shop_owner->shop()->value('id');
        $store = shop::find($store_id);
        $response = array();
        $response['msg']="";
        $response['code']=1;
        $response['data']=$store;
        return response()->json($response);
    }
    public function getManagerStore(Request $request)
    {
        //echo 'hi';
        $shop_manager = AuthController::me();
        //echo $shop_manager;
        if($shop_manager->role_id ==2){
            $chain_id = $shop_manager->chain_id;
        // $store_id=$request->query('store_id');
        // $store_id=$shop_owner->shop()->value('id');
        $chain=Chain::where('id',$chain_id)->first();
        $store_id = $chain->store_id;
        $store = shop::find($store_id);
        $response = array();
        $response['msg']="";
        $response['code']=1;
        $response['data']=$store;
        return response()->json($response);
        }
        else {
            $response = array();
        $response['msg']="";
        $response['code']=1;
        $response['data']="unauthorized";
        return response()->json($response);
        }
        
    }



     /* Update store information API
     - Parameters:'token','store_name','store_name_en','store_name_it','store_area','store_domain',
     'store_adress','contact','store_longitude','store_latitude'
     'opening_hour','closure_hour','store_ip','store_is_selfsupport',store_logo
     Accessible for : ShopOwner
    */

    public function updatestore(Request $request)
    {
        $shop_owner = AuthController::me();
        $shop=$shop_owner->shop()->get();
        $shop->store_name = $request->input('store_name');
        $shop->store_name_en = $request->input('store_name_en');
        $shop->store_name_it = $request->input('store_name_it');
        $shop->store_area = $request->input('store_area');
        $shop->store_domain = $request->input('store_domain');
        $shop->store_adress = $request->input('store_adress');
        $shop->contact = $request->input('contact');
        $shop->store_longitude = $request->input('store_longitude'); 
        $shop->store_latitude = $request->input('store_latitude');
        $shop->opening_hour = $request->input('opening_hour');
        $shop->closure_hour = $request->input('closure_hour');
        $shop->store_ip = $request->input('store_ip');
        $shop->store_is_selfsupport = $request->input('store_is_selfsupport');
        $shop->shop_owner_id = $shop_owner->id;
        // if ($request->hasFile('store_logo')) {
            //     $path = $request->file('store_logo')->store('logos', 'public');
            //     $fileUrl = Storage::url($path);
            //     $product->store_logo = $fileUrl;
            // }
        
        if($shop->save())
        {
            return response()->json(['msg' => 'Store has been added'], 200);
        }
        return response()->json(['msg' => 'Error while saving'], 500);
     
        
    }



}
