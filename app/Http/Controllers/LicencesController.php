<?php namespace App\Http\Controllers;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Models\License;
use Illuminate\Support\Collection;
use App\Models\user;
use Exception;

class LicencesController extends Controller {

    public function __construct()
    {
        $this->middleware('auth:api');
	$this->role_id = AuthController::me()->role_id;
    }

    public function newLicence (Request $request)
    {
        if($this->role_id !=4){
        return response()->json(['code'=>0,'msg'=>'method not allowed']);

        }
        $user= AuthController::me();
        try{
        $license = new License();
        $shop_owner_id=$request->input('shop_owner_id');
        $shop_owner = User::find($shop_owner_id);
        if($shop_owner->role_id!=1) {
            return response()->json(['code'=>0,'msg'=>'trying to assign licence to wrong users']);
        }
        $tmp = License::where('shop_owner_id',$shop_owner_id)->first();
        if(!$tmp) {
        $license->shop_owner_id= $request->input('shop_owner_id');
        $license->max_chains= $request->input('chains',5);
        $license->max_cachiers= $request->input('cashiers',5);
        $license->max_managers= $request->input('managers',5);
        $license->max_operators= $request->input('operators',5);
        $license->start_date= $request->input('start_date');
        $license->finish_date= $request->input('finish_date');
        
        
        if($license->save()){
           
            $response['code']=1;
            $response['msg']='';
            $response['data']="Success";
            return response()->json($response);
        }
        $response['code']=0;
        $response['msg']='';
        $response['data']="Error while saving";
        return response()->json($response);
        } else {
            return response()->json(['code'=>0,'msg'=>'this user already has a licence','data'=>'']);
        }

        }catch (Exception $e) {
            return response()->json([
                'code' => 0, 'msg' => $e->getMessage().$e->getLine().$e->getFile()
            ]);

        }

        
        
        

    }

    public function getLicencesList(Request $request)
    {   
if($this->role_id !=4){
	return response()->json(['code'=>0,'msg'=>'method not allowed']);

}

        $user= AuthController::me();
      
        $licenseList= License::with('shopOwner')->get();
        

        

        
        $response['code']=1;
        $response['msg']='';
        $response['data']=$licenseList;
        return response()->json($response);

    }
    public function getLicencesList1(Request $request)
    {   
if($this->role_id !=4){
	return response()->json(['code'=>0,'msg'=>'method not allowed']);

}

        $user= AuthController::me();
      
        $licenseList= License::with('shopOwner')->paginate(3)->toArray();
        

        

        
        $licenseList['code']=1;
        $licenseList['msg']='';
       // $response['data']=$licenseList;
        return response()->json($licenseList);

    }

    public function updateLicence (Request $request , $id)
    {   if($this->role_id !=4){
	return response()->json(['code'=>0,'msg'=>'method not allowed']);

}

try{
        $license =  License::find($id);
        
        $license->max_chains= $request->input('chains');
        $license->max_cachiers= $request->input('cashiers');
        $license->max_managers= $request->input('managers');
        $license->max_operators= $request->input('operators');

        $license->start_date= $request->input('start_date');
        $license->finish_date= $request->input('finish_date');
        
        
        if($license->save()){
           
            $response['code']=1;
            $response['msg']='';
            $response['data']="Success";
            return response()->json($response);
        }
        $response['code']=0;
        $response['msg']='';
        $response['data']="Error while saving";
    }catch (Exception $e) {
        return response()->json([
            'code' => 0, 'msg' => $e->getMessage().$e->getLine().$e->getFile()
        ]);

    }
        

    }

    public function deleteLicence (Request $request , $id)
    {   if($this->role_id !=4){
	return response()->json(['code'=>0,'msg'=>'method not allowed']);

    }


try{
        $user = AuthController::me();
       
      
        $license =  License::find($id);
        
        if($license->delete()){
            $response['code']=1;
            $response['msg']='';
            $response['data']="Success";
            return response()->json($response);
        }
        $response['code']=0;
        $response['msg']='';
        $response['data']="Error while deleting";
        return response()->json($response);
    }catch (Exception $e) {
            return response()->json([
                'code' => 0, 'msg' => $e->getMessage().$e->getLine().$e->getFile()
            ]);
    
        }
    }
    public function getLicenceById(Request $request,$id) {
        $user = AuthController::me();
       if($this->role_id !=4){
	return response()->json(['code'=>0,'msg'=>'method not allowed']);

}

        
        $license =  License::find($id);
        
        $response['code']=1;
        $response['msg']='';
        $response['data']=$license;
        return response()->json($response);

    }
    public function getMyLicense(Request $request) {
        $user = AuthController::me();
        $license =  License::where('shop_owner_id',$user->id)->first();
        if(!$license) {
            return response()->json(['code'=>0,'msg'=>'no licence found','data'=>null]);
        }
        $license->shop_owner=User::find($user->id);
        $response['code']=1;
        $response['msg']='';
        $response['data']=$license;
        return response()->json($license);
    }
    public function getAllShopOwners(Request $request) 
    {
        $user =AuthController::meme();
        if($user->role_id!=4) {
            return response()->json(['code'=>0,'msg'=>'method not alloowed']);
        }
        $shop_owners = User::where('role_id',1)->orderBy('id','desc')->get();
        $response['code']=1;
        $response['msg']='success';
        $response['data'] = $shop_owners;
        return response()->json($response);
    }

    public function activateManager(Request $request,$id) {
        $chain_id=$request->input('chain_id');
        
    }
}
