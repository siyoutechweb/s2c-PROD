<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class SiyouProduct extends Model {

    protected $fillable = [];

    protected $dates = [];
    protected $table='siyou_products';

    public static $rules = [
        // Validation rules
    ];

    // Relationships

    public function siyouProductImages()
    {
        return $this->belongsToMany(SiyouProductImage::class,'siyou_product_id')->withTimestamps();
    }

}
