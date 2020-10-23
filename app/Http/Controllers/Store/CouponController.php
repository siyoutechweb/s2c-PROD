<?php


namespace App\Http\Controllers\Store;


use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use mysql_xdevapi\Exception;

class CouponController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api',['except' => ['download']]);
    }
    public function download(Request $request){

        $chain_id=$request->get('chain_id');
        $store_id=$request->get('store_id');
        $rows=$request->get('rows',20);
        if(!$chain_id||!$store_id){
            return response()->json(['msg' =>'Missing parameter'], 500);
        }
        $from=$request->get('from');
        if($from&&!preg_match('/^\d+$/',$from)){
            return response()->json(['msg' =>'the key from must be int'], 500);
        }
        $rs=Coupon::where(['chain_id'=>$chain_id,'store_id'=>$store_id])->when($from,function ($query,$from){
            $query->where('updated_at','>=',date('Y-m-d H:i:s',$from));
        })->paginate($rows);
        return response()->json( collect($rs)->toArray() );
    }
    public function add(Request $request){

        $items=$request->post('data');


        try{
            $list=json_decode($items,1);
            if(json_last_error()){
                throw new Exception('invalid json');
            }
            foreach ($list as $data){
                if(!isset($data['barcode'],$data['start_date'],$data['expire_date'],$data['amount'])){
                    throw new Exception('缺少字段');
                }
                if(!preg_match('/^\d+$/',$data['start_date'])||!preg_match('/^\d+$/',$data['expire_date'])){
                    throw new \Exception('start_date or expire_date must be int');
                }
                Coupon::updateOrCreate(['chain_id'=>$data['chain_id'],'store_id'=>$data['store_id'],'barcode'=>$data['barcode']],[
                    'start_date'=>$data['start_date'] ,
                    'expire_date'=>$data['expire_date'] ,
                    'amount'=>$data['amount'] ,
                    'used'=>$data['used']
                ]);
            }

            return response()->json(['msg' => 'Coupon has been added'], 200);
        }catch (\Exception $e){
            return response()->json(['msg' =>$e->getMessage()], 500);
        }
    }
}