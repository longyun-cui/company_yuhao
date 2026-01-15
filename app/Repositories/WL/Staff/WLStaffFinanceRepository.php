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

use App\Models\WL\Common\WL_Common_Finance;
use App\Models\WL\Common\WL_Common_Fee;

use App\Models\WL\Staff\WL_Staff_Record_Operation;
use App\Models\WL\Staff\WL_Staff_Record_Visit;


use App\Models\WL\CLient\WL_Client_Staff;

use App\Models\YH\YH_Item;
use App\Models\YH\YH_Task;
use App\Models\YH\YH_Pivot_Circle_Order;
use App\Models\YH\YH_Pivot_Item_Relation;

use App\Repositories\Common\CommonRepository;

use Response, Auth, Validator, DB, Exception, Cache, Blade, Carbon;
use QrCode, Excel;


class WLStaffFinanceRepository {

    private $env;
    private $auth_check;
    private $me;
    private $me_admin;
    private $modelUser;
    private $modelItem;
    private $view_blade_403;
    private $view_blade_404;


    public function __construct()
    {
        $this->modelUser = new WL_Common_Staff;
        $this->modelItem = new YH_Item;

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
     * 财务-管理 Financial
     */
    // 【费用】返回-列表-数据
    public function v1__finance__datatable_list_query($post_data)
    {
        $this->get_me();
        $me = $this->me;


        $query = WL_Common_Finance::select('*')
            ->withTrashed()
            ->with([
                'creator'=>function($query) { $query->select(['id','username']); },
                'client_er'=>function($query) { $query->select(['id','name']); },
                'project_er'=>function($query) { $query->select(['id','name']); },
                'order_er'=>function($query) { $query->select(['*']); },
                'fee_er'=>function($query) { $query->select(['*']); }
            ]);

        if(!empty($post_data['id'])) $query->where('id', $post_data['id']);
        if(!empty($post_data['name'])) $query->where('name', 'like', "%{$post_data['name']}%");
        if(!empty($post_data['title'])) $query->where('title', 'like', "%{$post_data['title']}%");
        if(!empty($post_data['remark'])) $query->where('remark', 'like', "%{$post_data['remark']}%");
        if(!empty($post_data['description'])) $query->where('description', 'like', "%{$post_data['description']}%");
        if(!empty($post_data['keyword'])) $query->where('content', 'like', "%{$post_data['keyword']}%");

        // 状态 [|]
        if(!empty($post_data['finance_status']))
        {
            if(!in_array($post_data['finance_status'],[-1,0]))
            {
                $query->where('finance_status', $post_data['finance_status']);
            }
        }
        else
        {
            $query->where('finance_status', 1);
        }


        $total = $query->count();

        $draw  = isset($post_data['draw'])  ? $post_data['draw']  : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start']  : 0;
        $limit = isset($post_data['length']) ? $post_data['length'] : 100;

        if(isset($post_data['order']))
        {
            $columns = $post_data['columns'];
            $order = $post_data['order'][0];
            $order_column = $order['column'];
            $order_dir = $order['dir'];

            $field = $columns[$order_column]["data"];
            $query->orderBy($field, $order_dir);
        }
//        else $query->orderBy("name", "asc");
        else $query->orderBy("id", "desc");

        if($limit == -1) $list = $query->get();
        else $list = $query->skip($skip)->take($limit)->get();
//        dd($list->toArray());

        return datatable_response($list, $draw, $total);
    }






}