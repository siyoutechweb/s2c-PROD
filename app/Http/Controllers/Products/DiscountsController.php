<?php namespace App\Http\Controllers\Products;
/*
SIYOU THECH Tunisia
Author: Habiba Boujmil
ERROR MSG
* 1:parameters missing, in data field indicate whuch parameter is missing
* 2:token expired or forced to logout, take to relogin
* 3:error while saving
*/
use App\Models\Product_discount;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Discount;
use Illuminate\Http\Request;
use Exception;

class DiscountsController extends Controller {

    public function __construct()
    {
        $this->middleware('auth:api');
    }
    
    /* get the list of discounts Type API
     - Necessary Parameters: 'token',
     - Accessible for : ShopOwner / ShopManager
    */
    public function discountList(Request $request)
    {
        $discountList= Discount::all();
        $response = array();
        $response['code']=1;
        $response['msg']='';
        $response['data']= $discountList;
        return response()->json($response);
    }  

    /* add Promotion to products API
     - Necessary Parameters: 'token','discount_id', 'start_date', 'finish_date',products (array of products id)
                             'n_value','m_value' or 'percent' or 'amount' or 'fix_price'
     - Accessible for : ShopOwner / ShopManager
    */

    public function addPromotion(Request $request)
    {	$user = AuthController::meme();
        Product::updateDiscountPrice();
        $discount_id = $request->input('discount_id');
        $discount= Discount::find($discount_id);
        $products = $request->input('products_id');
        $start_date = $request->input('start_date');
        $finish_date = $request->input('finish_date');
	$val2 = $request->input('value2');
        $value1 = $request->input('value1');
         if(!$val2 ||empty($val2)) {
            $val2 = 0;
        }
        $value2 =$val2;      
        if ($discount_id == 1)
        {
            foreach ($products as $key => $product_id) 
            {
                $product = Product::find($product_id);
                $discount_price=$product->unit_price-($product->unit_price*(float)$value1)/100;
                if(DB::table('product_discount')->where('product_id',$product_id)->exists()){
                    DB::table('product_discount')->where("product_id", $product_id)->update(['product_id'=>$product_id,'discount_value1'=> $value1,
                'discount_value2'=> $value2,
                'start_date'=> $start_date,
                'finish_date'=> $finish_date,
		        'updated_at'=>Carbon::now(),'store_id'=>$user->store_id]);
                }
                else {
                    $product->Discount()->attach($discount, 
                ['discount_value1'=> $value1,
                'discount_value2'=> $value2,
                'start_date'=> $start_date,
                'finish_date'=> $finish_date,
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now(),
                'store_id'=>$user->store_id
		]);
                }
                
                $product->discount_price=$discount_price;
                $product->save();
            }
        }
        elseif ($discount_id == 2)
        {
            foreach ($products as $key => $product_id) 
            {
                  $product = Product::find($product_id);
                //  $discount_price=$product->unit_price-(float)$value1;


                if(DB::table('product_discount')->where('product_id',$product_id)->exists()){
                    DB::table('product_discount')->where("product_id", $product_id)->update(['product_id'=>$product_id,'discount_value1'=> $value1,
                'discount_value2'=> $value2,
                'start_date'=> $start_date,
                'finish_date'=> $finish_date,
                'updated_at'=>Carbon::now(),
                'store_id'=>$user->store_id
		]);
                }
                else {
                    $product->Discount()->attach($discount, 
                ['discount_value1'=> $value1,
                'discount_value2'=> $value2,
                'start_date'=> $start_date,
                'finish_date'=> $finish_date,
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now(),
                'store_id'=>$user->store_id

		]);
                }
                // $product->discount_price=$discount_price;
                // $product->save();
            }
        }
        elseif ($discount_id == 3)
        {
            foreach ($products as $key => $product_id) 
            {
                $product = Product::find($product_id);
                $discount_price=$product->unit_price-(float)$value1;


                if(DB::table('product_discount')->where('product_id',$product_id)->exists()){
                    DB::table('product_discount')->where("product_id", $product_id)->update(['product_id'=>$product_id,'discount_value1'=> $value1,
                'discount_value2'=> $value2,
                'start_date'=> $start_date,
                'finish_date'=> $finish_date,
                'updated_at'=>Carbon::now(),
                'store_id'=>$user->store_id
		]);
                }
                else {
                    $product->Discount()->attach($discount, 
                ['discount_value1'=> $value1,
                'discount_value2'=> $value2,
                'start_date'=> $start_date,
                'finish_date'=> $finish_date,
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now(),
                'store_id'=>$user->store_id
		]);
                }
                $product->discount_price=$discount_price;
                $product->save();
            }
        }
        elseif ($discount_id == 4)
        {
            foreach ($products as $key => $product_id) 
            {
                $product= Product::find($product_id);
                $discount_price = $value1;

                if(DB::table('product_discount')->where('product_id',$product_id)->exists()){
                    DB::table('product_discount')->where("product_id", $product_id)->update(['product_id'=>$product_id,'discount_value1'=> $value1,
                'discount_value2'=> $value2,
                'start_date'=> $start_date,
                'finish_date'=> $finish_date,
                'updated_at'=>Carbon::now(),
                'store_id'=>$user->store_id
		]);
                }
                else {
                    $product->Discount()->attach($discount, 
                ['discount_value1'=> $value1,
                'discount_value2'=> $value2,
                'start_date'=> $start_date,
                'finish_date'=> $finish_date,
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now(),
                'store_id'=>$user->store_id
		]);
                }
                $product->discount_price=$discount_price;
                $product->save();
            }
        }
        else 
        {
            $response = array();
            $response['code']=0;
            $response['msg']='1';
            $response['data']= 'missing data';
            return response()->json($response);
        }
        $response = array();
        $response['code']=1;
        $response['msg']='';
        $response['data']= 'Promotion has been saved';
        return response()->json($response);    
    }

    //chineese team
    //it is working only for one product

       /* add Promotion to products API
     - Necessary Parameters: 'token','discount_id', 'start_date', 'finish_date',products (array of products id)
                             'n_value','m_value' or 'percent' or 'amount' or 'fix_price'
     - Accessible for : ShopOwner / ShopManager
    */
    public function addPromotion2(Request $request)
    {$user = AuthController::meme();

        $response['code'] = 1;
        $response['msg'] = '';
        try {
            $data = $request->only(['discount_id', 'products_id', 'start_date', 'finish_date', 'discount_value1', 'discount_value2']);

            if (!isset($data['discount_id'], $data['products_id'], $data['start_date'], $data['finish_date'], $data['discount_value1'], $data['discount_value2'])) {
                $response['code'] = 0;
                throw new \Exception("arguments are required");
            }
            $product = Product::find($data['products_id']);
            if(!$product){
                $response['code'] = 0;
                throw new \Exception('the product is not found');
            }
            switch ($data['discount_id']) {
                case 1:
                    $discount_price = $product->unit_price - ($product->unit_price * (float)$data['discount_value1']) / 100;
                    break;
                case 2:
                case 3:
                $discount_price = $product->unit_price - (float)$data['discount_value1'];
                break;
                case 4:
                    $discount_price = $data['discount_value1'];
                    break;
                default:
                    $discount_price = 0;
                    break;
            }
            Product::updateDiscountPrice($data['products_id']);
            $discount = Product_discount::updateOrCreate(['product_id' => $data['products_id']], [
                'discount_value1' => $data['discount_value1'],
                'discount_value2' => $data['discount_value2'],
                'start_date' => $data['start_date'],
                'finish_date' => $data['finish_date'],
                'discount_id' => $data['discount_id'],
		        'store_id'=>$user->store_id

            ]);
            $product->discount_price = $discount_price;
            $product->save();
            $response['data'] = $discount;
        } catch (\Exception $e) {
            $response['code'] = 0;
            $response['data'] = $e->getMessage();
        }
        return response()->json($response);
    }

    public function getDiscountProducts(Request $request)
    {
        $shop_owner=AuthController::me();
//var_dump($shop_owner);
        Product::updateDiscountPrice();
        $chain_id = $request->input('chain_id');
        
        $category = $request->input('category_id');
        
        $barcode = $request->input('barcode');
      
        $keyWord = $request->input('keyword');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
	if(!$chain_id) {
        $chain_id = '';}
        if (!$start_date) {
           
            $start_date = '';
            
        }
        if (!$end_date) {

            $end_date = '';
        }
        
        $response=Product::with('Discount')
         ->where('shop_owner_id',$shop_owner->id)
                        ->when($chain_id != '', function ($query) use ($chain_id) {
                        $query->where('chain_id',$chain_id);})
                        ->when($category != '', function ($query) use ($category) {
                        $query->where('category_id',$category);})
                        ->when($barcode != '',  function ($q) use ($barcode)
                        {$q->where('product_barcode',$barcode)->get();})
                        ->when($keyWord != '', function ($q) use ($keyWord){ $q->where('product_name', 'like', '%' . $keyWord . '%')
                            ->orWhere('product_description', 'like', '%' . $keyWord . '%');})
                            ->whereNotNull('discount_price')
                        ->paginate(20)->toArray();
 
                        if(!empty($start_date)) {
                            
                            $response =array_filter($response["data"],function($query) use($start_date) {
                                //echo gettype($query['product_discount']);
                                if(isset($query['product_discount']))
                                $v=$query['product_discount']['start_date'];
                                return $v >= $start_date;
                            });


                        }
                        if(!empty($end_date)) {
                            //var_dump($response[0]);
                            $response = array_filter($response,function($query) use($end_date) {
                                //echo $query['product_discount']['start_date'];
                                if(isset($query['product_discount']))
                                $v=$query['product_discount']['finish_date'];
                                return $v < $end_date;
                            });

        
                
        $object['code'] = 1;
        $object['msg'] = 'success';
        $object['data'] =$response;
        
        return response()->json((object)$object);   
    }
    }
    public function getProducts(Request $request)
    {   
        $shop_owner=AuthController::meme();
        Product::updateDiscountPrice();
        $chain_id = $request->input('chain_id');
        $category = $request->input('category_id');
        $barcode = $request->input('barcode');
        $supplier = $request->input('supplier_id');
        $keyWord = $request->input('keyword');
        $response=Product::where('shop_id',$shop_owner->store_id)
                        ->where('chain_id',$chain_id )
                        ->when($category != '', function ($query) use ($category) {
                        $query->where('category_id',$category);})
                        ->when( isset($supplier) && $supplier != '', function ($query) use ($supplier) {
                            $query->where('supplier_id',$supplier);})
                        ->when($barcode != '',  function ($q) use ($barcode)
                        {$q->where('product_barcode',$barcode)->get();})
                        ->when($keyWord != '', function ($q) use ($keyWord)
                        { $q->where('product_name', 'like', '%' . $keyWord . '%')
                        ->orWhere('product_description', 'like', '%' . $keyWord . '%');})
                        ->whereNull('discount_price')->orderBy('id','DESC')->paginate(20);                         
      
        return response()->json($response);   
    }
public function batchAddProductDiscount(Request $request)
    {
       
        $shop_owner = AuthController::meme();
        try {
            $post = $request->post();
            
            if (!isset($post['store_id'], $post['chain_id'], $post['products'])) {
                throw new \Exception('Missing parameter');
            }
            //echo 
            $product = json_decode($post['products'], 1);
            if (json_last_error()) {
                throw new \Exception(json_last_error_msg());
            }
            if (!count($product)) {
                throw new \Exception('no more product array');
            }
            // $prod=new Product();
            //var_dump($product);
            foreach ($product as $v) {

                if (!isset($v['discount_type'], $v['item_barcode'], $v['start_date'], $v['finish_date'], $v['discount_value1'], $v['discount_value2'])) {
                    $response['code'] = 0;
                    throw new \Exception("arguments are required");
                }
                $product = Product::where('product_barcode',$v['item_barcode'])->where('chain_id',$post['chain_id'])->first();
		        $id = $product->id;
                if(!$product){
                    $response['code'] = 0;
                    throw new \Exception('the product is not found');
                }
                else {
                    $id = $product->id;
                }
                switch ($v['discount_type']) {
                    case 1:
                        $discount_price = $product->unit_price - ($product->unit_price * (float)$v['discount_value1']) / 100;
                        break;
                    case 2:
                    case 3:
                    $discount_price = $product->unit_price - (float)$v['discount_value1'];
                    break;
                    case 4:
                        $discount_price = $v['discount_value1'];
                        break;
                    default:
                        $discount_price = 0;
                        break;
                }
		$product->discount_price = $discount_price;
		$product->save();
                Product::updateDiscountPrice($id);
                $discount = Product_discount::updateOrCreate(['product_id' => $id], [
                    'discount_value1' => $v['discount_value1'],
                    'discount_value2' => $v['discount_value2'],
		            'product_id' => $id,
                    'start_date' => $v['start_date'],
                    'finish_date' => $v['finish_date'],
                    'discount_id' => $v['discount_type'],
                    'store_id'=>$post['store_id']
                ]);

                
                

            }


            $response['code'] = 0;
            $response['msg'] = '';
            $response['data'] = "promotion has been added !!";
            return response()->json($response);

        } catch (\Exception $e) {
            $response['data'] = $e->getMessage();

            return response()->json($response);
        }

    }
public function updatePromotion(Request $request,$id) {
    $discount =Product_discount::where('id',$id)->first();
    if(!$discount) {
        return response()->json(["code"=>0,"msg"=>"discount not found"]);
    }
        else {
            Product::updateDiscountPrice();
            $discount->start_date = $request->input('start_date');
            $discount->finish_date = $request->input('finish_date');
            $discount->save();
            Product::updateDiscountPrice();
            return response()->json(["code"=>1,"msg"=>"promotion updated successfully"]);
        }
    }    
    public function cancelPromotion(Request $request,$id) {
        $promotion =Product_discount::where('id',$id)->first();
        if(!$promotion) {
            return response()->json(["code"=>0,"msg"=>"promotion not found"]);
        }
        else {
            Product::updateDiscountPrice();
            $prod=Product::find($promotion->product_id);
	    $discount_price=$prod->discount_price;
	   
            $prod->unit_price=$prod->unit_price+$discount_price;
            $prod->discount_price=null;
            $prod->save();
            $promotion->delete();
            return response()->json(["code"=>1,"msg"=>"promotion deleted successfully"]);
        }
    }
    public function cancelPromotions(Request $request) {
        $user = AuthController::meme();
        if(!$request->filled('discount_ids')) {
            return response()->json(['code'=>0,'msg'=>'missing argument discount_ids']);
        }
        $string = $request->input('discount_ids');
        $discount_ids = array_values(preg_split("/\,/", $string));
        foreach($discount_ids as $id) {
            $promotion =Product_discount::where('id',$id)->first();
            if(!$promotion) {
                return response()->json(["code"=>0,"msg"=>"promotion  with id ".$id ."not found"]);
            }
            else {
                Product::updateDiscountPrice();
                $prod=Product::find($promotion->product_id);
                $discount_price=$prod->discount_price;
                
                $prod->unit_price=$prod->unit_price+$discount_price;
                $prod->discount_price=null;
                $prod->save();
                $promotion->delete();
                
            }
        }
        return response()->json(["code"=>1,"msg"=>"promotions deleted successfully"]);
        
    }
    public function getDiscountById(Request $request,$id) {
        $discount =Product_discount::where('id',$id)->first();
        if(!$discount) {
            return response()->json(["code"=>0,"msg"=>"discount not found"]);
        }
        else {
           // Product::updateDiscountPrice();
            $response['code'] = 1;
            $response['msg'] = "success";
            $response['data'] = $discount;
            return response()->json($response);
        }
    }
    public function getDiscountProductsForWeb(Request $request)
    {
        $shop_owner=AuthController::meme();
        Product::updateDiscountPrice();
        $chain_id = $request->input('chain_id');
        
        $category = $request->input('category_id');
        
        $barcode = $request->input('barcode');
        $discount_id = $request->input('discount_id');
      
        $keyWord = $request->input('keyword');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        if (!$start_date) {

            $start_date = '';
            
        }
        if (!$end_date) {

            $end_date = '';
        }
        if (!$discount_id) {

            $discount_id = '';
        }
	
        $userChain = $shop_owner->chain_id;
        $products_id = DB::table('product_discount')->where('store_id',$shop_owner->store_id)->pluck('product_id');
        $response=Product::with('Discount','ProductDiscount')
         ->whereIn('id',$products_id)
         ->where('shop_id',$shop_owner->store_id)
         ->when(isset($userChain), function ($query) use ($userChain) {
            $query->where('chain_id',$userChain);})
                        ->when($chain_id != '', function ($query) use ($chain_id) {
                        $query->where('chain_id',$chain_id);})
                        
                        ->when($category != '', function ($query) use ($category) {
                        $query->where('category_id',$category);})
                        ->when($barcode != '',  function ($q) use ($barcode)
                        {$q->where('product_barcode',$barcode)->get();})
                        ->when($keyWord != '', function ($q) use ($keyWord){ $q->where('product_name', 'like', '%' . $keyWord . '%')
                            ->orWhere('product_description', 'like', '%' . $keyWord . '%');})
                            ->whereNotNull('discount_price')
                      ->get()->toArray();
                        
                      $response = array_filter($response,function($query) use($discount_id) {
                        //echo $query['product_discount']['start_date'];
                        if(isset($query['product_discount']))
                        $v=$query['product_discount']['discount_id'];
                        return $v != 2;
                    });
                        if(!empty($start_date)) {
                            
                            $response =array_filter($response,function($query) use($start_date) {
                               
                                if(isset($query['product_discount']))
                                $v=$query['product_discount']['start_date'];
                                return $v > $start_date;
                            });
                        }
                        if(!empty($end_date)) {
                          
                            $response = array_filter($response,function($query) use($end_date) {
                                //echo $query['product_discount']['start_date'];
                                if(isset($query['product_discount']))
                                $v=$query['product_discount']['finish_date'];
                                return $v < $end_date;
                            });
                        } if(!empty($discount_id)) {
                     
                            $response = array_filter($response,function($query) use($discount_id) {
                                //echo $query['product_discount']['start_date'];
                                if(isset($query['product_discount']))
                                $v=$query['product_discount']['discount_id'];
                                return $v == $discount_id;
                            });
                        }
                        $response = array_values($response);
        
                
        $object['code'] = 1;
        $object['msg'] = 'success';
        $object['data'] =$response;
        
        return response()->json((object)$object);   
    }
public function getDiscountProductsNByM(Request $request) {
	try {
        $shop_owner=AuthController::meme();
        Product::updateDiscountPrice();
        $chain_id = $request->input('chain_id');
        
        $category = $request->input('category_id');
        
        $barcode = $request->input('barcode');
        $discount_id = 2;
      
        $keyWord = $request->input('keyword');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        if (!$start_date) {

            $start_date = '';
            
        }
        if (!$end_date) {

            $end_date = '';
        }

        $userChain = $shop_owner->chain_id;
	    $products_id = DB::table('product_discount')->where('store_id',$shop_owner->store_id)->pluck('product_id');
        $response=Product::with('Discount','ProductDiscount')->whereIn('id',$products_id)->where('shop_id',$shop_owner->store_id)
         ->when(isset($userChain), function ($query) use ($userChain) {
            $query->where('chain_id',$userChain);})
         ->when($chain_id != '', function ($query) use ($chain_id) {
            $query->where('chain_id',$chain_id);})
                        
         ->when($category != '', function ($query) use ($category) {
            $query->where('category_id',$category);})
         ->when($barcode != '',  function ($q) use ($barcode)
            {$q->where('product_barcode',$barcode)->get();})
         ->when($keyWord != '', function ($q) use ($keyWord){ $q
            ->where('product_name', 'like', '%' . $keyWord . '%')
            ->orWhere('product_description', 'like', '%' . $keyWord . '%');})
         ->get()->toArray();

                        //var_dump($response["data"][0]);
                         if(!empty($start_date)) {
                          //echo $response;  
                            $response =array_filter($response,function($query) use($start_date) {
                                //echo gettype($query['product_discount']);
                                if(isset($query['product_discount'])){
                                $v=$query['product_discount']['start_date'];
                                return $v > $start_date;}
                            });
                        }
                        if(!empty($end_date)) {
                            //var_dump($response[0]);
                            $response = array_filter($response,function($query) use($end_date) {
                                //echo $query['product_discount']['start_date'];
                                if(isset($query['product_discount'])){
                                $v=$query['product_discount']['finish_date'];
                                return $v < $end_date;}
                            });
                        } if(!empty($discount_id)) {
                            //var_dump($response[0]);
                            $response = array_filter($response,function($query) use($discount_id) {
                                //echo $query['product_discount']['start_date'];
                                //var_dump($query['product_discount']);
                                if(isset($query['product_discount'])){
                                    $v=$query['product_discount']['discount_id'];
                                     return $v == $discount_id;
                                }
                                
                            });
                        }
                        $response = array_values($response);
        
                
        $object['code'] = 1;
        $object['msg'] = 'success';
        $object['data'] =$response;return response()->json((object)$object); }
catch (Exception $e) {
            return response()->json([
                'code' => 0, 'msg' => $e->getMessage().$e->getLine().$e->getFile()
            ]);

        }

        
        
    }
    public function getDiscountHistory(Request $request) 
    {
        $shop_owner=AuthController::meme();

        Product::updateDiscountPrice();
        $chain_id = $request->input('chain_id');
        
        $category = $request->input('category_id');
        
        $barcode = $request->input('barcode');
        $discount_id = $request->input('discount_id');
      
        $keyWord = $request->input('keyword');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        if (!$start_date) {

            $start_date = '';
            
        }
        if (!$end_date) {

            $end_date = '';
        }
        $date = Carbon::now()->format('Y-m-d');
        $today = Carbon::today();
        //echo $date;
        $userChain = $shop_owner->chain_id;
        $products_id = DB::table('product_discount')->where('store_id',$shop_owner->store_id)->pluck('product_id');
        $response=Product::with('Discount','ProductDiscount')
         ->whereIn('id',$products_id)
         ->where('shop_id',$shop_owner->store_id)
         ->when(isset($userChain), function ($query) use ($userChain) {
            $query->where('chain_id',$userChain);})
         ->when($chain_id != '', function ($query) use ($chain_id) {
            $query->where('chain_id',$chain_id);})
                        
         ->when($category != '', function ($query) use ($category) {
            $query->where('category_id',$category);})
         ->when($barcode != '',  function ($q) use ($barcode)
            {$q->where('product_barcode',$barcode)->get();})
         ->when($keyWord != '', function ($q) use ($keyWord){ $q
            ->where('product_name', 'like', '%' . $keyWord . '%')
            ->orWhere('product_description', 'like', '%' . $keyWord . '%');})
         ->get()->toArray();

          if(count($response)>0){
            $response = array_filter($response,function($query) use($date) {

                if(isset($query['product_discount'])) {
                    //echo $date;
                    $v=$query['product_discount']['discount_id'];
                    //echo $v;
                    return $v !=2;
                }
                
            });
                        if(!empty($start_date)) {
                            
                            $response =array_filter($response,function($query) use($start_date) {

                                if(isset($query['product_discount'])) {
                                $v=$query['product_discount']['start_date'];
                                return $v >= $start_date;}
                            });
                        }
                        if(!empty($end_date)) {
                            //var_dump($response[0]);
                            $response = array_filter($response,function($query) use($end_date) {

                                if(isset($query['product_discount'])) {
                                $v=$query['product_discount']['finish_date'];
                                return $v <= $end_date;}
                            });
                        } if(!empty($discount_id)) {
                            //var_dump($response[0]);
                            $response = array_filter($response,function($query) use($discount_id) {
                               
                                if(isset($query['product_discount'])){
                                    $v=$query['product_discount']['discount_id'];
                                     return $v == $discount_id;
                                }
                                
                            });
                        }
                        $response = array_filter($response,function($query) use($date) {
                          
                            if(isset($query['product_discount'])) {
                            
                                $v=$query['product_discount']['finish_date'];
                             
                                return $v < $date;
                            }
                            
                        });
                        $response = array_values($response);}
        
                
        $object['code'] = 1;
        $object['msg'] = 'success';
        $object['data'] =$response;
        
        return response()->json((object)$object); 
    }
    public function getpromotionHistory(Request $request) 
    {
        $shop_owner=AuthController::meme();

        Product::updateDiscountPrice();
        $chain_id = $request->input('chain_id');
        
        $category = $request->input('category_id');
        
        $barcode = $request->input('barcode');
        $discount_id = 2;
      
        $keyWord = $request->input('keyword');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        if (!$start_date) {

            $start_date = '';
            
        }
        if (!$end_date) {
   
            $end_date = '';
        }
        $date = Carbon::now()->format('Y-m-d');
        $today = Carbon::today();
        //echo $date;
        $userChain = $shop_owner->chain_id;
        $products_id = DB::table('product_discount')->where('store_id',$shop_owner->store_id)->pluck('product_id');
        $response=Product::with('Discount','ProductDiscount')
         ->whereIn('id',$products_id)
         ->where('shop_id',$shop_owner->store_id)
         ->when(isset($userChain), function ($query) use ($userChain) {
            $query->where('chain_id',$userChain);})
         ->when($chain_id != '', function ($query) use ($chain_id) {
            $query->where('chain_id',$chain_id);})
                        
         ->when($category != '', function ($query) use ($category) {
            $query->where('category_id',$category);})
         ->when($barcode != '',  function ($q) use ($barcode)
            {$q->where('product_barcode',$barcode)->get();})
         ->when($keyWord != '', function ($q) use ($keyWord){ $q
            ->where('product_name', 'like', '%' . $keyWord . '%')
            ->orWhere('product_description', 'like', '%' . $keyWord . '%');})
         ->get()->toArray();
	
          if(count($response)>0){
                        if(!empty($start_date)) {
                            
                            $response =array_filter($response,function($query) use($start_date) {
                                //echo gettype($query['product_discount']);
                                if(isset($query['product_discount'])) {
                                $v=$query['product_discount']['start_date'];
                                return $v >= $start_date;}
                            });
                        }
                        if(!empty($end_date)) {
                            //var_dump($response[0]);
                            $response = array_filter($response,function($query) use($end_date) {
                                //echo $query['product_discount']['start_date'];
                                if(isset($query['product_discount'])) {
                                $v=$query['product_discount']['finish_date'];
                                return $v <= $end_date;}
                            });
                        } if(!empty($discount_id)) {
                            //var_dump($response[0]);
                            $response = array_filter($response,function($query) use($discount_id) {
                                //echo $query['product_discount']['start_date'];
                                //var_dump($query['product_discount']);
                                if(isset($query['product_discount'])){
                                    $v=$query['product_discount']['discount_id'];
                                     return $v == $discount_id;
                                }
                                
                            });
                        }
                        $response = array_filter($response,function($query) use($date) {
                            //echo $query['product_discount']['start_date'];
                            if(isset($query['product_discount'])) {
                                //echo $date;
                                $v=$query['product_discount']['finish_date'];
                                //echo $v;
                                return $v < $date;
                            }
                            
                        });
                        $response = array_values($response);}
        
                
        $object['code'] = 1;
        $object['msg'] = 'success';
        $object['data'] =$response;
        
        return response()->json((object)$object); 
    }
}
