<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model {

    protected $table = 'payment_methods';

    protected $dates = [];

    public static $rules = [
        // Validation rules
    ];

    // Relationships

    public function orders(){
        return $this->hasMany(Order::class);
    }

}
