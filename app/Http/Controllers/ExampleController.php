<?php

namespace App\Http\Controllers;

use App\Events\ProductStatisticsEvent;
use App\Models\Order;
use App\Models\Product;
use App\Models\StatisticOrder;
use App\Models\StatisticProduct;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ExampleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function index()
    {
        $rs=Order::where(['store_id'=>24])->get();
        foreach ($rs as $v){
            $prod=json_decode($v['cart_data'],1);
 if(!$prod||!is_array($prod)){
                continue;
            }
            foreach ($prod as $product){
                if(!$product['ProductID']){
                    continue;
                }
                echo "开始统计".$product['ProductID'];
                $p=Product::find($product['ProductID']);
                $where=[
                    'store_id' => $v['store_id'],
                    'chain_id' => $v['chain_id'],
                    'product_id' => $product['ProductID'],
                    'days' => date("Y-m-d",strtotime($v['created_at'])),
                    'category_id' => $p['category_id'],
                ];
                $rs=StatisticProduct::where($where)->first();
if($product['Quantity']<0){
                    echo "-------------------------";
                    echo $v['id'];
                    echo "-------------------------";
                }        


        StatisticProduct::updateOrCreate($where,
                    [
                        'price'=>$product['Subtotal'],
                        'sales' => DB::raw($rs?'sales+' . $product['Quantity']: $product['Quantity'])
                    ]);
            }
        }
        echo "统计结束";
    }


    function order(){

        $rs=Order::where(['store_id'=>24])->get();
        foreach ($rs as $order){
            echo "开始修复订单".$order['id'];
            $days= date("Y-m-d",strtotime($order['created_at']));
            $rs=StatisticOrder::where(['days'=>$days,'chain_id'=>$order['chain_id']])->first();

            $amount=$order['payment_amount'];
            //如果有支付状态。这里需要改动。。。
            $data=[
                'paid'=>DB::raw($rs?'paid+'.$amount:$amount),
                'confirmed'=>0,
                'pended'=>0,
            ];
            $data['income']=DB::raw($rs?'income+'.$amount:$amount);
            if(isset($order['payment_method_id'])){
                switch ($order['payment_method_id']){
                    case  1:
                        $data['cash']=DB::raw($rs?'cash+'.$amount:$amount);
                        break;
                    case 2:
                        $data['check']=DB::raw($rs?'`check`+'.$amount:$amount);
                        break;
                    case 3:
                        $data['card']=DB::raw($rs?'card+'.$amount:$amount);
                        break;
                }
            }
            //通过订单产品获取店家 ID
            StatisticOrder::updateOrCreate(
                ['days'=>$days,'chain_id'=>$order['chain_id'],'store_id'=>$order['store_id']],
                $data
            );
        }
        echo "统计结束";

    }
    //
}
