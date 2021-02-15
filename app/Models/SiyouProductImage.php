<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class SiyouProductImage extends Model {

    protected $fillable = [];

    protected $dates = [];
    protected $table='siyou_products_images';

    public static $rules = [
        // Validation rules
    ];

    // Relationships

    // public function shop_owner()
    // {
    //     return $this->belongsToMany(User::class,'shop_supplier','supplier_id','shop_id')->withTimestamps();
    // }

}
