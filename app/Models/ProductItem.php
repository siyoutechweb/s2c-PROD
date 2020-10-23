<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductItem extends Model {

    protected $table ='product_items';

    public static $rules = [
        // Validation rules
    ];

    // Relationships
    public function Product() {
        return $this->belongsTo(ProductBase::class, 'product_base_id');
    }

    public function Images() {
        return $this->hasMany(ProductImage::class, 'product_item_id');
    }

    public function CriteriaBase() {
        return $this->belongsToMany(CriteriaBase::class, 'item_criteria', 'product_item_id', 'criteria_id')
        ->withPivot(['criteria_value', 'criteria_unit_id'])
        ->withTimestamps();
    }


}
