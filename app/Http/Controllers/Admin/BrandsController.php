<?php namespace App\Http\Controllers\Admin;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Models\ProductBrand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrandsController extends Controller {

    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function getBrandList(Request $request)
    {
        $brandList = ProductBrand::all();
        $response = array();
        $response['code']= 1;
        $response['msg']='';
        $response['data']=$brandList;
        return response()->json($response, 200);
    }

    public function getBrand(Request $request)
    {
        $brand_id = $request->input('brand_id');
        $brand = ProductBrand::find($brand_id);
        $response = array();
        $response['code']= 1;
        $response['msg']='';
        $response['data']=$brand;
        return response()->json($response, 200);
        
    }

    public function addBrand(Request $request)
    {
        $brand = new ProductBrand;
        $brand->brand_name = $request->input('brand_name');
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('products', 'public');
            $fileUrl = Storage::url($path);
            $brand->brand_logo = $fileUrl;
        }
        if ($brand->save()) {
            return response()->json(['msg' => 'brand has been saved successfully']);
        }
        return response()->json(['msg' => 'Error !!'], 500);
    }

    public function updateBrand(Request $request)
    {
        $brand_id = $request->input('brand_id');
        $brand = ProductBrand::find($brand_id);
        $brand->brand_name = $request->input('brand_name');
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('products', 'public');
            $fileUrl = Storage::url($path);
            $brand->brand_logo = $fileUrl;
        }
        if ($brand->save()) {
            return response()->json(['msg' => 'brand has been updated successfully']);
        }
        return response()->json(['msg' => 'Error !!'], 500);
    }

    public function deleteBrand(Request $request)
    {
        $brand_id = $request->input('brand_id');
        $brand = ProductBrand::find($brand_id);
        if ($brand->delete()) {
            return response()->json(['msg' => 'brand has been removed'], 200);
        }
        return response()->json(['msg' => 'Error !!'], 500);
    }
}
