<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Shop;

class Bill extends Model {

    protected $fillable = ['store_id', 'chain_id', 'company_country', 'company', 'person_tax_code', 'vat_number', 'country', 'province', 'city', 'address', 'zipcode', 'pec', 'code_destination', 'phone', 'fax', 'email'];

    protected $dates = [];

    public static $rules = [
    ];

    // Relationships

}
