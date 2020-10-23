<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

//  Siyou Technology Tunisie 
// Author: Habiba Boujmil

class AccountsController extends Controller {

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    // get the list of inactive shopOwner's account
    public function getInactiveAccount(Request $request)
    {
        $InactiveAccount = user::where('activated_Account', 0)->get();
        return response()->json($InactiveAccount, 200);
    }
    // get the list of shopOwners
    public function getactiveAccount(Request $request)
    {
        $activeAccount = user::where('activated_Account', 0)->get();
            
        return response()->json($activeAccount, 200);
    }

    //  API which allows the admin to activate shopOwner's account
    public function activeAccount(Request $request)
    {
        $user_id= $request->input('user_id');
        $shop=user::find($user_id);
        $shop->activated_account=1;
        $shop->save();
        return response()->json(['msg' => 'Shop Owner account has been activated'], 200);
    }


     //  API which allows the admin to deactivate shopOwner's account
    public function desactiveAccount(Request $request)
    {
        $user_id= $request->input('user_id');
        $shop=user::find($user_id);
        $shop->activated_account = 0;
        $shop->save();
        return response()->json(['msg' => 'Shop Owner account has been desactivated'], 200);
    }
    
}
