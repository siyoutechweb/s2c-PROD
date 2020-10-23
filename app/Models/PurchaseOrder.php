<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Events\PurchaseOrderEvent;
use App\Models\PurchaseProduct;

class PurchaseOrder extends Model {

    protected $table ='purchase_orders';

    protected $fillable = [];

    protected $dates = [];

    public static $rules = [
        // Validation rules
    ];

    // Relationships
    public function purchaseProduct()
    {
        return $this->hasMany(PurchaseProduct::class, 'purchase_order_id');
    }

    public static function newOrder($request,$shop_owner_id){
        $data = [  'order_ref' => $request->input('order_ref'), 
                    'order_date' => $request->input('order_date'),
                    'required_date' => $request->input('required_date'),
                    'shipping_date' => $request->input('shipping_date'),
                    'shipping_type' => $request->input('shipping_type'),
                    'shipping_price' => $request->input('shipping_price'),
                    'shipping_adresse' => $request->input('shipping_adresse'),
                    'shipping_country' => $request->input('shipping_country'),
                    'order_price' => $request->input('order_price'),
                    'order_weight' => $request->input('order_weight'),
                    'shop_owner_id' => $shop_owner_id,
                    'statut_id' =>$request->input('statut_id'),
                    'supplier_id' =>$request->input('supplier_id'),
                    ];
        $preOrder= DB::table('purchase_orders')->insertGetId($data);
        //chineese team
        //event(new PurchaseOrderEvent($preOrder));
        return $preOrder;
    }
    
}
