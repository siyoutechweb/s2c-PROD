<?php namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
class Order extends Model {

    // protected $fillable = ['id_order','id_product'];
    protected $dates = [];
    // public $connection = 'mysql2';
    // public $table = 'orders';  
    // public $primaryKey = 'Ticket_Number';

    public static $rules = [
        // Validation rules
    ];

    // Relationships
    public function Products() 
    {
        return  $this->belongsToMany(Product::class, 'product_item_order', 'order_id', 'product_id')->withPivot(['order_item_quantity','order_item_amount','order_item_payment_amount'])->withTimestamps();
    }
    // public function items() 
    // {
    //     return  $this->belongsToMany(Product::class, 'product_order', 'order_id', 'product_id')->withPivot(['quantity'])->withTimestamps();
    // }
    public function cachier() 
    {
        return  $this->belongsTo(cachier::class, 'cachier_id');
    }
     public function payment_method(){
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }


    //this function inserts order details
    public static function insertItemOrders ($data){
        //echo $data->product_id;


        foreach ($data as  $value) 
        {
            // $arr[] = ["product_id" => $value->product_id, 
            //         'order_id' => $value->order_id,
            //         'order_item_quantity' => $value->order_item_quantity,
            //         'order_item_amount' => $value->order_item_amount,
            //         'order_item_payment_amount' =>$value->order_item_payment_amount,
            //         // 'tax_rate' => $value->tax_rate,
            //         // 'product_weight' => $value->product_weight,
            //         // 'product_size' => $value->product_size,
            //         // 'product_color' => $value->product_color,
            //         // 'product_quantity' => $value->product_quantity,
            //         // 'supplier_id' => $value->supplier_id,
            //         // 'shop_owner_id' =>$shop_owner_id,
            //         // 'category_id' => $value->category_id,
            //         // 'purchase_order_id' => $preOrder,
            //         ];
        }
       if (!empty($data)) { DB::table('product_item_order')->insert($data);}
    }


}
