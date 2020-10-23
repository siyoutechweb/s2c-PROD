<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model; 

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\Discount;
use App\Models\Chain;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Shop;
use App\Models\Product_discount;
use App\Models\QuickPrint;

class Product extends Model {

 

   protected $fillable = [ 'product_name','product_barcode','product_description','product_image','unit_price','cost_price','discount_price','member_price','member_point','tax_rate','product_weight','product_size','product_color','supplier_id','product_quantity','warn_quantity','expired_date','category_id','shop_owner_id','shop_id','chain_id','created_at','updated_at','deleted_at'];
    protected $dates = [];

    public static $rules = [
        // Validation rules
    ];

    // Relationships
    public function category() {
        //edited by zied
        return $this->belongsTo(Category::class,'category_id');
    }
    public function supplier() {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
    public function shopOwner() {
        return $this->belongsTo(User::class, 'shop_owner_id');
    }
    public function chain()
    {
        return $this->belongsTo(Chain::class,'chain_id');
    }
    public function shop()
    {
        return $this->belongsTo(Shop::class,'shop_id');
    }



    public function Discount() {
        return $this->belongsToMany(Discount::class, 'product_discount','product_id','discount_id')
        ->withPivot(['discount_value1','discount_value2','start_date','finish_date']);
    }
    public function QuickPrint()
    {
        return $this->belongsTo(Product::class,'product_barcode');
    }
    public function ProductDiscount()
    {
        return $this->hasOne(Product_discount::class,'product_id','id')->select(['id','discount_id','product_id','discount_value1','discount_value2','start_date','finish_date']);
    }
    public static function updateDiscountPrice()
    {
        $time= carbon::now();
        $productsId=DB::table('product_discount')->where('finish_date','<' ,$time)->pluck('product_id'); 
        //DB::table('product_discount')->where('finish_date','<' ,$time)->delete();
        Product::whereIn('id',$productsId)->update(['discount_price' => null]);
    }


    
    public static function insertProducts ($data, $chain_id, $store_id, $shop_owner_id){

        foreach ($data as  $value) 
        {
            $supplier_id= supplier::where('first_name',$value->supplier_name)
                          ->orWhere('last_name',$value->supplier_name)->value('id');
            $category_id= category::where('category_name',$value->category_name)->value('id');
            $arr[] = ['product_name' => $value->product_name, 
                    'product_barcode' => $value->product_barcode,
                    'product_description' => $value->product_description,
                    'product_image' => $value->product_image,
                    'cost_price' => (float)$value->cost_price,
                    'unit_price' => (float)$value->unit_price,
                    'member_price' => (float)$value->member_price,
                    'tax_rate' => $value->tax_rate,
                    'product_weight' => $value->product_weight,
                    'product_size' => $value->product_size,
                    'product_color' => $value->product_color,
                    'product_quantity' => $value->product_quantity,
                    'supplier_id' => $supplier_id,
                    'shop_owner_id' =>$shop_owner_id,
                    'chain_id' =>$chain_id,
                    'shop_id' =>$store_id,
                    'category_id' => $category_id,
                    ];
        }
       if (!empty($arr)) { DB::table('products')->insert($arr);}
    }
}
