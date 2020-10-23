<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Product_discount extends Model {

    public  $timestamps=true;
    protected $table='product_discount';
    protected $fillable = [

        'discount_id','product_id','discount_value1','discount_value2','start_date','finish_date'
    ];

    protected $dates = [

    ];

    public static $rules = [
        // Validation rules
    ];

    protected $finish_date = '';

    public function getFinish_DateAttribute(){
//        $this->fillable['expire_date'] = '2020-09-04';
    }

    public function getExpire_DateAttribute(){
    }
}