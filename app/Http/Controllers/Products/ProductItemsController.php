<?php namespace App\Http\Controllers\Products;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Models\CriteriaBase;
use App\Models\ProductBase;
use App\Models\ProductImage;
use App\Models\ProductItem;
use App\Models\item_criteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use phpDocumentor\Reflection\Types\Object_;
use stdClass;

class ProductItemsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function addItem(Request $request)
    {
        $supplier = AuthController::me();
        $product_base_id = $request->input('product_base_id');
        $product_item = new ProductItem();
        $product_item->item_offline_price = $request->input('item_offline_price');
        $product_item->item_online_price = $request->input('item_online_price');
        $product_item->unit_price = $request->input('unit_price');
        $product_item->cost_price = $request->input('cost_price');
        $product_item->item_barcode = $request->input('item_barcode');
        $product_item->item_quantity = $request->input('item_quantity');
        $product_item->item_warn_quantity = $request->input('item_warn_quantity');
        $product_item->item_discount_type = $request->input('item_discount_type');
        $product_item->item_discount_price = $request->input('item_discount_price');
        $product_item->member_price = $request->input('member_price');
        $product_item->member_point = $request->input('member_point');
        $product_item->product_base_id = $product_base_id;
        $criteria_list = $request->input('criteria_list');
        if ($product_item->save()) {
            foreach ($criteria_list as $criteriaItems) {
                $criteriaItem = (object) $criteriaItems;
                $criteria = CriteriaBase::find($criteriaItem->criteria_id);
                $product_item->CriteriaBase()->attach($criteria, ["criteria_value" => $criteriaItem->criteria_value, "criteria_unit_id" => $criteriaItem->criteria_unit_id]);
            }
            if ($request->filled('item_images')) {
                $item_images =$request->input('item_images');
                foreach ($item_images as $key => $image_id) {
                    $item_image = ProductImage::find($image_id);
                    $item_image->product_item_id = $product_item->id;
                    $item_image->save();
                }
            }  
        }
        $response = array();
        $response['code']= 1;
        $response['msg']='';
        $response['data']='Item has been saved';
        return response()->json($response, 200);
    }

    public function addItemCriteria(Request $request)
    {
        $supplier = AuthController::me();
        $item_id = $request->input('item_id');
        $criteria_list = $request->input('criteria_list');
        $product_item = ProductItem::find($item_id);
        foreach ($criteria_list as $criteriaItems) {
            $criteriaItem = (object) $criteriaItems;
            $criteria = CriteriaBase::find($criteriaItem->criteria_id);
            $product_item->CriteriaBase()->attach($criteria, ["criteria_value" => $criteriaItem->criteria_value, "criteria_unit_id" => $criteriaItem->criteria_unit_id]);
        }
        $response = array();
        $response['code']= 1;
        $response['msg']='';
        $response['data']='Item criteria has been saved';
        return response()->json($response, 200);
    }


    public function updateItem(Request $request)
    {
        $sync_array = array();
        $item_id = $request->input('item_id');
        $product_item = ProductItem::find($item_id);
        $product_item->item_offline_price = $request->input('item_offline_price');
        $product_item->item_online_price = $request->input('item_online_price');
        $product_item->unit_price = $request->input('unit_price');
        $product_item->cost_price = $request->input('cost_price');
        // $product_item->item_barcode = $request->input('item_barcode');
        $product_item->item_quantity = $request->input('item_quantity');
        $product_item->item_warn_quantity = $request->input('item_warn_quantity');
        $product_item->item_discount_type = $request->input('item_discount_type');
        $product_item->item_discount_price = $request->input('item_discount_price');
        // $product_item->product_base_id = $product_base->id;
        $product_item->member_price = $request->input('member_price');
        $product_item->member_point = $request->input('member_point');
        $criteria_list = $request->input('criteria_list');
        $item_images = $request->input('images');
        if ($product_item->save()) {
            // return $product_item;
            foreach ($criteria_list as $criteriaItems) {
                $criteriaItem = (object) $criteriaItems;
                $sync_array[$criteriaItem->criteria_id] = ["criteria_value" => $criteriaItem->criteria_value, "criteria_unit_id" => $criteriaItem->criteria_unit_id];
            }
            $product_item->CriteriaBase()->sync($sync_array);
            $response = array();
            $response['code']= 1;
            $response['msg']='';
            $response['data']='Item has been saved';
            return response()->json($response, 200);
        } else {
            $response = array();
            $response['code']= 0;
            $response['msg']='3';
            $response['data']='error while saving';
            return response()->json($response, 500);
        }
    }

    public function updateItemCriteria(Request $request)
    {
        $item_id = $request->input('item_id');
        $criteria_id = $request->input('criteria_id');
        $criteria_value = $request->input('criteria_value');
        $item = ProductItem::find($item_id);
        $item->CriteriaBase()->updateExistingPivot($criteria_id, ["criteria_value" => $criteria_value]);
        $response = array();
        $response['code']= 1;
        $response['msg']='';
        $response['data']='Product has been saved';
        return response()->json($response, 200);
    }


    public function deleteItem(Request $request)
    {
        $product_item_id = $request->input('item_id');
        $item = ProductItem::find($product_item_id);
        $item->Images()->delete();
        $item->CriteriaBase()->detach();
        if ($item->delete()) {
            $response = array();
            $response['code']= 1;
            $response['msg']='';
            $response['data']='Item has been deleted';
            return response()->json($response, 200);
        }
        $response = array();
        $response['code']= 0;
        $response['msg']='3';
        $response['data']='error while saving';
        return response()->json($response, 500);
    }

    public function deleteItemCriteria(Request $request)
    {
        $item_id = $request->input('item_id');
        $criteria_id = $request->input('criteria_id');
        $item = ProductItem::find($item_id);
        if ($item->CriteriaBase()->detach($criteria_id)) {
            $response = array();
            $response['code']= 1;
            $response['msg']='';
            $response['data']='Item criteria has been deleted';
            return response()->json($response, 200);
            
        }
        $response = array();
        $response['code']= 0;
        $response['msg']='3';
        $response['data']='error while saving';
        return response()->json($response, 500);
    }

    public function getItem(Request $request)
    {
        $supplier = AuthController::me();
        $item_id = $request->input('item_id');
        $item = ProductItem::with('CriteriaBase','images')->find($item_id);
        $response = array();
        $response['code']= 1;
        $response['msg']='';
        $response['data']=$item;
        return response()->json($response, 200);
        
    }

    public function getProductItemList(Request $request)
    {
        $supplier = AuthController::me();
        $product_id = $request->input('product_id');
        $product_base = ProductBase::find($product_id);
        $productList = $product_base->items()->with('CriteriaBase','images')->get();
        $response = array();
        $response['code']= 1;
        $response['msg']='';
        $response['data']=$productList;
        return response()->json($response, 200);
    }


    // public function uploadImage(Request $request)
    // {
    //     if ($request->hasFile('product_item_image')) {
    //         $path = $request->file('product_item_image')->store('products', 'public');
    //         $fileUrl = Storage::url($path);
    //         $image = DB::table('product_images')->insert([
    //             "image_url" => $fileUrl
    //         ]);
    //         return response()->json(["id" => $image->id, "image_url" => $fileUrl], 200);
    //     }
    //     return response()->json('Error', 500);
    // }

    // public function uploadImages(Request $request)
    // {
    //     if ($request->hasFile('file')) {
    //         $path = $request->file('file')->store('products', 'public');
    //         $fileUrl = Storage::url($path);
    //         $image = new ProductImage();
    //         $image->image_url = $fileUrl;
    //         $image->save();
    //     return response()->json(["id" => $image->id, "image_url" => $fileUrl],200);
    //     }
    //     return response()->json('Error', 500);
    // }

}

