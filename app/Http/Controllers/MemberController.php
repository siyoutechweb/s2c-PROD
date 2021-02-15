<?php namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Member;
use DB;

//chineese team
// use SimpleSoftwareIO\QrCode\Facades\QrCode; 
// use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Storage;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\LabelAlignment;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Response\QrCodeResponse;
use App\Http\Controllers\AuthController;
use App\Models\User;

use Ramsey\Uuid\Uuid;

use Illuminate\Support\Facades\Redis;
use lluminate\Support\Facades\Cache;

class MemberController extends Controller {

    public  function qrCode(Request $request){
        $token = $request->input('token');
        $shop_owner = AuthController::me();
        if (!$shop_owner->token === $token) {
            $response = array();
            $response['code']=0;
            $response['msg']="3";
            $response['data']='Error while saving!!';
            return response()->json($response);
        }     
        
    }

    

    //public function show_url(){
    // public function showUrl(){
    //     $data = Uuid::uuid1();
    //     $str = $data->getHex();    //32位字符串方法
    //     $img = $this->create_img("$str");
    //     echo "<br>$str";;
    //     echo "<br>";
    //     $url='https://siyou-b2s2c-stag.herokuapp.com/shop/shopoverview?token=' . $str;//要转成二维码的url地址
    //     $img = $this->createImg("$url");
    // }

    public function setRedis($val){
        Redis::set('name', 'guwenjie');
        $values = Redis::get('name');
        return $values;
    }

    // public function testRedis()
    // {
    //     Redis::set('name', 'guwenjie');
    //     $values = Redis::get('name');
    //     dd($values);
    //     //输出："guwenjie"
    //     //加一个小例子比如网站首页某个人员或者某条新闻日访问量特别高，可以存储进redis，减轻内存压力
    //     $userinfo = Member::find(id);
    //     Redis::set('user_key',$userinfo);
    //     if(Redis::exists('user_key')){
    //         $values = Redis::get('user_key');
    //     }else{
    //         $values = Member::find(id);
    //     }
    //     dump($values);
    // }

    //private function create_img($url){

    private function createImg($url){
        $qrCode = new QrCode("$url");
        $qrCode->setSize(300);
        //$qrCode->setMargin(10); 
        $qrCode->setMargin(10);

        // Set advanced options
        $qrCode->setWriterByName('png');
        $qrCode->setEncoding('UTF-8');
        $qrCode->setErrorCorrectionLevel(ErrorCorrectionLevel::HIGH());
        $qrCode->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0]);
        $qrCode->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0]);
        // $qrCode->setLabel('Scan the code', 16, __DIR__.'/../assets/fonts/noto_sans.otf', LabelAlignment::CENTER());
        // $qrCode->setLogoPath(__DIR__.'/../assets/images/symfony.png');
        $qrCode->setLogoSize(150, 200);
        $qrCode->setValidateResult(false);
        // Round block sizes to improve readability and make the blocks sharper in pixel based outputs (like png).
        // There are three approaches:
        $qrCode->setRoundBlockSize(true, QrCode::ROUND_BLOCK_SIZE_MODE_MARGIN); // The size of the qr code is shrinked, if necessary, but the size of the final image remains unchanged due to additional margin being added (default)
        $qrCode->setRoundBlockSize(true, QrCode::ROUND_BLOCK_SIZE_MODE_ENLARGE); // The size of the qr code and the final image is enlarged, if necessary
        $qrCode->setRoundBlockSize(true, QrCode::ROUND_BLOCK_SIZE_MODE_SHRINK); // The size of the qr code and the final image is shrinked, if necessary
        // Set additional writer options (SvgWriter example)
        $qrCode->setWriterOptions(['exclude_xml_declaration' => true]);
        // Directly output the QR code
        header('Content-Type: '.$qrCode->getContentType());
        // echo $qrCode->writeString();
        // Save it to a file
        // $qrCode->writeFile(__DIR__.'/qrcode.png');
        $qrCode->writeFile(base_path().'/public/images/qrcode.png');
        // Generate a data URI to include image data inline (i.e. inside an <img> tag)
        // $dataUri = $qrCode->writeDataUri();
        echo "<img src='http://localhost/images/qrcode.png'>";
    }

    //判断登录状态
    public function getLoginStatus(){
        $shop_owner = AuthController::me();
        if ($shop_owner->token){
            $response = array();
            $response['code']=1;
            $response['msg']='success';
            $response['data']=[];
            return response()->json($response);
        }else{
            $response = array();
            $response['code']=0;
            $response['msg']='success';
            $response['data']=[];
            return response()->json($response);
        }
    }

    //接收二维码跳转后传参,校验用户登录跳转
    // public function toLogin(Request $request){
    //     $token = $request->input('token');
    //     $user_id = $request->input('user_id');
    //     $shop_owner = AuthController::respondWithToken($token);

    // }



}


