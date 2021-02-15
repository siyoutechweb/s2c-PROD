<?php namespace App\Http\Controllers\Admin;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Models\SiyouProduct;
use App\Models\SiyouProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SiyouProductsController extends Controller {

    public function __construct()
    {
        $this->middleware('auth:api',['except'=>['getSiyouProducts']]);
    }
    public function getSiyouProducts(Request $request)
    {
        $siyouProducts = SiyouProduct::get();
        $response = array();
        $response['code']= 1;
        $response['msg']='';
        $response['data']=$siyouProducts;
        return response()->json($response, 200);
    }

    public function addSiyouProduct(Request $request)
    {   
        $user = AuthController::meme();
        if($user->role_id!=4) {
            return response()->json(['code'=>0,'msg'=>'method not allowed']);
        }
        $product_name = $request->input('product_name');
        $short_description = $request->input('short_description');
        $description = $request->input('description');
        $product_quantity = $request->input('product_quantity');
        $unit_price = $request->input('unit_price');
        $member_price = $request->input('member_price');
        $product_type = $request->input('product_type');

        if(!$product_name || !$short_description || ! $unit_price || !$product_type) {
            return response()->json(['code'=>0,'msg'=>'missing arguments']);
        }
        if($request->hasFile('siyou_product_image')) {
            $path = $request->file('siyou_product_image')->store('siyou_products', 'public');
            $fileUrl = Storage::url($path);
            $img_url = $fileUrl;
            $img_name = basename($path);
        } else {
            $img_url=null;
            $img_name=null;
        }
        $siyouProduct = new SiyouProduct();
        $siyouProduct->product_name=$product_name;
        $siyouProduct->short_description=$short_description;
        $siyouProduct->description=$description;
        $siyouProduct->product_quantity=$product_quantity;
        $siyouProduct->unit_price=$unit_price;
        $siyouProduct->member_price=$member_price;
        $siyouProduct->product_type=$product_type;
        $siyouProduct->img_url=$img_url;
        $siyouProduct->img_name=$img_name;
        if($siyouProduct->save()) {
            return response()->json(['code'=>1,'msg'=>'siyou product added successfully']);
        }
        $response = array();
        $response['code']= 0;
        $response['msg']='';
        $response['data']='error while saving';
        return response()->json($response, 200);
        
    }



    public function updateSiyouProduct(Request $request,$id)
    {
        $user = AuthController::meme();
        if($user->role_id!=4) {
            return response()->json(['code'=>0,'msg'=>'method not allowed']);
        }
        $product_name = $request->input('product_name');
        $short_description = $request->input('short_description');
        $description = $request->input('description');
        $product_quantity = $request->input('product_quantity');
        $unit_price = $request->input('unit_price');
        $member_price = $request->input('member_price');
        $product_type = $request->input('product_type');

        if(!$request-filled('product_name','short_description','unit_price','product_type')) {
            return response()->json(['code'=>0,'msg'=>'missing arguments']);
        }
        $siyouProduct = SiyouProduct::find($id);
        if($request->hasFile('siyou_product_image')) {
            $path = $request->file('siyou_product_image')->store('siyou_products', 'public');
            $fileUrl = Storage::url($path);
            $siyouProduct->img_url = $fileUrl;
            $siyouProduct->img_name = basename($path);
        } 
       
        $siyouProduct->product_name=$product_name;
        $siyouProduct->short_description=$short_description;
        $siyouProduct->description=$description;
        $siyouProduct->product_quantity=$product_quantity;
        $siyouProduct->unit_price=$unit_price;
        $siyouProduct->member_price=$member_price;
        $siyouProduct->product_type=$product_type;
     
        if($siyouProduct->save()) {
            return response()->json(['code'=>1,'msg'=>'siyou product updated successfully']);
        }
        $response = array();
        $response['code']= 0;
        $response['msg']='';
        $response['data']='error while updating';
        return response()->json($response, 200);
    }

 public function getSiyouProductById(Request $request,$id) {
     $user=AuthController::meme();
    $siyouProduct=SiyouProduct::where('id',$id)->with('siyouProductImages')->first();
    return response()->json(['code'=>1,'msg'=>'success','data'=>$siyouProduct]);
 }
 public function addImageToSiyouProduct(Request $request) {
    $user=AuthController::meme();
    if($user->role_id!=4) {
        return response()->json(['code'=>0,'msg'=>'fail','data'=>'method not allowed']);
    }
    if(!$request->filled('siyou_product_id')) {
        return response()->json(['code'=>0,'msg'=>"fail","data"=>"siyou_product_id is required"]);
    }
    if(!$request->hasFile('siyou_product_image')) {

        return response()->json(['code'=>0,'msg'=>"fail","data"=>"image  is required"]);
    } 

    $product_id=$request->input('siyouProduct_id');
     $path = $request->file('siyou_product_image')->store('siyou_products', 'public');
        $fileUrl = Storage::url($path);
        $siyouProductImage  = new SiyouProductImage();
        $siyouProductImage->product_id=$product_id;
        $siyouProductImage->img_url = $fileUrl;
        $siyouProductImage->img_name = basename($path);
        if($siyouProductImage->save()) {
            return response()->json(['code'=>1,'msg'=>'siyou product image added successfully']);
        } else {
            return response()->json(['code'=>0,'msg'=>'error while adding image']);
        }
        
    }
    public function deleteImageFromSiyouProducts(Request $request,$id) {
        $user= AuthController::meme();
        if($user->role_id!=4) {
            return response()->json(['code'=>0,'msg'=>'method not allowed']);
        }
        $siyouProductImage=SiyouProductImage::find($id);
        if($siyouProductImage->delete()) {
            return response()->json(['code'=>1,'msg'=>'siyou product image deleted successfully']);
        } else {
            return response()->json(['code'=>0,'msg'=>'error while deleting']);
        }
    }
}
