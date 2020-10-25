<?php namespace App\Http\Controllers;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Models\Advertisement;
use App\Models\Chain;

class AdvertisementsController extends Controller {

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function newAdvertisement (Request $request)
    {
        $user= AuthController::me();
        $chain_id = $request->input('chain_id');
        if($user->role_id ==1) {

            if(!$chain_id) {
                return response()->json(['code'=>0,'msg'=>'chain is required']);
            }
        }
        $advertisement = new Advertisement();
        $advertisement->user_id= $user->id;
        $advertisement->chain_id= $chain_id;
        if ($request->hasFile('advertisement_image')) {
            $path = $request->file('advertisement_image')->store('advertisements', 'google');
            $img_url = Storage::url($path);
            $img_name = basename($path);
        }
        else {
            $img_url = null;
            $img_name = null;
        }
        $advertisement->img_url= $img_url;
        $advertisement->img_name= $img_name;
        
        if($advertisement->save()){
           
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

    public function getAdvertisementList(Request $request)
    {   
        $user= AuthController::me();
        $chain_id = $request->input('chain_id');
        if($user->role_id ==1) {
            $advertisementList= Advertisement::where('chain_id',$chain_id)->orWhereNull('chain_id')->with('chain')->get();
        }
        else {
            $advertisementList= Advertisement::all();
        }

        

        
        $response['code']=1;
        $response['msg']='';
        $response['data']=$advertisementList;
        return response()->json($response);

    }



    public function deleteAdvertisement (Request $request , $id)
    {
        $user = AuthController::me();
        if($user->role_id==2) {
            $advertisement =  Advertisement::where('id',$id)->where('user_id',$user->id)->first();
        }
        else {
            $advertisement =  Advertisement::find($id);
        }
        if($advertisement->delete()){
            $response['code']=1;
            $response['msg']='';
            $response['data']="Success";
            return response()->json($response);
        }
        $response['code']=0;
        $response['msg']='';
        $response['data']="Error while deleting";
        return response()->json($response);
    }
    public function getAdvertisementById(Request $request,$id) {
        $user = AuthController::me();
        if($user->role_id==2) {
            $advertisement =  Advertisement::where('id',$id)->where('user_id',$user->id)->first();
        }
        else {
            $advertisement =  Advertisement::find($id);
        }
        $response['code']=1;
        $response['msg']='';
        $response['data']=$advertisement;
        return response()->json($response);

    }

}
