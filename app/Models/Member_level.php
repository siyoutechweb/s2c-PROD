<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member_level extends Model {

    protected $fillable = [];

    protected $dates = [];

    public static $rules = [
        // Validation rules
    ];

    // Relationships
    public function Member()
    {
        return $this->hasMany(Member::class, 'level_id');
    }

}
