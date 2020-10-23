<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CriteriaBase extends Model {

    protected $table ='criteria_base';

    protected $fillable = [];

    protected $dates = [];

    public static $rules = [
        // Validation rules
    ];
    // Relationships
    
    // public function CriteriaUnit() {
    //     return  $this->hasMany(CriteriaUnit::class);
    //  }
    public function CriteriaUnit()
    {
        return  $this->belongsToMany(CriteriaUnit::class, 'criteria_unit', 'criteria_id', 'unit_id');
    }
 
     // protected $hidden = ['pivot'];
     public function Categories() {
         return  $this->belongsToMany(Category::class,'category_criteria','criteria_id','category_id');
     }

}
