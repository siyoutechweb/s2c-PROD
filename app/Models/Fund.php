<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fund extends Model {

    protected $fillable = [];

    protected $dates = [];

    public static $rules = [
        // Validation rules
    ];

    // Relationships
    public function paymentmethods(){
        return $this->BelongsTo(PaymentMethod::class, 'payment_method_id');
    }

}
