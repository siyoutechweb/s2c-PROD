<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class StatisticProduct extends Model
{

    protected $table='statistic_product';
    protected $fillable=[
        'days','chain_id','product_id','category_id','sales','price','store_id','supplier_id'
    ];

    public function product(){
        return $this->hasOne(Product::class,'id','product_id')->select(['products.id','product_name','product_barcode','products.supplier_id','suppliers.first_name','suppliers.last_name'])->leftJoin('suppliers', 'products.supplier_id', '=', 'suppliers.id');
    }

    public function productsQuality()
    {
        return $this->hasOne(Product::class,'id','product_id')->select(['products.id','product_name'])->innerJoin('statistic_product', 'products.id', '=', 'product_id.id');
    }

    public function category(){
        return $this->hasOne(Category::class,'id','category_id')->select(['id','category_name']);
    }

    public function category2(){
        return $this->hasMany(Category::class,'id','category_id')->select(['id','category_name']);
    }

    public function supplier() {
        return $this->hasOne(Supplier::class, 'supplier_id', 'id')->select(['products.product_name','suppliers.id','suppliers.first_name','suppliers.last_name'])->leftJoin('products', 'statistic_product.product_id', '=', 'products.id')->leftJoin('suppliers', 'products.supplier_id', '=', 'suppliers.id');;
    }

}