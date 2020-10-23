<?php namespace App\Http\Controllers\Online;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CriteriaBase;
use App\Models\ProductBase;
use App\Models\ProductImage;
use App\Models\ProductItem;
use Illuminate\Http\Request;

class ProductsController extends Controller {

    public function getOnlineProducts(){

        $response= ProductBase::with(['supplier:id,first_name,last_name,email','brand:id,brand_name','category:id,category_name'])
                    ->WhereHas('items', function ($q) {
                        $q->whereNotNull('item_online_price'); })
                    ->with(['items'=> function ($q) 
                           {$q->with('images')->whereNotNull('item_online_price');}])->paginate(60)->toArray();
        
        $response['code']=1;
        $response['msg']='';
        return response()->json($response);
    }

}
