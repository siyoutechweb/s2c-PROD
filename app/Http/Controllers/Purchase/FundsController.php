<?php namespace App\Http\Controllers\Purchase;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\PurchaseProduct;
use App\Models\ShopFund;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use mysql_xdevapi\Exception;
class FundsController extends Controller {
    public function __construct()
    {
        $this->middleware('auth:api');
    }


    public function addPaymentMethod(Request $request)
    {
        $shop_owner= AuthController::me();
        //echo $shop_owner;
        if(!($shop_owner->role_id==1 || $shop_owner->role_id==4)) {
            return response()->json(['code'=>0,'msg'=>'unsufficient permissions']);
        }
        if($shop_owner->role_id==1) {
            $store_id = $shop_owner->shop()->value('id');
        }else {
            $store_id = null;
        }
        if(!$request->has('name')) {
            return response()->json(['code'=>0,'msg'=>'method name is required']);
        }
        $method_name=$request->input('name');

        $chain_id = $request->input('chain_id');
        $days =$request->has('days')? $request->input('days'):0;
        DB::table('shop_supplier_payment_methods')->insert(['store_id'=>$store_id,
        'chain_id'=>$chain_id,
        'name'=>$method_name,
        'days'=>$days,
        'created_at'=>Carbon::now(),
        'updated_at'=>Carbon::now()]);
  
        $response['code']=1;
        $response['msg']='payment method added successfully';
        
        return response()->json($response);
    }
    public function getPaymentMethods(Request $request)
    {
        $shop_owner= AuthController::me();
        //echo $shop_owner;
        if(!($shop_owner->role_id==1 || $shop_owner->role_id==4)) {
            return response()->json(['code'=>0,'msg'=>'unsufficient permissions']);
        }
        if($shop_owner->role_id==1 ) {
            $data = DB::table('shop_supplier_payment_methods')->where('store_id',null)->orWhere('store_id',$shop_owner->shop()->value('id'))->get(); 
        }
         if($shop_owner->role_id==4 ) {
            $data = DB::table('shop_supplier_payment_methods')->get(); 
        }

        $response['code']=1;
        $response['msg']='success';
        $response['data'] = $data;
        return response()->json($response);
    }
    public function editPaymentMethod(Request $request,$id)
    {
        $shop_owner= AuthController::me();
        //echo $shop_owner;
        if(!($shop_owner->role_id==1 || $shop_owner->role_id==4)) {
            return response()->json(['code'=>0,'msg'=>'unsufficient permissions']);
        }
        if($shop_owner->role_id==1) {
            $store_id = $shop_owner->shop()->value('id');
        }else {
            $store_id = null;
        }
        if(!$request->has('name')) {
            return response()->json(['code'=>0,'msg'=>'method name is required']);
        }
        $method_name=$request->input('name');

        $chain_id = $request->input('chain_id');
        $days =$request->has('days')? $request->input('days'):0;
	$payment_method = DB::table('shop_supplier_payment_methods')->where('id', $id)->first();
        if(!$payment_method) {$response['code']=0;
        $response['msg']='payment method not found';}
else {


   DB::table('shop_supplier_payment_methods')->where('id', $id)->update([
        'store_id'=>$store_id,
        'chain_id'=>$chain_id,
        'name'=>$method_name,
        'days'=>$days,
        'updated_at'=>Carbon::now()
    ]);}

        $response['code']=1;
        $response['msg']='payment method successfully updated';
        
        return response()->json($response);
    }
    public function deletePaymentMethod(Request $request,$id)
    {
        $shop_owner= AuthController::me();
        //echo $shop_owner;
        if(!($shop_owner->role_id==1 || $shop_owner->role_id==4)) {
            return response()->json(['code'=>0,'msg'=>'unsufficient permissions']);
        }
        DB::table('shop_supplier_payment_methods')->where('id', $id)->delete();

        $response['code']=1;
        $response['msg']='payment method successfully deleted';
        
        return response()->json($response);
    }
	 public function getPaymentMethodById(Request $request,$id)
    {
        $shop_owner= AuthController::me();
        //echo $shop_owner;
        if(!($shop_owner->role_id==1 || $shop_owner->role_id==4)) {
            return response()->json(['code'=>0,'msg'=>'unsufficient permissions']);
        }
        $data = DB::table('shop_supplier_payment_methods')->where('id', $id)->first();

        $response['code']=1;
        $response['data'] = $data;
        
        return response()->json($response);
    }
    public function addFund(Request $request)
    {
        $shop_owner= AuthController::me();
       // echo $shop_owner;
        if(!($shop_owner->role_id==1)) {
            return response()->json(['code'=>0,'msg'=>'unsufficient permissions']);
        }
        
        if(!$request->has('amount')) {
            return response()->json(['code'=>0,'msg'=>'payment amount is required']);
        }
        $payment_method_id = $request->input('payment_method_id');
        $days =DB::table('shop_supplier_payment_methods')->where('id',$payment_method_id)->get('days')->toArray();
        //echo $days[0]->days;
	  if(!$days) {
	return response()->json(["msg"=>"error"]);	   
        }

        if($days) {
		//var_dump($days);
            $tmp =  $days[0]->days;
	   
        }
        //echo gettype($days);
        $amount=$request->input('amount');

        $chain_id = $request->input('chain_id');
        $supplier_id = $request->input('supplier_id');
        $start_date = $request->input('start_date');
        if($tmp > 0) {
                    $finish_date = strtotime("+".$tmp." day", strtotime($start_date));
                    $finish_date = date("Y-m-d H:i:s",$finish_date);

        }else {
            $finish_date = $start_date;
        }
        //echo $finish_date;
        $status='not paid';
        if ($request->hasFile('fund_image')) {
            $path = $request->file('fund_image')->store('funds', 'google');
            $fileUrl = Storage::url($path);
            $img_url = $fileUrl;
            $img_name = basename($path);

        }else {
            $img_url =null;
            $img_name=null;
        }
        DB::table('funds')->insert(['amount'=>$amount,
        'chain_id'=>$chain_id,
        'supplier_id'=>$supplier_id,
        'start_date'=>$start_date,
        'finish_date'=>$finish_date,
        'payment_method_id'=>$payment_method_id,
        'status'=>$status,
        'img_url'=>$img_url,
        'created_at'=>Carbon::now(),
        'updated_at'=>Carbon::now(),
        'img_name'=>$img_name]);
  
        $response['code']=1;
        $response['msg']='fund added successfully';
        
        return response()->json($response);
    }
	  public function getFundById(Request $request,$id)
    {
        $shop_owner= AuthController::me();
        //echo $shop_owner;
        if(!($shop_owner->role_id==1 || $shop_owner->role_id==4)) {
            return response()->json(['code'=>0,'msg'=>'unsufficient permissions']);
        }
        $data = DB::table('funds')->where('id', $id)->first();

        $response['code']=1;
        
        $response['data'] = $data;
        
        return response()->json($response);
    }
    public function getFunds(Request $request) {
        $shop_owner= AuthController::me();
       // echo $shop_owner;
        if(!($shop_owner->role_id==1)) {
            return response()->json(['code'=>0,'msg'=>'unsufficient permissions']);
        }
        $store_id = $shop_owner->shop()->value('id');
        $chain_id = $request->input('chain_id');
        $supplier_id = $request->input('supplier_id');
        $created_at = $request->input('created_at');
        $finish_date = $request->input('finish_date');
        $status = $request->input('status');
        $payment_method = $request->input('payment_method');
        if(!$chain_id) {
            $chain_id = '';
        }
        if(!$supplier_id) {
            $supplier_id = '';
        }
        if(!$created_at) {
            $created_at = '';
        }
        if(!$status) {
            $status = '';
        }
      
        if(!$payment_method) {
            $payment_method = '';
        }
        if(!$finish_date) {
            $finish_date = '';
        }
        $chains_id=$shop_owner->shop->chains()->pluck('id');
        $response= ShopFund::whereIn('chain_id',$chains_id)
        ->when($chain_id != '', function ($query) use ($chain_id) {
            $query->where('chain_id',$chain_id);})
        ->when($payment_method != '', function ($query) use ($payment_method) {
            $query->where('payment_method_id',$payment_method);})
        ->when($supplier_id != '', function ($query) use ($supplier_id) {
            $query->where('supplier_id',$supplier_id);})
        ->when($status != '', function ($query) use ($status) {
            $query->where('status',$status);})
        ->when($created_at != '', function ($query) use ($created_at) {
            $query->whereDate('created_at','=',$created_at);})
        ->when($finish_date != '', function ($query) use ($finish_date) {
            $query->whereDate('finish_date','=',$finish_date);})
            
            ->orderBy('id','desc')->paginate(20);//->findOrFail();
            $response ->code = '1';
            $response ->msg = "success";
            return response()->json($response);
    }
    public function updateFund(Request $request,$id)
    {
        $shop_owner= AuthController::me();
       // echo $shop_owner;
        if(!($shop_owner->role_id==1)) {
            return response()->json(['code'=>0,'msg'=>'unsufficient permissions']);
        }
        
        if(!$request->has('amount')) {
            return response()->json(['code'=>0,'msg'=>'payment amount is required']);
        }
        $payment_method_id = $request->input('payment_method_id');
        $days =DB::table('shop_supplier_payment_methods')->where('id',$payment_method_id)->get('days')->toArray();
        //echo $days[0]->days;
        if($days) {
            $days =  $days[0]->days;
        }
        //echo gettype($days);
        $amount=$request->input('amount');

        $chain_id = $request->input('chain_id');
        $supplier_id = $request->input('supplier_id');
        $start_date = $request->input('start_date');
        if($days > 0) {
                    $finish_date = strtotime("+".$days." day", strtotime($start_date));
                    $finish_date = date("Y-m-d H:i:s",$finish_date);

        }else {
            $finish_date = $start_date;
        }
        //echo $finish_date;
        $status=$request->input('status');
        if ($request->hasFile('fund_image')) {
            $path = $request->file('fund_image')->store('funds', 'google');
            $fileUrl = Storage::url($path);
            $img_url = $fileUrl;
            $img_name = basename($path);

        }else {
           
        }
	$fund =  DB::table('funds')->where('id', $id)->first();
	if(!$fund) {
	$response['code']=0;
        $response['msg']='Fund not found';
	}else{
        DB::table('funds')
        ->where('id', $id)
        ->update([
            'amount'=>$amount,
        'chain_id'=>$chain_id,
        'supplier_id'=>$supplier_id,
        'start_date'=>$start_date,
        'finish_date'=>$finish_date,
        'payment_method_id'=>$payment_method_id,
        'status'=>$status,
        'img_url'=>$img_url,
        
        'updated_at'=>Carbon::now(),
        'img_name'=>$img_name]);
        
  
        $response['code']=1;
        $response['msg']='fund updated successfully';}
        
        return response()->json($response);
    }
  public function deleteFund(Request $request,$id)
    {
        $shop_owner= AuthController::me();
        //echo $shop_owner;
        if(!($shop_owner->role_id==1 || $shop_owner->role_id==4)) {
            return response()->json(['code'=>0,'msg'=>'unsufficient permissions']);
        }
        DB::table('funds')->where('id', $id)->delete();

        $response['code']=1;
        $response['msg']='fund successfully deleted';
        
        return response()->json($response);
    }
 public function getFunds1(Request $request) {
        $shop_owner= AuthController::me();
       // echo $shop_owner;
        if(!($shop_owner->role_id==1)) {
            return response()->json(['code'=>0,'msg'=>'unsufficient permissions']);
        }
        $store_id = $shop_owner->shop()->value('id');
        $chain_id = $request->input('chain_id');
        $supplier_id = $request->input('supplier_id');
        //$keyword = $request->input('supplier_id');
        $keyWord = $request->input('keyword');
        $created_at = $request->input('created_at');
        $payment_date = $request->input('payment_date');
        $status = $request->input('status');
        $payment_method = $request->input('payment_method');
        if(!$chain_id) {
            $chain_id = '';
        }
        if(!$supplier_id) {
            $supplier_id = '';
        }
        if(!$created_at) {
            $created_at = '';
        }
        if(!$status) {
            $status = '';
        }
      
        if(!$payment_method) {
            $payment_method = '';
        }
        if(!$payment_date) {
            $payment_date = '';
        }
        $chains_id=$shop_owner->shop->chains()->pluck('id');
        $response= ShopFund::with('supplier')->whereHas('supplier',function($q) use ($keyWord)
        {
            $q->when($keyWord != '', function ($q) use ($keyWord)
            { $q->where('suppliers.supplier_name', 'like', '%' . $keyWord . '%');});
        })->whereIn('chain_id',$chains_id)
        ->when($chain_id != '', function ($query) use ($chain_id) {
            $query->where('chain_id',$chain_id);})
        ->when($payment_method != '', function ($query) use ($payment_method) {
            $query->where('payment_method_id',$payment_method);})
        ->when($supplier_id != '', function ($query) use ($supplier_id) {
            $query->where('supplier_id',$supplier_id);})
        ->when($status != '', function ($query) use ($status) {
            $query->where('status',$status);})
        ->when($created_at != '', function ($query) use ($created_at) {
            $query->whereDate('created_at','=',$created_at);})
        ->when($payment_date != '', function ($query) use ($payment_date) {
            $query->whereDate('finish_date','<=',$payment_date);})
            
            ->orderBy('id','desc')->paginate(20);//->findOrFail();
            $response ->code = '1';
            $response ->msg = "success";
            return response()->json($response);
    }
   
}
