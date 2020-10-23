<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model {

    protected $fillable = [];

    protected $dates = [];

    protected $table = ['menu'];

    public static $rules = [
        // Validation rules
    ];

    // Relationships
    public function user_menu()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
