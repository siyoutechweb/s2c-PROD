<?php namespace App\Http\Controllers\Products;
/*
SIYOU THECH Tunisia
Author: Habiba Boujmil
ERROR MSG
* 1：parameters missing, in data field indicate whuch parameter is missing
* 2：token expired or forced to logout, take to relogin
* 3：error while saving
* 4: error while deleting
Accessible for : ShopOwner / ShopManager
*/

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Products\GetProductsController;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class ProductsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function uploadProducts(Request $request)
    {
        $shop_owner = AuthController::me();
        $store_id = $shop_owner->shop()->value('id');
        $chain_id = $request->input('chain_id');
        if ($request->hasFile('products')) {
            $path = $request->file('products')->getRealPath();
            $data = Excel::load($path)->get();
            if ($data->count()) {
                $products = Product::insertProducts($data, $chain_id, $store_id, $shop_owner->id);
                $response['code'] = 1;
                $response['msg'] = '';
                $response['data'] = "data has been saved";
                return response()->json($response);
            }
            $response['code'] = 0;
            $response['msg'] = '3';
            $response['data'] = 'error while saving';
            return response()->json($response);
        }
    }


    //chinese team
    public function batchAddProduct(Request $request)
    {
        $response['code'] = 0;
        $response['msg'] = '';
        $response['data'] = "order has been added !!";
        $shop_owner = AuthController::me();
        $store_id = $shop_owner->shop()->value('id');
        try {
            $post = $request->post();
            if (!isset($post['store_id'], $post['chain_id'], $post['products'])) {
                throw new \Exception('Missing parameter');
            }

            if ($store_id != $post['store_id']) {
                $response['code'] = 0;
                $response['msg'] = 'store_id not matched';
                $response['data'] = '';
                return response()->json($response);
            }
            //echo 

            $product = json_decode($post['products'], 1);
            if (json_last_error()) {
                throw new \Exception(json_last_error_msg());
            }
            if (!count($product)) {
                throw new \Exception('no more product array');
            }
            foreach ($product as $v) {
//                $prod = Product::where(['chain_id' => $post['chain_id'], 'product_barcode' => $v['item_barcode'],'store_id'=>$post['store_id']])->first();
//
//                if ($prod) {
//                    return response()->json(['code' => 0, 'msg' => 'this barcode already exists in this chain']);
//                }
                $where = ['chain_id' => $post['chain_id'], 'product_barcode' => $v['item_barcode'], 'shop_id' => $post['store_id']];
                $unit_price = sprintf('%.2f', $v['product_unit_price']);
                $tax_rate = isset($v['tax_rate']) ? $v['tax_rate'] : 0;
                $tmp = Product::where($where)->first();
                if ($tmp) {
                    $qty = $tmp->product_quantity;
                } else {
                    $qty = 0;
                }
                $v['product_quantity'] += $qty;

                $prod = Product::updateOrCreate($where,
                    [
                        'product_name' => $v['product_name'],// $v['product_name'],
                        'product_barcode' => $v['item_barcode'],
                        'product_description' => '',
                        'unit_price' => $unit_price,
                        'cost_price' => sprintf('%.2f', $v['product_cost_price']),
                        'item_vip_price' => isset($v['item_vip_price']) && $v['item_vip_price'] ? $v['item_vip_price'] : $unit_price,
                        'member_point' => isset($v['member_point']) && $v['member_point'] ? sprintf('.2f', $v['member_point']) : 0,
                        'tax_rate' => $tax_rate,
                        'product_quantity' => $v['product_quantity'],
                        'warn_quantity' => 0,
                        'product_weight' => 0,
                        'product_size' => 0,
                        'product_color' => 0,
                        'supplier_id' => $v['product_supplier_id'],
                        'category_id' => $v['store_product_cat_name'],
                        'shop_owner_id' => $shop_owner->id,
                        'shop_id' => $post['store_id'],
                        'chain_id' => $post['chain_id']

                    ]
                //$product->attributesToArray()
                );
                //var_dump($prod);
            }


            $response['code'] = 0;
            $response['msg'] = '';
            $response['data'] = "product has been added !!";
            return response()->json($response);

        } catch (\Exception $e) {
            $response['data'] = $e->getMessage();

            return response()->json($response);
        }

    }


    /* Add new Product API
     - Necessary Parameters: 'token','store_id','chain_id','product_name','barcode','unit_price','tax_rate','product_quantity'
    - optional Parameters: 'product_weight','cost_price','member_price','member_point','warn_quantity'
                            'product_size','product_color','supplier_id','category_id','product_image'
    */
    public function addProduct(Request $request)
    {

        $shop_owner = AuthController::me();
        //echo  $shop_owner;

        // $input = $request->store_id;
        // echo $input;
        $shop_id = $request->input('store_id');
        //echo $shop_id.'shop_id';
        $chain_id = $request->input('chain_id');
        $img_name = '';
        $img_url = "https://media-exp1.licdn.com/dms/image/C4D0BAQGjCiqglI2oUw/company-logo_200_200/0?e=2159024400&v=beta&t=Tj739azJkBXu_C1ZU8s9aBaSX1Kamz6igqn7he2Prms";
        if ($request->hasFile('product_image')) {
            $path = $request->file('product_image')->store('products', 'google');
            $img_url = Storage::url($path);
            $img_name = basename($path);
        }

        if ($request->filled('product_name', 'barcode', 'unit_price', 'tax_rate', 'product_quantity')) {
            $barcode = $request->input('barcode');
            $product = Product::updateOrCreate(['product_barcode' => $barcode, 'shop_id' => $shop_id, 'chain_id' => $chain_id], [
                'product_name' => $request->input('product_name'),
                'product_barcode' => $request->input('barcode'),
                'product_description' => $request->input('product_description'),
                'unit_price' => (float)$request->input('unit_price'),
                'cost_price' => (float)$request->input('cost_price'),
                'member_price' => empty($request->input('member_price')) ? $request->input('unit_price') : (float)$request->input('member_price'),
                'member_point' => (float)$request->input('member_point'),
                'tax_rate' => ((float)$request->input('tax_rate')),
                'range_id' => $request->input('range_id'),
                'product_quantity' => (int)$request->input('product_quantity'),
                'warn_quantity' => (int)$request->input('warn_quantity'),
                'product_weight' => (float)$request->input('product_weight'),
                'product_size' => (float)$request->input('product_size'),
                'product_color' => (float)$request->input('product_color'),
                'supplier_id' => $request->input('supplier_id'),
                'category_id' => (int)$request->input('category_id'),
                'shop_owner_id' => $shop_owner->id,
                'shop_id' => $shop_id,
                'chain_id' => $chain_id,
                'img_url' => $img_url,
                'img_name' => $img_name]);
            $response = array();
            $response['code'] = 1;
            $response['msg'] = '';
            $response['data'] = array_map('strval', $product->toArray());
            return response()->json($response);

        }
        $response = array();
        $response['code'] = 0;
        $response['msg'] = '1';
        $response['data'] = 'parameters missing, in data field';
        return response()->json($response);
    }


    /* Update existing Product API
     - Necessary Parameters: 'token','store_id','chain_id','product_name','barcode','unit_price','tax_rate','product_quantity'
     - optional Parameters: 'product_weight','cost_price','member_price','member_point','warn_quantity'
                            'product_size','product_color','supplier_id','category_id','product_image'
    */
    public function updateProduct(Request $request)
    {

        $input = $request->input();

        $shop_owner = AuthController::me();
        //echo $shop_owner;
        $shop_id = $request->input('store_id');

        $chain_id = $request->input('chain_id');

        $barcode = $request->input('barcode');

        if ($request->filled('product_name', 'barcode', 'unit_price', 'product_quantity')) {

            $product = Product::where(['product_barcode' => $barcode, 'shop_id' => $shop_id, 'chain_id' => $chain_id])->first();


            $product->product_name = $request->input('product_name');
            $product->product_barcode = (int)$request->input('barcode');
            // $product->product_description = $request->input('product_description');
            $product->unit_price = (float)$request->input('unit_price');
            $product->cost_price = (float)$request->input('cost_price');
            if (empty($request->input('member_price'))) {
                $product->member_price = $product->unit_price;
            } else $product->member_price = (float)$request->input('member_price');
            $product->member_point = (float)$request->input('member_point');
            $product->tax_rate = (float)$request->input('tax_rate');
            $product->product_quantity = (int)$request->input('product_quantity');
            $product->warn_quantity = (int)$request->input('warn_quantity');
            $product->product_weight = (float)$request->input('product_weight');
            $product->product_size = (float)$request->input('product_size');
            $product->product_color = (float)$request->input('product_color');
            $product->supplier_id = $request->input('supplier_id');
            $product->category_id = (int)$request->input('category_id');
            $product->range_id = $request->input('range_id');

            $product->shop_owner_id = $shop_owner->id;
            $product->shop_id = $shop_id;
            $product->chain_id = $chain_id;
            if ($request->hasFile('product_image')) {
                $path = $request->file('product_image')->store('products', 'google');
                $fileUrl = Storage::url($path);
                //$fileUrl = Storage::url($path);
                $product->img_url = $fileUrl;
                $product->img_name = basename($path);

            }
            //else $product->product_image=$product->product_image;

            $product->save();
            if ($product->save()) {
                $response = array();
                $response['code'] = 1;
                $response['msg'] = '';
                $response['data'] = array_map('strval', $product->toArray());
                return response()->json($response);
            }
            $response = array();
            $response['code'] = 0;
            $response['msg'] = '3';
            $response['data'] = 'error while saving';
            return response()->json($response);
        }
        $response = array();
        $response['code'] = 0;
        $response['msg'] = '1';
        $response['data'] = 'parameters missing, in data field';
        return response()->json($response);
    }


    /* delete existing Product API
    - Necessary Parameters: 'token','store_id','chain_id','barcode'
    - optional Parameters:
   */
    public function deleteProduct(Request $request)
    {
        $barcode = $request->input('barcode');
        $store_id = $request->input('store_id');
        $chain_id = $request->input('chain_id');
        $product = Product::where([["product_barcode", $barcode], ["chain_id", $chain_id]]);

        if ($product->delete()) {
            $response = array();
            $response['code'] = 1;
            $response['msg'] = "";
            $response['data'] = 'Product has been removed';
            return response()->json($response);
        }
        $response = array();
        $response['code'] = 0;
        $response['msg'] = "4";
        $response['data'] = 'Error while deleting';
        return response()->json($response);
    }

    public function quickScanForPrint(Request $request)
    {
        $barcode = $request->input('barcode');
        $chain_id = $request->input('chain_id');
        $quantity = $request->input('quantity');
        $product = product::where(['product_barcode' => $barcode, 'chain_id' => $chain_id])->exists();
        if ($product) {
            DB::table('quick_print')->insert(
                ["product_barcode" => $barcode,
                    "chain_id" => $chain_id, "created_at" => Carbon::now(),
                    "updated_at" => Carbon::now(), "quantity" => $quantity]);

            $response['code'] = 1;
            $response['msg'] = "";
            $response['data'] = 'data has been saved !!';
        } else {
            $response['code'] = 0;
            $response['msg'] = "";
            $response['data'] = 'product not found !!';
        }
        return response()->json($response);

    }

    public function quickPrint(Request $request)
    {
        $chain_id = $request->input('chain_id');
        $barcodes = DB::table('quick_print')->where("chain_id", $chain_id)->pluck('product_barcode');
        $products = array();
        foreach ($barcodes as $key => $barcode) {
            $product = Product::where('product_barcode', $barcode)->first();
            if (isset($product)) {
                $products[] = $product;
            }
            //DB::table('quick_print')->where('product_barcode',$barcode)->delete();
        }

        $response['code'] = 1;
        $response['msg'] = "";
        $response['data'] = $products;
        return response()->json($response);

    }

    public function quickScanForDiscount(Request $request)
    {
        $barcode = $request->input('barcode');
        $chain_id = $request->input('chain_id');
        $product = product::where(['product_barcode' => $barcode, 'chain_id' => $chain_id])->exists();
        if ($product) {
            DB::table('quick_discount')->insert(
                ["product_barcode" => $barcode,
                    "chain_id" => $chain_id,]);

            $response['code'] = 1;
            $response['msg'] = "";
            $response['data'] = 'data has been saved !!';
        } else {
            $response['code'] = 0;
            $response['msg'] = "";
            $response['data'] = 'product not found !!';
        }

        return response()->json($response);

    }

    public function quickDiscount(Request $request)
    {
        $chain_id = $request->input('chain_id');
        $barcodes = DB::table('quick_discount')->where("chain_id", $chain_id)->pluck('product_barcode');
        $products = array();
        foreach ($barcodes as $key => $barcode) {
            $products[] = Product::where('product_barcode', $barcode)->first();
            DB::table('quick_discount')->where('product_barcode', $barcode)->delete();
        }

        $response['code'] = 1;
        $response['msg'] = "";
        $response['data'] = $products;
        return response()->json($response);

    }

    public function getProduct(Request $request, $id)
    {
        $shop_owner = AuthController::me();
        $product = Product::where(['shop_owner_id' => $shop_owner->id])->find($id);
        $response['code'] = 1;
        $response['msg'] = "";
        $response['data'] = $product;
        return response()->json($response);
    }

    public function quickPrintDelete(Request $request)
    {
        $chain_id = $request->input('chain_id');
        //$id = $request->input('id');

        $string = $request->input('id');
        $ids = array_values(preg_split("/\,/", $string));
        foreach ($ids as $id) {
            //echo $id;
            if (DB::table('quick_print')->where('id', $id)->where('chain_id', $chain_id)->delete()) {


                $response['code'] = 1;
                $response['msg'] = "product print deleted successfully";
                //return response()->json($response);

            } else {
                $response['code'] = 1;
                $response['msg'] = "error while deleting";
                return response()->json($response);
            }
        }

        return response()->json($response);


    }
}


