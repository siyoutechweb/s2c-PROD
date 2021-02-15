<?php namespace App\Http\Controllers\Store;

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ShopChainFunction;


/**
 * Class ShopChainFunctionFunctionController
 * @package App\Http\Controllers\Store
 */

class ShopChainFunctionController extends Controller {

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * get getShopChainFunction API
     * Parameters:'token','store_id','no_fiscal_invoice','upload_non_fiscal','quick_scan_collect','excel_copy_multi_chain'
     * Accessible for : ShopOwner
     */
    public function getShopChainFunction(Request $request)
    {
        try {
            $data = $request->only(['chain_id']);
            if (!isset($data['chain_id'])) {
                return response()->json(['msg' => 'Missing parameter'], 500);
            }
            $shop_owner = AuthController::me();
            $store_id = $shop_owner->shop()->value('id');
            $ShopChainFunction = ShopChainFunction::where(['store_id' => $store_id,'chain_id' => $data['chain_id']])->get()->toArray();
            $response = array();
            $response['code']=1;
            $response['msg']="success";
            $response['data']=$ShopChainFunction;
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $response = array();
            $response['code'] = 0;
            $response['msg'] = 'fail';
            $response['data'] = $e->getMessage();
            return response()->json($response, 500);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * updateOrCreate ShopChainFunction API
     * Parameters:'token','chain_id','no_fiscal_invoice','upload_non_fiscal','quick_scan_collect','excel_copy_multi_chain'
     * Accessible for : ShopOwner
     * store_id??????
     */
    public function add(Request $request)
    {
        try {
                $data = $request->only(['chain_id', 'no_fiscal_invoice', 'upload_non_fiscal', 'quick_scan_collect', 'excel_copy_multi_chain']);
            if (!isset($data['chain_id'], $data['no_fiscal_invoice'], $data['upload_non_fiscal'], $data['quick_scan_collect'], $data['excel_copy_multi_chain'])) {
                return response()->json(['msg' => 'Missing parameters'], 500);
            }
            $shop_owner = AuthController::me();
            $store_id = $shop_owner->shop()->value('id');
            $data['store_id'] = $store_id;

            ShopChainFunction::updateOrCreate(
                ['store_id' => $store_id, 'chain_id' => $data['chain_id']],
                $data
            );
                $response['code'] = 0;
                $response['msg'] = "";
                $response['data'] = 'ShopChainFunction has been added';
                return response()->json($response, 200);

        } catch (\Exception $e) {
            $response = array();
            $response['code'] = 1;
            $response['msg'] = '3';
            $response['data'] = $e->getMessage();
            return response()->json($response, 500);
        }
    }



}