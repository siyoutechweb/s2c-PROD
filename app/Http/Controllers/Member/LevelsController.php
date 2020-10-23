<?php namespace App\Http\Controllers\Member;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Member_level;
use App\Models\User;
use Illuminate\Http\Request;

/*
SIYOU THECH Tunisia
Author: Habiba Boujmil
ERROR MSG
* 1：parameters missing, in data field indicate whuch parameter is missing
* 2：token expired or forced to logout, take to relogin
* 3：error while saving
* 4: error while deleting

*/

class LevelsController extends Controller {

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    
     /* Get shop's Member Level list  API
     - Necessary Parameters: 'token','store_id'
    - optional Parameters:
       Accessible for : ShopOwner 
    */
    public function getLevelsList(Request $request)
    {
        $shop_owner = AuthController::me();
        $store_id= $request->input('store_id');
        $levelList = member_level::where('store_id', $store_id)->get();
        $response = array();
        $response['code']=1;
        $response['msg']='';
        $response['data']=$levelList;
        return response()->json($response);

    }

     /* Get shop's Member Level API
     - Necessary Parameters: 'token','store_id','level_id'
    - optional Parameters:
       Accessible for : ShopOwner 
    */
    public function getLevel(Request $request, $level_id)
    {
        $shop_owner = AuthController::me();
        $store_id= $request->input('store_id');
        $levelList = member_level::where([['store_id', $store_id],['
        id', $level_id]])->get();
        $response = array();
        $response['code']=1;
        $response['msg']='';
        $response['data']=$levelList;
        return response()->json($response);

    }

    /* Add new Member Level API
     - Necessary Parameters: 'token','store_id','level_name','start_point','end_point'
    - optional Parameters: 'description','increment_point'
       Accessible for : ShopOwner 
    */
    public function addLevel(Request $request)
    {
        $shop_owner = AuthController::me();
        $store_id= $request->input('store_id');
        if ($request->filled('level_name','start_point','end_point')) 
        {
            $level= new member_level;
            $level->level = $request->input('level_name');
            $level->start_point = $request->input('start_point');
            $level->end_point = $request->input('end_point');
            $level->description = $request->input('description');
            $level->increment_point = $request->input('increment_point');
            $level->store_id = $store_id;
            
            if ($level->save()) {
                $response = array();
                $response['code']=1;
                $response['msg']='';
                $response['data']=$level;
                return response()->json($response);
            }
            $response = array();
            $response['code']=0;
            $response['msg']='3';
            $response['data']='error while saving';
            return response()->json($response);
        }
        $response = array();
        $response['code']=0;
        $response['msg']='1';
        $response['data']='parameters missing, in data field';
        return response()->json($response); 

    }

     /* Update Member Level API
     - Necessary Parameters: 'token','store_id','level_id','level_name','start_point','end_point'
    - optional Parameters: 'description','increment_point'
       Accessible for : ShopOwner 
    */
    public function updateLevel(Request $request, $level_id)
    {
        $shop_owner = AuthController::me();
        $store_id= $request->input('store_id');
        // $level_id = $request->input('level_id');
        $level = Member_level :: find($level_id);
        if ($request->filled('level_name','start_point','end_point')) 
        {
            $level->level = $request->input('level_name');
            $level->start_point = $request->input('start_point');
            $level->end_point = $request->input('end_point');
            $level->description = $request->input('description');
            $level->increment_point = $request->input('increment_point');
            $level->store_id = $store_id;
            
            if ($level->save()) {
                $response = array();
                $response['code']=1;
                $response['msg']='';
                $response['data']=$level;
                return response()->json($response);
            }
            $response = array();
            $response['code']=0;
            $response['msg']='3';
            $response['data']='error while saving';
            return response()->json($response);
        }
        $response = array();
        $response['code']=0;
        $response['msg']='1';
        $response['data']='parameters missing, in data field';
        return response()->json($response); 

    }

    
     /* Delete Member Level API
     - Necessary Parameters: 'token','store_id','level_id'
    - optional Parameters: 
       Accessible for : ShopOwner 
    */
    
    public function deleteLevel(Request $request, $level_id)
    {
       
        $store_id= $request->input('store_id');
        // $level_id= $request->input('level_id');
        $level = Member_level :: find($level_id);
        
        if($level->delete())
        {
            $response = array();
            $response['code']=1;
            $response['msg']="";
            $response['data']='level has been removed';
            return response()->json($response);
        }
        $response = array();
        $response['code']=1;
        $response['msg']="1";
        $response['data']='Error';
        return response()->json($response);
    }




}
