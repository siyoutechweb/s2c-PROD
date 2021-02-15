<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
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
    public static function insertMembers ($data, $store_id){

        foreach ($data as  $value) 
        {
           // $supplier_id= supplier::where('first_name',$value->supplier_name)
                        //  ->orWhere('last_name',$value->supplier_name)->value('id');
           // $category_id= category::where('category_name',$value->category_name)->value('id');
            $arr[] = [
'first_name' => $value->first_name, 
                    'last_name' => $value->last_name,
                    'gender' => $value->gender,
                    'card_num' => $value->card_number,
                    'contact' => $value->contact,
                    'email' => $value->email,
                    'points' => $value->points,
                    'level_id' => $value->level_id,
                    'store_id' => $store_id,
                    'expiration_date' => $value->expiration_date,

		    
                    ];
                    if (!empty($arr)) { DB::table('members')->insert($arr);}
        }
       
       
    }

}
