<?php
namespace App\Repositories\WL\Staff;

use App\Models\WL\Common\WL_Common_Company;
use App\Models\WL\Common\WL_Common_Department;
use App\Models\WL\Common\WL_Common_Team;
use App\Models\WL\Common\WL_Common_Staff;

use App\Models\WL\Common\WL_Common_Motorcade;
use App\Models\WL\Common\WL_Common_Car;
use App\Models\WL\Common\WL_Common_Driver;

use App\Models\WL\Common\WL_Common_Client;
use App\Models\WL\Common\WL_Common_Project;
use App\Models\WL\Common\WL_Common_Order;

use App\Repositories\Common\CommonRepository;

use Response, Auth, Validator, DB, Exception, Cache, Blade, Carbon;
use QrCode, Excel;


class WLStaffCommonRepository {

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
        $this->modelUser = new WL_Common_Staff;
        $this->modelOrder = new WL_Common_Order;

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




    // 返回主页视图
    public function view_staff_index()
    {
        $this->get_me();
        $me = $this->me;


        Cache::forget('wl_common_department_list');
        if(Cache::has('wl_common_department_list'))
        {
            $wl_common_department_list = Cache::get('wl_common_department_list');
        }
        else
        {
            $wl_common_department_list = WL_Common_Department::select(['id','item_status','name','department_category','department_type','leader_id','superior_department_id','remark','creator_id','created_at','updated_at','deleted_at'])
                ->withTrashed()
                ->with([
                    'creator'=>function($query) { $query->select(['id','username','true_name']); },
                    'leader'=>function($query) { $query->select(['id','username','true_name']); },
                    'superior_department_er'=>function($query) { $query->select(['id','name']); }
                ])
                ->get()->keyBy('id');

            Cache::forever('wl_common_department_list',$wl_common_department_list);
//            Cache::put('wl_common_department_list',$department_list,1440*7);
        }
//        dd($wl_common_department_list);
        $view_data['wl_common_department_list'] = $wl_common_department_list;

        $view_blade = env('TEMPLATE_WL_STAFF').'entrance.index';
        return view($view_blade)->with($view_data);
    }


    // 返回404视图
    public function view_staff_404()
    {
        $this->get_me();
        $view_blade = env('TEMPLATE_WL_STAFF').'entrance.errors.404';
        return view($view_blade);
    }





    /*
     * select2
     */
    // 公司
    public function o1__select2__company($post_data)
    {
        $this->get_me();
        $me = $this->me;

        $query = WL_Common_Company::select(['id','name as text'])
            ->where(['item_status'=>1]);

        if(!empty($post_data['keyword']))
        {
            $keyword = "%{$post_data['keyword']}%";
            $query->where('name','like',"%$keyword%");
        }

        if(!empty($post_data['type']))
        {
            $type = $post_data['type'];
            if($type == 'all')
            {
            }
            else if($type == 'company')
            {
                $query->where(['company_category'=>1]);
            }
            else if($type == 'channel')
            {
                $query->where(['company_category'=>11]);
                if(!empty($post_data['company_id']))
                {
                    $query->where('superior_company_id',$post_data['company_id']);
                }
            }
            else if($type == 'business')
            {
                $query->where(['company_category'=>21]);
                if(!empty($post_data['channel_id']))
                {
                    $query->where('superior_company_id',$post_data['channel_id']);
                }
            }
            else
            {
//                $query->where(['department_type'=>11]);
            }
        }
        else
        {
//            $query->where(['department_type'=>11]);
        }

//        if($me->staff_type == 81)
//        {
//            $query->where('id',$me->department_district_id);
//        }

        $list = $query->orderBy('id','asc')->get()->toArray();

//        $unSpecified = ['id'=>0,'text'=>'[未指定]'];
//        array_unshift($list,$unSpecified);
        $unSpecified = ['id'=>-1,'text'=>'[选择公司]'];
        array_unshift($list,$unSpecified);

        return $list;
    }
    // 部门
    public function o1__select2__department($post_data)
    {
        $query = WL_Common_Department::select(['id','name as text'])
            ->where(['item_status'=>1]);

        if(!empty($post_data['keyword']))
        {
            $keyword = "%{$post_data['keyword']}%";
            $query->where('name','like',"%$keyword%");
        }

        if(!empty($post_data['department_category']))
        {
            $query->where('department_category',$post_data['department_category']);
        }
        if(!empty($post_data['department_type']))
        {
            $query->where('department_type',$post_data['department_type']);
        }
        if(!empty($post_data['company_id']))
        {
            $query->where('company_id',$post_data['company_id']);
        }

        $list = $query->orderBy('id','asc')->get()->toArray();

//        $unSpecified = ['id'=>0,'text'=>'[未指定]'];
//        array_unshift($list,$unSpecified);
        $unSpecified = ['id'=>-1,'text'=>'选择部门'];
        array_unshift($list,$unSpecified);

        return $list;
    }
    // 团队
    public function o1__select2__team($post_data)
    {
        $query = WL_Common_Team::select(['id','name as text'])
            ->where(['item_status'=>1]);

        if(!empty($post_data['keyword']))
        {
            $keyword = "%{$post_data['keyword']}%";
            $query->where('name','like',"%$keyword%");
        }


        if(!empty($post_data['department_type']))
        {
            $query->where('department_type',$post_data['department_type']);
        }
        if(!empty($post_data['team_category']))
        {
            $query->where('team_category',$post_data['team_category']);
        }
        if(!empty($post_data['team_type']))
        {
            $query->where('team_type',$post_data['team_type']);
        }
        if(!empty($post_data['department_id']))
        {
            $query->where('department_id',$post_data['department_id']);
        }

        $list = $query->orderBy('id','asc')->get()->toArray();

//        $unSpecified = ['id'=>0,'text'=>'[未指定]'];
//        array_unshift($list,$unSpecified);
        $unSpecified = ['id'=>-1,'text'=>'选择团队'];
        array_unshift($list,$unSpecified);

        return $list;
    }
    // 员工
    public function o1__select2__staff($post_data)
    {
        $this->get_me();
        $me = $this->me;

        $query = WL_Common_Staff::select(['id','username as text'])
            ->where(['item_status'=>1]);

        if(!empty($post_data['keyword']))
        {
            $keyword = "%{$post_data['keyword']}%";
            $query->where('name','like',"%$keyword%");
        }


        if($me->department_id > 0)
        {
            $query->where('department_id',$me->department_id);
        }
        if($me->team_id > 0)
        {
            $query->where('team_id',$me->team_id);
        }


        if(!empty($post_data['staff_category']))
        {
            $staff_category_int = intval($post_data['staff_category']);
            if(!in_array($staff_category_int,[-1,0]))
            {
                $query->where('staff_category',$staff_category_int);
            }
        }
        if(!empty($post_data['staff_type']))
        {
            $staff_type_int = intval($post_data['staff_type']);
            if(!in_array($staff_type_int,[-1,0]))
            {
                $query->where('staff_type',$staff_type_int);
            }
        }


        if(!empty($post_data['type']))
        {
            $type = $post_data['type'];
            if($type == 'inspector') $query->where(['user_type'=>77]);
        }

        $list = $query->orderBy('id','asc')->get()->toArray();

//        $unSpecified = ['id'=>0,'text'=>'[未指定]'];
//        array_unshift($list,$unSpecified);
        $unSpecified = ['id'=>-1,'text'=>'选择员工'];
        array_unshift($list,$unSpecified);

        return $list;
    }
    // 车队
    public function o1__select2__motorcade($post_data)
    {
        $query = WL_Common_Motorcade::select(['id','name as text'])
            ->where(['item_status'=>1]);

        if(!empty($post_data['keyword']))
        {
            $keyword = "%{$post_data['keyword']}%";
            $query->where('name','like',"%$keyword%");
        }


        if(!empty($post_data['department_type']))
        {
            $query->where('department_type',$post_data['department_type']);
        }
        if(!empty($post_data['team_category']))
        {
            $query->where('team_category',$post_data['team_category']);
        }
        if(!empty($post_data['team_type']))
        {
            $query->where('team_type',$post_data['team_type']);
        }
        if(!empty($post_data['department_id']))
        {
            $query->where('department_id',$post_data['department_id']);
        }

        $list = $query->orderBy('id','asc')->get()->toArray();

//        $unSpecified = ['id'=>0,'text'=>'[未指定]'];
//        array_unshift($list,$unSpecified);
        $unSpecified = ['id'=>-1,'text'=>'选择团队'];
        array_unshift($list,$unSpecified);

        return $list;
    }
    // 车辆
    public function o1__select2__car($post_data)
    {
        $this->get_me();
        $me = $this->me;

        $query = WL_Common_Car::select(['id','name as text','trailer_id','driver_id','copilot_id'])
            ->with([
//                'trailer_er'=>function($query) { $query->select('id','name'); },
//                'driver_er'=>function($query) { $query->select('id','driver_name','driver_phone'); },
//                'copilot_er'=>function($query) { $query->select('id','driver_name','driver_phone'); },
            ])
            ->where(['item_status'=>1]);

        if(!empty($post_data['keyword']))
        {
            $keyword = "%{$post_data['keyword']}%";
            $query->where('name','like',"%$keyword%");
        }

        if(!empty($post_data['car_type']))
        {
            $query->where('car_type',$post_data['car_type']);
        }

        $list = $query->orderBy('id','asc')->get()->toArray();

//        $unSpecified = ['id'=>0,'text'=>'[未指定]'];
//        array_unshift($list,$unSpecified);
        $unSpecified = ['id'=>-1,'text'=>'选择车辆'];
        array_unshift($list,$unSpecified);

        return $list;
    }
    // 司机
    public function o1__select2__driver($post_data)
    {
        $this->get_me();
        $me = $this->me;

        $query = WL_Common_Driver::select(['id','driver_name as text','driver_phone'])
            ->where(['item_status'=>1]);

        if(!empty($post_data['keyword']))
        {
            $keyword = "%{$post_data['keyword']}%";
            $query->where('name','like',"%$keyword%");
        }

        $list = $query->orderBy('id','desc')->get()->toArray();

//        $unSpecified = ['id'=>0,'text'=>'[未指定]'];
//        array_unshift($list,$unSpecified);
        $unSpecified = ['id'=>-1,'text'=>'选择司机'];
        array_unshift($list,$unSpecified);

        return $list;
    }
    // 客户
    public function o1__select2__client($post_data)
    {
        $query = WL_Common_Client::select(['id','name as text'])
            ->where(['item_status'=>1]);

        if(!empty($post_data['keyword']))
        {
            $keyword = "%{$post_data['keyword']}%";
            $query->where('username','like',"%$keyword%");
        }

        if(!empty($post_data['client_category']))
        {
            $client_category_int = intval($post_data['client_category']);
            if(!in_array($client_category_int,[-1,0]))
            {
                $query->where('client_category',$client_category_int);
            }
        }
        if(!empty($post_data['client_type']))
        {
            $client_type_int = intval($post_data['client_type']);
            if(!in_array($client_type_int,[-1,0]))
            {
                $query->where('client_type',$client_type_int);
            }
        }

        $list = $query->orderBy('id','asc')->get()->toArray();

//        $unSpecified = ['id'=>0,'text'=>'[未指定]'];
//        array_unshift($list,$unSpecified);
        $unSpecified = ['id'=>-1,'text'=>'选择客户'];
        array_unshift($list,$unSpecified);

        return $list;
    }
    // 项目
    public function o1__select2__project($post_data)
    {
        $this->get_me();
        $me = $this->me;

        $query = WL_Common_Project::select(['id','name as text','transport_departure_place','transport_destination_place','transport_distance','transport_time_limitation','freight_amount'])
            ->where('item_status',1);

        if(!empty($post_data['keyword']))
        {
            $keyword = "%{$post_data['keyword']}%";
            $query->where('name','like',"%$keyword%");
        }

        if(!empty($post_data['project_category']))
        {
            $project_category_int = intval($post_data['project_category']);
            if(!in_array($project_category_int,[-1,0]))
            {
                $query->where('project_category',$project_category_int);
            }
        }
        if(!empty($post_data['project_type']))
        {
            $project_type_int = intval($post_data['project_type']);
            if(!in_array($project_type_int,[-1,0,]))
            {
                $query->where('project_type',$project_type_int);
            }
        }


        $list = $query->orderBy('id','asc')->get()->toArray();

//        $unSpecified = ['id'=>0,'text'=>'[未指定]'];
//        array_unshift($list,$unSpecified);
        $unSpecified = ['id'=>-1,'text'=>'选择项目'];
        array_unshift($list,$unSpecified);

        return $list;
    }
    



}