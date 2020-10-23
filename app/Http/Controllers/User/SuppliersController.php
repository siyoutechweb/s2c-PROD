<?php namespace App\Http\Controllers\User;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\User;
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
        // $name = $request->input('name');
        // $contact = $request->input('contact');
        // //$category = $request->input('category_id');
        // //$barcode = $request->input('barcode');
        // $tax_number = $request->input('tax_number');
        // $keyWord = $request->input('keyword');
    $shop_owner = AuthController::meme();
     //$shop_owner->shop()->value('id');
        //$supplierListe = $shop_owner->supplier;
        $suppliers_id = ShopSupplier::where('shop_id',$shop_owner->store_id)->pluck('supplier_id');      //var_dump($suppliers_id) ;
        $supplierListe = DB::table('suppliers')->whereIn('id',$suppliers_id)->get();
        //var_dump($supplierListe) ;
        // if($request->has('name') && !empty($request->input('name') )) {
        //     $name = $request->input('name');
        //     $supplierListe =array_values( array_filter($supplierListe->toArray(),function($query) use($name) {
               
        //         return $query['supplier_name']==$name ||$query['company_name']==$name;
        //     }));
        // }
        // if($request->has('contact') && !empty($request->input('contact') )) {
        //     $contact = $request->input('contact');
        //     $supplierListe =array_values( array_filter($supplierListe->toArray(),function($query) use($contact) {
               
        //         return $query['contact']==$contact;
        //     }));
        // }
        // if($request->has('tax_number') && !empty($request->input('tax_number') )) {
        //     $tax_number = $request->input('tax_number');
        //     $supplierListe =array_values( array_filter($supplierListe->toArray(),function($query) use($tax_number) {
               
        //         return $query['tax_number']==$tax_number;
        //     }));
        // }
      
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
        //$keyWord = $request->input('keyword');
        $shop_owner = AuthController::me();
	
	//$supplierListe = 
        $supplierListe = $shop_owner->supplier;
	//var_dump($supplierListe );
        if($request->has('name') && !empty($request->input('name') )) {
            $name = $request->input('name');
            $supplierListe =array_values( array_filter($supplierListe->toArray(),function($query) use($name) {
               return is_numeric(strpos($query['supplier_name'],$name)) ||is_numeric(strpos($query['company_name'],$name));

               // return $query['supplier_name']==$name ||$query['company_name']==$name;
            }));
        }
        if($request->has('contact') && !empty($request->input('contact') )) {
            $contact = $request->input('contact');
            $supplierListe =array_values( array_filter($supplierListe->toArray(),function($query) use($contact) {
               return is_numeric(strpos($query['contact'],$contact));
                //return $query['contact']==$contact;
            }));
        }
        if($request->has('tax_number') && !empty($request->input('tax_number') )) {
            $tax_number = $request->input('tax_number');
            $supplierListe =array_values( array_filter($supplierListe->toArray(),function($query) use($tax_number) {
               return is_numeric(strpos($query['tax_id'],$tax_number));
                //return $query['tax_number']==$tax_number;
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
        DB::table('shop_supplier')->where('supplier_id' ,$id)->where('shop_id',$store_id)->delete();
     
                  return response()->json(['code'=>'1','msg'=>'Suppliers removed successfully']);
    }  
    public function getSupplierById(Request $request,$id)
    {
        $shop_owner = AuthController::me();
        //$supplierListe = $shop_owner->supplier;
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
      
        
        $user = new Supplier();
        $password = $request->input('password');
        $user->supplier_name = $request->input('supplier_name');
        $user->company_name = $request->input('company_name');
        $user->email = $request->input('email');
        $user->description = $request->input('description');
       // $user->shop_id = $request->input('store_id');
       $user->contact =$request->input('contact');
         $user->city = $request->input('city');
       $user->tax_id=$request->input('tax_id');
        $user->website = $request->input('website');
         $user->adress = $request->input('adress');
       $user->country =$request->input('country');
         $user->province = $request->input('province');
         $user->postal_code =$request->input('postal_code');
        $user->longitude = $request->input('lng');
        $user->latitude = $request->input('lat');
        // $user->min_price = $request->input('min_price');
        // $user->logistic_service = $request->input('logistic_service');
        // $user->product_visibility = $request->input('product_visibility');
        if ($request->hasFile('profil_img')) {
            $path = $request->file('profil_img')->store('profils','google');
            $fileUrl = Storage::url($path);
            $user->img_url = $fileUrl;
            $user->img_name = basename($path);

        }
        //$role = Role::where('name', 'Supplier')->first();
        //$role->users()->save($user);
        if ($user->save()) {
            DB::table('shop_supplier')->insert(['shop_id'=>$request->input('store_id'),
            'supplier_id'=>$user->id]);
            // $commission = new SiyouCommission();
            // $commission->supplier_id = $user->id;
            // $commission->commission_percent = 0;
            // $commission->deposit = 0;
            // $commission->Deposit_rest = 0;
            // $commission->commission_amount = 0;
            // $commission->save();
            return response()->json(["msg" => "supplier added successfully !"], 200);
        }
        return response()->json(["msg" => "ERROR !"], 500);
    }

}
