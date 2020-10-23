<?php


namespace App\Http\Controllers;

use App\Http\Controllers\AuthController;
use App\Logic\ShopOwnerStatisticLogic;
use App\Models\Shop;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{

    protected $user;

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->user = AuthController::me();

    }


    /**
     * 按照订单状态统计
     *
     * @param Request $request ["from"=>"","to"=>"","order_status"=>""]
     * @return \Illuminate\Http\JsonResponse
     */
    public function orders(Request $request)
    {

        $from = $request->input('from');
        $to = $request->input('to');
        $chain_id = $request->input('chain_id');
        $data = ShopOwnerStatisticLogic::statisticsByOrderState($chain_id,  $from, $to);

        return response()->json([
            'code' => 1, 'msg' => '', 'data' => $data
        ]);

    }


    /**
     * Get instant insight into the sum of each fund (card, check, cash)
     *
     *  support show by days ,month
     *
     * @param Request $request ["from"=>"","to"=>"","show_way"=>""],the argument show_way options [day,month]
     * @return \Illuminate\Http\JsonResponse
     */
    public function fund(Request $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');
        $chain_id = $request->input('chain_id');
        $way=$request->input('show_way','day');
        $data = ShopOwnerStatisticLogic::statisticsByPaymentMethodId($chain_id,$way,$from, $to);
        return response()->json([
            'code' => 1, 'msg' => '', 'data' => $data
        ]);

    }


    /**
     *
     * Get instant insight into the state of products by category
     *
     *
     * @param Request $request ["from"=>"","to"=>"","category_id"=>"" ]
     * @return \Illuminate\Http\JsonResponse
     */
    public function products(Request $request)
    {
        $from = $request->input('from');
        //echo $from.'productsrequest';
        $to = $request->input('to');
        //echo $to.'productsrequest';
        $chain_id = $request->input('chain_id');
        $cate_id=$request->input('category_id');
        $data=ShopOwnerStatisticLogic::statisticsProductsByCategory($chain_id,$cate_id,$from,$to);
        return response()->json([
            'code' => 1, 'msg' => '', 'data' => $data
        ]);
    }


    /**
     *
     * Get instant insight into the state of stock by chain
     *
     * @param Request $request  ["from"=>"","to"=>"","chain_id"=>"" ]
     * @return \Illuminate\Http\JsonResponse
     */
    public function stock(Request $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');
        $chain_id = $request->input('chain_id');
        //$chain_id=$request->post('chain_id',$this->user->chain_id);
        $data=ShopOwnerStatisticLogic::statisticsStockByChain($chain_id,$from,$to);

        return response()->json([
            'code' => 1, 'msg' => '', 'data' => $data
        ]);

    }


    /**
     * View KPIs about each of our chains
     *
     * @param Request $request ["from"=>"","to"=>"","chain_id"=>"" ]
     * @return \Illuminate\Http\JsonResponse
     */
    public function kpi(Request $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');
        $chain_id=$request->input('chain_id');
        $data=ShopOwnerStatisticLogic::statisticsKPI($chain_id,$from,$to);
        return response()->json([
            'code' => 1, 'msg' => '', 'data' => $data
        ]);
    }
//chineese team update
    /**
     *  ???? ???� ???
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function orderAmount(Request $request)
    {
        try{
            $start = $request->input('start');
	    //echo $start;
            $end = $request->input('end');
	    //echo $end;
            $chain_id=$request->input('chain_id');
            $store_id=$request->input('store_id');
            if (!isset($store_id)||!isset($chain_id)||!strtotime($start)||!strtotime($end)){
                throw new \Exception("arguments error!");
            }
            $data=ShopOwnerStatisticLogic::statisticsOrder($chain_id,$store_id,$start,$end);
            return response()->json([
                'code' => 1, 'msg' => '', 'data' => $data
            ]);
        }catch (\Exception $e){
            return response()->json([
                'code' => 0, 'msg' => $e->getMessage()
            ]);
        }
    }


    /**
     *  Order Quantity ??? ?web??????,??,??????:
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function orderQuantity(Request $request)
    {
        try{
            $start = $request->input('start');
            $end = $request->input('end');
            $chain_id=$request->input('chain_id');
            $store_id=$request->input('store_id');
            if (!isset($store_id)||!isset($chain_id)||!strtotime($start)||!strtotime($end)){
                throw new \Exception("arguments error!");
            }
            $data=ShopOwnerStatisticLogic::staticsOrderQuantity($chain_id,$store_id,$start,$end);
            return response()->json([
                'code' => 1, 'msg' => '', 'data' => $data
            ]);
        }catch (\Exception $e){
            return response()->json([
                'code' => 0, 'msg' => $e->getMessage()
            ]);
        }

    }

    public function productQuantity(Request $request)
    {
        try{
            $store_id=$request->input("store_id");
            $chain_id=$request->input("chain_id");
            $start=$request->input("start");
            $end=$request->input("end");
            if (!$store_id||!$chain_id||!strtotime($start)||!strtotime($end)){
                throw new \Exception("arguments error!");
            }

            $data=ShopOwnerStatisticLogic::staticsProductQuantity($store_id,$chain_id,$start,$end);
            return response()->json([
                'code' => 1, 'msg' => '', 'data' => $data
            ]);
        }catch (\Exception $e){
            return response()->json([
                'code' => 0, 'msg' => $e->getMessage()
            ]);
        }

    }

    public function saleQuantity(Request $request)
    {
        try{
            $store_id=$request->input("store_id");
            $chain_id=$request->input("chain_id");
            $start=$request->input("start");
            $end=$request->input("end");
            $bar_code=$request->input("bar_code");
            $rows=$request->input('rows',20);

            if (!$store_id||!$chain_id||!strtotime($start)||!strtotime($end)){
                throw new \Exception("arguments error!");
            }
            $product_id=0;
            if($bar_code){
                $prod= Product::where(['product_barcode'=>$bar_code])->first() ;

                if(!$prod){
                    throw new \Exception("this bar code not found");
                }
                $product_id=$prod->id;
             }

            $data=ShopOwnerStatisticLogic::staticsSaleQuantity($store_id,$chain_id,$start,$end,$rows,$product_id);
            return response()->json([
                'code' => 1, 'msg' => '', 'data' => $data
            ]);
        }catch (\Exception $e){
            return response()->json([
                'code' => 0, 'msg' => $e->getMessage()
            ]);
        }
    }


    /**
     * ???????? Orders by suppliers
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function orderSupplier(Request $request)
    {
        try{
            $start = $request->input('start');
            $end = $request->input('end');
            $store_id=$request->input("store_id");
            $chain_id=$request->input("chain_id");
            if (!isset($store_id)||!isset($chain_id)||!strtotime($start)||!strtotime($end)){
                throw new \Exception("arguments error!");
            }
            $data=ShopOwnerStatisticLogic::staticsOrderSupplier($store_id,$chain_id,$start,$end);
            return response()->json([
                'code' => 1, 'msg' => '', 'data' => $data
            ]);
        }catch (\Exception $e){
            return response()->json([
                'code' => 0, 'msg' => $e->getMessage()
            ]);
        }
    }

    function test()
    {
        $data = [];
        for ($i = 1; $i < 1000; $i++) {
            $data[] = [
                'days'=>Carbon::today()->subDays(mt_rand(100,365))->toDateString(),
                'store_id'=>mt_rand(1,5),
                'chain_id'=>mt_rand(1,5),
                'product_id'=>mt_rand(1,50),
                'price'=>mt_rand(100,400),
                'category_id'=>mt_rand(1,5),
                'sales'=>mt_rand(10,40),
            ];
        }
        StatisticProduct::insert($data);
    }
}