<?php
namespace App\Repositories\WL\Staff;

use App\Models\WL\API\WL_API__G7_Received;
use App\Models\WL\Common\WL_Common_Car;
use App\Models\WL\Common\WL_Common_Order;
use App\Models\WL\Staff\WL_Staff_Record_Operation;

use App\Repositories\Common\CommonRepository;

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
    public function o1__api__g7__request__test()
    {
        $HTTP_Verb = 'POST';

        //content即Http请求body部分，返回的事件数据以json的方式存在
        $content = '{"plate_nums":["沪GC2705","沪GA1516"],"fields":["loc"],"addr_required":true}';
        $data['plate_nums'] = ["沪GC2705","沪GA1516"];
        $data['fields'] = ["loc"];
        $data['addr_required'] = true;
//        $content = json_encode($data);

        //对content进行md5计算后的结果
        $content_MD5 = strtolower(md5($content));

        $Content_Type = 'application/json; charset=UTF-8';

        $Timestamp = time() * 1000;

        $url = 'http://staff.lookwit.cn/o1/api/g7/receive';
        $uri = '/o1/api/g7/receive';

        //AccessId和Secret由G7分配，是对G7对数据访问权限的控制方式，请妥善保管，不要泄漏给第三方
        $AccessId = 'vtv4ly';
        $AccessId = 'x4raay';
        $Secret = '7kj7gpuaxgwklzodxerbnghvbbxywpax';
        $Secret = '0eeubxr9p6bbvcphyf0yyver75xznbrz';

        $StringToSign = $HTTP_Verb . '\n' . '' . '\n' . $Content_Type . '\n' . $Timestamp . '\n' . $uri;
        if (!mb_check_encoding($StringToSign, 'UTF-8'))
        {
            $StringToSign = mb_convert_encoding($StringToSign, 'UTF-8');
        }
        $Signature = base64_encode(hash_hmac('sha1', $StringToSign, $Secret, true));

        $Authorization = 'g7ac ' . $Timestamp . ' ' . $AccessId . ':' . $Signature;

        $return['content'] = $content;
        $return['content_MD5'] = $content_MD5;
        $return['Timestamp'] = $Timestamp;
        $return['Content_Type'] = $Content_Type;
        $return['AccessId'] = $AccessId;
        $return['Secret'] = $Secret;
        $return['StringToSign'] = $StringToSign;
        $return['Signature'] = $Signature;
        $return['Authorization'] = $Authorization;

//        dd($return);

        $request_url = 'http://openapi.huoyunren.com/v1/device/truck/current_info/batch';
        $request_url = 'demo.dsp.chinawayltd.com/altair/rest//v1/device/truck/current_info/batch';
        $request_data = $content;
//        dd($request_data);

        $HTTP_HEADER = [];
        $HTTP_HEADER['Content-Type'] = "application/json; charset=UTF-8";
        $HTTP_HEADER['X-G7-OpenAPI-Timestamp'] = $Timestamp;
        $HTTP_HEADER['Authorization'] = $Authorization;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $request_url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json; charset=UTF-8",
            "X-G7-OpenAPI-Timestamp: {$Timestamp}",
            "Authorization: {$Authorization}"
            )
        );
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true); // post数据
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request_data); // post的变量
        $request_result = curl_exec($ch);


        if(curl_errno($ch))
        {
            curl_close($ch);
            return response_error([],"请求失败！");
        }
        else
        {
            curl_close($ch);

            $result = json_decode($request_result);
            dd($result);
        }



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