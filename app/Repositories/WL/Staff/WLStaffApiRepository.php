<?php
namespace App\Repositories\WL\Staff;

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
//        $content = '{"plate_nums":["沪GC2705","沪GA1516"],"fields":["loc"],"addr_required":true}';
        $data['plate_nums'] = ["沪GC2705","沪GA1516"];
        $data['fields'] = ["loc"];
        $data['addr_required'] = true;
        $content = json_encode($data);

        //对content进行md5计算后的结果
        $content_MD5 = strtolower(md5($content));

        $Content_Type = 'application/json; charset=UTF-8';

        $Timestamp = time() * 1000;

        $url = 'http://staff.lookwit.cn/o1/api/g7/receive';
        $uri = '/v1/device/truck/current_info/batch';

        //AccessId和Secret由G7分配，是对G7对数据访问权限的控制方式，请妥善保管，不要泄漏给第三方
        $AccessId = 'vtv4ly';
        $Secret = '7kj7gpuaxgwklzodxerbnghvbbxywpax';

        $StringToSign = $HTTP_Verb . '\n' . $content_MD5 . '\n' . $Content_Type . '\n' . $Timestamp . '\n' . $uri;
        $Signature = base64_encode(hash_hmac('sha1', $StringToSign, $Secret, true));

        $Authorization = 'g7ac ' . $Timestamp . ' ' . $AccessId . ':' . $Signature;

        $return['content'] = $content;
        $return['content_MD5'] = $content_MD5;
        $return['Timestamp'] = $Timestamp;
        $return['AccessId'] = $AccessId;
        $return['Secret'] = $Secret;
        $return['StringToSign'] = $StringToSign;
        $return['Signature'] = $Signature;
        $return['Authorization'] = $Authorization;

        dd($return);

    }


    // 【车辆】获取 GET
    public function o1__car__item_get($post_data)
    {
        $messages = [
            'operate.required' => 'operate.required.',
            'item_id.required' => 'item_id.required.',
        ];
        $v = Validator::make($post_data, [
            'operate' => 'required',
            'item_id' => 'required',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }

        $this->get_me();
        $me = $this->me;

        $operate = $post_data["operate"];
        if($operate != 'item-get') return response_error([],"参数[operate]有误！");
        $item_id = $post_data["item_id"];
        if(intval($item_id) !== 0 && !$item_id) return response_error([],"参数[ID]有误！");

        $item = WL_Common_Car::withTrashed()
            ->with([
                'creator'=>function ($query) { $query->select('id','name'); },
                'motorcade_er'=>function ($query) { $query->select('id','name'); },
                'trailer_er'=>function ($query) { $query->select('id','name','sub_name'); },
                'driver_er'=>function ($query) { $query->select('id','driver_name','driver_phone','copilot_name','copilot_phone'); },
                'copilot_er'=>function ($query) { $query->select('id','driver_name','driver_phone','copilot_name','copilot_phone'); },
            ])
            ->find($item_id);
        if(!$item) return response_error([],"不存在警告，请刷新页面重试！");

        return response_success($item,"");
    }
    // 【车辆】保存数据
    public function o1__car__item_save($post_data)
    {
//        dd($post_data);
        $messages = [
            'operate.required' => 'operate.required.',
            'name.required' => '请输入车牌号！',
//            'name.unique' => '该车牌号已存在！',
        ];
        $v = Validator::make($post_data, [
            'operate' => 'required',
            'name' => 'required',
//            'name' => 'required|unique:yh_car,name',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }


        $this->get_me();
        $me = $this->me;
        if(!in_array($me->user_type,[0,1,11,19])) return response_error([],"你没有操作权限！");


        $operate = $post_data["operate"];
        $operate_type = $operate["type"];
        $operate_id = $operate['id'];

        if($operate_type == 'create') // 添加 ( $id==0，添加一个新用户 )
        {
            $is_exist = WL_Common_Car::select('id')->where('name',$post_data["name"])->count();
            if($is_exist) return response_error([],"该【车牌号】已存在，请勿重复添加！");

            $mine = new WL_Common_Car;
            $post_data["active"] = 1;
            $post_data["creator_id"] = $me->id;
        }
        else if($operate_type == 'edit') // 编辑
        {
            $mine = WL_Common_Car::find($operate_id);
            if(!$mine) return response_error([],"该【车辆】不存在，刷新页面重试！");

            $is_exist = WL_Common_Car::select('id')->where('id','!=',$operate_id)->where('name',$post_data["name"])->count();
            if($is_exist) return response_error([],"该【车牌号】已存在，请勿重复添加！");
        }
        else return response_error([],"参数有误！");


        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            if(!empty($post_data['custom']))
            {
                $post_data['custom'] = json_encode($post_data['custom']);
            }

            $mine_data = $post_data;
            unset($mine_data['operate']);

//            if(in_array($mine_data["trailer_type"],["0","-1"])) unset($mine_data['trailer_type']);
//            if(in_array($mine_data["trailer_length"],["0","-1"])) unset($mine_data['trailer_length']);
//            if(in_array($mine_data["trailer_volume"],["0","-1"])) unset($mine_data['trailer_volume']);
//            if(in_array($mine_data["trailer_weight"],["0","-1"])) unset($mine_data['trailer_weight']);
//            if(in_array($mine_data["trailer_axis_count"],["0","-1"])) unset($mine_data['trailer_axis_count']);


            $bool = $mine->fill($mine_data)->save();
            if($bool)
            {
            }
            else throw new Exception("WL_Common_Car--insert--fail");

            DB::commit();
            return response_success(['id'=>$mine->id]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            $msg = '操作失败，请重试！';
            $msg = $e->getMessage();
//            exit($e->getMessage());
            return response_fail([],$msg);
        }

    }



}