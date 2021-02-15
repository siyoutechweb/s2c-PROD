<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class Supplier extends Model {

    protected $fillable = [];

    protected $dates = [];

    public static $rules = [
        // Validation rules
    ];

    // Relationships

    public function shop_owner()
    {
        return $this->belongsToMany(User::class,'shop_supplier','supplier_id','shop_id')->withTimestamps();
    }
    public function shopOwner()
    {
        return $this->belongsTo(User::class,'shop_owner_id');
    }

}
