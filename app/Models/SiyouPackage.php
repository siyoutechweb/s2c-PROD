<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class SiyouPackage extends Model {

    protected $fillable = [];

    protected $dates = [];
    protected $table='siyou_packages';

    public static $rules = [
        // Validation rules
    ];

    // Relationships
    public function products()
    {
        return $this->belongsToMany(SiyouProduct::class,'siyou_products_packages','package_id','product_id')->with('siyouProductImages');
    }



}