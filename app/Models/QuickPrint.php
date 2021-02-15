<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Chain;
use App\Models\Product;


class QuickPrint extends Model
{
    protected $table = 'quick_print';
    protected $fillable = [];

    protected $dates = [];

    public static $rules = [
        // Validation rules
    ];

    // Relationships

    public function products() {
        return $this->hasMany(Product::class, 'product_barcode','product_barcode')->select(['id','product_name','product_barcode','unit_price','cost_price','discount_price','member_price','chain_id'])->with('ProductDiscount');
    }
    public function products1() {
        return $this->hasMany(Product::class, 'product_barcode','product_barcode')->select(['id','product_name','product_barcode','unit_price','cost_price','discount_price','member_price','chain_id'])->with('ProductDiscount');
    }

    public function chains() {
        return $this->hasOne(Chain::class,'id', 'chain_id')->select(['id','store_id','chain_name']);
    }
}