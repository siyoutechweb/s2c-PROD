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
    //         $path = $request->file('category_image')->store('categories', 'google');
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
        $category->category_name = $request->input('name');
        $category->category_cn = $request->input('category_cn');

        $category->category_fr = $request->input('category_fr');
        $category->category_it = $request->input('category_it');
        $category->parent_category_id = !empty($request->input('parent_id')) ?$request->input('parent_id'):null;
        if ($request->hasFile('category_image')) {
            $path = $request->file('category_image')->store('categories', 'google');
            $fileUrl = Storage::url($path);
            $category->img_url = $fileUrl;
            $category->img_name = basename($path);

        }

        if ($category->save()) {
            return response()->json("The Category has been added Successfully !!");
        }
        return response()->json("Error !!");
    }



    
    public function updateCategory(Request $request)
    {
        $id= $request->input('category_id');
        $category = Category::findorfail($id);
        $category->name = $request->input('name');
        $category->parent_id = $request->input('parent_id');
        if ($category->save()) {
            return response()->json($category);
        }
        return response()->json(["msg" => "ERROR !!"]);
    }

    public function deleteCategory($id)
    {
        $category = Category::find($id);
        $category->Delete();
        return response()->json(["msg" => "the category has been deleted !!"]);
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
        $result = Category::whereIn('id',$categories_id)->with('subCategories')->get();
        
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
    
    public function getCategorieById($id)
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
}
