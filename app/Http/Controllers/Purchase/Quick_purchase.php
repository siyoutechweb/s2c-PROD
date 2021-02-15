<?php namespace App\Http\Controllers\Purchase;

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\InventoryProduct;
use App\Models\Inventory;
use App\Models\PurchaseOrder;
use App\Models\Product;
use App\Models\Chain;
use App\Models\PurchaseProduct;
use App\Models\QuickPurchaseOrder;
use App\Models\Warehouse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
//use mysql_xdevapi\Exception;
class Quick_purchase extends Controller {

    public function __construct()
    {
        $this->middleware('auth:api');
    }
    
    public function generateBatchNumber()
    {
        $dt = Carbon::now();
        $date = str_replace("-","",$dt->toDateString());
        $time = str_replace(":","",$dt->toTimeString());
        $batchNumber = $date."-".$time;
        $response['code']=1;
        $response['msg']='';
        $response['data']= $batchNumber;
        return $batchNumber;
    }
    public function generateOrderRef()
    {
        $dt = Carbon::now();
        $date = str_replace("-","",$dt->toDateString());
        $time = str_replace(":","",$dt->toTimeString());
        $orderRef = "P.O-".$date."-".$time;
        $response['code']=1;
        $response['msg']='';
        $response['data']= $orderRef ;
        return $orderRef ;
    }

    public function newQuickPurcaseOrder(Request $request)
    {
        // $operator_status = true;
	$shop_owner = AuthController::meme();
        $products =$request->input('products');
	if(count($products)<1) {
	return response()->json(['code'=>0,'msg'=>'empty order cannot be created','data'=>'empty order cannot be created']);}
        $quick_order = new QuickPurchaseOrder();

        $quick_order->chain_id = $request->input('chain_id');
	$quick_order->store_id = $shop_owner->store_id;
	$quick_order->order_number = $this->generateOrderRef();

        $quick_order->supplier_id = $request->input('supplier_id');
        $quick_order->total_price = 0;
        $quick_order->status = 'pending';
        
        $total_cost=0;


        if ($quick_order->save()) {
            foreach ($products as  $ordered_product) 
            
        {   
           
            $prod=Product::find($ordered_product['product_id']);
            

                $total_cost += $prod->cost_price * $ordered_product['purchased_quantity'];
                $purchaseProduct = new PurchaseProduct();
                $purchaseProduct->product_name =$prod->product_name;
                $purchaseProduct->product_barcode =$prod->product_barcode;

                $purchaseProduct->product_quantity =$ordered_product['purchased_quantity'];
                $purchaseProduct->cost_price =$prod->cost_price;
                //$purchaseProduct->unit_price =isset($inv_product['unit_price'])?$inv_product['unit_price']:0.01;
                $purchaseProduct->tax_rate =$prod->tax_rate;  
                $purchaseProduct->product_weight =$prod->product_weight;    
                $purchaseProduct->product_color =$prod->product_color;      
                $purchaseProduct->supplier_id =$request->input('supplier_id');     
                $purchaseProduct->category_id =$prod->category_id;   
                $purchaseProduct->shop_owner_id =$shop_owner->id;
                $purchaseProduct->quick_order_id =$quick_order->id;
		$purchaseProduct->stock_quantity =$prod->product_quantity;   
                $purchaseProduct->save();   
   
        }
        $quick_order->total_price=$total_cost;
        $quick_order->save();
            $response['code']=1;
            $response['msg']='';
            $response['data']="Success";
            return response()->json($response);
        } 
        else 
        {
            $response['code']=0;
            $response['msg']='';
            $response['data']= "Error while saving";
            return response()->json($response);
        }  
    }


    public function getQuickOrders(Request $request)
    {        $user= AuthController::meme();


        $supplier = $request-> input('supplier_id');
 	$barcode = $request-> input('barcode');
	$created_at = $request->input('date');
	
	if(!isset($created_at)) {$created_at = "";}
	$order_number = $request->input('order_number');
	$status = $request->input('status');
	if($status==2) {$status='pending';}
	if($status==3) {$status='confirmed';}
        $chain = $request-> input('chain_id');
	$userChain=$user->chain_id;
        $quick_orders_id =QuickPurchaseOrder::where(['chain_id'=>$chain,'supplier_id'=>$supplier])->pluck('id');
        $quick_orders_products =QuickPurchaseOrder::where('store_id',$user->store_id)
						     ->when($userChain!='',function($query)use($userChain) {
						    $query->where('chain_id',$userChain);})
						   ->when($chain!='',function($query)use($chain) {
						    $query->where('chain_id',$chain);})
						   ->when($supplier!='',function($query)use($supplier) {
						    $query->where('supplier_id',$supplier);})
						   ->when($order_number!='',function($query)use($order_number) {
						    $query->where('order_number','like','%'.$order_number.'%');})
						   ->when($created_at !='' && $created_at!="null",function($query)use($created_at) {
						    $query->whereDate('created_at','=',$created_at);})
						   ->when($status  !='',function($query)use($status) {
						    $query->where('status','=',$status );})

						   ->with('supplier','chain')->orderBy('created_at','desc')
												->get();

foreach($quick_orders_products as $quick_order_product) {
$quick_order_product->products =PurchaseProduct::where('quick_order_id',$quick_order_product->id)->when($barcode!='',function($query) use($barcode) {
							//echo $barcode;
    							$query->where('product_barcode','like', '%'.$barcode.'%');
})->get();
if($quick_order_product->status=='confirmed') {
    $quick_order_product->inventory_batch_number = Inventory::where('quick_order_id',$quick_order_product->id)->first()->batch_number;
} else {
    $quick_order_product->inventory_batch_number = null;
}						



}

//}}
//$quick_orders_products =$quick_orders_products->filter(function($q) {return $q->products->count();} );


                $response['code']=1;
        $response['msg']='';
        $response['data']= $quick_orders_products;
        return response()->json($response);
    }
    public function getQuickOrderById(Request $request,$id) {
        $quick_order = QuickPurchaseOrder::where('id',$id)->with('supplier','chain')->get();
	$barcode=$request->input('barcode');
        if($quick_order->count()) {
	    foreach($quick_order as $quick) {
$quick->products =PurchaseProduct::where('quick_order_id',$quick->id)->when($barcode!='',function($query) use($barcode) {
							//echo $barcode;
    							$query->where('product_barcode','like', '%'.$barcode.'%');
})->get();
if($quick->status=='confirmed') {
    $quick->inventory_batch_number = Inventory::where('quick_order_id',$quick->id)->first()->batch_number;
} else {
    $quick->inventory_batch_number = null;
}							



}

            $response['code']=1;
            $response['msg']='';
            $response['data']= $quick_order;
        } else {
            $response['code']=0;
            $response['msg']='';
            $response['data']= 'no quick order found with these credentials';
        }
        return response()->json($response);
    }

public function deleteProductFromQuickOrder(Request $request,$id) {

        $user = AuthController::meme();
        $product_barcodes = $request->input('barcode');
        $count=0;
        if(count(($product_barcodes)) >0) {
            foreach($product_barcodes as $product_barcode) {
                $purchased_product=PurchaseProduct::where(['product_barcode'=>$product_barcode,'quick_order_id'=>$id])->first();
            
            if($purchased_product) {
                $cost_price = $purchased_product->cost_price;
                $purchased_quantity = $purchased_product->product_quantity;
                $order = QuickPurchaseOrder::find($id);
                $order->total_price = $order->total_price -$cost_price*$purchased_product->product_quantity;
                $order->save();
               if($purchased_product->delete()) {
                   $count = $count +1;
                   //return response()->json(['code'=>1,'msg'=>'product deleted successfully','data'=>'product deleted successfully']);
               } else {
                return response()->json(['code'=>0,'msg'=>'error while deleting','data'=>'error while deleting']);
               } 
            } else {
                return response()->json(['code'=>0,'msg'=>'no product found','data'=>'no product found']);
            }
            }
        }
        $related_purchase_products=PurchaseProduct::where('quick_order_id',$id)->get();
        if(!$related_purchase_products->count()) {
            $order->delete();
        }
        if($count == count(($product_barcodes)))
        return response()->json(['code'=>1,'msg'=>'product deleted successfully','data'=>'product deleted successfully']);
        
        

    }
    public function updateProductQuantityOfQuickOrder(Request $request,$id) {

        $user = AuthController::meme();
	$error = 0;
	$products= $request->input('products');
	foreach($products as $product) {
		$product_barcode =$product['barcode'];
        	$purchased_product=PurchaseProduct::where(['product_barcode'=>$product_barcode,'quick_order_id'=>$id])->first();
        
        $updated_quantity = $product['purchased_quantity'];
        if( !isset($updated_quantity)) {
            return response()->json(['code'=>0,'msg'=>"arguments error",'data'=>"arguments error"]);
        }
        
       
        if($purchased_product) {
            $order = QuickPurchaseOrder::find($id);
            $cost_price = $purchased_product->cost_price;
if($updated_quantity>$purchased_product->product_quantity) {$order->total_price = $order->total_price +$cost_price*($updated_quantity -$purchased_product->product_quantity);
} else {$order->total_price = $order->total_price  - $cost_price*($updated_quantity -$purchased_product->product_quantity);}
            
            $purchased_product->product_quantity = $updated_quantity;
           if($purchased_product->save()) {
               $order->save();
               
           } else {
		$error = 0;
            return response()->json(['code'=>0,'msg'=>'error while updating','data'=>'error while updating']);
           } 
        } else {
            return response()->json(['code'=>0,'msg'=>'no product found','data'=>'no product found']);
        }
	
	}
        return response()->json(['code'=>1,'msg'=>'product quantity updated successfully','data'=>'product quantity updated successfully']);

    }


public function confirmOrder(Request $request,$id) {
        $user = AuthController::meme();
        $purchase_order =QuickPurchaseOrder::find($id);
        if(!$purchase_order) {
            return response()->json(['code'=>0,'msg'=>'no order found','data'=>'no order found']);
        }
        $purchase_order->status = 'confirmed';
        if($purchase_order->save()) {
        $warehouse_id =Chain::find($purchase_order->chain_id)->warehouse_id;
        $inventory = new Inventory();
        $inventory->batch_number =$this->generateBatchNumber();
        $inventory->inventory_status = 'pending';
	$inventory->quick_order_id = $id;

        //$inventory->date = date('Y-m-d', Carbon::now());
        $inventory->warehouse_id = $warehouse_id;
        $inventory->chain_id = $purchase_order->chain_id;
        $inventory->supplier_id = $purchase_order->supplier_id;
        if($inventory->save()) {
            $products = PurchaseProduct::where(['quick_order_id'=>$id])->get();
            foreach($products as $product) {
                $product_id = Product::where(['product_barcode'=>$product->product_barcode,'chain_id'=>$purchase_order->chain_id])->first()->id;
                $inv_prod = DB::table('inventory_product')->insert(
                    [ 'inventory_id' => $inventory->id,
                    'product_id' => $product_id,
                    'arrived_quantity' => 0,
                    'verified_quantity' =>0,
                    'unverified_quantity' =>0,
                    'total_quantity' => $product->product_quantity,

                    'product_status'=> 2,
                    'created_at' =>  Carbon::now(),'updated_at' =>  Carbon::now()]);

            }
        }
        
        }
        return response()->json(['code'=>1,'msg'=>'order confirmed and inventory created','data'=>'order confirmed and inventory created']);

    }

public function deleteOrder(Request $request,$id) {
        $user = AuthController::meme();
        $userChain=$user->chain_id;
        $quick_order =QuickPurchaseOrder::where('id',$id)->where('store_id',$user->store_id)
                                                        ->when($userChain!='',function($q)use($userChain) {
                                                            $q->where('chain_id',$userChain);
                                                        })->first();

        if(!$quick_order) {
            return response()->json(['code'=>0,'msg'=>'no order found','data'=>'no order found']);
        } else if($quick_order->delete()) {
            return response()->json(['code'=>1,'msg'=>'order deleted succesfully','data'=>'order deleted succesfully']);
        } else {
            return response()->json(['code'=>0,'msg'=>'error while deleting order','data'=>'error while deleting order']);
        }
    }
public function addQuickOrderToStock(Request $request) {
        $user = AuthController::meme();
        $userChain=$user->chain_id;
        $quick_order_id= $request->input('quick_order_id');
        $inventory=Inventory::where('quick_order_id',$quick_order_id)->first();
        if(!$inventory) {
            return response()->json(['code'=>0,'msg'=>'no inventory related to this order','data'=>'no inventory related to this order']);
        }
        $inventory_id = $inventory->id;
        $inventory_product_ids = DB::table('inventory_product')->where('inventory_id',$inventory_id)->pluck('id');
        foreach($inventory_product_ids as $inventory_product_id) {
            $inv_prod = InventoryProduct::where('id',$inventory_product_id)->first();
            $inv_prod->arrived_quantity = $inv_prod->total_quantity;
            $inv_prod->verified_quantity = $inv_prod->total_quantity;
            $inv_prod->unverified_quantity = 0;
            $inv_prod->product_status=3;
            $inv_prod->updated_at = Carbon::now();
            $inv_prod->save();
            $prod = Product::find($inv_prod->product_id) ;
            $prod->product_quantity = $inv_prod->total_quantity;
            $prod->save();
        }
        $inventory->inventory_status = 'complete';
        if($inventory->save()) {
            return response()->json(['code'=>1,'msg'=>'inventory added to stock successfully','data'=>'inventory added to stock successfully']); 
        } else {
            return response()->json(['code'=>0,'msg'=>'error while operating','data'=>'error while operating']); 
        }
        
    }


    public function confirmOrderAndAddToStock(Request $request,$id) {
        $user = AuthController::meme();
        $purchase_order =QuickPurchaseOrder::find($id);
        if(!$purchase_order) {
            return response()->json(['code'=>0,'msg'=>'no order found','data'=>'no order found']);
        }
        $purchase_order->status = 'confirmed';
        if($purchase_order->save()) {
        $warehouse_id =Chain::find($purchase_order->chain_id)->warehouse_id;
        $inventory = new Inventory();
        $inventory->batch_number =$this->generateBatchNumber();
        $inventory->inventory_status = 'complete';
	    $inventory->quick_order_id = $id;

        //$inventory->date = date('Y-m-d', Carbon::now());
        $inventory->warehouse_id = $warehouse_id;
        $inventory->chain_id = $purchase_order->chain_id;
        $inventory->supplier_id = $purchase_order->supplier_id;
        if($inventory->save()) {
            $products = PurchaseProduct::where(['quick_order_id'=>$id])->get();
            foreach($products as $product) {
                $product_id = Product::where(['product_barcode'=>$product->product_barcode,'chain_id'=>$purchase_order->chain_id])->first()->id;
                $inv_prod = DB::table('inventory_product')->insert(
                    [ 'inventory_id' => $inventory->id,
                    'product_id' => $product_id,
                    'arrived_quantity' =>$product->product_quantity,
                    'verified_quantity' =>$product->product_quantity,
                    'unverified_quantity' =>$product->product_quantity,
                    'total_quantity' => $product->product_quantity,

                    'product_status'=> 3,
                    'created_at' =>  Carbon::now(),'updated_at' =>  Carbon::now()]);

            }
        }
        
        }
        return response()->json(['code'=>1,'msg'=>'order confirmed and inventory created','data'=>'order confirmed and inventory created']);

    }




   

}
