<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\AuthController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Bill;

/*
SIYOU THECH sz
Author: youxianyen
ERROR MSG
* code: 0. fail 1. success
*
*/

class BillController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /* Add Bill API
     - Parameters: 'token','chain_id','company_country', 'company', 'person_tax_code', 'vat_number', 'country', 'province', 'city', 'address', 'zipcode', 'pec', 'code_destination', 'phone', 'fax', 'email'
       Accessible for :ShopOwner
    */
    public function add(Request $request)
    {
        try {
            $data = $request->only(['chain_id', 'company_country', 'company', 'person_tax_code', 'vat_number', 'country', 'province', 'city', 'address', 'zipcode', 'pec', 'code_destination', 'phone', 'fax', 'email']);
            if (!isset($data['person_tax_code']) || !isset($data['vat_number'])) {
                return response()->json(['msg' => 'Missing parameters'], 500);
            }
            if (!isset($data['chain_id'])) {
                return response()->json(['msg' => 'Missing parameters'], 500);
            }
            if (!isset($data['pec']) && !isset($data['code_destination'])) {
                return response()->json(['msg' => 'Missing parameters'], 500);
            }
            $shop_owner = AuthController::me();
            $data['store_id'] = $shop_owner->shop()->value('id');
            Bill::updateOrCreate(['vat_number' => $data['vat_number'], 'person_tax_code' => $data['person_tax_code'],'address'=>$data['address']], $data);

            $response['code'] = 1;
            $response['msg'] = "";
            $response['data'] = 'Bill has been added';
            return response()->json($response, 200);

        } catch (\Exception $e) {
            $response = array();
            $response['code'] = 0;
            $response['msg'] = '3';
            $response['data'] = $e->getMessage();
            return response()->json($response, 500);
        }
    }
    /*billList
    - Parameters: 'token','chain_id','start_date'
   Accessible for :ShopOwner
     * */
    public function billList(Request $request)
    {
        try {
            $data = $request->only(['chain_id', 'start_date']);
            if (!isset($data['chain_id'], $data['start_date'])) {
                return response()->json(['msg' => 'Missing parameters'], 500);
            }
            $shop_owner = AuthController::me();
            $store_id = $shop_owner->shop()->value('id');
            $start_date = $data['start_date'];
            $finish_date = $request->input('finish_date');
            $start_date = Carbon::parse($start_date)->toDateTimeString();
            $finish_date = Carbon::parse($finish_date)->toDateTimeString();
            $rows = $request->get('rows', 20);
            $rs = Bill::where(['chain_id' => $data['chain_id'], 'store_id' => $store_id])
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