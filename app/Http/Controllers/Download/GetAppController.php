<?php namespace App\Http\Controllers\Download;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Models\Version;
use Illuminate\Http\Request;
use mysql_xdevapi\Exception;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;


/*
SIYOU THECH sz
Author: youxianyen
ERROR MSG
* 1:
*/

class GetAppController extends Controller {

    public function __construct()
    {
//        $this->middleware('auth:api');
    }

    /* get download URL API
     - Necessary Parameters:
    - optional Parameters:
       Accessible for : download
    */
    public function download(Request $request)
    {
        try{
            $type = $request->only(['type']);
            if (!isset($type['type'])) {
                throw new \Exception("arguments are required");
            }
            if ($type['type'] != 1 && $type['type'] != 2)
            {
                $response = array();
                $response['code']= 0;
                $response['msg']='3';
                $response['data']='file not found';
                return response()->json($response, 200);
            }
		//echo $type['type'];
	 if($type['type']==1) {
		 $filename = storage_path('app/public/version'.$type['type'].'.ini');
		}else {$filename = storage_path('app/public/version'.$type['type'].'.ini');}
           
	    //echo $filename;
            if (!file_exists($filename)) {
                throw new \Exception("file not found");
            }
            $str = file_get_contents($filename);
            $arr = json_decode($str, true);

            $response['code']=1;
            $response['msg']='';
            $response['data']=$arr;
//            $host = $_SERVER["HTTP_HOST"];
            //$host = 'http://47.115.128.57/s2c';
	    $host = 'https://siyoumarket.uk/s2c';
            if ($type['type'] == 1)
            {
                $path = $host . '/storage/versions/siyouCassa_v' . $arr['version'] . '.zip';
                $path_exe = $host . '/storage/versions/SiyouCassa_v' . $arr['version'] . '_setup.exe';
            }else
            {
		//echo $arr['version'];
                $path = $host . '/storage/versions/SiyouStore_V_' . $arr['version'] . '.zip';
                $path_exe = $host . '/storage/versions/SiyouStore_V_' . $arr['version'] . '_setup.exe';
               
            }
            //        $response['data']['path']=storage_path("$path");
            //        $path = response()->download(storage_path("$path"));
//            $response['data']['path']= base_path($path);;
            $response['data']['path']= ($path);
            $response['data']['path_exe']= ($path_exe);
            ;
            return response()->json($response);
        } catch (\Exception $e) {
            $response = array();
            $response['code']= 0;
            $response['msg']='3';
            $response['data']=$e->getMessage().$e->getLine();
            return response()->json($response, 200);
        }
    }

    /* get download URL API
 - Necessary Parameters:version,remarks,
    type = 1:SiyouCassa  type = 2:SiyouStore
- optional Parameters:
   Accessible for :
*/
    public function update(Request $request)
    {
        try {
            $data=$request->only(['version','remarks','type']);
            if (!isset($data['version'],$data['remarks'],$data['type'])) {
                throw new \Exception("arguments are required");
            }
            $logdata = json_encode($data);
            //????
            $filename = storage_path('app/public/version'.$data['type'].'.ini');
//            $fileStr = file_put_contents($filename,"$logdata".PHP_EOL, FILE_APPEND | LOCK_EX);
            $fileStr = file_put_contents($filename,"$logdata".PHP_EOL);
//            Storage::put('version/app.ini', $fileContents);
            $response['code'] = 1;
            $response['msg'] = "";
            $response['data'] = 'version info has been added';
            return response()->json($response,200);
        } catch (\Exception $e) {
            $response = array();
            $response['code']= 0;
            $response['msg']='3';
            $response['data']=$e->getMessage();
            return response()->json($response, 500);
        }

    }




}