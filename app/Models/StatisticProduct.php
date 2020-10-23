<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class StatisticProduct extends Model
{

    protected $table='statistic_product';
    protected $fillable=[
        'days','chain_id','product_id','category_id','sales','price','store_id'
    ];

    public function product(){
        return $this->hasOne(Product::class,'id','product_id')->select(['id','product_name']);
    }

    public function category(){
        return $this->hasOne(Category::class,'id','category_id')->select(['id','category_name']);
    }
}

