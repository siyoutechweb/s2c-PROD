<?php namespace App\Http\Controllers;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Supplier;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardsController extends Controller 
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function dashboard(Request $request)
    {
        $data = array();
        $shop_owner = AuthController::me();
        if ($request->has('chain_id')) {
            $chain= $request->input('chain_id');
            $data['today_amount'] = order::where('chain_id',$chain)
                                    ->whereDate('created_at', '=', date('Y-m-d'))
                                    ->sum('payment_amount');
            $data['month_amount'] = order::where('chain_id',$chain)
                                    ->whereMonth('created_at', '=', date('m'))
                                    ->sum('payment_amount');
            $data['year_amount']= order::where('chain_id',$chain)
                                ->whereYear('created_at', '=', date('Y'))
                                ->sum('payment_amount');
            $data['total_order']= order::where('chain_id',$chain)->whereMonth('created_at', '=', date('m'))->whereYear('created_at', '=', date('Y'))->count();
            
        
        }
        else {
            $data['today_amount'] =0;
            $data['month_amount'] = 0;
            $data['year_amount']=0;
            $data['total_order']= 0;

        }
        
        $response = array();
        $response['code']=1;
        $response['msg']='';
        $response['data']= $data;
        return response()->json($response);  
    }
    


    public function shopStatistic(Request $request)
    {
        $chain_id = $request->input('chain_id');
        $store_id = $request->input('store_id');
        $period_type = $request->input('period_type');
        $start_time = $request->input('start_time');
        $end_time = $request->input('end_time');
        if ($period_type == 'm' ) {
            for ($i=$start_time; $i <$start_time ; $i++) { 
                # code...
            }
        }


        $response = array();
        $response['code']=1;
        $response['msg']='';
        //$response['data']= $data;
        return response()->json($response);  
    }
     

}
