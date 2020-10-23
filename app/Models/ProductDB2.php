<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Order;

class ProductDB2 extends Model {

    protected $fillable = [];
    public $connection = 'mysql2';
    public $table = 'products';                                                                                                                                                                                                                                                                                                                                      
    public $primaryKey = 'Product_Number';
    protected $dates = [];

    public static $rules = [
        // Validation rules
    ];

    // Relationships
    public function Orders() 
    {
        return $this->belongsToMany(order::class, 'orders_products','id_order','id_product');
    }

}
