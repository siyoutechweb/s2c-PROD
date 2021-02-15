<?php namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes; 
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\ProductBase;
use App\Models\CriteriaBase;

class Category extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [];

    

    public static $rules = [
        // Validation rules
    ];

    // Relationships

    // public function getParentCategory() {
    //     return $this->belongsTo(Category::class, 'parent_category_id');
    // }

    // public function getChildCategories() {
    //     return $this->hasMany(Category::class, 'parent_category_id');
    // }

    public function products() {
        //edited
        return $this->hasMany(Product::class);
    }

    public function subCategories() {
        return $this->hasMany(Category::class, 'parent_category_id')->orderBy('category_order');
    }

    public function productBase() {
        return $this->hasMany(ProductBase::class);
    }

    public function CriteriaBase() {
        return $this->belongsToMany(CriteriaBase::class,'category_criteria','category_id','criteria_id');
    }

}
