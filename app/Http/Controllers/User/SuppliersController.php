<?php namespace App\Http\Controllers\User;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Product;
use App\Models\ShopSupplier;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
class SuppliersController extends Controller {
         public function getSupplierList1()
    {
        $shop_owner = AuthController::me();
        $supplierListe = $shop_owner->supplier;
        $response['msg']="";
        $response['code']=1;
        $response['data']=$supplierListe;
        return response()->json($response);
    }
	public function getSupplierList2(Request $request)

    {
        $user = AuthController::meme();
        
        $shop_owner_id=$user->id;
        if($user->role_id==2 || $user->role_id ==3) {$shop_owner=User::find($user->shop_owner_id); $shop_owner_id=$shop_owner->id;}
        $suppliers_id = ShopSupplier::where('shop_id',$user ->store_id)->pluck('supplier_id');      
        $supplierListe = DB::table('suppliers')->whereIn('id',$suppliers_id)->orWhere('id',580)->orWhere('shop_owner_id',$shop_owner_id)->orderBy('supplier_name')->get();
              
        $response['msg']="";
        $response['code']=1;
        $response['data']=$supplierListe;
        return response()->json($response);
    }

 	public function getSupplierList(Request $request)

    {
        $name = $request->input('name');
        $contact = $request->input('contact');
        $tax_number = $request->input('tax_number');
        
        $shop_owner = AuthController::me();
	
	
        $supplierListe = $shop_owner->supplier;
	
        if($request->has('name') && !empty($request->input('name') )) {
            $name = $request->input('name');
            $supplierListe =array_values( array_filter($supplierListe->toArray(),function($query) use($name) {
               return is_numeric(strpos($query['supplier_name'],$name)) ||is_numeric(strpos($query['company_name'],$name));

               
            }));
        }
        if($request->has('contact') && !empty($request->input('contact') )) {
            $contact = $request->input('contact');
            $supplierListe =array_values( array_filter($supplierListe->toArray(),function($query) use($contact) {
               return is_numeric(strpos($query['contact'],$contact));
                
            }));
        }
        if($request->has('tax_number') && !empty($request->input('tax_number') )) {
            $tax_number = $request->input('tax_number');
            $supplierListe =array_values( array_filter($supplierListe->toArray(),function($query) use($tax_number) {
               return is_numeric(strpos($query['tax_id'],$tax_number));
               }));
        }
         $response['msg']="";
        $response['code']=1;
        $response['data']=$supplierListe;
        return response()->json($response);
    }
	
  	public function getsuppliers()
    {
        $categories = Supplier::all();
        return response()->json($categories);
    }
    	public function addSuppliersToShop(Request $request)
    {
        $categories = $request->input('suppliers');
        $store_id = $request->input('store_id');

        foreach($categories as $category) {
            DB::table('shop_supplier')->insert(['shop_id'=>$store_id,'supplier_id'=>$category]);
        }
        return response()->json(['code'=>'1','msg'=>'Suppliers selected']);
    }
	public function removeSupplierFromShop(Request $request,$id)
    {
        
        $store_id = $request->input('store_id');
	$tmp = DB::table('shop_supplier')->where('supplier_id' ,$id)->where('shop_id',$store_id)->exists();
        if(!$tmp) {
            return response()->json(['code'=>'0','msg'=>'error while deleting']);
        }
        if(DB::table('shop_supplier')->where('supplier_id' ,$id)->where('shop_id',$store_id)->delete()){
     
        return response()->json(['code'=>'1','msg'=>'Suppliers removed successfully']);}
	else {response()->json(['code'=>'1','msg'=>'error while deleting']);}
    }  
    	public function getSupplierById(Request $request,$id)
    {
        $shop_owner = AuthController::me();
        
        $supplier = Supplier::find($id);
        if($supplier) {
             $response['msg']="";
        $response['code']=1;
        $response['data']=$supplier;
        } else {
            $response['msg']="";
            $response['code']=0;
            $response['data']="no supplier found";
        }
       
        return response()->json($response);
    }
    	public function addSupplier(Request $request)
    {	
    $shop_owner = AuthController::meme();
    if($shop_owner->role_id==1) {
        $shop_owner_id = $shop_owner->id;
    } else if($shop_owner->role_id==2 ||$shop_owner->role_id==3)  {
        $shop_owner_id=$shop_owner->shop_owner_id;
    }
    else{ $shop_owner_id=null;}
        $tax_id = $request->input('tax_id');
	if($supplier = Supplier::where('tax_id',$tax_id)->where('shop_owner_id',$shop_owner_id)->first()) 
	{
	    return response()->json(["code"=>0,'msg'=>"supplier with this tax number already exists"]);
	}
        
        $user = new Supplier();
        $password = $request->input('password');
        $user->supplier_name = $request->input('supplier_name');
        $user->company_name = $request->input('company_name');
        $email = $request->input('email');
        $user->email = $request->input('email');
	
        $user->description = $request->input('description');
       	$user->contact =$request->input('contact');
	
        $user->city = $request->input('city');
       	$user->tax_id=$request->input('tax_id');
        $user->website = $request->input('website');
        $user->adress = $request->input('adress');
       	$user->country =$request->input('country');
        $user->province = $request->input('province');
        $user->postal_code =$request->input('postal_code');
        $user->shop_owner_id = $shop_owner_id;
	$lng = $request->input('lng');
	$lat= $request->input('lat');
	if($lng=='') {$lng=0;}
	if($lat=='') {$lat=0;}

        $user->longitude = $lng;
        $user->latitude = $lat;
        if ($request->hasFile('profil_img')) 
	{
            $path = $request->file('profil_img')->store('profils','public');
            $fileUrl = Storage::url($path);
            $user->img_url = $fileUrl;
            $user->img_name = basename($path);

        }
        
        if ($user->save()) {
            if($user->role_id!=4) {
            DB::table('shop_supplier')->insert(['shop_id'=>$request->input('store_id'),
            					'supplier_id'=>$user->id
					      ]);}
            return response()->json(["code"=>0,"msg" => "supplier added successfully !"], 200);
        }
        return response()->json(["msg" => "ERROR !"], 500);
    }
	public function updateSupplier(Request $request,$id) {
        $user = AuthController::me();
        if(!($user->role_id ==4 ||$user->role_id ==1)) {
            return response()->json(["code"=>0,"msg"=>"operation not allowed"]);
        }
        $supplier = Supplier::find($id);
        if(!$supplier) {
            return response()->json(["code"=>0,"msg"=>"supplier not found"]);
        }

       
        $supplier->supplier_name = $request->input('supplier_name');
        $supplier->company_name = $request->input('company_name');
        $supplier->email = $request->input('email');
	
        $supplier->description = $request->input('description');
       	$supplier->contact =$request->input('contact');
	

	
        $supplier->city = $request->input('city');
        $supplier->website = $request->input('website');
        $supplier->adress = $request->input('adress');
       	$supplier->country =$request->input('country');
        $supplier->province = $request->input('province');
        $supplier->postal_code =$request->input('postal_code');
        $supplier->longitude = $request->input('lng');
        $supplier->latitude = $request->input('lat');
        if($supplier->save()) {
            $response['code'] = 1;
            $response['msg'] = 'success';
            $response['data'] = "supplier updated successfully";
        } else {
            $response['code'] = 0;
            $response['msg'] = 'fail';
            $response['data'] = "error while updating";
        }
        return response()->json($response);


    }

    public function deleteSupplier(Request $request,$id) 
      {
        $user = AuthController::me();
        if($user->role_id !=4) {
            return response()->json(["code"=>0,"msg"=>"operation not allowed"]);
        }
        $supplier = Supplier::find($id);
        if(!$supplier) {
            return response()->json(["code"=>0,"msg"=>"supplier not found"]);
        }
        $supplier = Supplier::find($id);
        if(!$supplier)
         {
             return response()->json(['code'=>0,'msg'=>'error while deleting']);
         }
        if($supplier->delete()) {
            Product::where('supplier_id',$id)->chunk(100,function($products) {
                foreach($products as $product) {
                    $product->supplier_id=580;
                    $product->save();
                }
            });
	    
            return response()->json(['code'=>1,'msg'=>'supplier  deleted successfully']); 
        } else {
            return response()->json(['code'=>0,'msg'=>'error while deleting']);
        }

        


        
    }
    public function getSystemSuppliers(Request $request) {
        $user = AuthController::meme();
        $choosen_suppliers_ids = DB::table('shop_supplier')->where('shop_id',$user->store_id)->pluck('supplier_id');
        $suppliers=Supplier::where('shop_owner_id',null)->whereNotIn('id',$choosen_suppliers_ids)->get();
        if($suppliers->count()) {
            $response['code']= 1;
            $response['msg']='success';
            $response['data'] = $suppliers;
        } else {
            $response['code']= 0;
            $response['msg']='fail';
            $response['data'] = 'no supplier found';
        }
        return response()->json($response);
    }
    public function getMySuppliersAdded(Request $request) {
        $user = AuthController::meme();
        $choosen_suppliers_ids = DB::table('shop_supplier')->where('shop_id',$user->store_id)->pluck('supplier_id');
        if($user->role_id ==1) {
            $shop_owner_id = $user->id;
        } else if($user->role_id ==2 ||$user->role_id==3) {
            $shop_owner_id = $user->shop_owner_id;
        }
        $suppliers=Supplier::where('shop_owner_id',$shop_owner_id)->get();
        if($suppliers->count()) {
            $response['code']= 1;
            $response['msg']='success';
            $response['data'] = $suppliers;
        } else {
            $response['code']= 0;
            $response['msg']='fail';
            $response['data'] = [];
        }
        return response()->json($response);
    }
    public function getChoosenSuppliers(Request $request) {
        $user = AuthController::meme();
        $choosen_suppliers_ids = DB::table('shop_supplier')->where('shop_id',$user->store_id)->pluck('supplier_id');
        if($user->role_id ==1) {
            $shop_owner_id = $user->id;
        } else if($user->role_id ==2 ||$user->role_id==3) {
            $shop_owner_id = $user->shop_owner_id;
        }
        $suppliers=Supplier::where('shop_owner_id',null)->whereIn('id', $choosen_suppliers_ids)->get();
        if($suppliers->count()) {
            $response['code']= 1;
            $response['msg']='success';
            $response['data'] = $suppliers;
        } else {
            $response['code']= 0;
            $response['msg']='fail';
            $response['data'] = [];
        }
        return response()->json($response);
    }
    public function deleteOwnSupplier(Request $request,$id)
    {
        $user = AuthController::meme();

        $supplier = Supplier::find($id);
        if(!$supplier)
         {
             return response()->json(['code'=>0,'msg'=>'error while deleting']);
         }
        if($supplier->delete()) {
            Product::where('supplier_id',$id)->chunk(100,function($products) {
                foreach($products as $product) {
                    $product->supplier_id=580;
                    $product->save();
                }
            });
	    
            return response()->json(['code'=>1,'msg'=>'supplier  deleted successfully']); 
        } else {
            return response()->json(['code'=>0,'msg'=>'error while deleting']);
        }

    }
    public function getAllShopOwnersSuppliers(Request $request) 
    {
        $user = AuthController::meme();
        if($user->role_id!=4) {
            return response()->json(['code'=>0,'msg'=>'method not allowed']);
        }
        $shop_owner_id=$request->input('shop_owner_id');
        $suppliers = Supplier::whereNotNull('shop_owner_id')
                            ->with('shopOwner')
                            ->when(isset($shop_owner_id) && $shop_owner_id!="",function($query) use($shop_owner_id) {
                                $query->where('shop_owner_id',$shop_owner_id);
                            })->paginate(20)->toArray();
        
        $response['code']=1;
        $response['msg']="";
        $response['data']=$suppliers;
        return response()->json($response);
    }

}
