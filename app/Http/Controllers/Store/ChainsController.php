<?php namespace App\Http\Controllers\Store;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Shop;
use App\Models\Chain;
use App\Models\Warehouse;
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
class ChainsController extends Controller {

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /* Add chain API
     - Parameters: 'token','chain_name','adress','contacte','chain_mobile','chain_telephone','chain_opening_hours'
     'chain_close_hours','chain_trafic_line','chain_lng','chain_lat','approved',
     'chain_ip','chain_district_id','chain_district_info'
       Accessible for : ShopOwner
    */
    public function addchain(Request $request)
    {
        $shop_owner = AuthController::me();
        $store_id=$shop_owner->shop()->value('id');
        $chain= new chain();
        $chain->chain_name = $request->input('chain_name');
        $chain->store_id = $store_id;
        $chain->adress = $request->input('adress');
        $chain->contact = $request->input('contact');
        $chain->chain_mobile = $request->input('chain_mobile');
        $chain->chain_telephone = $request->input('chain_telephone');
        $chain->chain_opening_hours = $request->input('chain_opening_hours');
        $chain->chain_close_hours = $request->input('chain_close_hours');
        $chain->chain_trafic_line = $request->input('chain_trafic_line'); 
        $chain->chain_lng = $request->input('chain_lng');
        $chain->chain_lat = $request->input('chain_lat');
        $chain->approved = $request->input('approved');
        $chain->chain_ip = $request->input('chain_ip');
        $chain->chain_district_id = $request->input('chain_district_id');
        $chain->chain_district_info = $request->input('chain_district_info');
        // if ($request->hasFile('store_logo')) {
            //     $path = $request->file('chain_img')->store('logos', 'public');
            //     $fileUrl = Storage::url($path);
            //     $product->chain_img = $fileUrl;
            // }
        $chain->shop_owner_id=$shop_owner->id;
        
        if( $chain->save())
        {
            return response()->json(['msg' => 'Chain has been added'], 200);
        }
        return response()->json(['msg' => 'Error while saving'], 500);
        
    }

     /* Assign shopManager to chain API
     - Parameters: 'token','manager_id','chain_id'
       Accessible for : ShopOwner
    */
    public function assignManagerToChain(Request $request)
    {
        $shop_owner = AuthController::me();
        $managerId = $request->input('manager_id');
        $chain_id= $request->input('chain_id');
        $manager = User::where('id', $managerId)->first();
        $chain=chain::find($chain_id);
        $chain->manager_id=$managerId;
        $chain->save();
        return  response()->json(["msg" => "Manager has been affected to chain"], 200);
    }

     /* Assign Cachier to chain API
     - Parameters: 'token','Cachier_id','chain_id'
       Accessible for : ShopOwner
    */
    public function assignCachierToChain(Request $request)
    {
        $shop_owner = AuthController::me();
        $cachier_id = $request->input('cachier_id');
        $chain_id= $request->input('chain_id');
        $cachier= User::where('id', $cachier_id)->first();
        $cachier->chain_id=$chain_id;
        $cachier->save();
        return  response()->json(["msg" => "cachier has been affected to chain"], 200);
    }

     /* get shop's chain list  API
     - Parameters: 'token','store_id'
       Accessible for : ShopOwner
    */
    public function getChainList(Request $request)
    {
        $shop_owner = AuthController::me();
       $store_id=$request->query('store_id');
     $store= Shop::find($store_id);
         $chainList = $store->chains()->get()
        //  ->map(function($query) { 
        //      echo $query;
        //      $query=$query->toArray();
        //      $query=array_map('strval', $query);
        //      return  $query; })
        ;
        // echo gettype($chainList);
       foreach($chainList as $key => $value) {
       
     
             if(isset($value['warehouse_id'])) {
               $warehouse= Warehouse::where('id',$value['warehouse_id'])->get();
              //echo $value['id'];
    //            //echo gettype($warehouse);
              if(!empty($warehouse)) {
               $value['warehouse']=$warehouse;
               //array_merge($value,array('warehouse'=>json_encode($warehouse)));
               
                }
               }
    //         }  
                
    //         }
        }


        $response = array();
        $response['msg']="";
        $response['code']=1;
        $response['data']=$chainList;
        return response()->json($response);
    }

      /* get chain  API
     - Parameters: 'token','store_id', 'chain_id'
       Accessible for : ShopOwner
    */
    public function getChain(Request $request)
    {
        $shop_owner = AuthController::me();
        $store_id=$request->query('store_id');
        $chain_id=$request->query('chain_id');
        //$store= shop::find($store_id);
        $chain =chain::where('id',$chain_id)->get()
        ->map(function($query) { 
            $query=$query->toArray();
            $query=array_map('strval', $query);
            return  $query; });

        $response = array();
        $response['msg']="";
        $response['code']=1;
        $response['data']=$chain;
        return response()->json($response);
    }


    public function chainsWithManager(Request $request)
    {
        $shop_owner = AuthController::me();
        $store_id=$request->input('store_id');
        $store= shop::find($store_id);
        $chainList = $store->chains()->with('Manager')->get();

        $response = array();
        $response['msg']="";
        $response['code']=1;
        $response['data']=$chainList;
        return response()->json($response);
    }

public function getChainById(Request $request,$id) {
        $chain = Chain::find($id);
        if(!$chain) {
            return response()->json(['code'=>0,'msg'=>'chain with these credentials not found']);
        }
        return response()->json(['code'=>1,'msg'=>'success','data'=>$chain]);
    }
    public function updateChain(Request $request,$id) {
        $chain = Chain::find($id);
        if(!$chain) {
            return response()->json(['code'=>0,'msg'=>'chain with these credentials not found']);
        }
        $shop_owner = AuthController::me();
        $store_id=$shop_owner->shop()->value('id');

        $chain->chain_name = $request->input('chain_name');
        //$chain->store_id = $store_id;
        $chain->adress = $request->input('adress');
        $chain->contact = $request->input('contact');
        $chain->chain_mobile = $request->input('chain_mobile');
        $chain->chain_telephone = $request->input('chain_telephone');
        $chain->chain_opening_hours = $request->input('chain_opening_hours');
        $chain->chain_close_hours = $request->input('chain_close_hours');
        $chain->chain_trafic_line = $request->input('chain_trafic_line'); 
        $chain->chain_lng = $request->input('chain_lng');
        $chain->chain_lat = $request->input('chain_lat');
        $chain->approved = $request->input('approved');
        $chain->chain_ip = $request->input('chain_ip');
        $chain->chain_district_id = $request->input('chain_district_id');
        $chain->chain_district_info = $request->input('chain_district_info');
        // if ($request->hasFile('store_logo')) {
            //     $path = $request->file('chain_img')->store('logos', 'public');
            //     $fileUrl = Storage::url($path);
            //     $product->chain_img = $fileUrl;
            // }
        //$chain->shop_owner_id=$shop_owner->id;
        
        if( $chain->save())
        {
            return response()->json(['msg' => 'Chain has been updated'], 200);
        }
        return response()->json(['msg' => 'Error while saving'], 500);
    }

}
