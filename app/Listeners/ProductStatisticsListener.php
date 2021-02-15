<?php


namespace App\Listeners;


use App\Events\ProductStatisticsEvent;
use App\Models\StatisticProduct;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProductStatisticsListener
{

    public function __construct()
    {

    }

    public function handle(ProductStatisticsEvent $product)
    {

        $where=[
            'store_id' => $product->data['store_id'],
            'chain_id' => $product->data['chain_id'],
            'product_id' => $product->data['product_id'],
            'days' => Carbon::now()->toDateString(),
            'category_id' => $product->data['category_id'],
        ];
        $rs=StatisticProduct::where($where)->first();
        StatisticProduct::updateOrCreate($where,
            [
                'supplier_id' => $product->data['supplier_id'],
                'price'=>$product->data['price'],
                'sales' => DB::raw($rs?'sales+' . $product->data['quantity']: $product->data['quantity'])
            ]);
    }
}
