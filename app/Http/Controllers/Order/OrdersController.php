<?php namespace App\Http\Controllers\Order;

use App\Models\PaymentMethod;
use App\Models\ProductItemOrder;
use Carbon\Carbon;
use Event;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Events\ProductStatisticsEvent;
use App\Events\OrderStatisticsEvent;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Member;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrdersController extends Controller
{

    public function getOrders(Request $request)
    {

        $date = $request->input('order_time');
        if ($request->filled('order_time')) {
            $response = order::withCount('products')->whereDate('date_time', '=', $date)->paginate(20)->toArray();
            $response['code'] = 1;
            $response['msg'] = '';
        } else {
            $response = order::withCount('products')->paginate(20)->toArray();
            $response['code'] = 1;
            $response['msg'] = '';
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
        $store_id = $request->input('store_id');
        if(!$store_id){
            $shop_owner = AuthController::me();
            $store_id =$shop_owner->shop()->value('id');
        }

	try {
            DB::transaction(function () use ($order, $store_id) {
                $new_order = new Order();
                // foreach ($orders as $order) {
                $mem_card = isset($order['card_number']) && preg_match('/^\d+$/', $order['card_number']) ? $order['card_number'] : 0;
                $mem_point = $order['vip_points'];
                $new_order->store_id = $order['store_id'];//Store_id
                $new_order->order_payment_amount = $order['order_payment_amount'];//order_payment_amount ?????
                $new_order->payment_amount = $order['amount'];//Amount????
                $new_order->discount_amount = $order['order_discount_amount'];
                $new_order->member_price = isset($order['member_price']) ? $order['member_price'] : 0;
                $new_order->chain_id = $order['chain_id'];//chain_id
                $new_order->cachier_id = $order['cashier_id'];//cachier_id cashier_id
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
                $products = json_decode($order['order_items'], 1);
//                while (gettype($products) == 'string') {
//                    $products = json_decode($products, true);
//                }

                foreach ($products as $prod) {
                    //echo $prod['product_id'];
                    if ($prod['product_id']) {
                        $product = Product::find($prod['product_id']);
                        $product->product_quantity = $product->product_quantity - $prod['order_item_quantity'];
                        $product->save();
                        //Sync update products statistics
                        //$product_id, $quantity,$store_id, $chain_id, $price, $category_id
                        event(new ProductStatisticsEvent(
                            $product['id'],
                            $product['order_item_quantity'],
                            $order['store_id'],
                            $order['chain_id'],
                            $prod['order_item_amount'],
                            $product['category_id']
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
                    $object['created_at'] = date('Y-m-d H:i:s', $order['order_time']);
                    $object['updated_at'] = date('Y-m-d H:i:s', $order['order_time']);
                    Order::insertItemOrders($object);
                }
                event(new OrderStatisticsEvent($new_order));
                if ($mem_card) {
                    Member::updateMemberPoints($mem_card, $mem_point, $store_id);
                }
                //   }
            });

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
            $mem_card = $order['card_number'];
            $mem_point = $order['vip_points'];
            $new_order = new Order();
            $new_order->payment_amount = $order['payment_amount'];
            $new_order->discount_amount = $order['discount_amount'];
            $new_order->member_price = $order['member_price'];
            $new_order->chain_id = $order['chain_id'];
            $new_order->cachier_id = $order['cachier_id'];
            $new_order->TVA = $order['taxe_tva'];
            $new_order->order_number = $order['order_number'];
            $new_order->payment_method_id = $order['pay_type'];
            $new_order->card_number = $mem_card;
            $new_order->vip_point = $mem_point;
            $new_order->cart_data = $order['cart_data'];
            $new_order->invoice = $order['invoice'];
            $new_order->store_id = $store_id;
            $new_order->operator = $order['operator'];

            $new_order->save();
            //echo "saved";
            //$products =$order['products'];
            $products = json_decode($order['order_items'], 1);

            foreach ($products as $product) {
                $prod = Product::find($product['product_id']);
                $category_id = $prod['category_id'];
                // echo $category_id;
                $new_order->Products()->attach($product['product_id'], ['order_item_quantity' => $product['order_item_quantity'], 'order_item_amount' => $product['order_item_amount'], 'order_item_payment_amount' => $product['order_item_payment_amount']]);
                $prod->product_quantity = $prod->product_quantity - $product['order_item_quantity'];
                $prod->save();
                //echo "product saved";
                //insert into product_item_order
                $object = [];
                $object['order_id'] = $new_order->id;
                $object['product_id'] = $product['product_id'];
                $object['order_item_quantity'] = $product['order_item_quantity'];
                $object['order_item_amount'] = $product['order_item_amount'];
                $object['order_item_payment_amount'] = $product['order_item_payment_amount'];


                Order::insertItemOrders($object);
                //echo 'order inserted';

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
                        //$prod["category_id"]
                        $prod)
                );
                //echo 'event product sent';
            }
            event(new OrderStatisticsEvent($new_order));
            //echo 'event order sent';
            //Event::dispatch (new OrderStatisticsEvent($new_order));

            Member::updateMemberPoints($mem_card, $mem_point, $store_id);

        }


        $response['code'] = 1;
        $response['msg'] = '';
        $response['data'] = "order has been added !!";
        return response()->json($response);
    }

    public function getOrderList2(Request $request)
    {
        $shop_owner = AuthController::meme();
        // echo $shop_owner;
        $chain_id = $request->input('chain_id');
        $payment_method_id = $request->input('payment_method_id');
        //$category = $request->input('category_id');
        //$barcode = $request->input('barcode');
        $start_date = $request->input('start_date');
        if (!$start_date) {
            $start_date = '';
        }

        $keyWord = $request->input('keyword');
        $end_date = $request->input('end_date');
        if (!$end_date) {
            $end_date = '';
        }

        $keyWord = $request->input('keyword');
        //$chains_id=$shop_owner->shop->chains()->pluck('id');
        $store_id = $shop_owner->store_id;
        //echo $store_id;
        $userChain = $shop_owner->chain_id;
        //echo $userChain;
        $response = Order::with('Products', 'cachier:id,first_name,last_name,contact', 'payment_method')->where('store_id', $shop_owner->store_id)
            //->whereIn('chain_id',$chains_id)
            ->when(isset($userChain), function ($query) use ($userChain) {
                $query->where('chain_id', $userChain);
            })
            ->when($chain_id != '', function ($query) use ($chain_id) {
                $query->where('chain_id', $chain_id);
            })
            ->when($payment_method_id != '', function ($query) use ($payment_method_id) {
                $query->where('payment_method_id', $payment_method_id);
            })
            ->when($start_date != '', function ($query) use ($start_date) {
                $query->whereDate('created_at', '=', $start_date);
            })
            ->when($end_date != '', function ($query) use ($start_date) {
                $query->whereDate('created_at', '<=', $end_date);
            })
            ->when($keyWord != '', function ($q) use ($keyWord) {
                $q->where('product_name', 'like', '%' . $keyWord . '%');
            })
            ->orderBy('id', 'desc')
            ->paginate(20);//->findOrFail();
        $response->code = 1;
        $response->msg = '';
        return response()->json($response);
    }

    public function getOrderList1(Request $request)
    {
        $shop_owner = AuthController::me();

        $chain_id = $request->input('chain_id');
        $keyWord = $request->input('keyword');

        $payment_method_id = $request->input('payment_method_id');
        $start_date = $request->input('start_date');
        if (!$start_date) {
            $start_date = '';
        }
        if (!$chain_id) {
            $chain_id = '';
        }


        $end_date = $request->input('end_date');
        if (!$end_date) {
            $end_date = '';
        }

        if (!$keyWord) {
            $keyWord = '';
        }


        if (!$payment_method_id) {
            $payment_method_id = '';
        }

        $chains_id = $shop_owner->shop->chains()->pluck('id');
        $response = Order::with('Products', 'cachier:id,first_name,last_name,contact', 'payment_method')->whereIn('chain_id', $chains_id)
            ->when($chain_id != '', function ($query) use ($chain_id) {
                $query->where('chain_id', $chain_id);
            })
            ->when($payment_method_id != '', function ($query) use ($payment_method_id) {
                $query->where('payment_method_id', $payment_method_id);
            })
            ->when($start_date != '', function ($query) use ($start_date) {
                $query->whereDate('created_at', '=', $start_date);
            })
            ->when($end_date != '', function ($query) use ($start_date) {
                $query->whereDate('created_at', '<=', $end_date);
            })
            ->when($keyWord != '', function ($q) use ($keyWord) {
                $q->where('product_name', 'like', '%' . $keyWord . '%');
            })
            ->orderBy('id', 'desc')->paginate(20);//->findOrFail();
        $response->code = 1;
        $response->msg = '';
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

    public function addOrders7(Request $request)
    {
        //$orders = $request->input('orders');
        $order = $request->post();
        $store_id = $request->input('store_id');
        try {
            DB::transaction(function () use ($order, $store_id) {
                $new_order = new Order();
                // foreach ($orders as $order) {
                $mem_card = isset($order['card_number']) && preg_match('/^\d+$/', $order['card_number']) ? $order['card_number'] : 0;
                echo $mem_card;
                $mem_point = $order['vip_points'];
                $new_order->store_id = $order['store_id'];//Store_id
                echo($new_order->store_id);
                $new_order->order_payment_amount = $order['order_payment_amount'];//order_payment_amount ?????
                echo($new_order->order_payment_amount);

                $new_order->payment_amount = $order['amount'];//Amount????
                echo($new_order->payment_amount);

                $new_order->discount_amount = $order['order_discount_amount'];
                echo($new_order->discount_amount);
                $new_order->member_price = isset($order['member_price']) ? $order['member_price'] : 1;
                echo($new_order->member_price);

                $new_order->chain_id = $order['chain_id'];//chain_id
                echo($new_order->chain_id);
                //  $new_order->cachier_id = $order['cachier_id'];
                $new_order->TVA = isset($order['taxe_tva']) ? $order['taxe_tva'] : 0;
                echo($new_order->TVA);
                $new_order->order_number = $order['order_number'];//order_number
                echo($new_order->order_number);
                $new_order->payment_method_id = $order['pay_type'];//pay_type
                echo($new_order->payment_method_id);
                $new_order->card_number = $mem_card;//Card_number
                echo($new_order->card_number);
                $new_order->vip_point = $mem_point;//Vip_points
                //$new_order->created_at = $order['order_time'];//order_time
                $new_order->cart_data = $order['cart_data'];//cart_data
                $new_order->invoice = $order['invoice'];//invoice
                $new_order->product_quantity = $order['product_quantity'];//produt_quantity
                $new_order->operator = $order['operator'];
                $new_order->save();
                echo "hi";
                //return $new_order;
                $tmp = $order['order_items'];
                //$products = json_decode($order['order_items'],1);
                while (gettype($tmp) == 'string') {
                    $tmp = json_decode($tmp, true);
                }
                $products = $tmp;
                echo "decoded";
                var_dump($products);
                foreach ($products as $prod) {
                    if ($prod['product_id']) {
                        $product = Product::find($prod['product_id']);
                        $product->product_quantity = $product->product_quantity - $prod['order_item_quantity'];
                        $product->save();
                        //Sync update products statistics
                        event(new ProductStatisticsEvent(
                            $product['id'],
                            $product['order_item_quantity'],
                            //$//order['store_id'],
                            $order['chain_id'],
                            $prod['order_item_amount'],
                            $product['category_id']
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
                    $object['created_at'] = date('Y-m-d H:i:s', $order['order_time']);
                    $object['updated_at'] = date('Y-m-d H:i:s', $order['order_time']);
                    Order::insertItemOrders($object);
                    event(new OrderStatisticsEvent($new_order));
                    if ($mem_card) {
                        Member::updateMemberPoints($mem_card, $mem_point, $store_id);
                    }

                    //insetion end
                }
                //   }
            });

            $response['code'] = 1;
            $response['msg'] = '';
            $response['data'] = "order has been added !!";
        } catch (\Exception $e) {
            $response['code'] = 0;
            $response['msg'] = '';
            $response['data'] = $e->getMessage();
            $response['data1'] = $e->getLine();
            Log::info(sprintf('file:%s,line:%s,msg:%s', $e->getFile(), $e->getLine(), $e->getMessage()));

        }
        return response()->json($response);
    }

    public function addOrdersch(Request $request)
    {
        $orders = $request->input('orders');
        $store_id = $request->input('store_id');


        foreach ($orders as $order) {
            //$order=json_decode($order,1);
            $mem_card = $order['card_number'];
            $mem_point = $order['vip_points'];
            $new_order = new Order();
            $new_order->payment_amount = $order['payment_amount'];
            $new_order->order_payment_amount = $order['order_payment_amount'];
            $new_order->discount_amount = $order['discount_amount'];
            $new_order->member_price = $order['member_price'];
            $new_order->chain_id = $order['chain_id'];
            $new_order->cachier_id = $order['cachier_id'];
            $new_order->TVA = $order['taxe_tva'];
            $new_order->order_number = $order['order_number'];
            //$new_order->payment_method_id=$order['payment_method_id'];
            $new_order->payment_method_id = $order['pay_type'];//pay_type
            $new_order->card_number = $mem_card;
            $new_order->vip_point = $mem_point;
            $new_order->cart_data = $order['cart_data'];
            $new_order->invoice = $order['invoice'];
            $new_order->store_id = $store_id;
            $new_order->created_at = date('Y-m-d H:i:s', $order['order_time']);
            $new_order->product_quantity = $order['product_quantity'];//produt_quantity
            $new_order->operator = $order['operator'];
            //    $mem_card = isset($order['card_number'])&&preg_match('/^\d+$/',$order['card_number'])?$order['card_number']:0;
            //         $mem_point = $order['vip_points'];
            //         $new_order->store_id = $order['store_id'];//Store_id
            //         $new_order->order_payment_amount = $order['order_payment_amount'];//order_payment_amount ?????
            //         $new_order->payment_amount = $order['amount'];//Amount????
            //         $new_order->discount_amount = $order['order_discount_amount'];
            //         $new_order->member_price = isset($order['member_price']) ? $order['member_price'] : 0;
            //         $new_order->chain_id = $order['chain_id'];//chain_id
            //         //  $new_order->cachier_id = $order['cachier_id'];
            //         $new_order->TVA = isset($order['taxe_tva']) ? $order['taxe_tva'] : 0;
            //         $new_order->order_number = $order['order_number'];//order_number
            //         $new_order->payment_method_id = $order['pay_type'];//pay_type
            //         $new_order->card_number = $mem_card;//Card_number
            //         $new_order->vip_point = $mem_point;//Vip_points
            //         $new_order->created_at = $order['order_time'];//order_time
            //         $new_order->cart_data = $order['cart_data'];//cart_data
            //         $new_order->invoice = $order['invoice'];//invoice
            //         $new_order->product_quantity = $order['product_quantity'];//produt_quantity
            //         $new_order->operator = $order['operator'];


            $new_order->save();
            //echo "saved";
            //$products =$order['products'];
            $products = json_decode($order['order_items'], 1);

            foreach ($products as $prod) {
                if ($prod['product_id']) {
                    $product = Product::find($prod['product_id']);
                    $product->product_quantity = $product->product_quantity - $prod['order_item_quantity'];
                    $product->save();
                    //Sync update products statistics
                    event(new ProductStatisticsEvent(
                        $product['id'],
                        $product['order_item_quantity'],
                        $store_id,
                        $order['chain_id'],
                        $prod['order_item_amount'],
                        $product['category_id']
                    ));
                }

                //insert into product_item_order
                $object = [];
                $object['chain_id'] = $order['chain_id'];
                $object['store_id'] = $store_id;
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
                $object['created_at'] = date('Y-m-d H:i:s', $order['order_time']);
                $object['updated_at'] = date('Y-m-d H:i:s', $order['order_time']);


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
                // event(new ProductStatisticsEvent(
                //     $product['product_id'],
                //     $product['order_item_quantity'],
                //     $order['chain_id'],
                //     $product['order_item_amount'],
                //     // $prod['unit_price'],
                //     // $prod["category_id"]
                //    // $prod           )
                // );

            }
            event(new OrderStatisticsEvent($new_order));
            // echo "order event";
            // Event::dispatch (new OrderStatisticsEvent($new_order));

            Member::updateMemberPoints($mem_card, $mem_point, $store_id);

        }


        $response['code'] = 1;
        $response['msg'] = '';
        $response['data'] = "order has been added !!";
        return response()->json($response);
    }

    public function getOrderList78(Request $request)
    {
        $shop_owner = AuthController::me();

        // echo $shop_owner.'this is a temporary variable from auth controller';
        $chains_id = $shop_owner->shop->chains()->pluck('id');
        //echo $chains_id."chainsid";
        $response = Order::with('Products', 'cachier:id,first_name,last_name,contact', 'payment_method')->whereIn('chain_id', $chains_id)
            ->orderBy('id', 'desc')->paginate(20);//->findOrFail();
        $response->code = 1;
        $response->msg = '';
        return response()->json($response);
    }

    public function addOrdersTesttt(Request $request)
    {
        $tmp = $this->convertData($request);
        $orders1 = json_decode($tmp);

        //var_dump($orders1);
        $orders = $orders1;
        //$request->input('orders');
        $store_id = $request->input('store_id');
        $chain_id = $request->input('chain_id');


        //$order=json_decode($order,1);
        //echo gettype($order);
        //echo $order."order";
        //$order = json_decode($order);

        $mem_card = (int)$orders->card_number;
        $mem_price = $request->input('member_price');
        //echo $order["card_number"];
        //echo $mem_card;
        $mem_point = $orders->vip_points;
        $new_order = new Order();
        $new_order->payment_amount = $request->input('order_payment_amount');
        $new_order->discount_amount = $request->input('order_discount_amount');
        $new_order->member_price = isset($mem_price) ? $request->input('member_price') : 0;
        $new_order->chain_id = $request->input('chain_id');
        $new_order->cachier_id = $request->input('cachier_id');
        $tva = $request->input('taxe_tva');
        $new_order->TVA = isset($tva) ? $request->input('taxe_tva') : 0;
        $new_order->order_number = $request->input('order_number');
        $new_order->payment_method_id = $request->input('pay_type');
        $new_order->card_number = $request->input('vip_points');
        $new_order->vip_point = $mem_point;
        $new_order->cart_data = $request->input('cart_data');
        $new_order->invoice = $request->input('invoice');
        $new_order->store_id = $store_id;


        $new_order->save();
        //echo "saved";
        //$products =$order['products'];
        $order_items = $request->input('order_items');
        //echo $order_items;
        $products = json_decode($order_items, true);
        //echo "decoded";
        //echo $products;
        //echo gettype($products);
        while (gettype($products) == 'string') {
            $products = json_decode($products, true);
        }
        foreach ($products as $product) {
            $prod = Product::find($product['product_id']);
            $category_id = $prod['category_id'];
            // echo $category_id;
            $new_order->Products()->attach($product['product_id'], ['order_item_quantity' => $product['order_item_quantity'], 'order_item_amount' => $product['order_item_amount'], 'order_item_payment_amount' => $product['order_item_payment_amount']]);
            $prod->product_quantity = $prod->product_quantity - $product['order_item_quantity'];
            $prod->save();
            //echo "product saved";
            //insert into product_item_order
            $object = [];
            $object['order_id'] = $new_order->id;
            $object['product_id'] = $product['product_id'];
            $object['order_item_quantity'] = $product['order_item_quantity'];
            $object['order_item_amount'] = $product['order_item_amount'];
            $object['order_item_payment_amount'] = $product['order_item_payment_amount'];


            Order::insertItemOrders($object);
            //echo 'order inserted';

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
                    $chain_id,
                    $product['order_item_amount'],
                    // $prod['unit_price'],
                    //$prod["category_id"]
                    $prod)
            );
            //echo 'event product sent';
        }
        event(new OrderStatisticsEvent($new_order));
        //echo 'event order sent';
        //Event::dispatch (new OrderStatisticsEvent($new_order));

        Member::updateMemberPoints($mem_card, $mem_point, $store_id);


        $response['code'] = 1;
        $response['msg'] = '';
        $response['data'] = "order has been added !!";
        return response()->json($response);
    }

    public function convertData(Request $request)
    {
        $order = $request->post();
        $Val = new \stdClass();
        $Val->store_id = $order['store_id'];
        $data = [];
        $Val->order_payment_amount = $order['order_payment_amount'];
        $Val->order_discount_amount = $order['order_discount_amount'];
        $Val->order_number = $order['order_number'];
        $Val->order_time = $order['order_time'];
        $Val->operator = $order['operator'];
        $Val->invoice = $order['invoice'];
        $Val->pay_type = $order['pay_type'];
        $Val->total_price = $order['total_price'];
        $Val->vip_points = $order['vip_points'];
        $Val->cart_data = $order['cart_data'];
        $Val->chain_id = $order['chain_id'];
        $Val->card_number = $order['card_number'];
        $Val->order_items = $order['order_items'];
        $Val->amount = $order['amount'];


        return json_encode($Val);
    }

    public function addOrderszh(Request $request)
    {
        //$orders = $request->input('orders');
        $order = $request->post();
        $store_id = $request->input('store_id');

        try {
            DB::transaction(function () use ($order, $store_id) {
                $new_order = new Order();
                // foreach ($orders as $order) {
                $mem_card = isset($order['card_number']) && preg_match('/^\d+$/', $order['card_number']) ? $order['card_number'] : 0;
                $mem_point = $order['vip_points'];
                $new_order->store_id = $order['store_id'];//Store_id
                $new_order->order_payment_amount = $order['order_payment_amount'];//order_payment_amount ?????
                $new_order->payment_amount = $order['amount'];//Amount????
                $new_order->discount_amount = $order['order_discount_amount'];
                $new_order->member_price = isset($order['member_price']) ? $order['member_price'] : 0;
                $new_order->chain_id = $order['chain_id'];//chain_id
                $new_order->cachier_id = $order['cachier_id'];
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
                $products = json_decode($order['order_items'], 1);
                while (gettype($products) == 'string') {
                    $products = json_decode($products, true);
                }
                foreach ($products as $prod) {
                    //echo $prod['product_id'];
                    if ($prod['product_id']) {
                        $product = Product::find($prod['product_id']);
                        $product->product_quantity = $product->product_quantity - $prod['order_item_quantity'];
                        $product->save();
                        //Sync update products statistics
                        event(new ProductStatisticsEvent(
                            $product['id'],
                            $prod['order_item_quantity'],
                            $order['store_id'],
                            $order['chain_id'],
                            $prod['order_item_amount'],
                            $product['category_id']
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
                    $object['created_at'] = date('Y-m-d H:i:s', $order['order_time']);
                    $object['updated_at'] = date('Y-m-d H:i:s', $order['order_time']);
                    Order::insertItemOrders($object);
                    event(new OrderStatisticsEvent($new_order));
                    //if($mem_card){
                    //    Member::updateMemberPoints($mem_card, $mem_point, $store_id);
                    // }

                    //insetion end
                }
                if ($mem_card) {
                    Member::updateMemberPoints($mem_card, $mem_point, $store_id);
                }
                //   }
            });

            $response['code'] = 1;
            $response['msg'] = '';
            $response['data'] = "order has been added !!";
        } catch (\Exception $e) {
            $response['code'] = 0;
            $response['msg'] = '';
            $response['data'] = $e->getMessage() . 'line' . $e->getLine() . $e->getFile();
            Log::info(sprintf('file:%s,line:%s,msg:%s', $e->getFile(), $e->getLine(), $e->getMessage()));

        }
        return response()->json($response);
    }

    public function getOrderItemsDetail(Request $request)
    {
        $post = $request->only(['chain_id', 'store_id', 'start_time', 'end_time', 'rows', 'page']);

        try {
            if (!isset($post['chain_id'], $post['store_id'], $post['start_time'], $post['end_time'])) {
                throw new \Exception('some arguments has been lost ...');
            }
            if (!$post['start_time']) {
                $post['start_time'] = Carbon::tomorrow()->subDays(15)->toDateTimeString();
            } else {
                $post['start_time'] = Carbon::parse($post['start_time'])->toDateTimeString();
            }
            if (!$post['end_time']) {
                $post['end_time'] = Carbon::tomorrow()->toDateTimeString();
            } else {
                $post['end_time'] = Carbon::parse($post['end_time'])->toDateTimeString();
            }
            //DB::connection()->enableQueryLog();
            $rs = ProductItemOrder::where(['chain_id' => $post['chain_id'], 'store_id' => $post['store_id']])
                ->whereBetween('created_at', [$post['start_time'], $post['end_time']])
                ->paginate($post['rows'] ? (int)$post['rows'] : 20);
            return response()->json(['code' => 1, 'msg' => 'ok', 'data' => $rs]);
        } catch (\Exception $e) {
            return response()->json(['code' => 0, 'msg' => $e->getMessage(), 'data' => []]);
        }
    }

}
