<?php namespace App\Http\Controllers\Products;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ItemImagesController extends Controller {
 
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function uploadImages(Request $request)
    {
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('products', 'public');
            $fileUrl = Storage::url($path);
            $image = new ProductImage();
            $image->image_url = $fileUrl;
            $image->save();
            $response = array();
            $response['code']= 1;
            $response['msg']='';
            $response['data']=["id" => $image->id, "image_url" => $fileUrl];
            return response()->json($response, 200);
        }
        $response = array();
        $response['code']= 0;
        $response['msg']='3';
        $response['data']='error while saving';
        return response()->json($response, 500);
    }


    public function deleteImages(Request $request)
    {
        $image_id = $request->input('image_id');
        $image =ProductImage::find($image_id);
        if($image->delete()){
            $response = array();
            $response['code']= 1;
            $response['msg']='';
            $response['data']='Image has been removed';
            return response()->json($response, 200);
        }
        $response = array();
        $response['code']= 0;
        $response['msg']='3';
        $response['data']='error while saving';
        return response()->json($response, 500);
    }

}
