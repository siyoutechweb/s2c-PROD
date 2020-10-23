<?php namespace App\Http\Controllers\Member;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Member_level;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

/*
SIYOU THECH Tunisia
Author: Habiba Boujmil
ERROR MSG
* 1：parameters missing, in data field indicate whuch parameter is missing
* 2：token expired or forced to logout, take to relogin
* 3：error while saving
* 4: error while deleting

*/

class MembersController extends Controller {

    public function __construct()
    {
        $this->middleware('auth');

    }

    /* Add Member API
     - Necessary Parameters: 'token','store_id','chain_id','first_name','last_name','contact','card_num'
    - optional Parameters: 'gender','email','points','level_id','expiration_date'
       Accessible for : ShopOwner/ ShopManager 
    */

    public function addMembre(Request $request)
    {
        $shop_owner = AuthController::me();
        $store_id=(int) $request->input('store_id');
        $chain_id= $request->input('chain_id');
        if ($request->filled('first_name','last_name','contact','card_num')) 
        {
            $member = new member();
            $member->first_name = $request->input('first_name');
            $member->last_name = $request->input('last_name');
            $member->gender = $request->input('gender');
            $member->contact = $request->input('contact');
            $member->email = $request->input('email');
            $member->card_num = $request->input('card_num');
            $member->points =(float) $request->input('points');
            $member->level_id = (int) $request->input('level_id');
            $member->expiration_date = $request->input('expiration_date'); 
            $member->birthday = $request->input('birthday');
            $member->remarks =$request->input('remarks');
            $member->id_card = $request->input('id_card');
            $member->is_active = $request->input('is_active'); 
            $member->card_barcode = $request->input('card_barcode');   
            $member->adress = $request->input('adress');    
            $member->store_id= $store_id;

            if ($member->save()) {
                $response = array();
                $response['code']=1;
                $response['msg']='';
                $response['data']=array_map('strval',$member->toArray());;
                return response()->json($response);
            }
            $response = array();
            $response['code']=0;
            $response['msg']='3';
            $response['data']='error while saving';
            return response()->json($response);
        }
        $response = array();
        $response['code']=0;
        $response['msg']='1';
        $response['data']='parameters missing, in data field';
        return response()->json($response);   
    }

    /* get shop's Members list API
     - Necessary Parameters: 'token','store_id'
    - optional Parameters:
       Accessible for : ShopOwner/ ShopManager 
    */
    public function getMembersList(Request $request)
    {
        $shop_owner = AuthController::meme();
        //$store_id = $shop_owner->shop()->value('id');
        //$store_id= $request->input('store_id');
        $store_id = $shop_owner->store_id;
        $card_num= $request->input('card_num');
        $first_name= $request->input('first_name');
        $last_name= $request->input('last_name');
        $contact= $request->input('contact');
        $level_id= $request->input('level_id');
        $gender= $request->input('gender');
        $status= $request->input('status');
        $card_id= $request->input('card_id');
        //chineese team
        $start_time = $request->input('start_time');
        $end_time = $request->input('end_time');
        if (!$start_time) {
            //$start_time = Date('Y-m-d 00:00:00', strtotime('0days'));
            $start_time = '';
            
        }
        if (!$end_time) {
            //$end_time = Date('Y-m-d 23:59:59', time());
            $end_time = '';
        }
        $response =Member::with('level')
                            ->where('store_id', $store_id)
                           
                            ->orderBy('id','DESC')
                            ->when($start_time != '', function ($query) use ($start_time) {
                                $query->where('updated_at', '>=',  $start_time);})
                            ->when($end_time != '', function ($query) use ($end_time) {
                                    $query->where('updated_at', '<=',  $end_time);})
                            ->when($card_num != '', function ($query) use ($card_num) {
                                $query->where('card_num',$card_num);})
                            ->when($first_name != '', function ($query) use ($first_name) {
                                $query->where('first_name', 'like', '%' . $first_name . '%');})
                            ->when($last_name != '', function ($query) use ($last_name) {
                                $query->where('last_name', 'like', '%' . $last_name . '%');})
                            ->when($contact != '', function ($query) use ($contact) {
                                $query->where('contact',$contact);})
                            ->when($level_id != '', function ($query) use ($level_id) {
                                $query->where('level_id',$level_id);})
                            ->when($gender != '', function ($query) use ($gender) {
                                $query->where('gender',$gender);})
                            ->when($status != '', function ($query) use ($status) {
                                $query->where('is_active',$status);})
                            ->when($card_id != '', function ($query) use ($card_id) {
                                $query->where('id_card',$card_id);})
                            ->paginate(20)->toArray();
        $response['code']=1;
        $response['msg']='';
        return response()->json($response);
    }
    

    public function getInvalidMembers(Request $request)
    {
        //$shop_owner = AuthController::me();
	$shop_owner = AuthController::meme();

       // $store_id = $shop_owner->shop()->value('id');
	$store_id = $shop_owner->store_id;

        $card_num= $request->input('card_num');
        $first_name= $request->input('first_name');
        $last_name= $request->input('last_name');
        $contact= $request->input('contact');
        $level_id= $request->input('level_id');
        $gender= $request->input('gender');
        $status= $request->input('status');
        $card_id= $request->input('card_id');
        $response =Member::with('level')->where(['store_id'=> $store_id, 'is_active'=>0])
                        ->when($card_num != '', function ($query) use ($card_num) {
                            $query->where('card_num',$card_num);})
                        ->when($first_name != '', function ($query) use ($first_name) {
                            $query->where('first_name', 'like', '%' . $first_name . '%');})
                        ->when($last_name != '', function ($query) use ($last_name) {
                            $query->where('last_name', 'like', '%' . $last_name . '%');})
                        ->when($contact != '', function ($query) use ($contact) {
                            $query->where('contact',$contact);})
                        ->when($level_id != '', function ($query) use ($level_id) {
                            $query->where('level_id',$level_id);})
                        ->when($gender != '', function ($query) use ($gender) {
                            $query->where('gender',$gender);})
                        ->when($status != '', function ($query) use ($status) {
                            $query->where('is_active',$status);})
                        ->when($card_id != '', function ($query) use ($card_id) {
                            $query->where('id_card',$card_id);})
                        ->orderBy('id','DESC')->paginate(20)->toArray();
        $response['code']=1;
        $response['msg']='';
        return response()->json($response);
    }


    public function activateMemberCard(Request $request, $id)
    {
        $shop_owner = AuthController::me();
        $store_id = $shop_owner->shop()->value('id');
        $member = Member::where('store_id',$store_id)->find($id);
        if (!empty($member)) {
            $member->is_active=true;
            $member->save();
            $response['code']=1;
            $response['msg']='';
            $response['data']='member card has been activated';
        }
        else {
            $response['code']=0;
            $response['msg']='';
            $response['data']='member not found';
        }
        
        return response()->json($response);

    }

     /* Generate EA13 barcode for 15 member cards  API
     - Necessary Parameters: 'token','store_id'
    - optional Parameters: 
       Accessible for : ShopOwner
    */
    public function generateMemberCode(Request $request)
    {
        $shop_Owner = AuthController::me();
        $store_id= $request->input('store_id');
        $num_cards=$request->input('num_cards');
        $memberList = Member::where('store_id', $store_id)->pluck('card_num')->toArray();
        $a = 0;
        while ($a < $num_cards) 
        {
            $new_barcode = $this->newBarcode();
            if (!in_array($new_barcode , $memberList)) 
            {
                $barcodesList[] = $new_barcode;
                $memberList [] = $new_barcode;
                $a++;
            }
        }
            $response = array();
            $response['code'] = 1 ;
            $response['msg'] = "";
            $response['data'] =(array) $barcodesList;
            return response()->json($response); 

    }
 
    
    public static function newBarcode()
    {
        $number = rand(pow(10, 7) - 1, pow(10, 8) - 1);
        $barcode = str_split($number);
        $sum=0;
        foreach ($barcode as $key => $num)
        {
            if($key % 2 == 0)  {$sum=$sum+$num;}
            else{ $sum=$sum+$num*3;}
        }

        if(($sum+4) % 10 == 0 ){ array_push($barcode,0);}
        else { array_push($barcode,10-(($sum+2) % 10));}
        array_unshift($barcode, "4","0","0","0");

        $new_barcode = implode('', $barcode);
        return $new_barcode;
    }

    public function updateMember(Request $request,$id) {
        $member =Member::where('id',$id)->first();
        if(!$member) {
            return response()->json(["code"=>0,"msg"=>"member with these credentials not found"]);
        }
        else {
           
            $member->first_name = $request->input('first_name');
            $member->last_name = $request->input('last_name');
            $member->gender = $request->input('gender');
            $member->contact = $request->input('contact');
            $member->email = $request->input('email');
            //$member->card_num = $request->input('card_num');
            $member->points =(float) $request->input('points');
            $member->level_id = (int) $request->input('level_id');
            $member->expiration_date = $request->input('expiration_date'); 
            $member->birthday = $request->input('birthday');
            $member->remarks =$request->input('remarks');
            $member->id_card = $request->input('id_card');
            $member->is_active = $request->input('is_active'); 
            //$member->card_barcode = $request->input('card_barcode');   
            $member->adress = $request->input('adress'); 
            if ($member->save()) {
                $response = array();
                $response['code']=1;
                $response['msg']='';
                $response['data']=array_map('strval',$member->toArray());;
                return response()->json($response);
            }
            $response = array();
            $response['code']=0;
            $response['msg']='3';
            $response['data']='error while saving';
            return response()->json($response);
            //return response()->json(["code"=>1,"msg"=>"meber updated successfully"]);
        }
    }
    public function deleteMember(Request $request,$id) {
        $member =Member::where('id',$id)->first();
        if(!$member) {
            return response()->json(["code"=>0,"msg"=>"member not found"]);
        }
        else {
           // Product::updateDiscountPrice();
            $member->delete();
            return response()->json(["code"=>1,"msg"=>"member deleted successfully"]);
        }
    }
    public function getMemberById(Request $request,$id) {
        $member =Member::where('id',$id)->first();
        if(!$member) {
            return response()->json(["code"=>0,"msg"=>"member not found"]);
        }
        else {
                       $response['code'] = 1;
            $response['msg'] = "success";
            $response['data'] = $member;
            return response()->json($response);
        }
    }

}
