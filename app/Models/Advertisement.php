<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Advertisement extends Model {

    protected $fillable = [];

    protected $dates = [];

    public static $rules = [
        // Validation rules
    ];

    // Relationships
    public function chain()
    {
        return $this->belongsTo(Chain::class, 'chain_id');
    }
   

}