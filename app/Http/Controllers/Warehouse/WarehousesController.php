<?php namespace App\Http\Controllers\Warehouse;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use App\Models\Chain;

class WarehousesController extends Controller {

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function newWarehouse (Request $request)
    {
        $shop_owner= AuthController::me();
        $warehouse = new Warehouse();
        $warehouse->name= $request->input('name');
        $warehouse->description= $request->input('description');
        $warehouse->first_responsible= $request->input('first_responsible');
        $warehouse->second_responsible= $request->input('second_responsible');
        $warehouse->latitude= $request->input('latitude');
        $warehouse->longitude= $request->input('longitude');
        $warehouse->chain_id= $request->input('chain_id');
        $warehouse->shop_owner_id= $shop_owner->id;
        if($warehouse->save()){
            $chain=Chain::where('id',$request->input('chain_id'))->first();
            if(!empty($chain)) {
                $chain->warehouse_id = $warehouse->id;
                $chain->save(); 
            }
            $response['code']=1;
            $response['msg']='';
            $response['data']="Success";
            return response()->json($response);
        }
        $response['code']=0;
        $response['msg']='';
        $response['data']="Error while saving";
        return response()->json($response);

    }

    public function getWarehouseList(Request $request,$chain_id)
    {
        // $chain_id = $request->input('chain_id');
        $warehouseList= warehouse::where('chain_id',$chain_id)->with('chain')->get();
        $response['code']=1;
        $response['msg']='';
        $response['data']=$warehouseList;
        return response()->json($response);

    }

    public function updateWarehouse (Request $request , $id)
    {
        $warehouse =  Warehouse:: find($id);
        $warehouse->name= $request->input('name');
        $warehouse->description= $request->input('description');
        $warehouse->first_responsible= $request->input('first_responsible');
        $warehouse->second_responsible= $request->input('second_responsible');
        $warehouse->latitude= $request->input('latitude');
        $warehouse->longitude= $request->input('longitude');
        $warehouse->chain_id= $request->input('chain_id');
        if($warehouse->save()){
            $response['code']=1;
            $response['msg']='';
            $response['data']="Success";
            return response()->json($response);
        }
        $response['code']=0;
        $response['msg']='';
        $response['data']="Error while saving";
        return response()->json($response);

    }

    public function deleteWarehouse (Request $request , $id)
    {
        $warehouse =  Warehouse:: find($id);
        if($warehouse->delete()){
            $response['code']=1;
            $response['msg']='';
            $response['data']="Success";
            return response()->json($response);
        }
        $response['code']=0;
        $response['msg']='';
        $response['data']="Error while saving";
        return response()->json($response);
    }
     public function getWarehouseById(Request $request,$id) {
        $user = AuthController::me();
        $warehouse =  Warehouse::where('id',$id)->where('shop_owner_id',$user->id)->first();
        $response['code']=1;
        $response['msg']='';
        $response['data']=$warehouse;
        return response()->json($response);

    }

}
