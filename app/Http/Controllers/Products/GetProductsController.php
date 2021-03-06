<?php namespace App\Http\Controllers\Products;
/*
SIYOU THECH Tunisia
Author: Habiba Boujmil
Accessible for : ShopOwner / ShopManager
*/

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Chain;
use App\Models\Supplier;
use App\Models\ProductDiscount;
use App\Models\QuickPrint;
use App\Models\Discount;
use App\Models\User;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
class GetProductsController extends Controller {

    public function __construct()
    {
        $this->middleware('auth:api');
    }
     /* get products list of one chain API
     - Necessary Parameters: 'token','store_id','chain_id'
     - optional Parameters to get filtred list: 'barcode','supplier_id','category_id'
    */
    public function shopProductList(request $request)
    {
//echo 'hello';
        $shop_owner=AuthController::meme();
        $chain_id = $request->input('chain_id');
        $category = $request->input('category_id');
        $barcode = $request->input('barcode');
        $keyWord = $request->input('keyword');
	$range_id = $request->input('range_id');
	$supplier_id = $request->query('supplier_id');
	$userChain=$shop_owner->chain_id;
        if(!$range_id) {
            $range_id = '';
        }
	  if(!$supplier_id) {
            $supplier_id = '';
        }
//echo $supplier_id.'supplier_id';

        $response=Product::with('category','supplier')
                        ->where('shop_id',$shop_owner->store_id)
			->when($userChain != '', function ($query) use ($userChain) {
                        $query->where('chain_id',$userChain);})

                        ->when($chain_id != '', function ($query) use ($chain_id) {
                        $query->where('chain_id',$chain_id);})
			->when($range_id != '', function ($query) use ($range_id) {
                        $query->where('range_id',$range_id);})
			->when($supplier_id != '', function ($query) use ($supplier_id) {
                        $query->where('supplier_id',$supplier_id);})

                        ->when($category != '', function ($query) use ($category) {
                        $query->where('category_id',$category);})
                        ->when($barcode != '',  function ($q) use ($barcode)
                        {$q->where('product_barcode','like','%'.$barcode. '%')->get();})
                        ->when($keyWord != '', function ($q) use ($keyWord)
                        { $q->where('product_name', 'like', '%' . $keyWord . '%');})
                        ->orderBy('id','DESC')->paginate(20)->toArray();
        $response['code']=1;
        $response['msg']='';
        return response()->json($response);
    }
 public function shopProductList1(request $request)
    {
        $shop_owner=AuthController::meme();
        $chain_id = $request->input('chain_id');
        $category = $request->input('category_id');
        $barcode = $request->input('barcode');
        $keyWord = $request->input('keyword');
        $range_id = $request->input('range_id');
	$supplier_id = $request->query('supplier_id');

        if(!$range_id) {
            $range_id = '';
        }
	 if(!$supplier_id) {
            $supplier_id = '';
        }
//echo $supplier_id.'supplier_id';
        $userChain = $shop_owner->chain_id;
        //echo $userChain;
        $response=Product::with('category','supplier','ProductDiscount')
                        ->where('shop_id',$shop_owner->store_id)
                        ->when(isset($userChain), function ($query) use ($userChain) {
                            $query->where('chain_id',$userChain);})
                        ->when($chain_id != '', function ($query) use ($chain_id) {
                        $query->where('chain_id',$chain_id);})
                        ->when($range_id != '', function ($query) use ($range_id) {
                        $query->where('range_id',$range_id);})
			->when($supplier_id != '', function ($query) use ($supplier_id) {
                        $query->where('supplier_id',$supplier_id);})
                        
    

                        ->when($category != '', function ($query) use ($category) {
                        $query->where('category_id',$category);})
                        ->when($barcode != '',  function ($q) use ($barcode)
                        {$q->where('product_barcode',$barcode)->get();})
                        ->when($keyWord != '', function ($q) use ($keyWord)
                        { $q->where('product_name', 'like', '%' . $keyWord . '%')->orWhere('product_barcode','like','%'.$keyWord.'%');})
                        ->orderBy('id','DESC')->paginate(20)->toArray();
        $response['code']=1;
        $response['msg']='';
        return response()->json($response);
    }
	 public function chainProductList(request $request)
    {
        $user = AuthController::meme();
        $userchain=$user->chain_id;
        $category_id = $request->query('category_id');
        $supplier_id = $request->query('supplier_id');
        $chain_id = $request->query('chain_id');
    $keyword = $request->query('keyword');
        $barcode = $request->query('barcode');
    $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
    $range_id = $request->input('range_id');
        if (!$start_date) {
            //$start_time = Date('Y-m-d 00:00:00', strtotime('0days'));
            $start_date = '';
            
        }
        if (!$end_date) {
            //$end_time = Date('Y-m-d 23:59:59', time());
            $end_date = '';
        }
    $where=[];
        if($category_id){
            $where['category_id']=$category_id;
        }
    if($range_id){
            $where['range_id']=$range_id;
        }

        // if($barcode){
        //     $where['product_barcode']=$barcode;
        // }
      if($keyword){
            $where['product_name']=$keyword;
        }

        if($chain_id){
            $where['chain_id']=$chain_id;
        }
        if($supplier_id){
            $where['supplier_id']=$supplier_id;
        }
   
       
   
    //var_dump($chains_id);
    $response = Product::select('products.*')->with('discount')->where($where)
       ->where('products.shop_id',$user->store_id)
           ->when($start_date != '', function ($query) use ($start_date) {
            $query->where('products.updated_at', '>=',  $start_date);})
            ->when($barcode != '' && isset($barcode) && is_numeric($barcode), function ($query) use ($barcode) {
                $query->where('products.product_barcode',$barcode);})
            ->when($barcode != '' && isset($barcode) && !is_numeric($barcode), function ($query) use ($barcode) {
                    $query->where('products.product_name','like','%'.$barcode .'%');}) 
             ->when($userchain != '', function ($query) use ($userchain) {
            $query->where('products.chain_id',  $userchain);})
            ->when($end_date != '', function ($query) use ($end_date) {
                $query->where('products.updated_at', '<=',  $end_date);})
           //->whereBetween('products.updated_at',[$start_date,$end_date])
           // get();
           ->orderBy('id','desc')->paginate(50)-> toArray();
//appends($request->except('page'));;

        $response['code']=1;
        $response['msg']='';
       

 
        
        return response()->json($response, 200);

        if ($request->filled('barcode')) {
            $response = product::where('chain_id', $chain_id)
            ->where('product_barcode', $barcode)->paginate(60)->toArray();
                   }
        elseif ($request->filled('supplier_id','category_id'))
        {
            $response = product::where('chain_id', $chain_id)
            ->where([["category_id",$category_id],
            ["supplier_id",$supplier_id]])->paginate(60)->toArray();
           
        } 
      
        elseif ($request->filled('category_id')) {
            $response = product::where('chain_id', $chain_id)
            ->where('category_id', $category_id)->paginate(60)->toArray();
            
        }
        elseif ($request->filled('supplier_id')) {
            $response = product::where('chain_id', $chain_id)
            ->where('supplier_id', $supplier_id)->paginate(60)->toArray();   
        }
        elseif ($request->filled('keyword')) {
            $response = product::where('chain_id', $chain_id)
            ->where('supplier_id', $supplier_id)->paginate(60)->toArray();   
        }
        else 
        { $response = product::where('chain_id', $chain_id)->paginate(60)->toArray();}      
        $response['code']=1;
        $response['msg']='';
 
        
        return response()->json($response, 200);
    }

    public function chainProductList1(request $request)
    {
        $category_id = $request->query('category_id');
        $supplier_id = $request->query('supplier_id');
        $chain_id = $request->query('chain_id');
        $barcode = $request->query('barcode');
        $row = $request->get('row',20);
        $shop_Owner = AuthController::meme();
        //$start_date = Carbon::parse($request->input('start_date'))->toDateTimeString();
        //$end_date = Carbon::parse( $request->input('end_date'))->toDateTimeString();

        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        if (!$start_date) {
            //$start_time = Date('Y-m-d 00:00:00', strtotime('0days'));
            $start_date = '';
            
        }
        if (!$end_date) {
            //$end_time = Date('Y-m-d 23:59:59', time());
            $end_date = '';
        }
        $where=[];
        if($category_id){
            $where['category_id']=$category_id;
        }
        if($barcode){
            $where['product_barcode']=$barcode;
        }
        if($chain_id){
            $where['chain_id']=$chain_id;
        }
        if($supplier_id){
            $where['supplier_id']=$supplier_id;
        }
        // echo $start_date;
        // echo $end_date;
        // if (!$start_date || !$end_date){
        //     throw new \Exception("arguments are required");
        // }
    //$chains_id=$shop_Owner->shop->chains()->pluck('id');
    $store_id=$shop_Owner->store_id;
    
       $data= product::where($where)
           ->select('products.*')->with('ProductDiscount')
           //->whereIn('chain_id',$chains_id)
            ->where('shop_id',$store_id)
           ->when($start_date != '', function ($query) use ($start_date) {
            $query->where('products.updated_at', '>=',  $start_date);})
            ->when($end_date != '', function ($query) use ($end_date) {
                $query->where('products.updated_at', '<=',  $end_date);})
           //->whereBetween('products.updated_at',[$start_date,$end_date])
           ->paginate($row)->appends($request->except('page'));;

        $response['code']=1;
        $response['msg']='';
        $response['data']=$data;

        // if ($request->filled('barcode')) {
        //     $response = product::where('chain_id', $chain_id)
        //     ->where('product_barcode', $barcode)->paginate(60)->toArray();
        //     // $discount = product::where('chain_id', $chain_id)
        //     // ->where('product_barcode', $barcode)
        //     // ->with('discount')->exists();
        //     // if ($discount) {
        //     //     return response()->json($discount);
        //     // }
        //     // ->get()->map(function($query) { 
        //     // $query=$query->toArray();
        //     // $query=array_map('strval', $query);
        //     // return  $query; });
        // }
        // elseif ($request->filled('supplier_id','category_id'))
        // {
        //     $response = product::where('chain_id', $chain_id)
        //     ->where([["category_id",$category_id],
        //     ["supplier_id",$supplier_id]])->paginate(60)->toArray();
           
        // } 
      
        // elseif ($request->filled('category_id')) {
        //     $response = product::where('chain_id', $chain_id)
        //     ->where('category_id', $category_id)->paginate(60)->toArray();
            
        // }
        // elseif ($request->filled('supplier_id')) {
        //     $response = product::where('chain_id', $chain_id)
        //     ->where('supplier_id', $supplier_id)->paginate(60)->toArray();   
        // }
        // else 
        // { $response = product::where('chain_id', $chain_id)->paginate(60)->toArray();}      
        // $response['code']=1;
        // $response['msg']='';
 
        
        return response()->json($response, 200);
    }   
     /* get one product in chain API
     - Necessary Parameters: 'token','chain_id',item_barcode
     - optional Parameters: 
    */
    public function getProduct(Request $request)
    {
        $shop_Owner = AuthController::me();
        $chain_id = $request->query('chain_id');
	//echo $chain_id."chain_id";
        $barcode=$request->query('item_barcode');
	//echo $barcode;
        $product = product::where('chain_id', $chain_id)
        ->where('product_barcode',$barcode)->first();
        
        if (!$product) {
            $response = array();
            $response['code']=0;
            $response['msg']='1';
            $response['data']='product does not exist';
            return response()->json($response); 
        }
//var_dump(chain::find($chain_id));
	$discount = DB::table('product_discount')->where('product_id',$product->id)->first();
	if($discount){
	$discount_start_date = $discount->start_date;
	$discount_end_date = $discount->finish_date;
}else{$discount_start_date = null;
	$discount_end_date = null;
}
        $product->discount_start_date= $discount_start_date;
	$product->discount_end_date= $discount_end_date;

	$product->chain_name= Chain::where('id',$chain_id)->first()->chain_name;
	$supplier = Supplier::find($product->supplier_id);
        if(!$supplier) {
            $product->supplier_name= null;
            $product->company_name= null;
        }else {
        $product->supplier_name= $supplier->supplier_name;
        $product->company_name= $supplier->company_name;
        }
//chain::find($chain_id)->value('chain_name');
        $response = array();
        $response['code']=1;
        $response['msg']='';
        $response['data']=array_map('strval',$product->toArray());
        return response()->json($response);   
    }
public function getProductById(Request $request,$id)
    {
        $shop_Owner = AuthController::me();
	//var_dump($shop_Owner);
        //$chain_id = $request->query('chain_id');
        //$barcode=$request->query('barcode');
        $chains_id=$shop_Owner->shop->chains()->pluck('id');
	//var_dump($chains_id);
        $product = product::whereIn('chain_id',$chains_id)->where('id', $id)->first();
        
        if (!$product) {
            $response = array();
            $response['code']=0;
            $response['msg']='1';
            $response['data']='product does not exist';
            return response()->json($response); 
        }
        //$product->chain_name= chain::find($chain_id)->value('chain_name');
        $response = array();
        $response['code']=1;
        $response['msg']='';
        $response['data']=array_map('strval',$product->toArray());
        return response()->json($response);   
    }

      /* get the list of categories throught chain's products 
     - Necessary Parameters: 'token','chain_id'
     - optional Parameters: 
    */
    public function getProductsCategories(Request $request)
    {
        $chain_id = $request->query('chain_id');
        //echo'hi';

        $user = AuthController::me();
        //echo $user;
        if (!empty($user->chain_id)) // if the user is a shopManager
        {  $user= $user->ManagerShopOwner()->first(); 
        //echo $user;
    } //change the user to shopOwner

         
        $categories = $user->Categories($chain_id)->get()
        ->map(function($query) { 
            $query=$query->toArray();
            $query=array_map('strval', $query);
            return  $query; });
//echo $categories;
        $response = array();
        $response['code'] = 1 ;
        $response['msg'] = "";
        $response['data'] = $categories;
        return response()->json($response); 
    }

    
    /* generate EAN13 Barcode API
     - Necessary Parameters: 'token','chain_id'
     - optional Parameters: 
    */
    public function generateBarcode(Request $request)
    {
        $user = AuthController::me();

        if (!empty($user->chain_id)) 
        {  $user= $user->ManagerShopOwner()->first(); } 
    
        $new_barcode = $this->newBarcode();
        
        $products = $user->products()->pluck('product_barcode')->toArray();
       
            while (in_array($new_barcode , $products, true)) 
            {
                $new_barcode = $this->newBarcode();
            }
        
            $response = array();
            $response['code'] = 1 ;
            $response['msg'] = "";
            $response['data'] = $new_barcode;
            return response()->json($response); 

    }

    public static function newBarcode()
    {
        $number = rand(pow(10, 7) - 1, pow(10, 8) - 1);
        $barcode = str_split($number);
        $sum=0;
        foreach ($barcode as $key => $num)
        {
            if($key % 2 == 0)  {$sum=$sum+$num;}
            else{ $sum=$sum+$num*3;}
        }

        if(($sum+2) % 10 == 0 ){ array_push($barcode,0);}
        else { array_push($barcode,10-(($sum+2) % 10));}
        array_unshift($barcode, "2","0","0","0");

        $new_barcode = implode('', $barcode);
        return $new_barcode;
    }

public function getLabels(Request $request)
    {
        $shop_owner = AuthController::me();
        $chain_id = $request->query('chain_id');
        $store_id = $request->query('store_id');
        $page = $request->query('page');
        $rows = $request->query('rows', 20);
        if (!$chain_id || !$store_id)
        {
            throw new \Exception("arguments are required");
        }
        $data = QuickPrint::where(['chain_id'=>$chain_id])->with(['products'=>function($query) use($chain_id){
            {$query->where('chain_id',$chain_id)->get();}
        }])->with(['chains'=>function($query) use($store_id){
            {$query->where('store_id',$store_id)->get();}
        }])->paginate($rows);
        $response = array();
        $response['code'] = 1 ;
        $response['msg'] = "";
        $response['data'] = $data;
        return response()->json($response, 200);
    }

    public function deleteLabels(Request $request)
    {
        $id = $request->input('id');
        $ids = explode(',', $id);
        if (!$id)
        {
            throw new \Exception("arguments are required");
        }

        $label = QuickPrint::destroy(collect($ids));
        if($label) {
                $response = array();
                $response['code']= 1;
                $response['msg']='';
                $response['data']='Label has been removed';
                return response()->json($response, 200);
        }
        $response = array();
        $response['code']= 0;
        $response['msg']='3';
        $response['data']='error while saving';
        return response()->json($response, 500);
    }
 

    public function getLabels1(Request $request)
    {
        $shop_owner = AuthController::meme();
        $chain_id = $request->input('chain_id');
	
        $store_id = $shop_owner->store_id;
	
        $page = $request->query('page');
        $rows = $request->query('rows', 20);
        if (!$chain_id || !$store_id)
        {
            throw new \Exception("arguments are required");
        }

        $data = QuickPrint::where(['chain_id'=>$chain_id])
            // ->with(['products1'=>function($query) use($chain_id){
            //     {$query->where('chain_id',$chain_id)->get();}
            // }])->with(['chains'=>function($query) use($store_id){
            //     {$query->where('store_id',$store_id)->get();}
            // }])
        ->paginate($rows);
        foreach($data as $quick) {
            $quick->products=Product::where('product_barcode',$quick->product_barcode)->where('chain_id',$chain_id)->with('ProductDiscount')->first();
            $quick->chains=Chain::find($chain_id);
        }
        $response = array();
        $response['code'] = 1 ;
        $response['msg'] = "";
        $response['data'] = $data;
        return response()->json($response, 200);
    }

    public function chainProductList2(request $request)
    {
        $category_id = $request->input('category_id');
        $supplier_id = $request->input('supplier_id');
        $chain_id = $request->input('chain_id');
        $barcode = $request->input('barcode');
        $keyword = $request->input('keyword');
        $row = $request->input('row',50);
        $shop_Owner = AuthController::me();
        

        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        if (!$start_date) {
           
            $start_date = '';
            
        }
        if (!$keyword) {
           
            $keyword = '';
            
        }
        if (!$end_date) {
         
            $end_date = '';
        }
        //$where=[];
        if(!$category_id){
            $where['category_id']=$category_id;
            $category_id = '';
        }
        if(!$barcode){
            $where['product_barcode']=$barcode;
            $barcode = '';
        }
        if(!$chain_id){
            //$where['chain_id']=$chain_id;
            $chain_id = '';
        }
        if(!$supplier_id){
            //$where['supplier_id']=$supplier_id;
            $supplier_id = '';
        }
        //var_dump($shop_Owner->shop);
        //$chains_id=$shop_Owner->shop->chains()->pluck('id');
        //echo $chains_id;
        if($shop_Owner->role_id == 1) {
            $id = $shop_Owner->id;
        }else {
            $id = $shop_Owner->shop_owner_id;
        }
        $userChain = $shop_Owner->chain_id;
       $response= Product::with('ProductDiscount')
            ->where('shop_owner_id',$id)
            ->when(isset($userChain), function ($query) use ($userChain) {
                $query->where('chain_id',$userChain);})
           ->when($start_date != '', function ($query) use ($start_date) {
            $query->where('updated_at', '>=',  $start_date);})
            ->when($end_date != '', function ($query) use ($end_date) {
                $query->where('updated_at', '<=',  $end_date);})
            ->when($category_id != '', function ($query) use ($category_id) {
                $query->where('category_id', '=',  $category_id);})
            
            ->when($barcode != '', function ($query) use ($barcode) {
                $query->where('product_barcode', '=',  $barcode);})
            ->when($chain_id != '', function ($query) use ($chain_id) {
                $query->where('chain_id', '=',  $chain_id);})
            ->when($supplier_id != '', function ($query) use ($supplier_id) {
                $query->where('supplier_id', '=',  $supplier_id);})
            ->when($keyword != '', function ($q) use ($keyword)
                { $q->where('product_name','like','%'.$keyword.'%')
                    ->orWhere('product_barcode','like','%'.$keyword.'%');})
            ->paginate(20);
           //->appends($request->except('page'));

        $response->code=1;
        $response->msg='';
        //$response['data']=$data;

      
 
        
        return response()->json($response, 200);
    }
public function shopProductListExpiration(request $request)
    {
//echo 'hello';
        $shop_owner=AuthController::meme();
        $chain_id = $request->input('chain_id');
        $category = $request->input('category_id');
        $barcode = $request->input('barcode');
        $keyWord = $request->input('keyword');
	$range_id = $request->input('range_id');
	$supplier_id = $request->query('supplier_id');
        if(!$range_id) {
            $range_id = '';
        }
	  if(!$supplier_id) {
            $supplier_id = '';
        }
//echo $supplier_id.'supplier_id';
        $expired=date('Y-m-d', strtotime("-3 days"));
	$userChain = $shop_owner->chain_id;
        $response=Product::with('category','supplier')
                        ->where('shop_id',$shop_owner->store_id)
                        ->when($chain_id != '', function ($query) use ($chain_id) {
                        $query->where('chain_id',$chain_id);})
			->when($userChain != '', function ($query) use ($userChain) {
                        $query->where('chain_id',$userChain);})

			->when($range_id != '', function ($query) use ($range_id) {
                        $query->where('range_id',$range_id);})
			->when($supplier_id != '', function ($query) use ($supplier_id) {
                        $query->where('supplier_id',$supplier_id);})

                        ->when($category != '', function ($query) use ($category) {
                        $query->where('category_id',$category);})
                        ->when($barcode != '',  function ($q) use ($barcode)
                        {$q->where('product_barcode','like','%'.$barcode. '%')->get();})
                        ->when($keyWord != '', function ($q) use ($keyWord)
                        { $q->where('product_name', 'like', '%' . $keyWord . '%');})
                        ->when($expired != '', function ($query) use ($expired) {
                            $query->where('expired_date','=',$expired);})
                        ->orderBy('id','DESC')->paginate(20)->toArray();
        $response['code']=1;
        $response['msg']='';
        return response()->json($response);
    }
    public function shopProductListWarningQuantity(request $request)
    {
//echo 'hello';
        $shop_owner=AuthController::meme();
        $chain_id = $request->input('chain_id');
        $category = $request->input('category_id');
        $barcode = $request->input('barcode');
        $keyWord = $request->input('keyword');
	$range_id = $request->input('range_id');
	$supplier_id = $request->query('supplier_id');
        if(!$range_id) {
            $range_id = '';
        }
	  if(!$supplier_id) {
            $supplier_id = '';
        }
//echo $supplier_id.'supplier_id';
        
	$userChain = $shop_owner->chain_id;
        $response=Product::with('category','supplier')
                        ->where('shop_id',$shop_owner->store_id)
                        ->when($chain_id != '', function ($query) use ($chain_id) {
                        $query->where('chain_id',$chain_id);})
                        ->whereColumn('product_quantity', 'warn_quantity')
			->when($userChain != '', function ($query) use ($userChain) {
                        $query->where('chain_id',$userChain);})

			->when($range_id != '', function ($query) use ($range_id) {
                        $query->where('range_id',$range_id);})
			->when($supplier_id != '', function ($query) use ($supplier_id) {
                        $query->where('supplier_id',$supplier_id);})

                        ->when($category != '', function ($query) use ($category) {
                        $query->where('category_id',$category);})
                        ->when($barcode != '',  function ($q) use ($barcode)
                        {$q->where('product_barcode','like','%'.$barcode. '%')->get();})
                        ->when($keyWord != '', function ($q) use ($keyWord)
                        { $q->where('product_name', 'like', '%' . $keyWord . '%');})
                        
                        ->orderBy('id','DESC')->paginate(20)->toArray();
        $response['code']=1;
        $response['msg']='';
        return response()->json($response);
    }
}
