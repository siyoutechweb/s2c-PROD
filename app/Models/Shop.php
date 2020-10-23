<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Chain;
use App\Models\User;
use App\Models\Member;
use App\Models\Product;
class Shop extends Model {

    protected $fillable = [];

    protected $dates = [];

    public static $rules = [
        // Validation rules
    ];

    // Relationships


    
public function shopOwner()
{
    return $this->belongsTo(User::class);
}

public function chains() {
    return $this->hasMany(Chain::class, 'store_id');
}

public function members() {
    return $this->hasMany(Member::class, 'store_id');
}


  // public function getShopsThroughOrder()
    // {
    //     return $this->hasManyThrough(User::class, Order::class, 'supplier_id', 'id', 'id', 'shop_owner_id');
    // }
    public function Category_id()
    {
        return $this->hasManyThrough( Category::class, Product::class, 'category_id', 'id','id', 'category_id');
    }
}
