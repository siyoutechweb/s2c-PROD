<?php namespace App\Http\Controllers\Funds;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use App\Models\Fund;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class FundsController extends Controller {

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function cashPayment(Request $request){
        $shop_owner=AuthController::me();
        $date = $request->input('date');
        $chain_id= $request->input('chain_id');
        $chains = $shop_owner->shop->chains->pluck('id');
        $response = order::with('payment_method','cachier:id,full_name')
                        ->where('payment_method_id',1)
                        ->when($date != '',function ($q) use($date) 
                        {$q->whereDate('created_at',$date);})
                        ->when($chain_id != '',function ($q) use($chain_id) 
                        {$q->where('chain_id',$chain_id);})
                        ->when($chain_id == '',function ($q) use($chains) 
                        {$q->whereIn('chain_id',$chains);})
                        ->paginate(20);
        
                                $response['code']=1;
        $response['msg']="";
        // $response['code'] = 1;
        // $response['msg'] = "";
        // $response= $funds;
        return response()->json($response);
        }

    public function checkPayment(Request $request){
        $shop_owner=AuthController::me();
        $date = $request->input('date');
        $chain_id= $request->input('chain_id');
        $chains = $shop_owner->shop->chains->pluck('id');
        $response = order::with('payment_method','cachier:id,full_name')
                        ->where('payment_method_id',2)
                        ->when($date != '',function ($q) use($date) 
                        {$q->whereDate('created_at',$date);})
                        ->when($chain_id != '',function ($q) use($chain_id) 
                        {$q->where('chain_id',$chain_id);})
                        ->when($chain_id == '',function ($q) use($chains) 
                        {$q->whereIn('chain_id',$chains);})
                        ->paginate(20);
        
        // $response['code'] = 1;
        // $response['msg'] = "";
        // $response['data']= $funds;
        return response()->json($response);
        }

    public function cardPayment(Request $request){
        $shop_owner=AuthController::me();
        $date = $request->input('date');
        $chain_id= $request->input('chain_id');
        $chains = $shop_owner->shop->chains->pluck('id');
        $response = order::with('payment_method','cachier:id,full_name')
                        ->where('payment_method_id',3)
                        ->when($date != '',function ($q) use($date) 
                        {$q->whereDate('created_at',$date);})
                        ->when($chain_id != '',function ($q) use($chain_id) 
                        {$q->where('chain_id',$chain_id);})
                        ->when($chain_id == '',function ($q) use($chains) 
                        {$q->whereIn('chain_id',$chains);})
                        ->paginate(20);
        
        // $response['code'] = 1;
        // $response['msg'] = "";
        // $response['data']= $funds;
        return response()->json($response);
        }

    public function cashFund(Request $request){
        $shop_owner=AuthController::me();
        $chains = $shop_owner->shop->chains->pluck('id');
        $date = $request->input('date');
        $chain_id= $request->input('chain_id');

        $funds = order::where('payment_method_id',1)
        ->when($date != '',function ($q) use($date) {$q->whereDate('created_at',$date);})
        ->when($chain_id != '',function ($q) use($chain_id){$q->where('chain_id',$chain_id);})
        ->when($chain_id == '',function ($q) use($chains) {$q->whereIn('chain_id',$chains);});
        
        $fund_received = $funds->count();
        $fund_amount = $funds->sum('price');
        $max_fund = $funds->max('price');
        $min_fund = $funds->min('price');

        $response['code'] = 1;
        $response['msg'] = "";
        $response['fund_received']= $fund_received;
        $response['fund_amount']= $fund_amount;
        $response['max_fund']= $max_fund;
        $response['min_fund']= $min_fund;

        return response()->json($response);
    }

    public function cardFund(Request $request){
        $shop_owner=AuthController::me();
        $chains = $shop_owner->shop->chains->pluck('id');
        $date = $request->input('date');
        $chain_id= $request->input('chain_id');

        $funds = order::where('payment_method_id',3)
        ->when($date != '',function ($q) use($date) {$q->whereDate('created_at',$date);})
        ->when($chain_id != '',function ($q) use($chain_id){$q->where('chain_id',$chain_id);})
        ->when($chain_id == '',function ($q) use($chains) {$q->whereIn('chain_id',$chains);});
        
        $fund_received = $funds->count();
        $fund_amount = $funds->sum('price');
        $max_fund = $funds->max('price');
        $min_fund = $funds->min('price');

        $response['code'] = 1;
        $response['msg'] = "";
        $response['fund_received']= $fund_received;
        $response['fund_amount']= $fund_amount;
        $response['max_fund']= $max_fund;
        $response['min_fund']= $min_fund;

        return response()->json($response);
    }

    public function checkFund(Request $request){
        $shop_owner=AuthController::me();
        $chains = $shop_owner->shop->chains->pluck('id');
        $date = $request->input('date');
        $chain_id= $request->input('chain_id');

        $funds = order::where('payment_method_id',2)
        ->when($date != '',function ($q) use($date) {$q->whereDate('created_at',$date);})
        ->when($chain_id != '',function ($q) use($chain_id){$q->where('chain_id',$chain_id);})
        ->when($chain_id == '',function ($q) use($chains) {$q->whereIn('chain_id',$chains);});
        
        $fund_received = $funds->count();
        $fund_amount = $funds->sum('price');
        $max_fund = $funds->max('price');
        $min_fund = $funds->min('price');

        $response['code'] = 1;
        $response['msg'] = "";
        $response['fund_received']= $fund_received;
        $response['fund_amount']= $fund_amount;
        $response['max_fund']= $max_fund;
        $response['min_fund']= $min_fund;

        return response()->json($response);
    }


}
