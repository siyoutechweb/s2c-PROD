<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopFund extends Model {
    protected $table = 'funds';
    protected $fillable = [];

    protected $dates = [];

    public static $rules = [
        // Validation rules
    ];

    // Relationships
    public function supplier() {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
   
}