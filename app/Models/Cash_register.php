<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cash_register extends Model {

    protected $fillable = [];

    protected $dates = [];

    public static $rules = [
        // Validation rules
    ];

    // Relationships
    public function chain()
    {
        return $this->belongsTo(chain::class, 'chain_id');
    }
}
