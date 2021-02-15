<?php


namespace App\Http\Controllers;

use App\Http\Controllers\AuthController;
use App\Logic\ShopOwnerStatisticLogic;
use App\Models\Shop;
use App\Models\Product;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{

    protected $user;

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->user = AuthController::me();

    }


    /**
     * ????????
     *
     * @param Request $request ["from"=>"","to"=>"","order_status"=>""]
     * @return JsonResponse
     */
    public function orders(Request $request)
    {

        $from = $request->input('from');
        $to = $request->input('to');
        $chain_id = $request->input('chain_id');
        $store_id = $request->input('store_id');
        $data = ShopOwnerStatisticLogic::statisticsByOrderState($store_id,$chain_id, $from, $to);

        return response()->json([
            'code' => 1, 'msg' => '', 'data' => $data
        ]);

    }


    /**
     * Get instant insight into the sum of each fund (card, check, cash)
     *
     *  support show by days ,month
     *
     * @param Request $request ["from"=>"","to"=>"","show_way"=>"","chain_id"=>'',"store_id"=>""],the argument show_way options [day,month]
     * @return JsonResponse
     */
    public function fund(Request $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');
        $chain_id = $request->input('chain_id');
        $store_id = $request->input('store_id');
        $way = $request->input('show_way', 'day');
        $data = ShopOwnerStatisticLogic::statisticsByPaymentMethodId($store_id,$chain_id, $way, $from, $to);
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
     * @return JsonResponse
     */
    public function products(Request $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');
        $chain_id = $request->input('chain_id');
        $cate_id = $request->input('category_id');
        $data = ShopOwnerStatisticLogic::statisticsProductsByCategory($chain_id, $cate_id, $from, $to);
        return response()->json([
            'code' => 1, 'msg' => '', 'data' => $data
        ]);
    }


    /**
     *
     * Get instant insight into the state of stock by chain
     * @deprecated ....
     * @param Request $request ["from"=>"","to"=>"","chain_id"=>"" ]
     * @return JsonResponse
     */
    public function stock(Request $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');
        $chain_id = $request->input('chain_id');
        //$chain_id=$request->post('chain_id',$this->user->chain_id);
        $data = ShopOwnerStatisticLogic::statisticsStockByChain($chain_id, $from, $to);

        return response()->json([
            'code' => 1, 'msg' => '', 'data' => $data
        ]);

    }


    /**
     * View KPIs about each of our chains
     *
     * @param Request $request ["from"=>"","to"=>"","chain_id"=>"" ]
     * @return JsonResponse
     */
    public function kpi(Request $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');
        $chain_id = $request->input('chain_id');
        $store_id=$request->input('store_id');
        $data = ShopOwnerStatisticLogic::statisticsKPI($store_id,$chain_id, $from, $to);
        return response()->json([
            'code' => 1, 'msg' => '', 'data' => $data
        ]);
    }

    /**
     *  ???? ???– ???
     * @param Request $request
     * @return JsonResponse
     */
    public function orderAmount(Request $request)
    {
        try {
            $start = $request->input('start');
            $end = $request->input('end');
            $chain_id = $request->input('chain_id');
            $store_id = $request->input('store_id');
            if (!isset($store_id) || !isset($chain_id) || !strtotime($start) || !strtotime($end)) {
                throw new Exception("arguments error!");
            }
            $data = ShopOwnerStatisticLogic::statisticsOrder($chain_id, $store_id, $start, $end);
            return response()->json([
                'code' => 1, 'msg' => '', 'data' => $data
            ]);
        } catch (Exception $e) {
            return response()->json([
                'code' => 0, 'msg' => $e->getMessage()
            ]);
        }
    }


    /**
     *  Order Quantity ??? ?web??????,??,??????:
     * @param Request $request
     * @return JsonResponse
     */
    public function orderQuantity(Request $request)
    {
        try {
            $start = $request->input('start');
            $end = $request->input('end');
            $chain_id = $request->input('chain_id');
            $store_id = $request->input('store_id');
            if (!isset($store_id) || !isset($chain_id) || !strtotime($start) || !strtotime($end)) {
                throw new Exception("arguments error!");
            }
            $data = ShopOwnerStatisticLogic::staticsOrderQuantity($chain_id, $store_id, $start, $end);
            return response()->json([
                'code' => 1, 'msg' => '', 'data' => $data
            ]);
        } catch (Exception $e) {
            return response()->json([
                'code' => 0, 'msg' => $e->getMessage()
            ]);
        }

    }

    /**
     *  product quantity
     * @author null<blackbulls@qq.com>
     * @param Request $request
     * @return JsonResponse
     */
    public function productQuantity(Request $request)
    {
        try {
            $store_id = $request->input("store_id");
            $chain_id = $request->input("chain_id");
            $start = $request->input("start");
            $end = $request->input("end");
            if (!$store_id || !$chain_id || !strtotime($start) || !strtotime($end)) {
                throw new Exception("arguments error!");
            }

            $data = ShopOwnerStatisticLogic::staticsProductQuantity($store_id, $chain_id, $start, $end);
            return response()->json([
                'code' => 1, 'msg' => '', 'data' => $data
            ]);
        } catch (Exception $e) {
            return response()->json([
                'code' => 0, 'msg' => $e->getMessage()
            ]);
        }

    }

    /**
     *youxianyen
     * @param Request $request
     * @return JsonResponse
     */
    public function saleQuantity(Request $request)
    {
        try {
            $store_id = $request->input("store_id");
            $chain_id = $request->input("chain_id");
            $start = $request->input("start");
            $end = $request->input("end");
            $bar_code = $request->input("bar_code");
            $rows = $request->input('rows', 3);

            if (!isset($store_id) || !isset($chain_id) || !strtotime($start)) {
                throw new Exception("arguments error!");
            }
            $product_id = 0;
            if ($bar_code) {
                $prod = Product::where(['product_barcode' => $bar_code])->first();

                if (!$prod) {
                    throw new Exception("This barcode cannot be found");
                }
                $product_id = $prod->id;
            }

            $data = ShopOwnerStatisticLogic::staticsSaleQuantity($store_id, $chain_id, $start, $end, $rows, $product_id);
            return response()->json([
                'code' => 1, 'msg' => '', 'data' => $data
            ]);
        } catch (Exception $e) {
            return response()->json([
                'code' => 0, 'msg' => $e->getMessage()
            ]);
        }
    }


    /**
     * ???????? Orders by suppliers
     * youxianyen
     * @param Request $request
     * @return JsonResponse
     */
    public function orderSupplier(Request $request)
    {
        try {
            $start = $request->input('start');
            $end = $request->input('end');
            $store_id = $request->input("store_id");
            $chain_id = $request->input("chain_id");
            $supplier_id = $request->input("supplier_id");
            if (!isset($store_id) || !isset($chain_id) || !isset($supplier_id) || !strtotime($start) || !strtotime($end)) {
                throw new Exception("arguments error!");
            }
            $data = ShopOwnerStatisticLogic::staticsOrderSupplier($store_id, $chain_id, $supplier_id, $start, $end);
            return response()->json([
                'code' => 1, 'msg' => '', 'data' => $data
            ]);
        } catch (Exception $e) {
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
                'days' => Carbon::today()->subDays(mt_rand(100, 365))->toDateString(),
                'store_id' => mt_rand(1, 5),
                'chain_id' => mt_rand(1, 5),
                'product_id' => mt_rand(1, 50),
                'price' => mt_rand(100, 400),
                'category_id' => mt_rand(1, 5),
                'sales' => mt_rand(10, 40),
            ];
        }
        StatisticProduct::insert($data);
    }

    /**
     *  taskID=40
     *  ????  memberAmount
     * @author youxianyen
     * @param Request $request
     * @return JsonResponse
     */
    public function memberAmount(Request $request)
    {
        try {
            $start = $request->input('start');
            $end = $request->input('end');
            $chain_id = $request->input('chain_id');
            $store_id = $request->input('store_id');
            if (!isset($store_id) || !isset($chain_id) || !strtotime($start)) {
                throw new Exception("arguments error!");
            }
            $data = ShopOwnerStatisticLogic::statisticsMemberAmount($chain_id, $store_id, $start, $end);
            return response()->json([
                'code' => 1, 'msg' => '', 'data' => $data
            ]);
        } catch (Exception $e) {
            return response()->json([
                'code' => 0, 'msg' => $e->getMessage()
            ]);
        }
    }

    /**
     *  taskID=54
     *  ????  memberQuantity
     * @author youxianyen
     * @param Request $request
     * @return JsonResponse
     */
    public function memberQuantity(Request $request)
    {
        try {
            $start = $request->input('start');
            $end = $request->input('end');
            $store_id = $request->input('store_id');
            if (!isset($store_id) || !strtotime($start)) {
                throw new Exception("arguments error!");
            }
            $data = ShopOwnerStatisticLogic::statisticsMemberQuantity($store_id, $start, $end);
            return response()->json([
                'code' => 1, 'msg' => '', 'data' => $data
            ]);
        } catch (Exception $e) {
            return response()->json([
                'code' => 0, 'msg' => $e->getMessage()
            ]);
        }
    }

    /**
     *  taskID=54
     *  ????  storeQuantity
     * @author youxianyen
     * @param Request $request
     * @return JsonResponse
     */
    public function storeQuantity(Request $request)
    {
        try {
            $store_id = $request->input('store_id');
            if (!isset($store_id)) {
                throw new Exception("arguments error!");
            }
            $data = ShopOwnerStatisticLogic::statisticsStoreQuantity($store_id);
            return response()->json([
                'code' => 1, 'msg' => '', 'data' => $data
            ]);
        } catch (Exception $e) {
            return response()->json([
                'code' => 0, 'msg' => $e->getMessage()
            ]);
        }
    }

    /**
     *  taskID=53
     *  ????  storeManagerQuantity
     * @author youxianyen
     * @param Request $request
     * @return JsonResponse
     */
    public function storeManagerQuantity(Request $request)
    {
        try {
            $store_id = $request->input('store_id');
            if (!isset($store_id)) {
                throw new Exception("arguments error!");
            }
            $data = ShopOwnerStatisticLogic::storeManagerQuantity($store_id);
            return response()->json([
                'code' => 1, 'msg' => '', 'data' => $data
            ]);
        } catch (Exception $e) {
            return response()->json([
                'code' => 0, 'msg' => $e->getMessage()
            ]);
        }
    }

    /**
     * taskID=42,discountAmount
     * @author youxianyen
     * @param Request $request
     * @return JsonResponse
     */
    public function discountAmount(Request $request)
    {
        try {
            $start = $request->input('start');
            $end = $request->input('end');
            $chain_id = $request->input('chain_id');
            $store_id = $request->input('store_id');
            if (!strtotime($start) || !isset($chain_id)) {
                throw new Exception("arguments error!");
            }
            $start = Carbon::parse($start)->toDateTimeString();
            if (empty($end)) {
                $end = Date('Y-m-d H:i:s', time());
            }
            $end = Carbon::parse($end)->toDateTimeString();
            $data = ShopOwnerStatisticLogic::statisticsDiscountAmount($start, $end, $chain_id, $store_id);
            return response()->json([
                'code' => 1, 'msg' => '', 'data' => $data
            ]);
        } catch (Exception $e) {
            return response()->json([
                'code' => 0, 'msg' => $e->getMessage()
            ]);

        }
    }

    /**
     * taskID=36,Top products
     * @author youxianyen
     * @param Request $request
     * @return JsonResponse
     */
    public function topProducts(Request $request)
    {
        try {
            $start = $request->input('start');
            $end = $request->input('end');
            $chain_id = $request->input('chain_id');
            $store_id = $request->input('store_id');
            $top = $request->input('top', 100);
            if (!strtotime($start) || !isset($chain_id) || !isset($store_id)||!strtotime($start)||!strtotime($end)) {
                throw new Exception("arguments error!");
            }
            $end = Carbon::parse($end)->toDateTimeString();
            $data = ShopOwnerStatisticLogic::statisticssaletopProducts($start, $end, $chain_id, $store_id, $top);
            return response()->json([
                'code' => 1, 'msg' => '', 'data' => $data
            ]);
        } catch (Exception $e) {
            return response()->json([
                'code' => 0, 'msg' => $e->getMessage()
            ]);

        }
    }

    /**
     * Hot selling products, taskID=35
     * @author youxianyen
     * @param Request $request
     * @return JsonResponse
     */
    public function hotCategoryProducts(Request $request)
    {
        try {
            $store_id = $request->input("store_id");
            $chain_id = $request->input("chain_id");
            $start = $request->input("start");
            $end = $request->input("end");
            $top = $request->input('top', 100);
            if (!isset($store_id) || !isset($chain_id) || !strtotime($start) || !strtotime($end)) {
                throw new Exception("arguments error!");
            }

            $data = ShopOwnerStatisticLogic::hotCategoryProducts($start, $end, $chain_id, $store_id, $top);
            return response()->json([
                'code' => 1, 'msg' => '', 'data' => $data
            ]);
        } catch (Exception $e) {
            return response()->json([
                'code' => 0, 'msg' => $e->getMessage()
            ]);
        }

    }


    /**
     *  taskID=34
     *  Top products in supplier
     * @author youxianyen
     * @param Request $request
     * @return  jsonResponse
     *
     */
    public function topProductsSuppliers(Request $request)
    {
        try {
            $start = $request->input('start');
            $end = $request->input('end');
            $chain_id = $request->input('chain_id');
            $store_id = $request->input('store_id');
            $top = $request->input('top', 100);
            $start = Carbon::parse($start)->toDateTimeString();
            if (!strtotime($start)||!strtotime($end) || !isset($chain_id) || !isset($store_id)) {
                throw new Exception("arguments error!");
            }

            $data = ShopOwnerStatisticLogic::statisticsTopSuppliers($start, $end, (int)$chain_id, (int)$store_id, (int)$top);
            return response()->json([
                'code' => 1, 'msg' => '', 'data' => $data
            ]);
        } catch (Exception $e) {
            return response()->json([
                'code' => 0, 'msg' => $e->getMessage()
            ]);

        }
    }

    /**
     * promotional quantity
     * @author youxianyen
     */
    public function promotionalQuantity(Request $request)
    {
        try {
            $start = $request->input('start');
            $end = $request->input('end');
            $store_id = $request->input("store_id");
            $chain_id = $request->input("chain_id");
            if (!isset($store_id) || !isset($chain_id) || !strtotime($start) || !strtotime($end)) {
                throw new Exception("arguments error!");
            }
            $data = ShopOwnerStatisticLogic::promotionalQuantity($store_id, $chain_id, $start, $end);
            return response()->json([
                'code' => 1, 'msg' => '', 'data' => $data
            ]);
        } catch (Exception $e) {
            return response()->json([
                'code' => 0, 'msg' => $e->getMessage()
            ]);
        }
    }

    /**
     * promotional quantity
     * @author youxianyen
     */
    public function promotionalAmount(Request $request)
    {
        try {
            $start = $request->input('start');
            $end = $request->input('end');
            $store_id = $request->input("store_id");
            $chain_id = $request->input("chain_id");
            if (!isset($store_id) || !isset($chain_id) || !strtotime($start) || !strtotime($end)) {
                throw new Exception("arguments error!");
            }
            $data = ShopOwnerStatisticLogic::promotionalAmount($store_id, $chain_id, $start, $end);
            return response()->json([
                'code' => 1, 'msg' => '', 'data' => $data
            ]);
        } catch (Exception $e) {
            return response()->json([
                'code' => 0, 'msg' => $e->getMessage()
            ]);
        }
    }

    /**
     * discounted products quantity
     * @author null<blackbulls@qq.com>
     */
    public function discountedQuantity(Request $request)
    {
        try {
            $start = $request->input('start');
            $end = $request->input('end');
            $store_id = $request->input("store_id");
            $chain_id = $request->input("chain_id");
            if (!isset($store_id) || !isset($chain_id) || !strtotime($start) || !strtotime($end)) {
                throw new Exception("arguments error!");
            }
            $data = ShopOwnerStatisticLogic::discountedProductsQuantity($store_id, $chain_id, $start, $end);
            return response()->json([
                'code' => 1, 'msg' => '', 'data' => $data
            ]);
        } catch (Exception $e) {
            return response()->json([
                'code' => 0, 'msg' => $e->getMessage()
            ]);
        }
    }

    /**
     * ??? gross profit
     * @param Request $request
     * @return JsonResponse
     * @author null<blackbulls@qq.com>
     */
    public  function grossProfit(Request $request)
    {
        try {
            $start = $request->input('start');
            $end = $request->input('end');
            $store_id = $request->input("store_id");
            $chain_id = $request->input("chain_id");
            if (!isset($store_id) || !isset($chain_id) || !strtotime($start) || !strtotime($end)) {
                throw new Exception("arguments error!");
            }
            $data = ShopOwnerStatisticLogic::grossProfit($store_id, $chain_id, $start, $end);
            return response()->json([
                'code' => 1, 'msg' => '', 'data' => $data
            ]);
        } catch (Exception $e) {
            return response()->json([
                'code' => 0, 'msg' => $e->getMessage()
            ]);
        }

    }

    /**
     * purchase quantity ????
     * @author null <blackbulls@qq.com>
     * @param Request $request
     * @return JsonResponse
     */
    public  function purchaseQuantity(Request $request)
    {
        try {
            $start = $request->input('start');
            $end = $request->input('end');
            $store_id = $request->input("store_id");
            $chain_id = $request->input("chain_id");
            if (!isset($store_id) || !isset($chain_id) || !strtotime($start) || !strtotime($end)) {
                throw new Exception("arguments error!");
            }
            $data = ShopOwnerStatisticLogic::purchaseQuantity($store_id, $chain_id, $start, $end);
            return response()->json([
                'code' => 1, 'msg' => '', 'data' => $data
            ]);
        } catch (Exception $e) {
            return response()->json([
                'code' => 0, 'msg' => $e->getMessage()
            ]);
        }
    }


    /**
     * purchase amount ????
     * @author null <blackbulls@qq.com>
     * @param Request $request
     * @return JsonResponse
     */
    public  function purchaseAmount(Request $request)
    {
        try {
            $start = $request->input('start');
            $end = $request->input('end');
            $store_id = $request->input("store_id");
            $chain_id = $request->input("chain_id");
            if (!isset($store_id) || !isset($chain_id) || !strtotime($start) || !strtotime($end)) {
                throw new Exception("arguments error!");
            }
            $data = ShopOwnerStatisticLogic::purchaseAmount($store_id, $chain_id, $start, $end);
            return response()->json([
                'code' => 1, 'msg' => '', 'data' => $data
            ]);
        } catch (Exception $e) {
            return response()->json([
                'code' => 0, 'msg' => $e->getMessage()
            ]);
        }
    }


    /**
     * refund Quantity ????
     * @author null <blackbulls@qq.com>
     * @param Request $request
     * @return JsonResponse
     */
    public  function refundQuantity(Request $request)
    {
        try {
            $start = $request->input('start');
            $end = $request->input('end');
            $store_id = $request->input("store_id");
            $chain_id = $request->input("chain_id");
            if (!isset($store_id) || !isset($chain_id) || !strtotime($start) || !strtotime($end)) {
                throw new Exception("arguments error!");
            }
            $data = ShopOwnerStatisticLogic::refundQuantity($store_id, $chain_id, $start, $end);
            return response()->json([
                'code' => 1, 'msg' => '', 'data' => $data
            ]);
        } catch (Exception $e) {
            return response()->json([
                'code' => 0, 'msg' => $e->getMessage()
            ]);
        }
    }

    /**
     * refund Amount ????
     * @author null <blackbulls@qq.com>
     * @param Request $request
     * @return JsonResponse
     */
    public  function refundAmount(Request $request)
    {
        try {
            $start = $request->input('start');
            $end = $request->input('end');
            $store_id = $request->input("store_id");
            $chain_id = $request->input("chain_id");
            if (!isset($store_id) || !isset($chain_id) || !strtotime($start) || !strtotime($end)) {
                throw new Exception("arguments error!");
            }
            $data = ShopOwnerStatisticLogic::refundAmount($store_id, $chain_id, $start, $end);
            return response()->json([
                'code' => 1, 'msg' => '', 'data' => $data
            ]);
        } catch (Exception $e) {
            return response()->json([
                'code' => 0, 'msg' => $e->getMessage()
            ]);
        }
    }

    /**
     * Turnover ?????
     * @author youxianyen
     * @param Request $request
     * @return JsonResponse
     */
    public  function Turnover(Request $request)
    {
        try {
            $start = $request->input('start');
            $end = $request->input('end');
            $store_id = $request->input("store_id");
            $chain_id = $request->input("chain_id");
            if (!isset($store_id) || !isset($chain_id) || !strtotime($start) || !strtotime($end)) {
                throw new Exception("arguments error!");
            }
            $data = ShopOwnerStatisticLogic::Turnover($store_id, $chain_id, $start, $end);
            return response()->json([
                'code' => 1, 'msg' => '', 'data' => $data
            ]);
        } catch (Exception $e) {
            return response()->json([
                'code' => 0, 'msg' => $e->getMessage()
            ]);
        }
    }

    /**
     * WarningQuantity ??????
     * @author youxianyen
     * @param Request $request
     * @return JsonResponse
     */
    public  function WarningQuantity(Request $request)
    {
        try {
            $start = $request->input('start');
            $end = $request->input('end');
            $store_id = $request->input("store_id");
            $chain_id = $request->input("chain_id");
            if (!isset($store_id) || !isset($chain_id) || !strtotime($start) || !strtotime($end)) {
                throw new Exception("arguments error!");
            }
            $data = ShopOwnerStatisticLogic::WarningQuantity($store_id, $chain_id, $start, $end);
            return response()->json([
                'code' => 1, 'msg' => '', 'data' => $data
            ]);
        } catch (Exception $e) {
            return response()->json([
                'code' => 0, 'msg' => $e->getMessage()
            ]);
        }
    }
	public function purchasedFunds(Request $request) {
        try {
            $user = AuthController::meme();
            $start = $request->input('start');
            $end = $request->input('end');
            $store_id = $user->store_id;
            $chain_id = $request->input("chain_id");
            $supplier_id = $request->input("supplier_id");
	    $type = $request->input('type');
		
            if (!isset($store_id) || !isset($chain_id) || !strtotime($start) || !strtotime($end)||!isset($type)) {
                throw new Exception("arguments error!");
            }
           if($type =="supplier") {   $data = DB::table('funds')
                        
                        ->where('chain_id',$chain_id)
                        ->whereBetween('finish_date', [$start,$end])
                        ->when($supplier_id!='',function($query) use($supplier_id) {
                            $query->where('supplier_id',$supplier_id);
                        })
                        ->selectRaw('SUM(amount) AS total_amount,supplier_id')
			->groupBy('supplier_id')
                        ->get()->toArray();
	foreach($data as $item) {
		$item->supplier_name = DB::table('suppliers')->where('id',$item->supplier_id)->first()->supplier_name;
		}
	} else  if($type =="chain") {   
$data = DB::table('funds')
                        
                        ->where('chain_id',$chain_id)
                        ->whereBetween('finish_date', [$start,$end])
                        ->when($supplier_id!='',function($query) use($supplier_id) {
                            $query->where('supplier_id',$supplier_id);
                        })
                        ->selectRaw('SUM(amount) AS total_amount,chain_id')
			->groupBy('chain_id')
                        ->get()->toArray();
		foreach($data as $item) {
		$item->chain_name = DB::table('chains')->where('id',$item->chain_id)->first()->chain_name;
		}

	} else {return response()->json(['code'=>0,'msg'=>'type not found']);}
         
                        
            return response()->json([
                'code' => 1, 'msg' => '', 'data' => $data
            ]);
        } catch (Exception $e) {
            return response()->json([
                'code' => 0, 'msg' => $e->getMessage()
            ]);
        }
    }

    public function orderAmount1(Request $request) {
        try {
            $start = $request->input('start');
            $end = $request->input('end');
            $chain_id = $request->input('chain_id');
            $store_id = $request->input('store_id');
            if (!isset($store_id) || !isset($chain_id) || !strtotime($start) || !strtotime($end)) {
                throw new Exception("arguments error!");
            }
            $data = ShopOwnerStatisticLogic::statisticsOrder1($chain_id, $store_id, $start, $end);
            return response()->json([
                'code' => 1, 'msg' => '', 'data' => $data
            ]);
        }catch (Exception $e) {
            return response()->json([
                'code' => 0, 'msg' => $e->getMessage()
            ]);
        }
        
    }
	public function orderAmountByPaymentMethod(Request $request) {
        try {
            $start = $request->input('start');
            $end = $request->input('end');
            $chain_id = $request->input('chain_id');
            $store_id = $request->input('store_id');
            if (!isset($store_id) || !isset($chain_id) || !strtotime($start) || !strtotime($end)) {
                throw new Exception("arguments error!");
            }
            $data = ShopOwnerStatisticLogic::statisticsOrderByPaymentMethodId($chain_id, $store_id, $start, $end);
            return response()->json([
                'code' => 1, 'msg' => '', 'data' => $data
            ]);
        }catch (Exception $e) {
            return response()->json([
                'code' => 0, 'msg' => $e->getMessage()
            ]);
        }
        
    }
    public function discountAmount1(Request $request) {
        try {
            $start = $request->input('start');
            $end = $request->input('end');
            $chain_id = $request->input('chain_id');
            $store_id = $request->input('store_id');
            if (!isset($store_id) || !isset($chain_id) || !strtotime($start) || !strtotime($end)) {
                throw new Exception("arguments error!");
            }
            $data = ShopOwnerStatisticLogic::statisticsDiscount1($chain_id, $store_id, $start, $end);
            return response()->json([
                'code' => 1, 'msg' => '', 'data' => $data
            ]);
        }catch (Exception $e) {
            return response()->json([
                'code' => 0, 'msg' => $e->getMessage()
            ]);
        }
        
    }

   public function orderAmountByShop1(Request $request) {
        try {
            $start = $request->input('start');
            $end = $request->input('end');
            
            $store_id = $request->input('store_id');
            if (!isset($store_id) || !strtotime($start) || !strtotime($end)) {
                throw new Exception("arguments error!");
            }
            $data = ShopOwnerStatisticLogic::statisticsOrderByShop1( $store_id, $start, $end);
            return response()->json([
                'code' => 1, 'msg' => '', 'data' => $data
            ]);
        }catch (Exception $e) {
            return response()->json([
                'code' => 0, 'msg' => $e->getMessage()
            ]);
        }
        
    }
public function orderAmountByCachier1(Request $request) {
        try {
            $start = $request->input('start');
            $end = $request->input('end');
            $cachier_id = $request->input('cachier_id');
            $chain_id = $request->input('chain_id');
            $store_id = $request->input('store_id');
            if (!isset($store_id) || !isset($chain_id) || !isset($cachier_id) || !strtotime($start) || !strtotime($end)) {
                throw new Exception("arguments error!");
            }
            $data = ShopOwnerStatisticLogic::statisticsOrderByCashier1( $store_id,$chain_id,$cachier_id, $start, $end);
            return response()->json([
                'code' => 1, 'msg' => '', 'data' => $data
            ]);
        }catch (Exception $e) {
            return response()->json([
                'code' => 0, 'msg' => $e->getMessage()
            ]);
        }
        
    }
public function grossProfit1(Request $request) {
        try {
            $start = $request->input('start');
            $end = $request->input('end');
            $chain_id = $request->input('chain_id');
            $store_id = $request->input('store_id');
            if (!isset($store_id) || !isset($chain_id) || !strtotime($start) || !strtotime($end)) {
                throw new Exception("arguments error!");
            }
            $data = ShopOwnerStatisticLogic::statisticGrossProfit1($chain_id, $store_id, $start, $end);
            return response()->json([
                'code' => 1, 'msg' => '', 'data' => $data
            ]);
        }catch (Exception $e) {
            return response()->json([
                'code' => 0, 'msg' => $e->getMessage()
            ]);
        }
        
    }
public function productQuantity1(Request $request)
    {
        try {
            $store_id = $request->input("store_id");
            $chain_id = $request->input("chain_id");
            $start = $request->input("start");
            $end = $request->input("end");
            if (!$store_id || !$chain_id || !strtotime($start) || !strtotime($end)) {
                throw new Exception("arguments error!");
            }

            $data = ShopOwnerStatisticLogic::staticsProductQuantity1($store_id, $chain_id, $start, $end);
            return response()->json([
                'code' => 1, 'msg' => '', 'data' => $data
            ]);
        } catch (Exception $e) {
            return response()->json([
                'code' => 0, 'msg' => $e->getMessage()
            ]);
        }

    }
public function orderQuantity1(Request $request)
    {
        try {
            $start = $request->input('start');
            $end = $request->input('end');
            $chain_id = $request->input('chain_id');
            $store_id = $request->input('store_id');
            if (!isset($store_id) || !isset($chain_id) || !strtotime($start) || !strtotime($end)) {
                throw new Exception("arguments error!");
            }
            $data = ShopOwnerStatisticLogic::staticsOrderQuantity1($chain_id, $store_id, $start, $end);
            return response()->json([
                'code' => 1, 'msg' => '', 'data' => $data
            ]);
        } catch (Exception $e) {
            return response()->json([
                'code' => 0, 'msg' => $e->getMessage()
            ]);
        }

    }
public function fund1(Request $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');
        $chain_id = $request->input('chain_id');
        $store_id = $request->input('store_id');
        $way = $request->input('show_way', 'day');
        $data = ShopOwnerStatisticLogic::statisticsByPaymentMethodId1($store_id,$chain_id, $way, $from, $to);
        return response()->json([
            'code' => 1, 'msg' => '', 'data' => $data
        ]);

    }

}
