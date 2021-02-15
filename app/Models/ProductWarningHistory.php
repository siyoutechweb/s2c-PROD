<?php namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class ProductWarningHistory extends Model
{
    protected $fillable = ['store_id', 'chain_id', 'product_id', 'warn_quantity', 'inventory', 'days'];

    protected $dates = [];

    public $timestamps = true;

    public $table = 'product_warning_history';
    // public $primaryKey = 'Ticket_Number';

    public static $rules = [
        // Validation rules
    ];

    // Relationships

    //this function inserts Storelogs details

}