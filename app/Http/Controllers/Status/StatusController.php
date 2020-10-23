<?php namespace App\Http\Controllers\Status;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Status;

class StatusController extends Controller {

    public function getStatus(Request $request)
    {
        $Status= Status::all();
        $response['code']=1;
        $response['msg']='';
        $response['data']=$Status;
        return response()->json($response);
    }
}
