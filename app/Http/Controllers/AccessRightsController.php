<?php namespace App\Http\Controllers;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Supplier;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AccessRightsController extends Controller {

    const MODEL = "App\AccessRights";

    use RESTActions;

    public function shopManagerAccessRights(Request $requst) {
        
    }

}
