<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Shop;

class Operatorlog extends Model {

    protected $fillable = ['operator_id', 'shop_owner_id', 'store_id', 'op_description'];

    protected $dates = [];

    public static $rules = [
        // Validation rules
    ];

    // Relationships
    public function shop()
    {
        return $this->belongsTo(Shop::class, 'chain_id')->select(['id', 'store_name']);
    }

    public function shopOwner() {
        return $this->belongsTo(User::class, 'shop_owner_id');
    }
}
