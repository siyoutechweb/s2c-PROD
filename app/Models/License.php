<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class License extends Model {

    protected $fillable = [];

    protected $dates = [];

    public static $rules = [
        // Validation rules
    ];

    // Relationships
    public function shopOwner()
    {
        return $this->belongsTo(User::class, 'shop_owner_id');
    }
   

}