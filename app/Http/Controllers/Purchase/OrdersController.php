<?php namespace App\Http\Controllers\Purchase;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\PurchaseProduct;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use mysql_xdevapi\Exception;
class OrdersController extends Controller {
    public function __construct()
    {
        $this->middleware('auth:api');
    }


    public function getPreOrder(Request $request)
    {
        $shop_owner= AuthController::me();
        $preOrderList = $shop_owner->purchase_orders()->with(['purchaseProduct'
                            =>function ($q){$q->with('category:id,category_name')->get();
                            }])->get();
        $response['code']=1;
        $response['msg']='';
        $response['data']= $preOrderList;
        return response()->json($response);
    }

    //chineese team
    public function preOrderCreate(Request $request)
    {
        try{
            $post=$request->post();
            $shop_owner= AuthController::me();
            $post['shop_owner_id']=$shop_owner->id;
            if(!$post['products']){
                throw  new Exception('need products items');
            }
            $post['products']=json_decode($post['products'],1);
            if(json_last_error()){
              throw  new Exception(json_last_error_msg());
            }
            if(!$post['required_date']||!$post['shipping_date']){
                throw new \Exception('need required_date ,shipping_date');
            }
            return DB::transaction(function ()use($post){

               $order=PurchaseOrder::create(
                   [
                       'order_ref'=>$post['order_ref'],
                       'order_date'=>$post['order_date'],
                       'required_date'=>$post['required_date'],
                       'shipping_date'=>$post['shipping_date'],
                       'shipping_type'=>$post['shipping_type'],
                       'shipping_price'=>$post['shipping_price'],
                       'shipping_adresse'=>$post['shipping_address'],
                       'shipping_country'=>$post['shipping_country'],
                       'order_price'=>$post['order_price'],
                       'order_weight'=>$post['order_weight'],
                       'supplier_id'=>3,
                       'shop_owner_id'=>$post['shop_owner_id'],
                       'statut_id'=>1,
                   ]);
                $arr=[];
                foreach ($post['products'] as  $value)
                {

                    $arr[] = ['product_name' => $value['product_name'],
                        'product_barcode' => $value['product_barcode'],
                        'product_description' => $value['product_description'],
                        'product_image' => $value['product_image'],
                        'cost_price' => (float)$value['cost_price'],
                        'tax_rate' => $value['tax_rate'],
                        'product_weight' => $value['product_weight'],
                        'product_size' => $value['product_size'],
                        'product_color' => $value['product_color'],
                        'product_quantity' => $value['product_quantity'],
                        'supplier_id' => $value['supplier_id'],
                        'shop_owner_id' =>$post['shop_owner_id'],
                        'category_id' => $value['category_id'],
                        'purchase_order_id' => $order->id,
                        'created_at'=>Carbon::now(),
                        'updated_at'=>Carbon::now(),
                    ];
                }
                if($arr){
                    PurchaseProduct::insert($arr);
                }
                $response['code']=0;
                $response['msg']='order has been created';
                return response()->json($response);
            });
        }catch (\Exception $e){
            $response['code']=1;
            $response['msg']=$e->getMessage();
            return response()->json($response);
        }
    }
    //chineese team


    public function preOrderUpload(Request $request)
    {
        $shop_owner= AuthController::me();
        $preOrder = PurchaseOrder::newOrder($request,$shop_owner->id); 
        if ($request->hasFile('products')) {
            $path = $request->file('products')->getRealPath();
            $data = Excel::load($path)->get();
            if ($data->count()) {
                $products = PurchaseProduct::insertProducts($data, $preOrder,$shop_owner->id);
                $response['code']=1;
                $response['msg']='';
                $response['data']="data has been saved";
                return response()->json($response);
            }
            $response['code']=0;
            $response['msg']='3';
            $response['data']='error while saving';
            return response()->json($response);
        }
        
        $response['code']=0;
        $response['msg']='3';
        $response['data']='missing data';
        return response()->json($response);
    }

    public function searchPreOrder(Request $request, $oreder_ref)
    {
        $preOrder = PurchaseOrder::with(['purchaseProduct'=> function ($q)
                                    {$q->with('category:id,category_name')->get();}])
                                ->where('order_ref',$oreder_ref)->get();
        if (!empty($preOrder)) {
            $response['code']=1;
            $response['msg']='';
            $response['data']= $preOrder;
            return response()->json($response);
        }
        else {
            $response['code']=0;
            $response['msg']='';
            $response['data']= "data not found";
            return response()->json($response);
        }
                                
    }
}
