<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuickPurchaseOrder extends Model {
    protected $table = 'quick_purchase_orders';
    protected $fillable = [];

    protected $dates = [];

    public static $rules = [
        // Validation rules
    ];

    // Relationships
      public function products()
    {
        return $this->hasMany(PurchaseProduct::class, 'quick_order_id');
    }
	    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
      public function chain()
    {
        return $this->belongsTo(Chain::class, 'chain_id');
    }

    

}