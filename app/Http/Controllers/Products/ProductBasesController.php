<?php namespace App\Http\Controllers\Products;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Models\Catalog;
use App\Models\Category;
use App\Models\CriteriaBase;
use App\Models\ProductBase;
use App\Models\ProductImage;
use App\Models\ProductItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use phpDocumentor\Reflection\Types\Object_;
use stdClass;

class ProductBasesController extends Controller {

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function addProductBase(Request $request)
    {
        
        $user = AuthController::me();
        if (!empty($user->chain_id)) // if the user is a shopManager
        { $user= $user->ManagerShopOwner()->first();}
        $product_base = new ProductBase();
        // $catalog = Catalog::find(1);
        $product_base->chain_id = $request->input('chain_id');
        $product_base->product_name = $request->input('product_name');
        $product_base->product_description = $request->input('product_description');
        $product_base->taxe_rate = $request->input('taxe_rate');
        $product_base->supplier_id = $request->input('supplier_id');
        $product_base->category_id = $request->input('category_id');
        $product_base->brand_id = $request->input('brand_id');
        $product_base->product_image_url = $request->input('product_image_url');
        $product_base->shop_owner_id = $user->id;
        $product_items = $request->input('items');
        
        if ($product_base->save()) {
            foreach ($product_items as $items) {
                $item = (object) $items;
                $product_item = new ProductItem();
                $product_item->item_offline_price = isset($item->item_offline_price) ? $item->item_offline_price : null;
                $product_item->item_online_price = isset($item->item_online_price) ? $item->item_online_price : null;
                $product_item->unit_price = isset($item->unit_price) ? $item->unit_price : null;
                $product_item->cost_price = isset($item->cost_price) ? $item->cost_price : null;
                $product_item->item_barcode = $item->item_barcode;
                $product_item->item_quantity = $item->item_quantity;
                $product_item->item_warn_quantity = isset($item->item_warn_quantity) ? $item->item_warn_quantity : null;
                $product_item->item_discount_type = isset($item->item_discount_type) ? $item->item_discount_type : null;
                $product_item->item_discount_price = isset($item->item_discount_price) ? $item->item_discount_price : null;
                $product_item->member_price = isset($item->member_price) ? $item->member_price : null;
                $product_item->member_point = isset($item->member_point) ? $item->member_point : null;
                $product_item->product_base_id = $product_base->id;
                $criteria_list = $item->criteria_list;
                $item_images = $item->image_list;
                if ($product_item->save()) {
                    foreach ($criteria_list as $criteriaItems) {
                        $criteriaItem = (object) $criteriaItems;
                        $criteria = CriteriaBase::find($criteriaItem->criteria_id);
                        $product_item->CriteriaBase()->attach($criteria, ["criteria_value" => $criteriaItem->criteria_value, "criteria_unit_id" => $criteriaItem->criteria_unit_id]);
                    }
                    foreach ($item_images as $key => $image_id) {
                        $item_image = ProductImage::find($image_id);
                        $item_image->product_item_id = $product_item->id;
                        $item_image->save();
                    }
                    
                }
            };
            $response = array();
            $response['code']= 1;
            $response['msg']='';
            $response['data']='Product has been saved';
            return response()->json($response, 200);
        }
        $response = array();
        $response['code']= 0;
        $response['msg']='3';
        $response['data']='Error while saving';
        return response()->json($response, 500);
    }

    public function updateProductBase(Request $request)
    {
        $user = AuthController::me();
        $product_base_id = $request->input('product_base_id');
        $product_base = ProductBase::find($product_base_id);
        $product_base->product_name = $request->input('product_name');
        $product_base->product_description = $request->input('product_description');
        $product_base->taxe_rate = $request->input('taxe_rate');
        // $product_base->supplier_id = $supplier->id;
        $product_base->category_id = $request->input('category_id');
        $product_base->brand_id = $request->input('brand_id');
        $product_items = $request->input('items');
        if ($product_base->save()) {
            foreach ($product_items as $items) {
                $sync_array = array();
                $item = (object) $items;
                $product_item = ProductItem::find($item->item_id);
                $product_item->item_offline_price = isset($item->item_offline_price) ? $item->item_offline_price : null;
                $product_item->item_online_price = isset($item->item_online_price) ? $item->item_online_price : null;
                $product_item->unit_price = isset($item->unit_price) ? $item->unit_price : null;
                $product_item->cost_price = isset($item->cost_price) ? $item->cost_price : null;
                // $product_item->item_barcode = $item->item_barcode;
                $product_item->item_quantity = $item->item_quantity;
                $product_item->item_warn_quantity = isset($item->item_warn_quantity) ? $item->item_warn_quantity : null;
                $product_item->item_discount_type = isset($item->item_discount_type) ? $item->item_discount_type : null;
                $product_item->item_discount_price = isset($item->item_discount_price) ? $item->item_discount_price : null;
                $product_item->member_price = isset($item->member_price) ? $item->member_price : null;
                $product_item->member_point = isset($item->member_point) ? $item->member_point : null;
                // $product_item->product_base_id = $product_base->id;
                $criteria_list = $item->criteria_list;
                $item_images = $item->image_list;
                if ($product_item->save()) {
                    foreach ($criteria_list as $criteriaItems) {
                        $criteriaItem = (object) $criteriaItems;
                        $sync_array[$criteriaItem->criteria_id] = ["criteria_value" => $criteriaItem->criteria_value, "criteria_unit_id" => $criteriaItem->criteria_unit_id ] ; 
                    }
                    $product_item->CriteriaBase()->sync($sync_array);
                    foreach ($item_images as $key => $image_id) {
                        $item_image = ProductImage::find($image_id);
                        $item_image->product_item_id = $product_item->id;
                        $item_image->save();
                    }
                }
                else {
                    $response = array();
                    $response['code']= 0;
                    $response['msg']='3';
                    $response['data']='error while saving';
                    return response()->json($response, 500);
                }
                
            };
            $response = array();
            $response['code']= 1;
            $response['msg']='';
            $response['data']='Product has been saved';
            return response()->json($response, 200);
        }
        else {
            $response = array();
            $response['code']= 0;
            $response['msg']='3';
            $response['data']='error while saving';
            return response()->json($response, 500);
        }
    }

    public function deleteProductBase(Request $request)
    {
        $product_base_id = $request->input('product_id');
        $product = ProductBase::with('items')->find($product_base_id);
        foreach ($product->items as $item) 
        {
            $item->Images()->delete();
            $item->CriteriaBase()->detach();
        }
        $product->items()->delete();
        if ($product->delete()) {
            $response = array();
            $response['code']=1;
            $response['msg']='';
            $response['data']='Product has been deleted';
            return response()->json($response, 200);
        } 
        $response = array();
        $response['code']= 0;
        $response['msg']='3';
        $response['data']='error while saving';
        return response()->json($response, 500);
        

    }

    public function getProductList(Request $request)
    {
        $user = AuthController::me();
        $chain_id = $request->input('chain_id');
        $response= ProductBase::with(['brand','category','items'=>function ($query)
        {$query->with('CriteriaBase','images')->get();}])
             ->where('chain_id',$chain_id)->paginate(60)->toArray();
        $response['code']=1;
        $response['msg']='';
        return response()->json($response, 200);
    }

    public function getProduct(Request $request)
    {
        $supplier = AuthController::me();
        $product_id = $request->query('product_id');
        $product= ProductBase::with(['brand','category','items'=>function ($query)
        {$query->with('CriteriaBase','images')->get();}])
        // ->where('supplier_id',$supplier->id)
        ->where('id',$product_id)->get();
        
        $response = array();
        $response['code']=1;
        $response['msg']='';
        $response['data']= $product;
        return response()->json($response, 200);
    }


    public function getProductsCategories(Request $request)
    {
        $chain_id = $request->query('chain_id');
        $user = AuthController::me();

        if (!empty($user->chain_id)) // if the user is a shopManager
        {  $user= $user->ManagerShopOwner()->first(); } //change the user to shopOwner
         
        $categories = $user->Categories($chain_id)->get()
        ->map(function($query) { 
            $query=$query->toArray();
            $query=array_map('strval', $query);
            return  $query; });

        $response = array();
        $response['code'] = 1 ;
        $response['msg'] = "";
        $response['data'] = $categories;
        return response()->json($response); 
    }

    public function searchProduct(Request $request)
    {
        $key_word = $request->input('key_word');
        if (is_numeric($key_word)) {
            $items = ProductItem::with('CriteriaBase','images')->where('item_barcode', $key_word)->get();
            if (!$items->isEmpty()) {
                $product_base = $items[0]->Product()->first();
                $product_base->items = $items;
                $response = array();
                $response['code'] = 1 ;
                $response['msg'] = "";
                $response['data'] = $product_base;
                return response()->json($response);
            }
            return response()->json(["msg" => "Item not found "]);
        } else {
            $product_base = ProductBase::with(['items'=>function ($query)
            {$query->with('CriteriaBase','images')->get();}])
            ->where('product_name', $key_word)->get();
            $response = array();
            $response['code'] = 1 ;
            $response['msg'] = "";
            $response['data'] = $product_base;
            return response()->json($response);
        }
    }
}
