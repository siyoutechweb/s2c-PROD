<?php namespace App\Http\Controllers\Store;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Shop;
use App\Models\Chain;
use Illuminate\Support\Facades\DB;
use App\Models\Cash_register;

class CashRegistersController extends Controller {

    public function __construct()
    {
        $this->middleware('auth:api',['except' => ['getCashiersList','getCashiersPassword']]);
    }
    
    public function addCashRegister(Request $request)
    {
        $user = AuthController::me();
        $chain_id = $request->input('chain_id');
        $chain = Chain::find($chain_id);
        $references = $this->cacherRefrence($chain);
        for ($i=0; $i < $chain->cash_registers ; $i++) { 
            $cash_regirster = new Cash_register();
            $cash_regirster->cassa_id = $references[$i];
            $cash_regirster->chain_id = $chain_id;
            $cash_regirster->save();
        }
        return response()->json(['msg' => 'Cash registers has been added'], 200);
       
    }

    private static function cacherRefrence($chain)
    {
        for ($i=0; $i < $chain->cash_registers ; $i++) { 
            $reference[] = $chain->chain_name[0].$chain->chain_name[1].$chain->chain_name[2].rand (10,99) ;
        }
        return $reference;
    }

    public function getCashRegisterList(Request $request)
    {
        $user = AuthController::me();
        $chain_id = $request->input('chain_id');
        $chain = Cash_register::where('chain_id',$chain_id)->pluck('reference');
        return response()->json(['msg' => 'Cash registers has been added'], 200);
       
    }

    public function validateCashRegister(Request $request)
    {
        $user = AuthController::me();
        $chain_id = $request->input('chain_id');
        $cassa_id = $request->input('cassa_id');
        $token = $request->input('token');
        $Cash_register = Cash_register::where('chain_id',$chain_id)
                                ->where('cassa_id',$cassa_id)
                                ->get();                 
        if (!$Cash_register->isEmpty()) {
            if ($user->token == $token) {
                $Cash_register[0]->token = $token;
                $Cash_register[0]->save();
                $user->token=$token;
                $user->save();
                $response['code']= 1;
                $response['msg']='';
                $response['data']='Success';
                return response()->json($response, 200);
            }
            $response = array();
            $response['code']= 0;
            $response['msg']='2';
            $response['data']='Token expired. You need to relogin';
            return response()->json($response);
        }
        $response['code']= 0;
        $response['msg']='2';
        $response['data']='Cash Register reference does not exist';
        return response()->json($response,404);
       
    }

    public function getCashiersList(Request $request)
    {
        $chain_id = $request->input('chain_id');
        $cachiers = DB::table('cachiers')->where('chain_id','=',$chain_id)->get(['id','first_name','last_name','password','is_manager']);
        $response['code']= 1;
        $response['msg']='';
        $response['data']=$cachiers;
        return response()->json($response,200);
    }

    public function getCashiersPassword(Request $request)
    {
        $chain_id = $request->input('chain_id');
        $cachiers = DB::table('cachiers')->where('chain_id','=',$chain_id)->pluck('password');
        $response['code']= 1;
        $response['msg']='';
        $response['data']=$cachiers;
        return response()->json($response,200);
    }




}
