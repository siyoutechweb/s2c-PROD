<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Shop;
use App\Models\User;
use App\Models\Warehouse;

class Chain extends Model {

    protected $fillable = [];

    protected $dates = [];

    public static $rules = [
        // Validation rules
    ];

    // Relationships
    public function shop()
    {
        return $this->belongsTo(Shop::class, 'store_id');
    }
    
    public function Manager()
    {
        return $this->belongsTo(User::class);
    }
    public function Manager2()
    {
        return $this->belongsTo(User::class,'manager2_id');
    }
    public function Manager3()
    {
        return $this->belongsTo(User::class,'manager3_id');
    }
    public function Cachiers()
    {
        return $this->hasMany(User::class);
    }
    public function warehouse()
    {
        return $this->hasMany(Warehouse::class);
    }


}
