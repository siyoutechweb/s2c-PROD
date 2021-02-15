<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\SiyouPackage;
use App\Models\SiyouProduct;
use App\Models\SiyouProductPackage;
use Illuminate\Http\Request;
use Exception;
class SiyouPackagesController extends Controller {
    public function __construct()
    {
        $this->middleware('auth:api',['except'=>'getAllPackages,getPackageById']);
    }

    public function addSiyouPackage(Request $request) 
    {   
            try {
            $user=AuthController::meme();
            if($user->role_id!=4) {
                return response()->json(['code'=>0,'msg'=>'method not allowed']);
            }
            if(!$request->filled('package_name','short_description','products')) {
                return response()->json(['code'=>0,'msg'=>'missing arguments']);
            }
            $products=$request->input('products');
            $package_price = $request->input('package_price');
            if(!isset($package_price) || $package_price=="") {
                $package_price=0;
                foreach($products as $product) {
                    if(!isset( $product['product_id']) || !isset($product['product_quantity'])) {
                        return response()->json(['code'=>0,'msg'=>'product format should be {\"product_id\":value,\"product_quantity\":value,}']);
                    }
                    $id = $product['product_id'];
                    $tmp = SiyouProduct::find($id);
                    if(isset($tmp) && isset($tmp->unit_price) ) {
                        $package_price=$package_price+$tmp->unit_price*$product['product_quantity'];
                    }
                }
            }
            
            $siyouPackage = new SiyouPackage() ;
            $siyouPackage->package_name=$request->input('package_name');
            $siyouPackage->short_description=$request->input('short_description');
            $siyouPackage->description=$request->input('description');
            $siyouPackage->package_price=$package_price;
            if(!$request->filled('member_price')) {
                $siyouPackage->short_description=$request->input('short_description');
            }
            if($siyouPackage->save()) {
                foreach($products as $product) {
                    if(!isset( $product['product_id']) || !isset($product['product_quantity'])) {
                        return response()->json(['code'=>0,'msg'=>'product format should be {\"product_id\":value,\"product_quantity\":value,}']);
                    }
                    $siyouProductPackage=new SiyouProductPackage();
                    $siyouProductPackage->package_id=$siyouPackage->id;
                    $siyouProductPackage->product_id = $product['product_id'];
                    $siyouProductPackage->product_quantity = $product['product_quantity'];
                    $siyouProductPackage->save();

                }
                $count = siyouProductPackage::where('package_id',$siyouPackage->id)->count();
                if(!$count) {
                    $siyouPackage->delete();
                    return response()->json(['code'=>0,'msg'=>'no sense to create package with no products']);
                }
                return response()->json(['code'=>1,'msg'=>'package added successfully']);
            }else {
                return response()->json(['code'=>0,'msg'=>'error while creating package']);
            }
        }catch(Exception $e) {
            return response()->json(['code'=>0,'msg'=>$e->getMessage().$e->getLine().$e->getFile()]);
        }
    }
    public function updatePackage(Request $request,$id)
    {
        try {
            $user=AuthController::meme();
            if($user->role_id!=4) {
                return response()->json(['code'=>0,'msg'=>'method not allowed']);
            }
            $siyouPackage=SiyouPackage::find($id);
            if(!$siyouPackage) {
                return response()->json(['code'=>0,'msg'=>'package not found']);
            }
            if(!$request->filled('package_name','short_description','products')) {
                return response()->json(['code'=>0,'msg'=>'missing arguments']);
            }
            $products=$request->input('products');
            $package_price = $request->input('package_price');
            if(!isset($package_price) || $package_price=="") {
                $package_price=0;
                foreach($products as $product) {
                    if(!isset( $product['product_id']) || !isset($product['product_quantity'])) {
                        return response()->json(['code'=>0,'msg'=>'product format should be {\"product_id\":value,\"product_quantity\":value,}']);
                    }
                    $id = $product['product_id'];
                    $tmp = SiyouProduct::find($id);
                    if(isset($tmp) && isset($tmp->unit_price) ) {
                        $package_price=$package_price+$tmp->unit_price*$product['product_quantity'];
                    }
                }
            }
            
            
            $siyouPackage->package_name=$request->input('package_name');
            $siyouPackage->short_description=$request->input('short_description');
            $siyouPackage->description=$request->input('description');
            $siyouPackage->package_price=$package_price;
            if(!$request->filled('member_price')) {
                $siyouPackage->short_description=$request->input('short_description');
            }
            if($siyouPackage->save()) {
                foreach($products as $product) {
                    if(!isset( $product['product_id']) || !isset($product['product_quantity'])) {
                        return response()->json(['code'=>0,'msg'=>'product format should be {\"product_id\":value,\"product_quantity\":value,}']);
                    }
                    $siyouProductPackage = SiyouProductPackage::where('package_id',$id)->where('product_id',$product['product_id'])->first();
                    if(!$siyouProductPackage) {
                        $siyouProductPackage=new SiyouProductPackage();
                    }
                    
                    $siyouProductPackage->package_id=$siyouPackage->id;
                    $siyouProductPackage->product_id = $product['product_id'];
                    $siyouProductPackage->product_quantity = $product['product_quantity'];
                    $siyouProductPackage->save();

                }
                return response()->json(['code'=>1,'msg'=>'package updated successfully']);
            }else {
                return response()->json(['code'=>0,'msg'=>'error while updating package']);
            }
        }catch(Exception $e) {
            return response()->json(['code'=>0,'msg'=>$e->getMessage().$e->getLine().$e->getFile()]);
        }
    }
    public function getAllPackages(Request $request) 
    {
        $result = SiyouPackage::with('products')->get();
        $response['code']=1;
        $response['msg']='';
        $response['data']=$result;
        return response()->json($response);
    }
    public function getPackageById(Request $request,$id) 
    {
        $result = SiyouPackage::where('id',$id)->with('products')->get();
        $response['code']=1;
        $response['msg']='';
        $response['data']=$result;
        return response()->json($response);
    }
    public function deletePackageById(Request $request,$id) 
    {
        try{
            $user=AuthController::meme();
            if($user->role_id!=4) {
                return response()->json(['code'=>0,'msg'=>'method not allowed']);
            }
            $siyouPackage = SiyouPackage::find($id);
            if(!$siyouPackage) {
                return response()->json(['code'=>0,'msg'=>'package not found']);
            }
            if($siyouPackage->delete()) {
                return response()->json(['code'=>1,'msg'=>'package with id '.$id ." deleted successfully"]);
            }
            return response()->json(['code'=>0,'msg'=>'error while deleting']); 
        }catch(Exception $e) {
            return response()->json(['code'=>0,'msg'=>$e->getMessage().$e->getLine().$e->getFile()]);
        }

    }
    public function deleteProductFromPackage(Request $request,$id) 
    {   
        try {
            $user = AuthController::meme();
            if($user->role_id!=4) {
                return response()->json(['code'=>0,'msg'=>'method not allowed']);
            }
            if(!$request->filled('product_id')) {
                return response()->json(['code'=>0,'msg'=>'missing argument']);
            }
            $siyouPackage=SiyouPackage::find($id);
            if(!$siyouPackage) {
                return response()->json(['code'=>0,'msg'=>'package not found']);
            }
            $product_id = $request->input('product_id');
            $siyouProduct = SiyouProduct::find($product_id);
            if(!$siyouProduct) {
                return response()->json(['code'=>0,'msg'=>'product not found']);
            }
            $siyouProductPackage = SiyouProductPackage::where('package_id',$id)->where('product_id',$product_id)->first();
            if(!$siyouProductPackage) {
                return response()->json(['code'=>0,'msg'=>"product not found in this package"]);
            }
            $quantity = $siyouProductPackage->product_quantity;
            if($siyouProductPackage->delete()) {
                $count = SiyouProductPackage::where('package_id',$id)->count();
                if($count==0) {
                    $siyouPackage->delete();
                    return response()->json(['code'=>0,'msg'=>'product and package deleted successfully']);
                }
                $siyouPackage->package_price = $siyouPackage->package_price-$quantity * $siyouProduct->unit_price;
                $siyouPackage->member_price = $siyouPackage->member_price-$quantity * $siyouProduct->member_price;
                $siyouPackage->save();
                return response()->json(['code'=>0,'msg'=>'product deleted successfully from package']);
            } else {
                return response()->json(['code'=>0,'msg'=>'error while deleting product from package']);
            }
        } catch(Exception $e) {
            return response()->json(['code'=>0,'msg'=>$e->getMessage().$e->getLine().$e->getFile()]);
        }
            
    }

}