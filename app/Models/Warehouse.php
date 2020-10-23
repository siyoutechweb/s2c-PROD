<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model {

    protected $fillable = [];

    protected $dates = [];

    public static $rules = [
        // Validation rules
    ];

    // Relationships

    public function inventories()
    {
        return $this->belongsTo(Inventory::class, 'warehouse_id');
    }
    
    public function chain()
    {
        return $this->belongsTo(Chain::class, 'chain_id');
    }

}
