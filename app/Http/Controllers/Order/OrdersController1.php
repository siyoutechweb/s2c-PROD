<?php namespace App\Http\Controllers\Order;

use Event;
use App\Events\OrderStatisticsEvent;
use App\Events\ProductStatisticsEvent;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Models\ProductItemOrder;
use App\Models\User;
use Carbon\Carbon;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Models\Order;
use App\Models\Member;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use mysql_xdevapi\Exception;

use App\Models\PaymentMethod;
class OrdersController1 extends Controller {
	
    public function getOrders(Request $request)
    {
        
        $date=$request->input('order_time');
        if($request->filled('order_time'))
        {
            $response = order::withCount('products')->whereDate('date_time', '=',  $date)->paginate(20)->toArray();
            $response['code']=1;
            $response['msg']='';
        }
        else 
        {
            $response = order::withCount('products')->paginate(20)->toArray();
            $response['code']=1;
            $response['msg']='';
        }
       

        return $response;
       
    }
    // public function addOrder(Request $request) {
    //     $orders = $request->input('orders');
    //     $store_id = $request->input('store_id');

    // }
public function addOrders(Request $request)
    {

        $order = $request->post();
	//return $order;
        $store_id = $request->input('store_id');
	//echo $store_id;

        try {
	
            
                $new_order = new Order();
                // foreach ($orders as $order) {
                $mem_card = isset($order['card_number'])&&preg_match('/^\d+$/',$order['card_number'])?$order['card_number']:0;
                $mem_point = $order['vip_points'];
                $new_order->store_id = $order['store_id'];//Store_id
                $new_order->order_payment_amount = $order['order_payment_amount'];//order_payment_amount ?????
                $new_order->payment_amount = $order['amount'];//Amount????
		echo  $order['amount'];
                $new_order->discount_amount = $order['order_discount_amount'];
                $new_order->member_price = isset($order['member_price']) ? $order['member_price'] : 0;
                $new_order->chain_id = $order['chain_id'];//chain_id
                //  $new_order->cachier_id = $order['cachier_id'];
                $new_order->TVA = isset($order['taxe_tva']) ? $order['taxe_tva'] : 0;
                $new_order->order_number = $order['order_number'];//order_number
                $new_order->payment_method_id =$order['pay_type'];//pay_type
                $new_order->card_number = $mem_card;//Card_number
                $new_order->vip_point = $mem_point;//Vip_points
                //$new_order->created_at = $order['order_time'];//order_time
                $new_order->cart_data = $order['cart_data'];//cart_data
                $new_order->invoice = $order['invoice'];//invoice
                $new_order->product_quantity = $order['product_quantity'];//produt_quantity
                $new_order->operator = $order['operator'];
		
		if($new_order->save()) {
echo 'saved';} else{
echo 'unable to save';}
		//$new_order->save();
		echo "saved";
                $products = json_decode($order['order_items'], 1);
                foreach ($products as $prod) {
                    if ($prod['product_id']) {
                        $product = Product::find($prod['product_id']);
                        $product->product_quantity = $product->product_quantity - $prod['order_item_quantity'];
                        $product->save();
                        //Sync update products statistics
                       //var_dump($prod);
                        event(new ProductStatisticsEvent(
                            $product['id'],
                            $product['product_quantity'],
                            $order['store_id'],
                            $order['chain_id'],
                            $prod['order_item_amount'],
                            $prod['category_id']
                        ));
                    }

                    //insert into product_item_order
                    $object = [];
                    $object['chain_id'] = $order['chain_id'];
                    $object['store_id'] = $order['store_id'];
                    $object['order_id'] = $new_order->id;
                    $object['item_id'] = isset($prod['item_id']) ? $prod['item_id'] : 0;//Item_id
                    $object['product_id'] = $prod['product_id'];//
                    $object['item_unit_price'] = $prod['item_unit_price'];//Item_unit_price
                    $object['item_name'] = $prod['item_name'];//Item_name
                    $object['order_item_quantity'] = $prod['order_item_quantity'];//Order_item_quantity
                    $object['order_item_amount'] = $prod['order_item_amount'];//Order_item_amount
                    $object['order_item_payment_amount'] = $prod['order_item_payment_amount'];//Order_item_payment_amount
                    $object['order_item_return'] = $prod['order_item_return'];//Order_item_return
                    $object['item_rate'] = $prod['item_rate'];//Item_rate
                    $object['category_id'] = $prod['category_id'];//Category_id: INT
                    $object['created_at']=date('Y-m-d H:i:s',$order['order_time']);
                    $object['updated_at']=date('Y-m-d H:i:s',$order['order_time']);
                    Order::insertItemOrders($object);
                    event(new OrderStatisticsEvent($new_order));
                    if($mem_card){
                        Member::updateMemberPoints($mem_card, $mem_point, $store_id);
                    }

                    //insetion end
                }
                //   }
            

            $response['code'] = 1;
            $response['msg'] = '';
            $response['data'] = "order has been added !!";
        } catch (\Exception $e) {
            $response['code'] = 0;
            $response['msg'] = '';
            $response['data'] = $e->getMessage();
            Log::info(sprintf('file:%s,line:%s,msg:%s', $e->getFile(), $e->getLine(), $e->getMessage()));

        }
        return response()->json($response);

           }
    public function addOrders1(Request $request)
    {
        $orders = $request->input('orders');
        $store_id = $request->input('store_id');

       
        foreach ($orders as $order) {
            //$order=json_decode($order,1);
            /*$mem_card =$order['card_number'];
            $mem_point = $order['vip_point'];
            $new_order= new Order();
            $new_order->payment_amount=$order['payment_amount'];
            $new_order->discount_amount=$order['discount_amount'];
            $new_order->member_price=$order['member_price'];
            $new_order->chain_id=$order['chain_id'];
            $new_order->cachier_id=$order['cachier_id'];
            $new_order->TVA=$order['taxe_tva'];
            $new_order->order_number=$order['order_number'];
            $new_order->payment_method_id=$order['payment_method_id'];
            $new_order->card_number=$mem_card ;
            $new_order->vip_point=$mem_point;
            $new_order->cart_data=$order['cart_data'];
            $new_order->invoice=$order['invoice'];
*/
		   $mem_card = isset($order['card_number'])&&preg_match('/^\d+$/',$order['card_number'])?$order['card_number']:0;
                $mem_point = $order['vip_points'];
                $new_order->store_id = $order['store_id'];//Store_id
                $new_order->order_payment_amount = $order['order_payment_amount'];//order_payment_amount ?????
                $new_order->payment_amount = $order['amount'];//Amount????
                $new_order->discount_amount = $order['order_discount_amount'];
                $new_order->member_price = isset($order['member_price']) ? $order['member_price'] : 0;
                $new_order->chain_id = $order['chain_id'];//chain_id
                //  $new_order->cachier_id = $order['cachier_id'];
                $new_order->TVA = isset($order['taxe_tva']) ? $order['taxe_tva'] : 0;
                $new_order->order_number = $order['order_number'];//order_number
                $new_order->payment_method_id = $order['pay_type'];//pay_type
                $new_order->card_number = $mem_card;//Card_number
                $new_order->vip_point = $mem_point;//Vip_points
                $new_order->created_at = $order['order_time'];//order_time
                $new_order->cart_data = $order['cart_data'];//cart_data
                $new_order->invoice = $order['invoice'];//invoice
                $new_order->product_quantity = $order['product_quantity'];//produt_quantity
                $new_order->operator = $order['operator'];

            
            $new_order->save();
		echo "saved";
            $products =$order['products'];
		$products = json_decode($order['order_items'], 1);

            foreach ($products as $product) 
            {
                $prod=Product::find($product['product_id']);
                $category_id =$prod['category_id'];
                //echo $category_id;
               $new_order->Products()->attach($product['product_id'], ['order_item_quantity' => $product['order_item_quantity'],'order_item_amount'=>$product['order_item_amount'],'order_item_payment_amount'=>$product['order_item_payment_amount']]);   
                $prod->product_quantity = $prod->product_quantity-$product['order_item_quantity'];
                $prod->save();

                //insert into product_item_order
                $object = [];
                $object['order_id'] = $new_order->id;
                $object['product_id'] = $product['product_id'];
                $object['order_item_quantity'] = $product['order_item_quantity'];
                $object['order_item_amount'] = $product['order_item_amount'];
                $object['order_item_payment_amount'] = $product['order_item_payment_amount'];


                Order::insertItemOrders($object);

                //insetion end
               //echo $prod["category_id"] ;
                // event(new ProductStatisticsEvent(
                //     $product['product_id'],
                //     $product['order_item_quantity'],
                //     $order['chain_id'],
                //     $product['order_item_amount'],
                //     $prod['unit_price'],
                //     $prod["category_id"]           )
                // );
                event(new ProductStatisticsEvent(
                    $product['product_id'],
                    $product['order_item_quantity'],
                    $order['chain_id'],
                    $product['order_item_amount'],
                    // $prod['unit_price'],
                    // $prod["category_id"]
                    $prod           )
                );

            }
           // event(new OrderStatisticsEvent($new_order));

            Event::dispatch (new OrderStatisticsEvent($new_order));

                Member::updateMemberPoints($mem_card, $mem_point, $store_id);
      
        }

        
        $response['code']=1;
        $response['msg']='';
        $response['data']="order has been added !!";
        return response()->json($response);
    }

    public function getOrderList2(Request $request)
    {
        $shop_owner=AuthController::me();
       
        // echo $shop_owner.'this is a temporary variable from auth controller';
        $chains_id=$shop_owner->shop->chains()->pluck('id');
        //echo $chains_id."chainsid";
        $response=Order::with('Products','cachier:id,first_name,last_name,contact','payment_method')->whereIn('chain_id',$chains_id)
                    ->orderBy('id','desc')->paginate(20);//->findOrFail();
        $response->code=1;
        $response->msg='';
        return response()->json($response);
    }

    public function getOrderList1(Request $request)
    {
        $shop_owner=AuthController::me();
       
        $chain_id = $request->input('chain_id');
        $payment_method_id = $request->input('payment_method_id');
        //$category = $request->input('category_id');
        //$barcode = $request->input('barcode');
        $start_date = $request->input('start_date');
	 if(!$start_date) {
            $start_date = '';
        }
	
        $keyWord = $request->input('keyword');
        $end_date = $request->input('end_date');
	  if(!$end_date) {
            $end_date = '';
        }

        $keyWord = $request->input('keyword');
             $chains_id=$shop_owner->shop->chains()->pluck('id');
        $response=Order::with('Products','cachier:id,first_name,last_name,contact','payment_method')->whereIn('chain_id',$chains_id)
                    ->when($chain_id != '', function ($query) use ($chain_id) {
                    $query->where('chain_id',$chain_id);})
                    ->when($payment_method_id != '', function ($query) use ($payment_method_id) {
                    $query->where('payment_method_id',$payment_method_id);})
                    ->when($start_date != '', function ($query) use ($start_date) {
                    $query->whereDate('created_at','=',$start_date);})
                    ->when($end_date != '', function ($query) use ($start_date) {
                        $query->whereDate('created_at','<=',$end_date);})
                    ->when($keyWord != '', function ($q) use ($keyWord)
                     { $q->where('product_name', 'like', '%' . $keyWord . '%');})
                    ->orderBy('id','desc')->paginate(20);//->findOrFail();
        $response->code=1;
        $response->msg='';
        return response()->json($response);
    }
	public function getOrderList(Request $request)
    {
        $shop_owner = AuthController::me();

        $start_time = $request->get('start_time', 0);
        if ($start_time) {
            $start_time = date('Y-m-d', $start_time);
        }
        $store_id = $request->get('store_id');
        $chain_id = $request->get('chain_id', $shop_owner->shop->chains()->pluck('id'));
        $rows = $request->get('rows', 20);
        $rs = Order::select([
            'store_id',
            'chain_id',
            DB::raw('id as order_id'),
            'order_number',
            'order_payment_amount',
            DB::raw('discount_amount as order_discount_amount'),
            DB::raw('created_at as order_time'),
            'operator',
            'invoice',
            DB::raw('payment_method_id as pay_type'),
            DB::raw('payment_amount as amount'),
            'card_number',
            'vip_point', 'cart_data', 'product_quantity'
        ])->where(['store_id' => $store_id, 'chain_id' => $chain_id])
            ->when($start_time, function ($query) use ($start_time) {
                $query->where('updated_at', '>=', $start_time);
            })->paginate($rows);
        return response()->json($rs);
    }
     public function getPaymentMethods()
    {
        $paymentMethods = PaymentMethod::all();
        return response()->json($paymentMethods);
    }

}
