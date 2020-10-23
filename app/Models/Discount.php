<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Discount extends Model {

    protected $fillable = [];

    protected $dates = [];

    public static $rules = [
        // Validation rules
    ];

    // Relationships

    public function Products() {
        return $this->belongsToMany(Product::class,'product_discount','product_id','discount_id','start_date','finish_date','discount_value1','discount_value2')
        ->withPivot(['start_date','finish_date','discount_value1','discount_value2']);
    }

}
