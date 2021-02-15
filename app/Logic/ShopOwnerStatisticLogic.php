<?php


namespace App\Logic;


use App\Models\Product;
use App\Models\ProductItemOrder;
use App\Models\StatisticOrder;
use App\Models\Order;
use App\Models\Chain;
use App\Models\Shop;
use DateTime;
use App\Models\StatisticProduct;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Self_;

/**
 *
 *
 * Class ShopOwnerStatisticLogic
 *
 * @package App\Logic
 */
class ShopOwnerStatisticLogic
{
    /**
     * statistics By Order State ????????
     * @param $store_id
     * @param $chain_id
     * @param string $from
     * @param string $to
     * @return array
     * @author null<blackbulls@qq.com>
     */
    public static function statisticsByOrderState($store_id, $chain_id, $from = '', $to = '')
    {
        $rs = StatisticOrder::selectRaw("sum(paid) as paid,sum(confirmed) as confirmed,sum(pended) as  pended,days")
            ->where(['chain_id' => $chain_id, 'store_id' => $store_id])
            ->whereBetween('days', self::buildBetween($from, $to))->groupBy('days')->get();

        $label = [];

        $data = [
            'paid' => [],
            'pended' => [],
            'confirmed' => [],
        ];
        foreach ($rs as $v) {
            $label[] = $v['days'];
            $data['paid'][] = $v['paid'];
            $data['pended'][] = $v['pended'];
            $data['confirmed'][] = $v['confirmed'];
        }
        return ['label' => $label, 'data' => $data];


    }


    /**
     * statistics by payment method ????????
     * @author null<blackbulls@qq.com>
     * @param $store_id
     * @param $chain_id
     * @param string $show_way options [day,month],day:by days ,month:by_month
     * @param string $from
     * @param string $to
     * @return array
     */
    public static function statisticsByPaymentMethodId($store_id, $chain_id, $show_way = 'month', $from = '', $to = '')
    {
        $where['chain_id'] = $chain_id;
	$store=(int) $store_id;
	$chain=(int) $chain_id;
        $where['store_id'] = $store_id;
        if ($show_way == 'month') {
            $sql = sprintf("select card,`check`, cash,DATE_FORMAT(days,\"%%Y-%%m\") as months,days from statistic_order where chain_id=%d and store_id=%d",$chain,$store);
            $rs = DB::table(DB::raw("($sql) as t"))
                ->selectRaw(" sum(card) as card,sum(`check`) as `check`,sum(cash) as cash,months as days")
                ->whereBetween('months', self::buildBetween($from, $to,"months"))
                ->groupBy('months')
                ->get();

        } else {
            $rs = StatisticOrder::select(['card', 'check', 'cash', 'days'])
                ->where($where)
                ->whereBetween('days', self::buildBetween($from, $to))
                ->get();
        }
        $data = ['card' => [], 'check' => [], 'cash' => []];
        $total=['cash'=>0,'check'=>0,'card'=>0];
        $label = [];
        foreach ($rs as $k => $v) {
            $v = collect($v)->toArray();
            $label[] = $v['days'];
            $data['card'][] = round($v['card'], 2);
            $data['check'][] = round($v['check'], 2);
            $data['cash'][] = round($v['cash'], 2);
        }
        $total['cash']=round(array_sum($data['cash']),2);
        $total['check']=round(array_sum($data['check']),2);
        $total['card']=round(array_sum($data['card']),2);
        return ['label' => $label,'card'=>$data['card'],'check'=>$data['check'],'cash'=>$data['cash'],'total'=>$total];
    }


    /**
     * ??????
     *
     * @author null<blackbulls@qq.com>
     * @param $chain_id
     * @param string $category_id
     * @param string $from
     * @param string $to
     * @return array
     */
    public static function statisticsProductsByCategory($chain_id, $category_id = '', $from = '', $to = '')
    {

        $where['chain_id'] = $chain_id;
        if ($category_id) {
            $where['category_id'] = $category_id;
        }
        $rs = StatisticProduct::where($where)
            ->with('product')->with('category')
            ->whereBetween('days', self::buildBetween($from, $to))
            ->orderBy('days', 'desc')->get();

        $label = $data = $price = [];
        foreach ($rs as $k => $v) {

            $label[] = $v['product']['product_name'];
            $data[] = $v['sales'];
            $price[] = $v['price'];

        }

        return ['label' => $label, 'sales' => $data, 'price' => $price];

    }


    /**
     * ????KPI,??????
     * @author null<blackbulls@qq.com>
     * @param $store_id
     * @param $chain_id
     * @param string $from
     * @param string $to
     * @return array
     */
    public static function statisticsKPI($store_id,$chain_id, $from = '', $to = '')
    {
        $sql =sprintf("select card,`check`, cash,DATE_FORMAT(days,\"%%Y-%%m\") as months,days,income from statistic_order where chain_id=%d and store_id=%d",$store_id,$chain_id);
        $rs = DB::table(DB::raw("($sql) as t"))
            ->selectRaw(" sum(income) as income, days ")
            ->whereBetween('days', self::buildBetween($from, $to,"days"))
            ->groupBy('days')
            ->get();
        $data = [];
        $label = [];
        foreach ($rs as $k => $v) {
            $label[] = $v->days;
            $data[] = $v->income;
        }
        $total=DB::table(DB::raw("($sql) as t"))->whereBetween('days', self::buildBetween($from, $to,"days"))->sum('income');
        return ['label' => $label, 'data' => $data,'total'=>$total];

    }

    /**
     * ??????,?web??????,??,??????:
     *
     * @author null<blackbulls@qq.com>
     * @param $chain_id
     * @param $store_id
     * @param string $start
     * @param string $end
     * @return array
     */
    public static function statisticsOrder($chain_id, $store_id, $start, $end)
    {

        $table=DB::raw("(SELECT  income,days,DATE_FORMAT(days,'%Y-%m')  as months,DATE_FORMAT(days,\"%Y\")  as years,store_id,chain_id from  statistic_order) as t");
        $data = [];
        foreach (['days','months','years'] as $p){
          $data[$p]=  DB::table($table)
              ->selectRaw(" sum(income) as income, $p  as period")
              ->where([
                'store_id'=>$store_id,'chain_id'=>$chain_id
            ])->whereBetween($p,self::buildBetween($start,$end,$p))->groupBy($p)->get() ;
        }
        $data['total']=DB::table($table)->where([ 'store_id'=>$store_id,'chain_id'=>$chain_id])->sum('income');

        return $data;
    }

    /**
     *  taskid=40 ????????,?web??????,??,??????:
     * @author youxianyen
     * @param $chain_id
     * @param $store_id
     * @param Date $start
     * @param Date $end
     * @return array
     */
    public static function statisticsMemberAmount($chain_id, $store_id, $start, $end)
    {

        $sql =sprintf( "SELECT id, member_price,DATE_FORMAT(created_at, \"%%Y-%%m-%%d\") AS days,
                DATE_FORMAT(created_at, \"%%Y-%%m\") AS months,DATE_FORMAT(created_at, \"%%Y\") as years
                FROM orders WHERE store_id=%d and chain_id=%d",$store_id,$chain_id);
        $data = [];
        foreach (['days', 'months', 'years'] as $k => $p) {
            $data[$p] = DB::table(DB::raw("($sql) as t"))
                ->selectRaw("$p as period,round(sum(member_price),2) as total")
                ->whereBetween($p, self::buildBetween($start, $end,$p))
                ->groupBy([$p])
                ->orderBy('period', 'desc')
                ->get();
        }
        return $data;
    }

    /**
     *  taskid=54 ??????,?web??????,??,??????:
     * @author youxianyen
     * @param $chain_id
     * @param $store_id
     * @param Date $start
     * @param Date $end
     * @return array
     */
    public static function statisticsMemberQuantity($store_id, $start, $end)
    {
        $sql = sprintf("SELECT id, DATE_FORMAT(created_at, \"%%Y-%%m-%%d\") AS days,
                DATE_FORMAT(created_at, \"%%Y-%%m\") AS months,DATE_FORMAT(created_at, \"%%Y\") as years
                FROM members WHERE store_id=%d",$store_id);
        $data = [];
        foreach (['days', 'months', 'years'] as $k => $p) {
            $data[$p] = DB::table(DB::raw("($sql) as t"))
                ->selectRaw("$p as period,count(id) as total")
                ->whereBetween($p, self::buildBetween($start, $end,$p))
                ->groupBy([$p])
                ->orderBy('period', 'desc')
                ->get();
        }
        return $data;
    }

    /**
     * taskid=52
     * ??????????,?????chain:
     * @author youxianyen
     * @param $store_id
     * @return array
     */
    public static function statisticsStoreQuantity($store_id)
    {
        $count_chains = Chain::where('store_id', $store_id)->count();
        return ['total' => $count_chains];

    }

    /**
     * taskid=53
     * ??chain???????:
     * @author youxianyen
     * @param $store_id
     * @return array
     */
    public static function storeManagerQuantity($store_id)
    {
        $count_chains = Chain::where('store_id', $store_id)->where('manager_id', '>', 0)->count();
        $data = ['total' => $count_chains];
        return $data;
    }


    /**
     * ?????? Discount product amount
     *
     * @param $start
     * @param $end
     * @param $chain_id
     * @param $store_id
     * @return array
     */
    public static function statisticsDiscountAmount($start, $end, $chain_id, $store_id)
    {
        $store_id = $store_id * 1;
        $chain_id = $chain_id * 1;

        $sql = sprintf("select b.product_name, b.unit_price, DATE_FORMAT(a.start_date,'%%Y-%%m-%%d') days,
                DATE_FORMAT(a.start_date,'%%Y-%%m') months,DATE_FORMAT(a.start_date,'%%Y') years
                from product_discount a INNER JOIN products b on a.product_id=b.id and a.discount_id<>2
                and a.start_date >= '%s' and a.finish_date <= '%s' and  b.shop_id= %d
                and b.chain_id= %d",$start,$end,$store_id,$chain_id);
        $data=[];
        foreach (['days', 'months', 'years'] as $k=>$p) {
            $data[$p] = DB::table(DB::raw("($sql) as t"))
                ->selectRaw("$p as period,round(sum(unit_price),2) as total, product_name")
                ->groupBy([$p, 'product_name'])
                ->get() ;
        }
        return $data;
    }


    /**
     * ????????,?web??????,??,??????:
     *
     * @author null<blackbulls@qq.com>
     * @param $chain_id
     * @param $store_id
     * @param string $start
     * @param string $end
     * @return array
     */
    public static function staticsOrderQuantity($chain_id, $store_id, $start , $end)
    {

        $sql=sprintf("select DATE_FORMAT(days,'%%Y-%%m')  as months,DATE_FORMAT(days,'%%Y')  as years,days,total_order from statistic_order where store_id=%d and chain_id=%d",$store_id,$chain_id);
        $data = [];
        foreach (['days', 'months', 'years'] as $k=>$p) {
            $data[$p] = DB::table(DB::raw("($sql) as t"))
                ->selectRaw("$p as period, sum(total_order) total")
                ->whereBetween($p, self::buildBetween($start, $end,$p))
                ->groupBy($p)
                ->get() ;
        }
        $data['total']  = StatisticOrder::where('store_id', $store_id)->where('chain_id', $chain_id)->sum("total_order");
        return $data;
    }


    /**
     * ???????? Orders by suppliers
     *
     * @param $store_id
     * @param $chain_id
     * @param $supplier_id
     * @param string $start
     * @param string $end
     * @return array
     */
    public static function staticsOrderSupplier($store_id, $chain_id, $supplier_id, $start = '', $end = '')
    {

        $sql = sprintf("select days,DATE_FORMAT(days,'%%Y-%%m')  as months,
                DATE_FORMAT(days,'%%Y')  as years, b.supplier_name, sales
                from statistic_product a, suppliers b
                where a.supplier_id=b.id and  a.store_id=%d and a.chain_id=%d",$store_id,$chain_id);
        $data = [];
        foreach (['days', 'months', 'years'] as $k => $p) {
            $data[$p] = DB::table(DB::raw("($sql) as t"))
                ->selectRaw("$p as period,round(sum(sales),2) as total, supplier_name")
                ->whereBetween($p, self::buildBetween($start, $end,$p))
                ->groupBy([$p, 'supplier_name'])
                ->orderBy('period', 'desc')
                ->get();
        }
        return $data;
    }


    /**
     * ???????
     *
     * @param $chain_id
     * @param string $from
     * @param string $to
     * @return array
     */
    public static function statisticsStockByChain($chain_id, $from = '', $to = '')
    {


        $rs = Product::select(['product_name', 'product_quantity', 'warn_quantity'])
            ->where(['chain_id' => $chain_id])
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)->get()->toArray();


        $data = [
            'product_quantity' => [],
            'warn_quantity' => [],

        ];
        $label = [];
        foreach ($rs as $k => $v) {

            $label[] = $v['product_name'];
            $data['product_quantity'][] = $v['product_quantity'];
            $data['warn_quantity'][] = $v['warn_quantity'];

        }
        return ['label' => $label, 'data' => $data];

    }

    /**
     * ??between and ??
     *
     * @param $from
     * @param $to
     * @param $type
     * @return array
     */
    private static function buildBetween($from, $to, $type = 'day')
    {

        $from = strtotime($from) ? $from : Carbon::now()->subDays(15)->toDateString();
	if($to==null || $to =='') {
            if(strtotime($to) == strtotime($from)) {
                $to = Date('Y-m-d H:i:s', strtotime($to) + 60 * 60 * 24);
            }else {
                $to =Carbon::now()->toDateString(); ;
            }
        }
        //$to = strtotime($to) == strtotime($from) ? Date('Y-m-d H:i:s', strtotime($to) + 60 * 60 * 24) : Carbon::now()->toDateString();
        list($y, $m, $d) = explode("-", $from);
        list($e_y, $e_m, $e_d) = explode("-", $to);
        if ($type == 'years') {
            $from = $y;
            $to = $e_y;
        }
        if ($type == 'months') {
            $from = sprintf("%s-%s", $y, $m);
            $to = sprintf("%s-%s", $e_y, $e_m);
        }

        return [$from, $to];
    }


    /**
     *Product Quantity ????
     *
     * @param $store_id
     * @param $chain_id
     * @param $start 2020-09-01
     * @param $end 2020-09-02
     * @return array
     */
    public static function staticsProductQuantity($store_id, $chain_id, $start, $end)
    {
        $sql =sprintf("SELECT a.product_name, DATE_FORMAT(a.created_at,'%%Y-%%m-%%d') days,
                DATE_FORMAT(a.created_at,'%%Y-%%m') months,DATE_FORMAT(a.created_at,'%%Y') years FROM products a
                where a.chain_id=%d and a.shop_id=%d",$store_id,$chain_id);
        $data = [];

        foreach (['days', 'months', 'years'] as $p) {
            $data[$p] = DB::table(DB::raw("($sql) as t"))
                ->selectRaw("$p as period,count(product_name) as total")
                ->whereBetween($p,self::buildBetween($start,$end,$p))
                ->groupBy($p)
                ->orderBy('period', 'desc')
                ->get();
        }
        return $data;
    }

    /**
     *Hot selling products ??????
     *
     * @param $store_id
     * @param $chain_id
     * @param $start 2020-09-01
     * @param $end 2020-09-02
     * @return array
     */
    public static function hotCategoryProducts($start, $end, $chain_id, $store_id, $top)
    {
        return DB::table('statistic_product')
            ->leftJoin('categories', 'statistic_product.category_id', '=', 'categories.id')
            ->whereBetween('days', [$start,$end])
            ->where('chain_id', $chain_id)
            ->where('store_id', $store_id)
            ->selectRaw('SUM(sales) AS total_sales,category_id,category_name')
            ->groupBy('category_id')
            ->orderBy('total_sales', 'DESC')
            ->limit($top)
            ->get();
    }


    /**
     * Sale Quantity ?????
     * @author youxianyen
     * @param $store_id
     * @param $chain_id
     * @param $start
     * @param $end
     * @param $rows
     * @return array
     */
    public static function staticsSaleQuantity($store_id, $chain_id, $start, $end, $rows = 3)
    {
        $sql = sprintf("SELECT product_id, sales, DATE_FORMAT(days,'%%Y-%%m-%%d') days,
                DATE_FORMAT(days,'%%Y-%%m') months,DATE_FORMAT(days,'%%Y') years FROM statistic_product where chain_id=%d and store_id=%d
                ORDER BY sales desc",$chain_id,$store_id);

        $data = [];
        $data['total'] = DB::table(DB::raw("($sql) as t"))->count("t.product_id");

        $sql2=sprintf("SELECT DISTINCT product_id, product_name,product_barcode,DATE_FORMAT(days,'%%Y-%%m-%%d') days, DATE_FORMAT(days,'%%Y-%%m') months,DATE_FORMAT(days,'%%Y') years from statistic_product  a left JOIN products b on a.product_id =b.id where store_id=%d and a.chain_id=%d and a.days between '%s' and '%s'",$store_id,$chain_id,$start,$end);
      //  echo $sql2;die();

        foreach (['days', 'months', 'years'] as $p) {

            $data[$p] = DB::table(DB::raw("($sql) as t"))
                ->selectRaw("$p as period,count(product_id) as total,sum(sales) as sold")
                ->whereBetween($p,self::buildBetween($start,$end,$p))
                ->groupBy($p)
                ->get()->each(function ($item)use($sql2,$p,$rows){
                    $where[$p]=$item->period;
                      $item->products[]= DB::table(DB::raw("($sql2) t"))
                          ->selectRaw("product_id ,product_name,product_barcode")
                          ->where($where)
                          ->limit($rows)
                          ->get();
                }) ;
        }


        return $data;
    }


    /**
     * taskID=36, hot-sale product
     * @param $start
     * @param $end
     * @param $chain_id
     * @param $store_id
     * @param $top
     * @return Collection
     */
    public static function statisticsSaleTopProducts($start, $end, $chain_id, $store_id, $top)
    {
        return DB::table('statistic_product')
            ->join('products', 'statistic_product.product_id', '=', 'products.id')
            ->whereBetween('days', [$start,$end])
            ->where('products.chain_id', $chain_id)
            ->where('products.shop_id', $store_id)
            ->select(DB::raw('SUM(sales) AS total_sales'),'product_id','products.product_name')
            ->groupBy('product_id')
            ->orderBy('total_sales', 'DESC')
            ->limit($top)
            ->get();

    }




    /**
     *
     * Top products in supplier
     * @author  youxianyen
     * @param $start
     * @param $end
     * @param $chain_id
     * @param $store_id
     * @param $top
     * @return Collection
     */
    public static function statisticsTopSuppliers($start, $end, $chain_id, $store_id, $top)
    {
        return DB::table('statistic_product')
            ->join('suppliers', 'statistic_product.supplier_id', '=', 'suppliers.id')
            ->whereBetween('days', [$start,$end])
            ->where('chain_id', $chain_id)
            ->where('store_id', $store_id)
            ->selectRaw('SUM(sales) AS total_sales,supplier_name,supplier_id')
            ->groupBy(['supplier_name', 'supplier_id'])
            ->orderBy('total_sales', 'DESC')
            ->limit($top)
            ->get();
    }

    /**
     * ?????????
     * @author  youxianyen
     * @param $store_id
     * @param $chain_id
     * @param $start
     * @param $end
     * @return array
     */
    public static function promotionalQuantity($store_id, $chain_id, $start, $end)
    {
        $sql = sprintf("select a.id, b.product_name, a.created_at,DATE_FORMAT(a.start_date,'%%Y-%%m-%%d') days, DATE_FORMAT(a.start_date,'%%Y-%%m') months,DATE_FORMAT(a.start_date,'%%Y') years
 from product_discount a INNER JOIN products b on a.product_id=b.id and a.start_date >= '%s' and a.finish_date >='%s' and a.discount_id=2 and  b.shop_id=%d and b.chain_id=%d",$start,$end,$store_id,$chain_id);
        $data = [];
        $data['total'] = DB::table(DB::raw("($sql) as t"))->count("t.id");

        foreach (['days', 'months', 'years'] as $p) {
            $data[$p] = DB::table(DB::raw("($sql) as t"))
                ->selectRaw("$p as period,count(*) as total, product_name")
                ->whereBetween($p,self::buildBetween($start,$end,$p))
                ->groupBy([$p, 'product_name'])
                ->get();
        }

        return $data;
    }

    /**
     * ?????????
     * @author  youxianyen
     * @param $store_id
     * @param $chain_id
     * @param $start
     * @param $end
     * @return array
     */
    public static function promotionalAmount($store_id, $chain_id, $start, $end)
    {

        $sql = sprintf("select b.product_name, b.unit_price, DATE_FORMAT(a.start_date,'%Y-%m-%d') days,
                DATE_FORMAT(a.start_date,'%Y-%m') months,DATE_FORMAT(a.start_date,'%Y') years
                from product_discount a INNER JOIN products b on a.product_id=b.id and a.discount_id=2
                and a.start_date >= '%s' and a.finish_date <= '%s' and  b.shop_id= %d
                and b.chain_id= %d ",$start,$end,$store_id,$chain_id);
        $data=[];
        foreach (['days', 'months', 'years'] as $k=>$p) {
            $data[$p] = DB::table(DB::raw("($sql) as t"))
                ->selectRaw("$p as period,round(sum(unit_price),2) as total, product_name")
                ->groupBy([$p, 'product_name'])
                ->get() ;
        }
        return $data;
    }

    /**
     * ??? = ???-???
     * turnover = market value - cost price
     * @author  youxianyen
     * @param $store_id
     * @param $chain_id
     * @param $start
     * @param $end
     * @return array
     */
    public static function Turnover($store_id, $chain_id, $start, $end)
    {

        $sql =sprintf( 'select b.unit_price, b.cost_price, DATE_FORMAT(a.days,"%%Y-%%m-%%d") days, DATE_FORMAT(a.days,"%%Y-%%m") months,DATE_FORMAT(a.days,"%%Y") years
 from statistic_product a INNER JOIN products b on a.product_id=b.id  and  b.shop_id=%d and b.chain_id=%d',$store_id,$chain_id);
        $data = [];
        $table = DB::raw("($sql) as t");
        foreach (['days', 'months', 'years'] as $k => $p) {
            $data[$p] = DB::table($table)
                ->selectRaw("$p as period,round(sum(unit_price - cost_price),2) as total")
                ->whereBetween($p, self::buildBetween($start, $end,$p))
                ->groupBy($p)
                ->orderBy('period', 'desc')
                ->get();
        }
        return $data;
    }

    /**
     * ??????
     * InventoryQuantity
     * @author  youxianyen
     * @param $store_id
     * @param $chain_id
     * @param $start
     * @param $end
     * @return array
     */
    public static function WarningQuantity($store_id, $chain_id, $start, $end)
    {
        $sql = sprintf('select a.inventory, b.product_name, DATE_FORMAT(a.days,"%%Y-%%m-%%d") days,
            DATE_FORMAT(a.days,"%%Y-%%m") months,DATE_FORMAT(a.days,"%%Y") years
            from product_warning_history a INNER JOIN products b on a.product_id=b.id
            and  b.shop_id=%d and b.chain_id=%d',$store_id,$chain_id);

        $data=[];
        $table=DB::raw("($sql) as t");
        $data['total']=DB::table($table)->whereBetween("days",self::buildBetween($start,$end))->sum('inventory');
        foreach (['days', 'months', 'years'] as $k=>$p) {
            $data[$p] = DB::table(DB::raw("($sql) as t"))
                ->selectRaw("product_name, $p as period,sum(inventory) as warning_quantity")
                ->whereBetween($p,self::buildBetween($start,$end,$p))
                ->groupBy(['product_name', $p])
                ->get() ;
        }
        return $data;
    }

    /**
     * ?????????
     * @param $store_id
     * @param $chain_id
     * @param $start
     * @param $end
     * @return array
     * @author  null<blackbulls@qq.com>
     */
    public static function discountedProductsQuantity($store_id, $chain_id, $start, $end)
    {

        $sql = sprintf('select a.id,a.created_at,DATE_FORMAT(a.created_at,"%%Y-%%m-%%d") days, DATE_FORMAT(a.created_at,"%%Y-%%m") months,DATE_FORMAT(a.created_at,"%%Y") years
 from product_discount a INNER JOIN products b on a.product_id=b.id and discount_id!=2 and  b.shop_id=%d and b.chain_id=%d',$store_id, $chain_id );

        $data = [];
        $data['total'] = DB::table(DB::raw("($sql) as t"))->count("t.id");

        foreach (['days', 'months', 'years'] as $p) {
            $data[$p] = DB::table(DB::raw("($sql) as t"))
                ->selectRaw("$p as period,count(*) as total")
                ->whereBetween($p,self::buildBetween($start,$end,$p))
                ->groupBy($p)
                ->orderBy('period', 'desc')
                ->get();
        }

        return $data;
    }


    /**
     * ???
     * @param $store_id
     * @param $chain_id
     * @param $start
     * @param $end
     * @return array
     * @author null<blackbulls@qq.com>
     */
    public static function grossProfit($store_id, $chain_id, $start, $end)
    {

        //?product_item_order  ??????,?left join product ,????????
        $sql = sprintf('SELECT  order_id,product_id, order_item_payment_amount-cost_price*order_item_quantity as profit,DATE_FORMAT(b.created_at,"%%Y-%%m-%%d") as days, DATE_FORMAT(b.created_at,"%%Y-%%m") as months,DATE_FORMAT(b.created_at,"%%Y") as years,b.store_id,b.chain_id from product_item_order a
 INNER  JOIN orders b on a.order_id=b.id LEFT JOIN products c on a.product_id=c.id where  b.store_id=%d and b.chain_id=%d',$store_id , $chain_id);
        $data = [];
        $table = DB::raw("($sql) as t");
        $data['total'] = round(DB::table($table)->whereBetween("days", self::buildBetween($start, $end))->sum('profit'), 2);
        foreach (['days', 'months', 'years'] as $k => $p) {
            $data[$p] = DB::table($table)
                ->selectRaw("$p as period,round(sum(profit),2) as total")
                ->whereBetween($p, self::buildBetween($start, $end,$p))
                ->groupBy($p)
                ->orderBy('period', 'desc')
                ->get();
        }
        return $data;
    }


    /**
     * ???? refund quantity
     * @param $store_id
     * @param $chain_id
     * @param $start
     * @param $end
     * @return array
     * @author null<blackbulls@qq.com>
     */
    public static function refundQuantity($store_id, $chain_id, $start, $end)
    {

        $sql = sprintf("SELECT id,shop_id,chain_id,cost_price,item_return, DATE_FORMAT(created_at, \"%%Y-%%m-%%d\") AS days, DATE_FORMAT(created_at, \"%%Y-%%m\") AS months, DATE_FORMAT(created_at, \"%%Y\") as years FROM products WHERE item_return>0 and shop_id =%d and chain_id=%d",$store_id,$chain_id);

        $data = [];
        $table = DB::raw("($sql) as t");
        $data['total'] = DB::table($table)->whereBetween("days", self::buildBetween($start, $end))->count('id');
        foreach (['days', 'months', 'years'] as $k => $p) {
            $data[$p] = DB::table($table)
                ->selectRaw("$p as period,count(id) as total")
                ->whereBetween($p, self::buildBetween($start, $end,$p))
                ->groupBy($p)
                ->orderBy('period', 'desc')
                ->get();
        }
        return $data;
    }

    /**
     * ???? refund amount
     * @param $store_id
     * @param $chain_id
     * @param $start
     * @param $end
     * @return array
     * @author  null<blackbulls@qq.com>
     */
    public static function refundAmount($store_id, $chain_id, $start, $end)
    {
        $sql = sprintf("SELECT id,shop_id,chain_id, round(unit_price*item_return,2) as amount, DATE_FORMAT(created_at, \"%%Y-%%m-%%d\") AS days, DATE_FORMAT(created_at, \"%%Y-%%m\") AS months, DATE_FORMAT(created_at, \"%%Y\") as years FROM products WHERE shop_id=%d and chain_id=%d  and item_return>0",$store_id,$chain_id);

        $data = [];
        $table = DB::raw("($sql) as t");
        $data['total'] = DB::table($table)->whereBetween("days", self::buildBetween($start, $end))->sum('amount');
        foreach (['days', 'months', 'years'] as $k => $p) {
            $data[$p] = DB::table(DB::raw("($sql) as t"))
                ->selectRaw("$p as period,round(sum(amount),2) as total")
                ->whereBetween($p, self::buildBetween($start, $end,$p))
                ->groupBy($p)
                ->orderBy('period', 'desc')
                ->get();
        }
        return $data;
    }

    /**
     * ???? new products quantity
     * @param $store_id
     * @param $chain_id
     * @param $start
     * @param $end
     * @return array
     * @author  null<blackbulls@qq.com>
     */
    public static function purchaseQuantity($store_id, $chain_id, $start, $end)
    {
        $sql = sprintf("SELECT id,shop_id,chain_id,DATE_FORMAT(created_at, \"%%Y-%%m-%%d\") AS days,DATE_FORMAT(created_at, \"%%Y-%%m\") AS months,DATE_FORMAT(created_at, \"%%Y\") as years FROM products WHERE shop_id=%d and chain_id=%d and  created_at = updated_at",$store_id,$chain_id);
        $data = [];
        $table = DB::raw("($sql) as t");
        $data['total'] = DB::table($table)->whereBetween("days", self::buildBetween($start, $end))->count('id');
        foreach (['days', 'months', 'years'] as $k => $p) {
            $data[$p] = DB::table(DB::raw("($sql) as t"))
                ->selectRaw("$p as period,count(id) as total")
                ->whereBetween($p, self::buildBetween($start, $end,$p))
                ->groupBy($p)
                ->orderBy('period', 'desc')
                ->get();
        }
        return $data;

    }

    /**
     * ???? new products amount
     * @param $store_id
     * @param $chain_id
     * @param $start
     * @param $end
     * @return array
     * @author  null<blackbulls@qq.com>
     */
    public static function purchaseAmount($store_id, $chain_id, $start, $end)
    {

        $sql = sprintf("SELECT id,shop_id,chain_id,cost_price,created_at,updated_at,DATE_FORMAT(created_at, \"%%Y-%%m-%%d\") AS days,DATE_FORMAT(created_at, \"%%Y-%%m\") AS months,DATE_FORMAT(created_at, \"%%Y\") as years FROM products WHERE  shop_id=%d and chain_id=%d",$store_id,$chain_id);
        $data = [];
        $table = DB::raw("($sql) as t");
        $data['total'] = DB::table($table)->whereBetween("days", self::buildBetween($start, $end))->sum('cost_price');
        $data['total'] = round($data['total'], 2);
        foreach (['days', 'months', 'years'] as $k => $p) {
            $data[$p] = DB::table(DB::raw("($sql) as t"))
                ->selectRaw("$p as period,round(sum(cost_price),2) as total")
                ->whereBetween($p, self::buildBetween($start, $end,$p))
                ->whereRaw("created_at = updated_at")
                ->groupBy($p)
                ->orderBy('period', 'desc')
                ->get();
        }
        return $data;
    }

    public static function statisticsOrder1($chain_id, $store_id, $start, $end)
    {

        $table=DB::raw("(SELECT  id,payment_amount,discount_amount,payment_method_id,DATE_FORMAT(created_at,'%Y-%m-%d') as days,DATE_FORMAT(created_at,'%Y-%m')  as months,DATE_FORMAT(created_at,'%Y')  as years,store_id,chain_id from  orders) as t");
        $data = [];
        foreach (['days','months','years'] as $p){
          $data[$p]=  DB::table($table)
              ->selectRaw(" sum(payment_amount) as income, $p  as period")
              ->where([
                'store_id'=>$store_id,'chain_id'=>$chain_id
            ])->whereBetween($p,self::buildBetween($start,$end,$p))->groupBy($p)->get() ;
        }
        $data['total']=DB::table($table)->where([ 'store_id'=>$store_id,'chain_id'=>$chain_id])->sum('payment_amount');

        return $data;
    }
	public static function statisticsOrderByPaymentMethodId($chain_id, $store_id, $start, $end)
    {

        $table=DB::raw("(SELECT  id,payment_amount,discount_amount,payment_method_id,DATE_FORMAT(created_at,'%Y-%m-%d') as days,DATE_FORMAT(created_at,'%Y-%m')  as months,DATE_FORMAT(created_at,'%Y')  as years,store_id,chain_id from  orders) as t");
        $data = [];
        
        $data['total']=DB::table($table)->where([ 'store_id'=>$store_id,'chain_id'=>$chain_id])->whereBetween('days',array([$start,$end]))->sum('payment_amount');
        $data['card']=DB::table($table)->where([ 'store_id'=>$store_id,'chain_id'=>$chain_id])->whereBetween('days',array([$start,$end]))->where('payment_method_id',3)->sum('payment_amount');
        $data['check']=DB::table($table)->where([ 'store_id'=>$store_id,'chain_id'=>$chain_id])->whereBetween('days',array([$start,$end]))->where('payment_method_id',2)->sum('payment_amount');
        $data['cash']=DB::table($table)->where([ 'store_id'=>$store_id,'chain_id'=>$chain_id])->whereBetween('days',array([$start,$end]))->where('payment_method_id',1)->sum('payment_amount');

        return $data;
    }
	    public static function statisticsDiscount1($chain_id, $store_id, $start, $end)
    {

        $table=DB::raw("(SELECT  id,payment_amount,discount_amount,payment_method_id,DATE_FORMAT(created_at,'%Y-%m-%d') as days,DATE_FORMAT(created_at,'%Y-%m')  as months,DATE_FORMAT(created_at,'%Y')  as years,store_id,chain_id from  orders) as t");
        $data = [];
        foreach (['days','months','years'] as $p){
          $data[$p]=  DB::table($table)
              ->selectRaw(" sum(discount_amount) as discount, $p  as period")
              ->where([
                'store_id'=>$store_id,'chain_id'=>$chain_id
            ])->whereBetween($p,self::buildBetween($start,$end,$p))->groupBy($p)->get() ;
        }
        $data['total']=DB::table($table)->where([ 'store_id'=>$store_id,'chain_id'=>$chain_id])->sum('discount_amount');

        return $data;
    }

	public static function statisticsOrderByShop1($store_id, $start, $end)
    {

        $table=DB::raw("(SELECT  id,payment_amount,discount_amount,payment_method_id,DATE_FORMAT(created_at,'%Y-%m-%d') as days,DATE_FORMAT(created_at,'%Y-%m')  as months,DATE_FORMAT(created_at,'%Y')  as years,store_id,chain_id from  orders) as t");
        $data = [];
        foreach (['days','months','years'] as $p){
          $data[$p]=  DB::table($table)
              ->selectRaw(" sum(payment_amount) as income, $p  as period")
              ->where([
                'store_id'=>$store_id
            ])->whereBetween($p,self::buildBetween($start,$end,$p))->groupBy($p)->get() ;
        }
        $data['total']=DB::table($table)->where([ 'store_id'=>$store_id])->whereBetween('days',array([$start,$end]))->sum('payment_amount');

        return $data;
    }
	 public static function statisticsOrderByCashier1( $store_id,$chain_id,$cachier_id, $start, $end)
    {

        $table=DB::raw("(SELECT  id,payment_amount,discount_amount,cachier_id,payment_method_id,DATE_FORMAT(created_at,'%Y-%m-%d') as days,DATE_FORMAT(created_at,'%Y-%m')  as months,DATE_FORMAT(created_at,'%Y')  as years,store_id,chain_id from  orders) as t");
        $data = [];
        foreach (['days','months','years'] as $p){
          $data[$p]=  DB::table($table)
              ->selectRaw(" sum(payment_amount) as income, $p  as period")
              ->where([
                'store_id'=>$store_id,'chain_id'=>$chain_id,'cachier_id'=>$cachier_id
            ])->whereBetween($p,self::buildBetween($start,$end,$p))->groupBy($p)->get() ;
        }
        $data['total']=DB::table($table)->where([ 'store_id'=>$store_id,'chain_id'=>$chain_id,'cachier_id'=>$cachier_id])->whereBetween('days',array([$start,$end]))->sum('payment_amount');

        return $data;
    }
	public static function statisticGrossProfit1($chain_id, $store_id, $start, $end)
    {

        $table=DB::raw("(SELECT  id,payment_amount,discount_amount,order_cost,payment_method_id,DATE_FORMAT(created_at,'%Y-%m-%d') as days,DATE_FORMAT(created_at,'%Y-%m')  as months,DATE_FORMAT(created_at,'%Y')  as years,store_id,chain_id from  orders) as t");
        $data = [];
        foreach (['days','months','years'] as $p){
          $data[$p]=  DB::table($table)
              ->selectRaw(" sum(payment_amount - order_cost) as profit, $p  as period")
              ->where([
                'store_id'=>$store_id,'chain_id'=>$chain_id
            ])->whereBetween($p,self::buildBetween($start,$end,$p))->groupBy($p)->get() ;
        }
        //$data['total']=DB::table($table)->where([ 'store_id'=>$store_id,'chain_id'=>$chain_id])->whereBetween('days',array([$start,$end]))->sum('payment_amount');

        return $data;
    }
   public static function staticsProductQuantity1($store_id, $chain_id, $start, $end)
    {
        $sql =sprintf("SELECT a.product_id, DATE_FORMAT(a.created_at,'%%Y-%%m-%%d') days,
                DATE_FORMAT(a.created_at,'%%Y-%%m') months,DATE_FORMAT(a.created_at,'%%Y') years FROM product_item_order a
                where a.chain_id=%d and a.store_id=%d",$store_id,$chain_id);
        $data = [];

        foreach (['days', 'months', 'years'] as $p) {
            $data[$p] = DB::table(DB::raw("($sql) as t"))
                ->selectRaw("$p as period,count(product_id) as total")
                ->whereBetween($p,self::buildBetween($start,$end,$p))
                ->groupBy($p)
                ->orderBy('period', 'desc')
                ->get();
        }
        return $data;
    }
public static function staticsOrderQuantity1($chain_id, $store_id, $start , $end)
    {

        $sql=sprintf("select id,DATE_FORMAT(created_at,'%%Y-%%m')  as months,DATE_FORMAT(created_at,'%%Y')  as years,DATE_FORMAT(created_at,'%%Y-%%m-%%d')as days from orders where store_id=%d and chain_id=%d",$store_id,$chain_id);
        $data = [];
        foreach (['days', 'months', 'years'] as $k=>$p) {
            $data[$p] = DB::table(DB::raw("($sql) as t"))
                ->selectRaw("$p as period, count(id) as total")
                ->whereBetween($p, self::buildBetween($start, $end,$p))
                ->groupBy($p)
                ->get() ;
        }
        $data['total']  = Order::where('store_id', $store_id)->where('chain_id', $chain_id)->count();
        return $data;
    }
public static function statisticsByPaymentMethodId1($store_id, $chain_id, $show_way = 'month', $from = '', $to = '')
    {
        $where['chain_id'] = $chain_id;
	$store=(int) $store_id;
	$chain=(int) $chain_id;
        $where['store_id'] = $store_id;
        if ($show_way == 'month') {
            $sql = sprintf("select payment_method_id as payment_method,payment_amount,DATE_FORMAT(created_at,\"%%Y-%%m\") as months, DATE_FORMAT(created_at,\"%%Y-%%m-%%d\") as days from orders where chain_id=%d and store_id=%d",$chain,$store);

            $rs = DB::table(DB::raw("($sql) as t"))
                ->selectRaw("sum(payment_amount) as total,payment_method,months as days")
                ->whereBetween('months', self::buildBetween($from, $to,"months"))
                
                ->groupBy('months')
		->groupBy('payment_method')
		->orderBy('months','desc')
                ->get();

        } else {
	$sql = sprintf("select payment_method_id as payment_method,payment_amount,store_id,chain_id,DATE_FORMAT(created_at,\"%%Y-%%m\") as months, DATE_FORMAT(created_at,\"%%Y-%%m-%%d\") as days from orders where chain_id=%d and store_id=%d",$chain,$store);

            $rs = DB::table(DB::raw("($sql) as t"))
		->selectRaw("sum(payment_amount) as total,payment_method,days")
                ->where($where)
                ->whereBetween('days', self::buildBetween($from, $to))
		->groupBy('payment_method')
		->groupBy('days')

                ->get();
        }
        $data = ['card' => [], 'check' => [], 'cash' => []];
        $total=['cash'=>0,'check'=>0,'card'=>0];
        $label = [];
        foreach ($rs as $k => $v) {
            $v = collect($v)->toArray();
	if($v['payment_method']==1) {$data['cash'][] = round($v['total'], 2);$data['check'][]=0;$data['card'][]=0;}
	else if($v['payment_method']==2) {$data['check'][] = round($v['total'], 2);$data['cash'][]=0;$data['card'][]=0;}
	else if($v['payment_method']==3) {$data['card'][] = round($v['total'], 2);$data['check'][]=0;$data['card'][]=0;}



	     //var_dump($v);
           $label[] = $v['days'];
      //     $data['card'][] = round($v['card'], 2);
       //   $data['check'][] = round($v['check'], 2);
    //       
        }
//return response()->json($rs);
        $total['cash']=round(array_sum($data['cash']),2);
        $total['check']=round(array_sum($data['check']),2);
        $total['card']=round(array_sum($data['card']),2);
        return ['label' => $label,'card'=>$data['card'],'check'=>$data['check'],'cash'=>$data['cash'],'total'=>$total];
    }



}
