<?php namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\InventoryProduct;
use App\Models\Inventory;
use App\Models\PurchaseOrder;
use App\Models\Product;
use App\Models\PurchaseProduct;
use App\Models\Warehouse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
//use mysql_xdevapi\Exception;
class InventoriesController extends Controller {

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
        return response()->json($response);
    }

    public function newInventory(Request $request)
    {
        // $operator_status = true;
	$shop_owner = AuthController::meme();
        $products =$request->input('products');
$tmp = Inventory::where('batch_number',$request->input('batch_number'))->first();
if($tmp) {
return response()->json(['code'=>0,'msg'=>'batch number already exists']);
}
        $inventory = new Inventory();
        $inventory->batch_number = $request->input('batch_number');
        $inventory->operator = $request->input('operator');
        $inventory->verifier = $request->input('verifier');
        $inventory->date = $request->input('date');
        $inventory->warehouse_id = $request->input('warehouse_id');
	$inventory->chain_id = $request->input('chain_id');
	
        $inventory->supplier_id = $request->input('supplier_id');
        $inventory->operator_status = $request->input('operator_status');
        $inventory->verifier_status = $request->input('verifier_status');
	$inventory->inventory_status = 'complete';

	$inventory_status = 'complete';
        if ($inventory->save()) {
            foreach ($products as  $inv_product) 
            
        {   $product_status = isset($inv_product['product_status'])?$inv_product['product_status']:2;
if($product_status==2) {

		$inventory_status = 'pending';
            }
            $arrived_quantity = isset($inv_product['arrived_quantity'])?$inv_product['arrived_quantity']:0;
            $quantity =isset($inv_product['verified_quantity'])?$inv_product['verified_quantity']:0;
            $unverified_quantity = $arrived_quantity - $quantity;
            //$value2 =$request->has('value2')? $request->input('value2'):0;
            $purchaseProduct =PurchaseProduct::where('product_barcode', $inv_product["product_barcode"])->first();
            // PurchaseProduct::find($inv_product["id"]);
          if(!$purchaseProduct) {
                $purchaseProduct = new PurchaseProduct();
                $purchaseProduct->product_name =$inv_product['product_name'];
                $purchaseProduct->product_barcode =$inv_product['product_barcode'];
                //$purchaseProduct->product_description =$inv_product['product_description'];
                $purchaseProduct->product_quantity =isset($inv_product['product_quantity'])? $inv_product['product_quantity'] :0 ;
                $purchaseProduct->cost_price =isset($inv_product['cost_price'])?$inv_product['cost_price']:0.01;
                //$purchaseProduct->unit_price =isset($inv_product['unit_price'])?$inv_product['unit_price']:0.01;
                $purchaseProduct->tax_rate =isset($inv_product['tax_rate'])?$inv_product['tax_rate']:0.01;     
                $purchaseProduct->product_weight =isset($inv_product['product_weight'])?$inv_product['product_weight']:0.01;   
                $purchaseProduct->product_color =isset($inv_product['product_color'])?$inv_product['product_color']:0;      
                $purchaseProduct->supplier_id =isset($inv_product['supplier_id'])?$inv_product['supplier_id']:$request->input('supplier_id');     
                $purchaseProduct->category_id =isset($inv_product['category_id'])?$inv_product['category_id']:66;     
                $purchaseProduct->shop_owner_id =$shop_owner->id;         
                $purchaseProduct->save();   
                
            }            // User::where('email','=',$email)->orWhere('contact', $phone_num1)->first()
            $product =  product::where([
                    'product_barcode' => $purchaseProduct->product_barcode,
                    'shop_owner_id' => $purchaseProduct->shop_owner_id,'chain_id'=>$request->input('chain_id'),
])->first();
            
            if (empty($product)) {
                $product_id = DB::table('products')->insertGetId(
                    [ 'product_name' =>  $purchaseProduct->product_name,
                      'product_barcode' => $purchaseProduct->product_barcode,
                      'product_description' =>  $purchaseProduct->product_description,
                      'cost_price' =>  $purchaseProduct->cost_price,
                      'unit_price' =>  $purchaseProduct->cost_price,
                      'tax_rate' => $purchaseProduct->tax_rate,
                      'product_weight' => $purchaseProduct->product_weight,
                      'product_color' => $purchaseProduct->product_color,
                      'product_quantity' => $quantity,
                      //$inv_product['quantity'],
                      'supplier_id' => $request->input('supplier_id'),
                      'category_id' => $purchaseProduct->category_id,
                      'shop_owner_id' => $purchaseProduct->shop_owner_id,
		      'chain_id'=>$request->input('chain_id'),
		      'shop_id'=>$shop_owner->store_id
                    ]
                );
                $inv_prod = DB::table('inventory_product')->insert(
                    [ 'inventory_id' => $inventory->id,
                    'product_id' => $product_id,
                    'arrived_quantity' =>  $arrived_quantity,
                    'verified_quantity' =>  $quantity,
                    'unverified_quantity' =>  $unverified_quantity,
                    'total_quantity' =>  isset($inv_product['product_quantity']) && $inv_product['product_quantity']!='' ? $inv_product['product_quantity']:0,

            'product_status'=>    $product_status,
                    'created_at' =>  Carbon::now(),'updated_at' =>  Carbon::now()]);
                // if( $inv_product['quantity'] < $purchaseProduct->product_quantity)
                // {
                //     $operator_status = false;
                // }
            }
            else {
		$product->product_quantity += $quantity;
		$product->cost_price =isset($inv_product['cost_price'])?$inv_product['cost_price']:$product->cost_price;
                $product->unit_price =isset($inv_product['unit_price'])?$inv_product['unit_price']:$product->unit_price;
                $product->tax_rate =isset($inv_product['tax_rate'])?$inv_product['tax_rate']:$product->tax_rate;     
                $product->product_weight =isset($inv_product['product_weight'])?$inv_product['product_weight']:$product->product_weight;   
                $product->product_color =isset($inv_product['product_color'])?$inv_product['product_color']:$product->product_color;      
                $product->supplier_id =isset($inv_product['supplier_id'])?$inv_product['supplier_id']:$product->supplier_id;     
                $product->category_id =isset($inv_product['category_id'])?$inv_product['category_id']: $product->category_id;  
                $product->save();
                $inv_prod = DB::table('inventory_product')->insert(
                    [ 'inventory_id' => $inventory->id,
                    'product_id' => $product->id,
                    'arrived_quantity' =>  $arrived_quantity,
                    'verified_quantity' =>  $quantity,
                    'unverified_quantity' =>  $unverified_quantity,
                    'total_quantity' =>  isset($inv_product['product_quantity']) && $inv_product['product_quantity']!='' ? $inv_product['product_quantity']:0,

            'product_status'=>    $product_status,
                    'created_at' =>  Carbon::now(),'updated_at' =>  Carbon::now()]);
                      
               
                // if( $inv_product['quantity'] < $purchaseProduct->product_quantity)
                // {
                //     $operator_status = false;
                // }
            }    
        }
         if ($inventory_status == 'pending') {
      
            $inventory->inventory_status = 'pending';
             $inventory->save();
             }  
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


    public function getInventories(Request $request, $warehouse_id)
    {
        //$shop_owner= AuthController::me();

        $operator_status = $request-> input('operator_status');
        $supplier = $request-> input('supplier_id');
        $batchNumber = $request-> input('batch_number');
        $verifier_status = $request-> input('verifier_status');
        $inventoryList= Inventory::with('warehouse','suppliers','verifier_status:id,statut_name','operator_status:id,statut_name','chain')->with('products')
        ->where('warehouse_id', $warehouse_id)
     
        ->when($operator_status != '', function ($q) use ($operator_status) 
            {$q->where('operator_status',$operator_status);})
        ->when($verifier_status != '', function ($q) use ($verifier_status) 
            {$q->where('verifier_status',$verifier_status);})
        ->when($batchNumber != '', function ($q) use ($batchNumber) 
            {$q->where('batch_number','like','%'.$batchNumber.'%');})
        ->when($supplier != '', function ($q) use ($supplier) 
            {$q->where('supplier_id',$supplier);})
        ->orderBy('id', 'DESC')->get();
        $response['code']=1;
        $response['msg']='';
        $response['data']= $inventoryList;
        return response()->json($response);
    }
    public function newInventoryMobile(Request $request)
    {
        // $operator_status = true;
        $products =$request->input('products');
        $inventory = new Inventory();
        $inventory->batch_number = $request->input('batch_number');
        $inventory->operator = $request->input('operator');
        $inventory->verifier = $request->input('verifier');
        $inventory->date = $request->input('date');
        $inventory->warehouse_id = $request->input('warehouse_id');
        $inventory->supplier_id = $request->input('supplier_id');
        $inventory->operator_status = $request->input('operator_status');
        $inventory->verifier_status = $request->input('verifier_status');
        if ($inventory->save()) {
            foreach ($products as  $inv_product) 
        {   
            $quantity =isset($inv_product['quantity'])? $request->input('quantity'):0;
            //$value2 =$request->has('value2')? $request->input('value2'):0;
            $purchaseProduct =PurchaseProduct::where('id','=',$inv_product["id"])->orWhere('product_barcode', $inv_product["product_barcode"])->first();
            // PurchaseProduct::find($inv_product["id"]);
            // User::where('email','=',$email)->orWhere('contact', $phone_num1)->first()
            $product =  product::where([
                    'product_barcode' => $purchaseProduct->product_barcode,
                    'shop_owner_id' => $purchaseProduct->shop_owner_id])->first();
            
            if (empty($product)) {
                $product_id = DB::table('products')->insertGetId(
                    [ 'product_name' =>  $purchaseProduct->product_name,
                      'product_barcode' => $purchaseProduct->product_barcode,
                      'product_description' =>  $purchaseProduct->product_description,
                      'cost_price' =>  $purchaseProduct->cost_price,
                      'unit_price' =>  $purchaseProduct->cost_price,
                      'tax_rate' => $purchaseProduct->tax_rate,
                      'product_weight' => $purchaseProduct->product_weight,
                      'product_color' => $purchaseProduct->product_color,
                      'product_quantity' => $quantity,
                      //$inv_product['quantity'],
                      'supplier_id' => $purchaseProduct->supplier_id,
                      'category_id' => $purchaseProduct->category_id,
                      'shop_owner_id' => $purchaseProduct->shop_owner_id,
                    ]
                );
                $inv_prod = DB::table('inventory_product')->insert(
                    [ 'inventory_id' => $inventory->id,
                      'product_id' => $product_id,
                      'arrived_quantity' =>  0,
                      'total_quantity' => $quantity,
                      'created_at' =>  Carbon::now()]);
                // if( $inv_product['quantity'] < $purchaseProduct->product_quantity)
                // {
                //     $operator_status = false;
                // }
            }
            else {
                $inv_prod = DB::table('inventory_product')->insert(
                    [ 'inventory_id' => $inventory->id,
                      'product_id' => $product->id,
                      'arrived_quantity' =>  0,
                      'total_quantity' => $quantity,
                      'created_at' =>  Carbon::now()]);
                      
                $product->product_quantity += $quantity;
                $product->save();
                // if( $inv_product['quantity'] < $purchaseProduct->product_quantity)
                // {
                //     $operator_status = false;
                // }
            }    
        }
        // if ($operator_status) {
        //     $inventory->operator_status = 3;
        //     $inventory->verifier_status = 5;
        //     $inventory->save();
        //     }  
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




    public function getInventory(Request $request, $id)
    {
        $status = $request->input('status_id');
        $inventory= Inventory::with('warehouse','suppliers','operator_status','verifier_status','products')->where('id',$id)->get();
        $response['code']=1;
        $response['msg']='';
        $response['data']= $inventory;
        return response()->json($response);
    }

    public function updateInventory(Request $request, $id)
    {
        $products = $request->input('products');
        $inventory= Inventory::find($id);
	if(!$inventory) {
	return response()->json(['code'=>0,'msg'=>'error while updating']);
}
        $status = true;
	$inventory_status = 'complete';
	$chain_id = $inventory->chain_id;
        foreach ($products as $product) {
		
	    $prod_id = Product::where('product_barcode',$product['product_barcode'])->where('chain_id',$chain_id)->first()->id;
            $inv_prod = DB::table('inventory_product')->where(['inventory_id'=> $id, 'product_id'=> $prod_id])->first();
	   
	//var_dump($inv_prod);
            $updated_qt=$product['added_quantity'];
            $verified_quantity = isset($product['verified_quantity'])? $product['verified_quantity']:0;
	     //$product_status = isset($product['product_status'])? $product['product_status']:2;
	    if($updated_qt > $inv_prod->total_quantity  || $verified_quantity >$updated_qt) {
		return response()->json(['code'=>0,'msg'=>'error while updating']);
		}
            if((int)$verified_quantity<$inv_prod->total_quantity) {
                $product_status = 2;
		$status=false;
            } 
            if((int)$verified_quantity==$inv_prod->total_quantity) {
                $product_status = 3;
            }

            $unverified_quantity =$product['added_quantity']- $verified_quantity;
            DB::table('inventory_product')->where(['inventory_id'=> $id, 'product_id'=> $prod_id])
            ->update(['arrived_quantity' => $updated_qt,
            'product_status'=>$product_status,
            'verified_quantity'=>$verified_quantity,
            'unverified_quantity'=>$unverified_quantity]);

	    if($product_status==2) {
		$inventory_status = 'pending';
            }
            $prod_qt=DB::table('products')->where(['id'=> $prod_id])->value('product_quantity');
            DB::table('products')->where(['id'=> $prod_id])->update(['product_quantity' =>$product['verified_quantity']]);
          
            if($updated_qt < $inv_prod->total_quantity)
            {
                $status = false;
            }
        }
if (!$status) {
            $inventory->operator_status = 3;
            $inventory->verifier_status = 3;
	    $inventory->inventory_status = 'pending';
            $inventory->save();
            }
else{
                 $inventory->inventory_status = $inventory_status;
	     $inventory->save();}

        $response['code']=1;
        $response['msg']='';
        $response['data']= "inventory has been update!";
        return response()->json($response);
    }
    public function deleteInventory(Request $request)
    {
        $response = [];
        try {
            if (!$request->id) {
                throw  new \Exception("the arguments id needed");
            }

            DB::transaction(function () use ($request) {
                $inventory = Inventory::find($request->id);
                if ($inventory) {
                    $inventory->delete();
                    InventoryProduct::where('inventory_id', $request->id)->delete();
                }
            });
            $response['code'] = 0;
            $response['msg'] = '';
            $response['data'] = "inventory has been delete!";
        } catch (\Exception $e) {
            $response['code'] = 1;
            $response['msg'] = '';
            $response['data'] = $e->getMessage();
        }
        return response()->json($response);
    }

    public function getInventoryProductList(Request $request)
    {
        try {
            $inventory_id = $request->get('inventory_id') * 1;
            if (!$inventory_id) {
                throw new \Exception('need inventory_id');
            }
            $stock_min = $request->get('stock_min', 0);
            $stock_max = $request->get('stock_max', 0);
            $rs = InventoryProduct::where('inventory_id', $inventory_id)->when($stock_min || $stock_max, function ($query) use ($stock_min, $stock_max) {
                $query->whereBetween('total_quantity', [$stock_min, $stock_max]);
            })->paginate();
            $response['code'] = 0;
            $response['msg'] = '';
            $response['data'] = $rs;
            return response()->json($response);
        } catch (\Exception $e) {
            $response['code'] = 1;
            $response['msg'] = '';
            $response['data'] = $e->getMessage();
            return response()->json($response);
        }

    }
    public function getInventoryByBatchNumber(Request $request)
    {   
        $batchNumber  = $request->input('batch_number');
        
        $inventory= Inventory::with('warehouse','suppliers','operator_status','verifier_status','products')->where('batch_number',$batchNumber)->first();
        $response['code']=1;
        $response['msg']='';
        $response['data']= $inventory;
        return response()->json($response);
    
    }
public function getInventoryByBatchNumberForWeb(Request $request)
    {   
        $batchNumber  = $request->input('batch_number');
        
        $inventory= Inventory::with('warehouse','suppliers','operator_status','verifier_status','products')->where('batch_number',$batchNumber)->get();
        $response['code']=1;
        $response['msg']='';
        $response['data']= $inventory;
        return response()->json($response);
    }

public function getBatchNumberListByChainId(Request $request) {
        $user = AuthController::me();

        $chain_id = $request->input('chain_id');
        if(!$chain_id) {
            return response()->json(['code'=>0 ,'msg'=>'chain data is required']);
        }
        $batchnumbers = Inventory::where('chain_id',$chain_id)->pluck('batch_number');
        $response['code'] = 1;
        $response['msg'] = '';
        $response['data'] = $batchnumbers;
	return response()->json($response);
    }

    public function getInventoriesByChain(Request $request)
    {
        //$shop_owner= AuthController::me();
        $chain_id = $request-> input('chain_id');
        $operator_status = $request-> input('operator_status');
        $supplier = $request-> input('supplier_id');
        $batchNumber = $request-> input('batch_number');
        $verifier_status = $request-> input('verifier_status');
        $inventoryList= Inventory::with('warehouse','suppliers','verifier_status:id,statut_name','operator_status:id,statut_name','chain')->with('products')
        ->where('chain_id', $chain_id)
     
        ->when($operator_status != '', function ($q) use ($operator_status) 
            {$q->where('operator_status',$operator_status);})
        ->when($verifier_status != '', function ($q) use ($verifier_status) 
            {$q->where('verifier_status',$verifier_status);})
        ->when($batchNumber != '', function ($q) use ($batchNumber) 
            {$q->where('batch_number','like','%'.$batchNumber.'%');})
        ->when($supplier != '', function ($q) use ($supplier) 
            {$q->where('supplier_id',$supplier);})
        ->paginate(20)->toArray();
        $response['code']=1;
        $response['msg']='';
        $response['data']= $inventoryList;
        return response()->json($response);
    }
     public function getInventoryProductsByBatchNumber(Request $request)
    {   
        $batchNumber  = $request->input('batch_number');
	$barcode  = $request->input('barcode');
        $inventory = DB::table('inventory')->where('batch_number',$batchNumber)->first();
        if(!$inventory) {
            return response()->json(['code'=>0,'msg'=>'no inventory with this batch number found']);
        } else {
            $inventory_id = $inventory->id;
            $products_id =  DB::table('inventory_product')->where('inventory_id',$inventory_id)->pluck('product_id');
            $products = Product::whereIn('id',$products_id)->when($barcode   != '', function ($query) use ($barcode  ) {
                        $query->where('product_barcode',$barcode);})->get();
            foreach($products as $product) {
	$product->pivot=DB::table('inventory_product')->where('inventory_id',$inventory_id)->where('product_id',$product->id)->first();
}

            $response['code']=1;
            $response['msg']='';
            $response['data']= $products;
            return response()->json($response);
        }
        
    }

  public function quickupdateInventory(Request $request, $id)
    {
        $products = $request->input('products');
        $inventory= Inventory::find($id);
	if(!$inventory) {
	return response()->json(['code'=>0,'msg'=>'error while updating']);
}
        $status = true;
	    $inventory_status = 'complete';
	    $chain_id = $inventory->chain_id;
        foreach ($products as $product) {
	        $prod_id = Product::where('product_barcode',$product['product_barcode'])->where('chain_id',$chain_id)->first()->id;
            $inv_prod = DB::table('inventory_product')->where(['inventory_id'=> $id, 'product_id'=> $prod_id])->first();
            $updated_qt=$inv_prod->arrived_quantity+1;
            $verified_quantity = $inv_prod->verified_quantity+1;
	        
            if($verified_quantity<$inv_prod->total_quantity) {
                $product_status = 2;
            } 
            if($verified_quantity==$inv_prod->total_quantity) {
                $product_status = 3;
            }
            $unverified_quantity =$updated_qt- $verified_quantity;
            if($updated_qt<=$inv_prod->total_quantity) {
                DB::table('inventory_product')->where(['inventory_id'=> $id, 'product_id'=> $prod_id])
            ->update(['arrived_quantity' => $updated_qt,
                      'product_status'=>$product_status,
                      'verified_quantity'=>$verified_quantity,
                      'unverified_quantity'=>$unverified_quantity
                    ]);
            } else {
                return response()->json(['code'=>0,'msg'=>'received quantity can not be greater than total quantity','data'=>'received quantity can not be greater than total quantity']);
            }
            

	    if($product_status==2) {
		$inventory_status = 'pending';
            }
            $prod_qt=Product::where(['id'=> $prod_id])->first();
            $prod_qt->product_quantity = $prod_qt->product_quantity +1;
            $prod_qt->save(); 
     
        }
        if ($status) {
            $inventory->operator_status = 3;
            $inventory->verifier_status = 3;
            $inventory->save();
            }
            $inventory->inventory_status = $inventory_status;
	        $inventory->save();

        $response['code']=1;
        $response['msg']='';
        $response['data']= "inventory has been update!";
        return response()->json($response);
    }
    


}
