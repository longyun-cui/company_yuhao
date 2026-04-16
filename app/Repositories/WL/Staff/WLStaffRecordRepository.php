<?php
namespace App\Repositories\WL\Staff;

use App\Models\WL\Common\WL_Common_Company;
use App\Models\WL\Common\WL_Common_Department;
use App\Models\WL\Common\WL_Common_Team;
use App\Models\WL\Common\WL_Common_Staff;

use App\Models\WL\Common\WL_Common_Car;
use App\Models\WL\Common\WL_Common_Driver;

use App\Models\WL\Common\WL_Common_Client;
use App\Models\WL\Common\WL_Common_Project;
use App\Models\WL\Common\WL_Common_Order;
use App\Models\WL\Common\WL_Common_Order_Operation_Record;

use App\Models\WL\Common\WL_Common_Finance;
use App\Models\WL\Common\WL_Common_Fee;

use App\Models\WL\Common\WL_Common_Transport_Journey;
use App\Models\WL\Staff\WL_Staff_Record_Operation;
use App\Models\WL\Staff\WL_Staff_Record_Visit;

use App\Repositories\Common\CommonRepository;

use Response, Auth, Validator, DB, Exception, Cache, Blade, Carbon;
use QrCode, Excel;


class WLStaffRecordRepository {

    private $env;
    private $auth_check;
    private $me;
    private $me_admin;
    private $modelOrder;
    private $modelItem;
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
     * 导出
     */
    // 【导出】工单
    public function o1__record__list__datatable_query($post_data)
    {
        $this->get_me();
        $me = $this->me;

        $query = WL_Staff_Record_Operation::select('*')->withTrashed()
            ->with('creator')
//            ->where(['owner_id'=>100,'item_category'=>100])
//            ->where('item_type', '!=',0);
            ->where(['record_object'=>21,'operate_object'=>71]);

        if(!empty($post_data['name'])) $query->where('name', 'like', "%{$post_data['name']}%");
        if(!empty($post_data['title'])) $query->where('title', 'like', "%{$post_data['title']}%");
        if(!empty($post_data['tag'])) $query->where('tag', 'like', "%{$post_data['tag']}%");


        // 创建方式 [人工|导入|api]
        if(isset($post_data['operate_type']))
        {
            if(!in_array($post_data['operate_type'],[-1,'-1']))
            {
                $query->where('operate_type', $post_data['operate_type']);
            }
        }

        // 员工
        if(!empty($post_data['staff']))
        {
            if(!in_array($post_data['staff'],[-1,0,'-1','0']))
            {
                $query->where('creator_id', $post_data['staff']);
            }
        }



        if($me->staff_category == 51)
        {
            if($me->staff_position == 99)
            {
                $query->where('creator_id',$me->id);
            }
            else
            {
                $subordinates_array = WL_Common_Staff::select('id')->where('superior_id',$me->id)->get()->pluck('id')->toArray();

                $query->where(function($query) use($me,$subordinates_array) {
                    $query->where('creator_id',$me->id)->orWhereIn('creator_id',$subordinates_array);
                });
            }
        }


        $item_type = isset($post_data['item_type']) ? $post_data['item_type'] : '';
        if($item_type == "record") $query->whereIn('operate_category', [109,110,111]);
//        else if($item_type == "object") $query->where('item_type', 1);
//        else if($item_type == "people") $query->where('item_type', 11);
//        else if($item_type == "product") $query->where('item_type', 22);
//        else if($item_type == "event") $query->where('item_type', 33);
//        else if($item_type == "conception") $query->where('item_type', 91);


        $total = $query->count();

        $draw  = isset($post_data['draw'])  ? $post_data['draw']  : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start']  : 0;
        $limit = isset($post_data['length']) ? $post_data['length'] : 10;
        if($limit > 100) $limit = 100;

        if(isset($post_data['order']))
        {
            $columns = $post_data['columns'];
            $order = $post_data['order'][0];
            $order_column = $order['column'];
            $order_dir = $order['dir'];

            $field = $columns[$order_column]["data"];
            $query->orderBy($field, $order_dir);
        }
        else $query->orderBy("id", "desc");

        if($limit == -1) $list = $query->skip($skip)->take(100)->get();
        else $list = $query->skip($skip)->take($limit)->get();

        foreach ($list as $k => $v)
        {
//            $list[$k]->description = replace_blank($v->description);
        }
//        dd($list->toArray());
        return datatable_response($list, $draw, $total);
    }




}