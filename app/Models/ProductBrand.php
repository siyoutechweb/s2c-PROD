<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductBrand extends Model {

    protected $table ='product_brands';

    public static $rules = [
        // Validation rules
    ];

    // Relationships
    public function Products() {
        return $this->hasMany(ProductBase::class, 'brand_id');
    }

}
