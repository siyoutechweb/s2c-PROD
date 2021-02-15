<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Operatorlog;
use App\Models\StorehouseOperator;
use function foo\func;


/*
SIYOU THECH sz
Author: youxianyen
ERROR MSG
* 1
*/

class LogsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /* Add Operatorlog API
     - Parameters: 'token', 'chain_id', 'op_cashier_name','op_description', 'addtime'
       Accessible for : ShopOwner
    */
    public function add(Request $request)
    {
        try {
            $data = $request->only(['chain_id', 'op_cashier_name', 'op_description', 'addtime', 'cash_number']);
            if (!isset($data['chain_id'], $data['addtime'], $data['op_cashier_name'], $data['op_description']) || !is_numeric($data['cash_number'])) {
                return response()->json(['msg' => 'Missing parameter'], 500);
            }
            $shop_owner = AuthController::me();
            $chain_id = $shop_owner->shop()->value('id');
            $Operatorlog = new Operatorlog();
            $Operatorlog->chain_id = $chain_id;
            $Operatorlog->op_cashier_name = $data['op_cashier_name'];
            $Operatorlog->operator_id = 0;
            $Operatorlog->op_description = $data['op_description'];
            $Operatorlog->shop_owner_id = $shop_owner->id;
            $Operatorlog->addtime = $data['addtime'];
            $Operatorlog->cash_number = $data['cash_number'];

            if ($Operatorlog->save()) {
                $response['code'] = 0;
                $response['msg'] = "";
                $response['data'] = 'Operatorlog has been added';
                return response()->json($response, 200);
            }
        } catch (\Exception $e) {
            $response = array();
            $response['code'] = 1;
            $response['msg'] = '3';
            $response['data'] = $e->getMessage();
            return response()->json($response, 500);
        }

    }

    /* get Operatorlog to chain API
    - Parameters: 'token','store_id', 'start_date', 'finish_date'
      Accessible for : ShopOwner
   */
    public function getOperatorlog(Request $request)
    {
        try {
            $data = $request->only(['chain_id', 'start_date', 'finish_date']);
            if (!isset($data['chain_id'], $data['start_date'])) {
                return response()->json(['msg' => 'Missing parameter'], 500);
            }

            $chain_id = $request->input('chain_id');
            $start_date = $data['start_date'];
            $finish_date = $request->input('finish_date');

            $rows = $request->get('rows', 20);
            $rs = Operatorlog::with(['shop'])
                ->where(['chain_id' => $chain_id])
                ->when($start_date != '', function ($query) use ($start_date) {
                    $query->where('created_at', '>=', $start_date);
                })
                ->when($finish_date != '', function ($query) use ($finish_date) {
                    $query->where('created_at', '<=', $finish_date);
                })
                ->paginate($rows);
            return response()->json(collect($rs)->toArray());
        } catch (\Exception $e) {
            return response()->json(['msg' => $e->getMessage()], 500);
        }
    }

}
