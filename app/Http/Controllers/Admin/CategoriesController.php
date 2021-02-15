<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
//use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
//use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Exception;

class CategoriesController extends Controller {


    public function __construct()
    {
        $this->middleware('auth:api');
    }

    
    // public function addCategory(Request $request)
    // {
    //     $category = new Category();
    //     $category->category_name = $request->input('name');
    //     $category->parent_category_id = $request->input('parent_id');
    //     if ($request->hasFile('category_image')) {
    //         $path = $request->file('category_image')->store('categories', 'public');
    //         $fileUrl = Storage::url($path);
    //         $category->img_url = $fileUrl;
    //         $category->img_name = basename($path);

    //     }

    //     if ($category->save()) {
    //         return response()->json("The Category has been added Successfully !!");
    //     }
    //     return response()->json("Error !!");
    // }
    public function addCategory(Request $request)
    {
        $category = new Category();
        $taxe = $request->input('tax',22);
        $category->category_name = $request->input('name');
        $category->category_cn = $request->input('category_cn');

        $category->category_fr = $request->input('category_fr');
        $category->category_it = $request->input('category_it');
	    $category->has_user = 0;
        $category->taxe=$taxe;
        $category->parent_category_id = !empty($request->input('parent_id')) ?$request->input('parent_id'):null;
        if ($request->hasFile('category_image')) {
            $path = $request->file('category_image')->store('categories', 'public');
            $fileUrl = Storage::url($path);
            $category->img_url = $fileUrl;
            $category->img_name = basename($path);

        }

        if ($category->save()) {
            return response()->json("The Category has been added Successfully !!");
        }
        return response()->json("Error !!");
    }



    
   public function updateCategory(Request $request,$id)
    {
        //$id= $request->input('category_id');
        $category = Category::find($id);
        if($category){
        $category->category_name = $request->input('name');
        $category->category_cn = $request->input('category_cn');
        $taxe = $request->input('tax',22);
        $category->taxe = $taxe;
        $category->category_fr = $request->input('category_fr');
        $category->category_it = $request->input('category_it');
        if ($request->hasFile('category_image')) {
            $path = $request->file('category_image')->store('categories', 'public');
            $fileUrl = Storage::url($path);
            $category->img_url = $fileUrl;
            $category->img_name = basename($path);
        }
        if ($category->save()) {
            return response()->json($category);
        }}
        return response()->json(["msg" => "ERROR !!"]);
    }
    public function updateownCategory(Request $request)
    {
        $id= $request->input('category_id');
        $category = Category::find($id);
        if($category){
        $category->category_name = $request->input('name');
        $category->category_cn = $request->input('category_cn');
        $taxe = $request->input('tax',22);
        $category->taxe = $taxe;
        $category->category_fr = $request->input('category_fr');
        $category->category_it = $request->input('category_it');
        if ($request->hasFile('category_image')) {
            $path = $request->file('category_image')->store('categories', 'public');
            $fileUrl = Storage::url($path);
            $category->img_url = $fileUrl;
            $category->img_name = basename($path);
        }
        if ($category->save()) {
            return response()->json($category);
        }}
        return response()->json(["msg" => "ERROR !!"]);
    }
    public function deleteCategory(Request $request,$id)
    {
        $category = Category::find($id);
     if($category->delete()) {
            Product::where('category_id',$id)->chunk(100,function($products) {
                foreach($products as $product) {
                    $product->category_id=66;
                    $product->save();
                }
            });;
        return response()->json(["msg" => "the category has been deleted !!"]);
     }
     else {
        return response()->json(["msg" => "error while deleting !!"]);
     }
    }


    public function getCategories()
    {
        $categories = Category::whereNull('parent_category_id')->with('subCategories')->get();
        return response()->json($categories);
    }
    public function addCategoriesToShop(Request $request)
    {
        $categories = $request->input('categories');
        $store_id = $request->input('store_id');

        foreach($categories as $category) {
            DB::table('shop_category')->insert(['store_id'=>$store_id,'category_id'=>$category]);
        }
        return response()->json(['code'=>'1','msg'=>'categories selected']);
    }
    public function getCategoriesByShop(Request $request)
    {	
        $store_id = $request->input('store_id');

        $categories_id=DB::table('shop_category')->where('store_id',$store_id)->pluck('category_id');
        //var_dump($categories_id);
        $result = Category::whereIn('id',$categories_id)->orWhere('id',66)->with('subCategories')->get();
	
        
        return response()->json(['code'=>'1','msg'=>'categories selected','data'=>$result]);
    }
	 public function getSystemCategoriesByShop(Request $request)
    {
	$user = AuthController::me();
        $store_id = $request->input('store_id');
	//echo $user->category_id;
        $categories_id=DB::table('shop_category')->where('store_id',$store_id)->pluck('category_id');
        //var_dump($categories_id);
        $result = Category::where('id','!=',$user->category_id)->where('parent_category_id',null)->where('has_user',0)->with('subCategories')->get();
        
        return response()->json(['code'=>'1','msg'=>'categories selected','data'=>$result]);
    }



    public function getCategoryParent($id)
    {
        $subCat = Category::Find($id);
        $parentCat = $subCat->getParentCategory;

        return response()->json($parentCat);
    }

    public function getCategoryChild($id)
    {
        $parentCat = Category::find($id);
        $subCat = $parentCat->getChildCategories;
        return response()->json($subCat);
    }
    
    public function getCategorieById(Request $request,$id)
    {
        $Category = Category::find($id);
        return response()->json($Category,200);
    }
public function removeCategoryFromShop(Request $request,$id)
    {
        
        $store_id = $request->input('store_id');
        $tmp = DB::table('shop_category')->where('category_id' ,$id)->where('store_id',$store_id)->exists();
        if(!$tmp) {
            return response()->json(['code'=>'0','msg'=>'error while deleting']);
        }
        DB::table('shop_category')->where('category_id' ,$id)->where('store_id',$store_id)->delete();
     
                   return response()->json(['code'=>'1','msg'=>'category removed successfully']);
    }  

public function addCategoryByShop(Request $request) {
        try{
            $user = AuthController::meme();
            if($user->role_id !=1) {
                return response()->json(['code'=>0,'msg'=>'method not allowed']);
            }
            if(!$user->category_id) {
                $userCategory = new Category();
                $userCategory->category_name = 'Category_'.$user->first_name;            
                $userCategory->category_cn = 'Category'.$user->store_id;
                $taxe=$request->input('tax',22);
                $userCategory->taxe=$taxe;
                $userCategory->category_fr = 'Category'.$user->store_id;
                $userCategory->category_it = 'Category'.$user->store_id;
		$userCategory->has_user = 1;

                if($userCategory->save()) {
                    //DB::table('shop_category')->insert(['category_id'=>$userCategory->id,'store_id'=>$user->store_id]);
                    $shopOwner = User::find($user->id);
                    $shopOwner->category_id = $userCategory->id;
                    $shopOwner->save();
                    $category = new Category();
                    $category->category_name = $request->input('name');
                    $category->category_cn = $request->input('category_cn');
                    $taxe1=$request->input('tax',22);
                    $category->taxe=$taxe1;
                    $category->category_fr = $request->input('category_fr');
                    $category->category_it = $request->input('category_it');
		            $category->has_user = 1;
                    $category->parent_category_id = $userCategory->id;
                    if ($request->hasFile('category_image')) {
                        $path = $request->file('category_image')->store('categories', 'public');
                        $fileUrl = Storage::url($path);
                        $category->img_url = $fileUrl;
                        $category->img_name = basename($path);

                    }

        if ($category->save()) {
            //DB::table('shop_category')->insert(['category_id'=>$category->id,'store_id'=>$user->store_id]);
            return response()->json(['code'=>1,'msg'=>"The Category has been added Successfully !!"]);
        }
        return response()->json("Error !!");
                }
            } else {
		//echo $user->category_id;
                $category = new Category();
                    $category->category_name = $request->input('name');
                    $category->category_cn = $request->input('category_cn');
                    $taxe2=$request->input('tax',22);
                    $category->taxe=$taxe2;
                    $category->category_fr = $request->input('category_fr');
                    $category->category_it = $request->input('category_it');
                    $category->parent_category_id = $user->category_id;
		            $category->has_user = 1;

                    if ($request->hasFile('category_image')) {
                        $path = $request->file('category_image')->store('categories', 'public');
                        $fileUrl = Storage::url($path);
                        $category->img_url = $fileUrl;
                        $category->img_name = basename($path);

                    }

        if ($category->save()) {
            //DB::table('shop_category')->insert(['category_id'=>$Category->id,'store_id'=>$user->store_id]);
             return response()->json(['code'=>1,'msg'=>"The Category has been added Successfully !!"]);
        }
        return response()->json("Error !!");
            }

        }catch (Exception $e) {
            return response()->json([
                'code' => 0, 'msg' => $e->getMessage().$e->getLine().$e->getFile()
            ]);

        }
        
    }

    public function getOwnCategory(Request $request) 
    {

        $user = AuthController::meme();
	//echo $user->store_name;
        $result = Category::where('id',$user->category_id)->with('subCategories')->get();
        
        return response()->json(['code'=>'1','msg'=>'categories selected','data'=>$result]);

    }
    public function deleteOwnSubCategory(Request $request,$id)
    {
        $user= AuthController::meme();
        $category = Category::where('parent_category_id',$user->category_id)->where('id',$id)->first();
        if(!$category) {
            return response()->json(['code'=>0,'msg'=>'category not found']);
        }
        if($category->delete()) {
            Product::where('category_id',$id)->chunk(100,function($products) {
                foreach($products as $product) {
                    $product->category_id=66;
                    $product->save();
                }
            });
	    //DB::table('shop_category')::where('store_id',$user->store_id)->where('category_id',$id)->delete;
            return response()->json(['code'=>1,'msg'=>'category deleted successfully']); 
        } else {
            return response()->json(['code'=>0,'msg'=>'error while deleting']);
        }
    }
    public function getChoosenCategories(Request $request) 
    {
        $user= AuthController::meme();
	$category_id=$user->category_id;
	if($user->role_id==2 || $user->role_id ==3) {$shop_owner=User::find($user->shop_owner_id); $category_id=$shop_owner->category_id;}
        $choosen_categories_id = DB::table('shop_category')->where('store_id',$user->store_id)->pluck('category_id');
        $choosen_categories =Category::whereIn('id',$choosen_categories_id)->where('id','!=',$category_id)->with('subCategories')->get();
        return response()->json(['code'=>'1','msg'=>'categories selected','data'=>$choosen_categories]);
    }
    public function getShopCategoriesForWeb(Request $request) 
    {
        $user= AuthController::meme();
	$category_id=$user->category_id;
	if($user->role_id==2 || $user->role_id ==3) {$shop_owner=User::find($user->shop_owner_id); $category_id=$shop_owner->category_id;}
        $choosen_categories_id = DB::table('shop_category')->where('store_id',$user->store_id)->pluck('category_id');
        $choosen_categories =Category::whereIn('id',$choosen_categories_id)->where('id','!=',$category_id)->with('subCategories')->get();
        $result = [];
        $result['choosen category'] = '';
        return response()->json(['code'=>'1','msg'=>'categories selected','data'=>$choosen_categories]);
    }
    public function getCategoriesByShopForWeb(Request $request)
    {	$user= AuthController::meme();
        $category_id=$user->category_id;
        if($user->role_id==2 || $user->role_id ==3) {$shop_owner=User::find($user->shop_owner_id); $category_id=$shop_owner->category_id;}
        $store_id = $request->input('store_id');

        $categories_id=DB::table('shop_category')->where('store_id',$store_id)->pluck('category_id');
        //var_dump($categories_id);
        $result = Category::whereIn('id',$categories_id)->orWhere('parent_category_id',$category_id)->where('id','!=',$category_id)->get();
	
        
        return response()->json(['code'=>'1','msg'=>'categories selected','data'=>$result]);
    }

    public function updateCategoryOrderByShopOwner(Request $request)
    {
        $user = AuthController::meme();
        $Categories_orders=$request->input('categories_orders');
        
	    $categorie=$user->category_id;
        if($user->role_id==2 || $user->role_id ==3) {$shop_owner=User::find($user->shop_owner_id); $categorie=$shop_owner->category_id;}
        //$store_id = $request->input('store_id');

        //if(count($categories_order))
	Category::where('parent_category_id',$categorie)->update(['category_order'=>null]);

        foreach($Categories_orders as $category_order)
         {
             $category_id= $category_order['category_id'];
             $order_category=$category_order['order'];
             $category=Category::where('id',$category_id)->where("parent_category_id",$user->category_id)->first();
             if(!$category) {
                 return response()->json(['code'=>0,'msg'=>' error !!! category with id '.$category_id .'and parent category id'.$user->category_id. 'not found']);
             }
             $category->category_order=$order_category;
             $category->save();


         }
         return response()->json(['code'=>1,'msg'=>'','data'=>'categories orders updated successfully']);
    }
}
