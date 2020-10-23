<?php namespace App\Http\Controllers\Admin;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Models\Discount;
use Illuminate\Http\Request;

class DiscountTypesController extends Controller {

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    // API which allows the admin to add a new discount type
    public function addType(Request $request) 
    {
        $newType = new Discount();
        if ($request->filled('discount'))
        {
            $newType->type = $request->input('discount');
            if ($newType->save()) {
                $response = array();
                $response['code']=1;
                $response['msg']='';
                $response['data']=array_map('strval',$newType->toArray());
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

    public function getType(Request $request,$type_id)
    {
        // $type_id= $request->input('discount_id');
        $type = Discount::find($type_id);
        $response = array();
        $response['code']=1;
        $response['msg']="";
        $response['data']= $type;
        return response()->json($response);

    } 
    
    // API which allows the admin to update an existing discount type
    public function updateType(Request $request,$type_id)
    {
        // $type_id= $request->input('discount_id');
        $discount= $request->input('type');
        $type = Discount::find($type_id);
        $type->type= $discount;
        if($type->save())
        {
            $response = array();
            $response['code']=1;
            $response['msg']="";
            $response['data']='Discount type has been updated';
            return response()->json($response);
        }
        $response = array();
        $response['code']=0;
        $response['msg']="1";
        $response['data']='Error';
        return response()->json($response);   
    }
    
    // API which allows the admin to delete a discount type from database
    public function deleteType(Request $request, $type_id)
    {
        // $type_id= $request->input('discount_id');
        $type = Discount::find($type_id);
        if($type->delete())
        {
            $response = array();
            $response['code']=1;
            $response['msg']="";
            $response['data']='Discount type has been removed';
            return response()->json($response);
        }
        $response = array();
        $response['code']=0;
        $response['msg']="1";
        $response['data']='Error';
        return response()->json($response);   
    }

}
