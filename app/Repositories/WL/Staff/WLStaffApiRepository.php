<?php
namespace App\Repositories\WL\Staff;

//use App\Models\WL\API\WL_API__G7_Received;
//use App\Models\WL\Common\WL_Common_Car;
//use App\Models\WL\Common\WL_Common_Order;
//use App\Models\WL\Staff\WL_Staff_Record_Operation;

require_once base_path('lib/g7-openapi-sdk/Init.php');

use OpenApiSDK\Constant\ContentType;
use OpenApiSDK\Constant\HttpHeader;
use OpenApiSDK\Constant\HttpMethod;
use OpenApiSDK\Http\HttpClient;
use OpenApiSDK\Http\HttpRequest;
use OpenApiSDK\Util\SignUtil;


//use App\Repositories\Common\CommonRepository;

use Response, Auth, Validator, DB, Exception, Cache, Blade, Carbon;
use QrCode, Excel;


class WLStaffApiRepository {

    private $env;
    private $auth_check;
    private $me;
    private $me_admin;
    private $modelUser;
    private $modelOrder;
    private $view_blade_403;
    private $view_blade_404;


    public function __construct()
    {
        $this->view_blade_403 = env('TEMPLATE_WL_STAFF').'entrance.errors.403';
        $this->view_blade_404 = env('TEMPLATE_WL_STAFF').'entrance.errors.404';

        Blade::setEchoFormat('%s');
        Blade::setEchoFormat('e(%s)');
        Blade::setEchoFormat('nl2br(e(%s))');
    }


    // 登录情况
    public function get_me()
    {
        if(Auth::guard("wl_staff")->check())
        {
            $this->auth_check = 1;
            $this->me = Auth::guard("wl_staff")->user();
            view()->share('me',$this->me);
        }
        else $this->auth_check = 0;

        view()->share('auth_check',$this->auth_check);

        if(isMobileEquipment()) $is_mobile_equipment = 1;
        else $is_mobile_equipment = 0;
        view()->share('is_mobile_equipment',$is_mobile_equipment);
    }


    /*
     * 车辆-管理 Car
     */
    // 【车辆】返回-列表-数据
    public function o1__api__g7__request__test1()
    {

//        $demo = new Demo();
//        $demo->doDelete();

        $request_url = 'https://openapi.g7.com.cn/v1/device/truck/current_info/batch';
        $request_url = 'https://openapi.huoyunren.com/v1/device/truck/current_info/batch';
        $path = '/v1/device/truck/current_info/batch';
        $Content_Type = 'application/json; charset=UTF-8';
        $HTTP_Verb = 'POST';

        //AccessId和Secret由G7分配，是对G7对数据访问权限的控制方式，请妥善保管，不要泄漏给第三方
        $appId = 'vtv4ly';
        $appSecret = '7kj7gpuaxgwklzodxerbnghvbbxywpax';


//        date_default_timezone_set('UTC');
        date_default_timezone_set('PRC');
        $Timestamp = strval(time() * 1000);


        //content即Http请求body部分，返回的事件数据以json的方式存在
//        $content = '{"plate_nums":["沪GC2705","沪GA1516"],"fields":["loc"],"addr_required":true}';
        $data['plate_nums'] = ["沪GC2705","沪GA1516"];
        $data['fields'] = ["loc"];
        $data['addr_required'] = true;
        $content = json_encode($data);
        $bodys = array();
        $bodys[''] = $content;
//        $content = http_build_query($data);
        echo $content;
        echo '<br>';

        //对content进行md5计算后的结果
        $content_MD5 = base64_encode(md5($content, true));
//        $content_MD5 = base64_encode(md5(http_build_query($bodys), true));
        echo $content_MD5;
        echo '<br>';



//        $url = 'http://staff.lookwit.cn/o1/api/g7/receive';

        $StringToSign = $HTTP_Verb . '\n' . $content_MD5 . '\n' . $Content_Type . '\n' . $Timestamp . '\n' . $path;
        if (!mb_check_encoding($StringToSign, 'UTF-8'))
        {
            $StringToSign = mb_convert_encoding($StringToSign, 'UTF-8');
        }
        echo $StringToSign;
        echo '<br>';

        // 生成签名
        $sign = base64_encode(hash_hmac('sha256', $StringToSign, $appSecret, true));
        echo $sign;
        echo '<br>';

//        $Authorization = 'g7ac ' . $appId . ':' . $sign;
        $Authorization = 'g7ac '.$appId.':'.$sign;
        echo $Authorization;
        echo '<br>';

        $return['AccessId'] = $appId;
        $return['Secret'] = $appSecret;
        $return['Timestamp'] = $Timestamp;
        $return['content'] = $content;
        $return['content_MD5'] = $content_MD5;
        $return['Content_Type'] = $Content_Type;
        $return['StringToSign'] = $StringToSign;
        $return['Signature'] = $sign;
        $return['Authorization'] = $Authorization;

//        dd($return);

        $headers = array(
            "Content-Type: {$Content_Type}",
            "Content-MD5: {$content_MD5}",
            "X-G7-OpenAPI-Timestamp: {$Timestamp}",
            "Authorization: {$Authorization}"
        );
//        dd($headers);


        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_URL, $request_url);
//        curl_setopt($curl, CURLOPT_USERAGENT, "alpha_client");
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_POST, true); // post数据
        curl_setopt($curl, CURLOPT_POSTFIELDS, $content); // post的变量


        curl_setopt($curl, CURLOPT_TIMEOUT, 80000);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30000);

//        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
//        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        $request_result = curl_exec($curl);
        dd($request_result);
//        dd(json_decode($request_result));


//        $curl = curl_init();
//        curl_setopt($curl, CURLOPT_HTTPHEADER, $headerArray);
//        curl_setopt($curl, CURLOPT_HEADER, true);
//
//        if (0 === strpos($host, HttpSchema::HTTPS))
//        {
//            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
//            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
//        }
//        return $curl;


        if(curl_errno($curl))
        {
            curl_close($curl);
            return response_error([],"请求失败！");
        }
        else
        {
            curl_close($curl);

            $result = json_decode($request_result);
            dd($result);
        }



    }


    public function o1__api__g7__request__test() {
        //域名后、query前的部分
        $request_url = 'https://openapi.g7.com.cn/v1/device/truck/current_info/batch';
        $request_url = 'https://openapi.huoyunren.com';
        $path = '/v1/device/truck/current_info/batch';
        $AccessId = 'vtv4ly';
        $secret = '7kj7gpuaxgwklzodxerbnghvbbxywpax';
        $request = new HttpRequest($request_url, $path, HttpMethod::POST, $AccessId, $secret);
        //传入内容是json格式的字符串
        $data['plate_nums'] = ["沪GC2705","沪GA1516"];
        $data['fields'] = ["loc"];
        $data['addr_required'] = true;
        $bodyContent = json_encode($data);
//        $bodyContent = json_encode(['aa'=>'bbb']);
        //设定Content-Type，根据服务器端接受的值来设置
        $request->setHeader(HttpHeader::HTTP_HEADER_CONTENT_TYPE, ContentType::CONTENT_TYPE_JSON);

        //设定Accept，根据服务器端接受的值来设置
        //$request->setHeader(HttpHeader::HTTP_HEADER_ACCEPT, ContentType::CONTENT_TYPE_JSON);

        //注意：业务header部分，如果没有则无此行(如果有中文，请做Utf8ToIso88591处理)
        //mb_convert_encoding("headervalue2中文", "ISO-8859-1", "UTF-8");
//        $request->setHeader("X-G7-Ca-header2", "headervalue2");
//        $request->setHeader("X-G7-Ca-header1", "headervalue1");

        //注意：业务body部分，不能设置key值，只能有value
        if (0 < strlen($bodyContent)) {
            $request->setHeader(HttpHeader::HTTP_HEADER_CONTENT_MD5,SignUtil::md5Body($bodyContent));
            $request->setBodyString($bodyContent);
        }

        $response = HttpClient::execute($request);
        dd(json_decode($response->getBody()));
    }


    // 【车辆】获取 GET
    public function o1__api__g7__receive__test($post_data)
    {

        $datetime = date('Y-m-d H:i:s');
        $date = date("Y-m-d");
        $time = time();


        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $api_insert_data['received_date'] = $date;


            $g7_received = new WL_API__G7_Received;
            $bool_crc = $g7_received->fill($api_insert_data)->save();
            if(!$bool_crc) throw new Exception("WL_API__G7_Received--insert--fail");


            DB::commit();

            $return['result']['error'] = 0;
            $return['result']['msg'] = '';
            return json_encode($return);

        }
        catch (Exception $e)
        {
            DB::rollback();
//            $msg = '操作失败，请重试！';
            $msg = $e->getMessage();
//            exit($e->getMessage());
//            return response_fail([],$msg);

            $return['result']['error'] = 1;
            $return['result']['msg'] = $msg;
            return json_encode($return);
        }

        return response_success($item,"");
    }



}