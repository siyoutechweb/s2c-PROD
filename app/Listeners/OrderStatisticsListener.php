<?php


namespace App\Listeners;


use App\Events\OrderStatisticsEvent;
use App\Models\StatisticOrder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use mysql_xdevapi\Exception;

class OrderStatisticsListener
{


    public function __construct()
    {

    }


    /**
     * Handle the event.
     *
     * @param  OrderStatisticsEvent  $order
     * @return void
     */
    public function handle(OrderStatisticsEvent $order)
    {
        $now=Carbon::now();
        $rs=StatisticOrder::where(['days'=>$now->toDateString(),'chain_id'=>$order->order->chain_id])->first();

        $amount=$order->order->payment_amount;
        //如果有支付状态。这里需要改动。。。
        $data=[
            'paid'=>DB::raw($rs?'paid+'.$amount:$amount),
            'confirmed'=>0,
            'pended'=>0,
        ];
        $data['income']=DB::raw($rs?'income+'.$amount:$amount);


      $data['total_order']=DB::raw($rs?'total_order+1':1);
	  if(isset($order->order->payment_method_id)){
            switch ($order->order->payment_method_id){
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
            ['days'=>$now->toDateString(),'chain_id'=>$order->order->chain_id,'store_id'=>$order->order->store_id],
            $data
        );
    }
}
