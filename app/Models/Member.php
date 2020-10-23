<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Member_level;

class Member extends Model {

    protected $fillable = [];

    protected $dates = [];

    public static $rules = [
        // Validation rules
    ];

    // Relationships

    public function level()
    {
        return $this->belongsTo(Member_level::class);
    }

    public function store()
    {
        return $this->belongsTo(shop::class);
    }

    //function 

    public static function updateMemberPoints($mem_card,$mem_point,$store_id)
    {
        $member = Member::with('level')->where('card_num',$mem_card)->first();
        if(!empty($member))
        {
            $member->points += $mem_point;
            if ($member->points>$member->level->end_point)
            {
                $next_level = Member_level::where('start_point','>',$member->level->end_point)
                                     ->where('store_id',$store_id)->first();
                if (!empty($next_level)) {
                    $member->level_id = $next_level->id;
                }  
            }
            $member->save();
        }
     

    }

}
