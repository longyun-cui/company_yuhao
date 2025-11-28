<?php
namespace App\Repositories\WL;

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


class WLStaffRepository {

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
    public function v1_operate_select2_company($post_data)
    {
        $this->get_me();
        $me = $this->me;

        $query = WL_Common_Company::select(['id','name as text'])
            ->where(['user_status'=>1]);

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

        $list = $query->orderBy('id','desc')->get()->toArray();
        $unSpecified = ['id'=>0,'text'=>'[未指定]'];
        array_unshift($list,$unSpecified);
        $unSpecified = ['id'=>-1,'text'=>'[选择]'];
        array_unshift($list,$unSpecified);
        return $list;
    }
    // 部门
    public function v1_operate_select2_department($post_data)
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

        $list = $query->orderBy('id','desc')->get()->toArray();
        $unSpecified = ['id'=>0,'text'=>'[未指定]'];
        array_unshift($list,$unSpecified);
        $unSpecified = ['id'=>-1,'text'=>'选择部门'];
        array_unshift($list,$unSpecified);
        return $list;
    }
    // 团队
    public function v1_operate_select2_team($post_data)
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

        $list = $query->orderBy('id','desc')->get()->toArray();

        $unSpecified = ['id'=>0,'text'=>'[未指定]'];
        array_unshift($list,$unSpecified);
        $unSpecified = ['id'=>-1,'text'=>'选择团队'];
        array_unshift($list,$unSpecified);

        return $list;
    }
    // 员工
    public function v1_operate_select2_staff($post_data)
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
            if(!in_array($post_data['staff_category'],[-1,0,'-1','0']))
            {
                $query->where('staff_category',$post_data['staff_category']);
            }
        }
        if(!empty($post_data['staff_type']))
        {
            if(!in_array($post_data['staff_type'],[-1,0,'-1','0']))
            {
                $query->where('staff_type',$post_data['staff_type']);
            }
        }


        if(!empty($post_data['type']))
        {
            $type = $post_data['type'];
            if($type == 'inspector') $query->where(['user_type'=>77]);
        }

        $list = $query->orderBy('id','desc')->get()->toArray();

        $unSpecified = ['id'=>0,'text'=>'[未指定]'];
        array_unshift($list,$unSpecified);
        $unSpecified = ['id'=>-1,'text'=>'选择员工'];
        array_unshift($list,$unSpecified);

        return $list;
    }
    // 车辆
    public function v1_operate_select2_car($post_data)
    {
        $this->get_me();
        $me = $this->me;

        $query = WL_Common_Car::select(['id','name as text'])
            ->with('driver_er')
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

        $list = $query->orderBy('id','desc')->get()->toArray();

        $unSpecified = ['id'=>0,'text'=>'[未指定]'];
        array_unshift($list,$unSpecified);
        $unSpecified = ['id'=>-1,'text'=>'选择车辆'];
        array_unshift($list,$unSpecified);

        return $list;
    }
    // 司机
    public function v1_operate_select2_driver($post_data)
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
        $unSpecified = ['id'=>0,'text'=>'[未指定]'];
        array_unshift($list,$unSpecified);
        $unSpecified = ['id'=>-1,'text'=>'选择司机'];
        array_unshift($list,$unSpecified);

        return $list;
    }
    // 客户
    public function v1_operate_select2_client($post_data)
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
            if(!in_array($post_data['client_category'],[-1,0,'-1','0']))
            {
                $query->where('client_category',$post_data['client_category']);
            }
        }
        if(!empty($post_data['client_type']))
        {
            if(!in_array($post_data['client_type'],[-1,0,'-1','0']))
            {
                $query->where('client_type',$post_data['client_type']);
            }
        }

        $list = $query->orderBy('id','desc')->get()->toArray();
        $unSpecified = ['id'=>0,'text'=>'[未指定]'];
        array_unshift($list,$unSpecified);
        $unSpecified = ['id'=>-1,'text'=>'选择客户'];
        array_unshift($list,$unSpecified);
        return $list;
    }
    // 项目
    public function v1_operate_select2_project($post_data)
    {
        $this->get_me();
        $me = $this->me;

        $query = WL_Common_Project::select(['id','name as text','transport_departure_place','transport_destination_place','transport_mileage','transport_time_limitation','freight_amount'])
            ->where('item_status',1);

        if(!empty($post_data['keyword']))
        {
            $keyword = "%{$post_data['keyword']}%";
            $query->where('name','like',"%$keyword%");
        }

        if(!empty($post_data['project_category']))
        {
            if(!in_array($post_data['project_category'],[-1,0,'-1','0']))
            {
                $query->where('project_category',$post_data['project_category']);
            }
        }
        if(!empty($post_data['project_type']))
        {
            if(!in_array($post_data['project_type'],[-1,0,'-1','0']))
            {
                $query->where('project_type',$post_data['project_type']);
            }
        }


        $list = $query->orderBy('id','desc')->get()->toArray();
        $unSpecified = ['id'=>0,'text'=>'[未指定]'];
        array_unshift($list,$unSpecified);
        $unSpecified = ['id'=>-1,'text'=>'选择项目'];
        array_unshift($list,$unSpecified);
        return $list;
    }
    





    /*
     * 部门-管理 Department
     */
    // 【部门】返回-列表-数据
    public function v1_operate__department_datatable_list_query($post_data)
    {
        $this->get_me();
        $me = $this->me;


        $query = WL_Common_Department::select(['id','item_status','name','department_category','department_type','leader_id','superior_department_id','remark','creator_id','created_at','updated_at','deleted_at'])
            ->withTrashed()
            ->with([
                'creator'=>function($query) { $query->select(['id','username','true_name']); },
                'leader'=>function($query) { $query->select(['id','username','true_name']); },
                'superior_department_er'=>function($query) { $query->select(['id','name']); }
            ]);

        if(in_array($me->staff_type,[41,81]))
        {
            $query->where('superior_department_id',$me->department_district_id);
            $query->whereNotIn('superior_department_id', [-1,0]);
        }

        if(!empty($post_data['id'])) $query->where('id', $post_data['id']);
        if(!empty($post_data['name'])) $query->where('name', 'like', "%{$post_data['name']}%");
        if(!empty($post_data['title'])) $query->where('title', 'like', "%{$post_data['title']}%");
        if(!empty($post_data['remark'])) $query->where('remark', 'like', "%{$post_data['remark']}%");
        if(!empty($post_data['description'])) $query->where('description', 'like', "%{$post_data['description']}%");
        if(!empty($post_data['keyword'])) $query->where('content', 'like', "%{$post_data['keyword']}%");
        if(!empty($post_data['username'])) $query->where('username', 'like', "%{$post_data['username']}%");
        if(!empty($post_data['mobile'])) $query->where('mobile', $post_data['mobile']);

        // 状态 [|]
        if(!empty($post_data['item_status']))
        {
            if(!in_array($post_data['item_status'],[-1,0,'-1','0']))
            {
                $query->where('item_status', $post_data['item_status']);
            }
        }
        else
        {
            $query->where('item_status', 1);
        }

        // 部门类型 [人事部|行政部|财务部]
        if(!empty($post_data['department_category']))
        {
            if(!in_array($post_data['department_category'],[-1,0]))
            {
                $query->where('department_category', $post_data['department_category']);
            }
        }

        $total = $query->count();

        $draw  = isset($post_data['draw'])  ? $post_data['draw']  : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start']  : 0;
        $limit = isset($post_data['length']) ? $post_data['length'] : 10;

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

        if($limit == -1) $list = $query->get();
        else $list = $query->skip($skip)->take($limit)->get();
//        dd($list->toArray());

        foreach($list as $k => $v)
        {
            if($v->department_type == 11)
            {
                $v->district_id = $v->id;
            }
            else if($v->department_type == 21)
            {
                $v->district_id = $v->superior_department_id;
            }

            $v->district_group_id = $v->district_id.'.'.$v->id;
        }
//        $list = $list->sortBy(['rank'=>'asc'])->values();
//        $list = $list->sortBy(function ($item, $key) {
//            return $item['district_group_id'];
//        })->values();
//        dd($list->toArray());

        return datatable_response($list, $draw, $total);
    }
    // 【部门】获取 GET
    public function v1_operate__department_item_get($post_data)
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
        $id = $post_data["item_id"];
        if(intval($id) !== 0 && !$id) return response_error([],"参数[ID]有误！");

        $item = WL_Common_Department::withTrashed()
            ->with([
                'leader'=>function($query) { $query->select('id','username'); },
                'superior_department_er'=>function($query) { $query->select('id','name'); }
            ])
            ->find($id);
        if(!$item) return response_error([],"不存在警告，请刷新页面重试！");

        return response_success($item,"");
    }
    // 【部门】保存 SAVE
    public function v1_operate__department_item_save($post_data)
    {
        $messages = [
            'operate.required' => 'operate.required.',
            'department_category.required' => '请选择部门类型！',
            'name.required' => '请输入部门名称！',
//            'name.unique' => '该部门号已存在！',
        ];
        $v = Validator::make($post_data, [
            'operate' => 'required',
            'department_category' => 'required',
            'name' => 'required',
//            'name' => 'required|unique:WL_Common_Department,name',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }


        $operate = $post_data["operate"];
        $operate_type = $operate["type"];
        $operate_id = $operate['id'];

        $this->get_me();
        $me = $this->me;

        // 判断用户操作权限
        if(!in_array($me->staff_type,[0,1,11,19,41])) return response_error([],"你没有操作权限！");


        if($operate_type == 'create') // 添加 ( $id==0，添加一个新用户 )
        {
            $is_exist = WL_Common_Department::select('id')->where('name',$post_data["name"])->count();
            if($is_exist) return response_error([],"该【部门】已存在，请勿重复添加！");

            $mine = new WL_Common_Department;
            $post_data["active"] = 1;
            $post_data["creator_id"] = $me->id;
        }
        else if($operate_type == 'edit') // 编辑
        {
            $mine = WL_Common_Department::find($operate_id);
            if(!$mine) return response_error([],"该【部门】不存在，刷新页面重试！");
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

            if(in_array($me->staff_type,[41,61,71,81]))
            {
                $mine_data['superior_department_id'] = $me->department_district_id;
            }

            $bool = $mine->fill($mine_data)->save();
            if($bool)
            {
            }
            else throw new Exception("WL_Common_Department--insert--fail");

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

    // 【部门】管理员-启用
    public function v1_operate__department_item_enable($post_data)
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


        $operate = $post_data["operate"];
        if($operate != 'item-enable') return response_error([],"参数【operate】有误！");
        $id = $post_data["item_id"];
        if(intval($id) !== 0 && !$id) return response_error([],"参数【ID】有误！");

        $this->get_me();
        $me = $this->me;

        // 判断用户操作权限
        if(!in_array($me->user_type,[0,1,9,11])) return response_error([],"你没有操作权限！");

        // 判断对象是否合法
        $mine = WL_Common_Department::find($id);
        if(!$mine) return response_error([],"该【部门】不存在，刷新页面重试！");


        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $mine->item_status = 1;
            $mine->timestamps = false;
            $bool = $mine->save();
            if(!$bool) throw new Exception("WL_Common_Department--update--fail");

            DB::commit();
            return response_success([]);
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
    // 【部门】管理员-禁用
    public function v1_operate__department_item_disable($post_data)
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


        $operate = $post_data["operate"];
        if($operate != 'item-disable') return response_error([],"参数【operate】有误！");
        $id = $post_data["item_id"];
        if(intval($id) !== 0 && !$id) return response_error([],"参数【ID】有误！");

        $this->get_me();
        $me = $this->me;

        // 判断用户操作权限
        if(!in_array($me->user_type,[0,1,9,11])) return response_error([],"你没有操作权限！");

        // 判断对象是否合法
        $mine = WL_Common_Department::find($id);
        if(!$mine) return response_error([],"该【部门】不存在，刷新页面重试！");


        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $mine->item_status = 9;
            $mine->timestamps = false;
            $bool = $mine->save();
            if(!$bool) throw new Exception("WL_Common_Department--update--fail");

            DB::commit();
            return response_success([]);
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




    /*
     * 团队-管理 Department
     */
    // 【团队】返回-列表-数据
    public function v1_operate__team_datatable_list_query($post_data)
    {
        $this->get_me();
        $me = $this->me;


        $query = WL_Common_Team::select(['id','item_status','name','team_category','team_type','department_id','leader_id','remark','creator_id','created_at','updated_at','deleted_at'])
            ->withTrashed()
            ->with([
                'creator'=>function($query) { $query->select(['id','username','true_name']); },
                'leader'=>function($query) { $query->select(['id','username','true_name']); },
                'department_er'=>function($query) { $query->select(['id','name']); }
            ]);


        if(!empty($post_data['id'])) $query->where('id', $post_data['id']);
        if(!empty($post_data['name'])) $query->where('name', 'like', "%{$post_data['name']}%");
        if(!empty($post_data['title'])) $query->where('title', 'like', "%{$post_data['title']}%");
        if(!empty($post_data['remark'])) $query->where('remark', 'like', "%{$post_data['remark']}%");
        if(!empty($post_data['description'])) $query->where('description', 'like', "%{$post_data['description']}%");
        if(!empty($post_data['keyword'])) $query->where('content', 'like', "%{$post_data['keyword']}%");

        // 状态 [|]
        if(!empty($post_data['item_status']))
        {
            if(!in_array($post_data['item_status'],[-1,0,'-1','0']))
            {
                $query->where('item_status', $post_data['item_status']);
            }
        }
        else
        {
            $query->where('item_status', 1);
        }

        // 部门类型 [大区|组]
        if(!empty($post_data['team_type']))
        {
            if(!in_array($post_data['team_type'],[-1,0]))
            {
                $query->where('team_type', $post_data['team_type']);
            }
        }

        $total = $query->count();

        $draw  = isset($post_data['draw'])  ? $post_data['draw']  : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start']  : 0;
        $limit = isset($post_data['length']) ? $post_data['length'] : 10;

        if(isset($post_data['order']))
        {
            $columns = $post_data['columns'];
            $order = $post_data['order'][0];
            $order_column = $order['column'];
            $order_dir = $order['dir'];

            $field = $columns[$order_column]["data"];
            $query->orderBy($field, $order_dir);
        }
        else $query->orderBy("id", "asc");

        if($limit == -1) $list = $query->get();
        else $list = $query->skip($skip)->take($limit)->get();
//        dd($list->toArray());

        foreach($list as $k => $v)
        {
            if($v->department_type == 11)
            {
                $v->district_id = $v->id;
            }
            else if($v->department_type == 21)
            {
                $v->district_id = $v->superior_department_id;
            }

            $v->district_group_id = $v->district_id.'.'.$v->id;
        }
//        $list = $list->sortBy(['rank'=>'asc'])->values();
//        $list = $list->sortBy(function ($item, $key) {
//            return $item['district_group_id'];
//        })->values();
//        dd($list->toArray());

        return datatable_response($list, $draw, $total);
    }
    // 【团队】获取 GET
    public function v1_operate__team_item_get($post_data)
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
        $id = $post_data["item_id"];
        if(intval($id) !== 0 && !$id) return response_error([],"参数[ID]有误！");

        $item = WL_Common_Team::withTrashed()
            ->with([
                'leader'=>function($query) { $query->select('id','username'); },
                'department_er'=>function($query) { $query->select('id','name'); }
            ])
            ->find($id);
        if(!$item) return response_error([],"不存在警告，请刷新页面重试！");

        return response_success($item,"");
    }
    // 【团队】保存 SAVE
    public function v1_operate__team_item_save($post_data)
    {
        $messages = [
            'operate.required' => 'operate.required.',
            'name.required' => '请输入部门名称！',
//            'name.unique' => '该部门号已存在！',
        ];
        $v = Validator::make($post_data, [
            'operate' => 'required',
            'name' => 'required',
//            'name' => 'required|unique:WL_Common_Department,name',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }


        $operate = $post_data["operate"];
        $operate_type = $operate["type"];
        $operate_id = $operate['id'];

        $this->get_me();
        $me = $this->me;

        // 判断用户操作权限
        if(!in_array($me->staff_type,[0,1,11,19,41])) return response_error([],"你没有操作权限！");


        if($operate_type == 'create') // 添加 ( $id==0，添加一个新用户 )
        {
            $is_exist = WL_Common_Team::select('id')->where('name',$post_data["name"])->count();
            if($is_exist) return response_error([],"该【部门】已存在，请勿重复添加！");

            $mine = new WL_Common_Team;
            $post_data["active"] = 1;
            $post_data["creator_id"] = $me->id;
        }
        else if($operate_type == 'edit') // 编辑
        {
            $mine = WL_Common_Team::find($operate_id);
            if(!$mine) return response_error([],"该【部门】不存在，刷新页面重试！");
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

            if(in_array($me->staff_type,[41,61,71,81]))
            {
                $mine_data['superior_department_id'] = $me->department_district_id;
            }

            $bool = $mine->fill($mine_data)->save();
            if($bool)
            {
            }
            else throw new Exception("WL_Common_Team--insert--fail");

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

    // 【团队】管理员-启用
    public function v1_operate__team_item_enable($post_data)
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


        $operate = $post_data["operate"];
        if($operate != 'item-enable') return response_error([],"参数【operate】有误！");
        $id = $post_data["item_id"];
        if(intval($id) !== 0 && !$id) return response_error([],"参数【ID】有误！");

        $this->get_me();
        $me = $this->me;

        // 判断用户操作权限
        if(!in_array($me->user_type,[0,1,9,11])) return response_error([],"你没有操作权限！");

        // 判断对象是否合法
        $mine = WL_Common_Team::find($id);
        if(!$mine) return response_error([],"该【团队】不存在，刷新页面重试！");


        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $mine->item_status = 1;
            $mine->timestamps = false;
            $bool = $mine->save();
            if(!$bool) throw new Exception("WL_Common_Team--update--fail");

            DB::commit();
            return response_success([]);
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
    // 【团队】管理员-禁用
    public function v1_operate__team_item_disable($post_data)
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


        $operate = $post_data["operate"];
        if($operate != 'item-disable') return response_error([],"参数【operate】有误！");
        $id = $post_data["item_id"];
        if(intval($id) !== 0 && !$id) return response_error([],"参数【ID】有误！");

        $this->get_me();
        $me = $this->me;

        // 判断用户操作权限
        if(!in_array($me->user_type,[0,1,9,11])) return response_error([],"你没有操作权限！");

        // 判断对象是否合法
        $mine = WL_Common_Team::find($id);
        if(!$mine) return response_error([],"该【团队】不存在，刷新页面重试！");


        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $mine->item_status = 9;
            $mine->timestamps = false;
            $bool = $mine->save();
            if(!$bool) throw new Exception("WL_Common_Team--update--fail");

            DB::commit();
            return response_success([]);
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




    /*
     * 员工-管理 Staff
     */
    // 【员工】返回-列表-数据
    public function v1_operate__staff_datatable_list_query($post_data)
    {
        $this->get_me();
        $me = $this->me;

        $query = WL_Common_Staff::withTrashed()->select('*')
            ->with([
                'creator'=>function ($query) { $query->select('id','username'); },
                'department_er',
                'team_er',
                'sub_team_er',
                'group_er'
            ])
            ->whereIn('staff_category',[11,21,31,81]);


        if(!empty($post_data['id'])) $query->where('id', $post_data['id']);
        if(!empty($post_data['name'])) $query->where('name', 'like', "%{$post_data['name']}%");
        if(!empty($post_data['title'])) $query->where('title', 'like', "%{$post_data['title']}%");
        if(!empty($post_data['remark'])) $query->where('remark', 'like', "%{$post_data['remark']}%");
        if(!empty($post_data['description'])) $query->where('description', 'like', "%{$post_data['description']}%");
        if(!empty($post_data['keyword'])) $query->where('content', 'like', "%{$post_data['keyword']}%");

        // 状态 [|]
        if(!empty($post_data['item_status']))
        {
            if(!in_array($post_data['item_status'],[-1,0,'-1','0']))
            {
                $query->where('item_status', $post_data['item_status']);
            }
        }
        else
        {
//            $query->where('item_status', 1);
        }


        // 部门
        if(!empty($post_data['department_id']))
        {
            if(!in_array($post_data['department_id'],[-1,0]))
            {
                $query->where('department_id', $post_data['department_id']);
            }
        }


        // 员工类型
        if(!empty($post_data['staff_category']))
        {
            if(!in_array($post_data['staff_category'],[-1,0]))
            {
                $query->where('staff_category', $post_data['staff_category']);
            }
        }

        $total = $query->count();

        $draw  = isset($post_data['draw'])  ? $post_data['draw']  : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start']  : 0;
        $limit = isset($post_data['length']) ? $post_data['length'] : 50;

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

        if($limit == -1) $list = $query->get();
        else $list = $query->skip($skip)->take($limit)->get();

        foreach ($list as $k => $v)
        {
            $list[$k]->encode_id = encode($v->id);
        }
//        dd($total);
//        dd($list->toArray());
        return datatable_response($list, $draw, $total);
    }
    // 【员工】获取 GET
    public function v1_operate__staff_item_get($post_data)
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
        $id = $post_data["item_id"];
        if(intval($id) !== 0 && !$id) return response_error([],"参数[ID]有误！");

        $item = WL_Common_Staff::withTrashed()
            ->with([
                'department_er'=>function($query) { $query->select('id','name'); },
                'team_er'=>function($query) { $query->select('id','name'); },
                'group_er'=>function($query) { $query->select('id','name'); }
            ])
            ->find($id);
        if(!$item) return response_error([],"不存在警告，请刷新页面重试！");

        return response_success($item,"");
    }
    // 【员工】保存数据
    public function v1_operate__staff_item_save($post_data)
    {
//        dd($post_data);
        $messages = [
            'operate.required' => '参数有误！',
            'login_number.required' => '请输入电话！',
            'true_name.required' => '请输入用户名！',
//            'mobile.unique' => '电话已存在！',
//            'api_staffNo.required' => '请输入外呼系统坐席ID！',
//            'api_staffNo.numeric' => '坐席用户ID必须为数字！',
//            'api_staffNo.min' => '坐席用户ID必须为数字，并且不小于0！',
        ];
        $v = Validator::make($post_data, [
            'operate' => 'required',
            'login_number' => 'required',
            'true_name' => 'required',
//            'mobile' => 'required|unique:dk_user,mobile',
//            'api_staffNo' => 'required|numeric|min:0',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }


        $operate = $post_data["operate"];
        $operate_type = $operate["type"];
        $operate_id = $operate['id'];

        $this->get_me();
        $me = $this->me;

        // 判断用户操作权限
        if(!in_array($me->staff_type,[0,1,11,21,31,41,61,71,81])) return response_error([],"你没有操作权限！");


//        $post_data['api_staffNo']  = isset($post_data['api_staffNo'])  ? $post_data['api_staffNo'] : 0;

//        if($post_data['api_staffNo'] > 0)
//        {
//            $api_staffNo_is_exist = WL_Common_Staff::where('api_staffNo',$post_data['api_staffNo'])->where('id','!=',$operate_id)->first();
//            if($api_staffNo_is_exist) return response_error([],"坐席用户ID重复，请更换再试一次！");
//        }

        if($operate_type == 'create') // 添加 ( $id==0，添加一个新用户 )
        {
            $is_exist = WL_Common_Staff::where('login_number',$post_data['login_number'])->first();
            if($is_exist) return response_error([],"工号已存在！");

            $mine = new WL_Common_Staff;
            $post_data["user_status"] = 0;
            $post_data["user_category"] = 11;
            $post_data["active"] = 1;
            $post_data["password"] = password_encode("12345678");
            $post_data["creator_id"] = $me->id;
            $post_data['username'] = $post_data['true_name'];
        }
        else if($operate_type == 'edit') // 编辑
        {
            $mine = WL_Common_Staff::find($operate_id);
            if(!$mine) return response_error([],"该用户不存在，刷新页面重试！");
            if($mine->login_number != $post_data['login_number'])
            {
                $is_exist = WL_Common_Staff::where('login_number',$post_data['login_number'])->where('id','!=',$operate_id)->first();
                if($is_exist) return response_error([],"工号重复，请更换工号再试一次！");
            }
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


            $bool = $mine->fill($mine_data)->save();
            if($bool)
            {
                if($operate == 'create') // 添加 ( $id==0，添加一个新用户 )
                {
//                    $user_ext = new WL_Common_Staff_Ext;
//                    $user_ext_create['user_id'] = $mine->id;
//                    $bool_2 = $user_ext->fill($user_ext_create)->save();
//                    if(!$bool_2) throw new Exception("insert--user-ext--failed");
                }

                // 头像
                if(!empty($post_data["portrait"]))
                {
                    // 删除原图片
                    $mine_portrait_img = $mine->portrait_img;
                    if(!empty($mine_portrait_img) && file_exists(storage_resource_path($mine_portrait_img)))
                    {
                        unlink(storage_resource_path($mine_portrait_img));
                    }

//                    $result = upload_storage($post_data["portrait"]);
//                    $result = upload_storage($post_data["portrait"], null, null, 'assign');
                    $result = upload_img_storage($post_data["portrait"],'portrait_for_user_by_user_'.$mine->id,'dk/unique/portrait_for_user','');
                    if($result["result"])
                    {
                        $mine->portrait_img = $result["local"];
                        $mine->save();
                    }
                    else throw new Exception("upload--portrait_img--file--fail");
                }
                else
                {
                    if($operate == 'create')
                    {
                        $portrait_path = "wl/unique/portrait_for_user/".date('Y-m-d');
                        if (!is_dir(storage_resource_path($portrait_path)))
                        {
                            mkdir(storage_resource_path($portrait_path), 0777, true);
                        }
                        copy(storage_resource_path("materials/portrait/user0.jpeg"), storage_resource_path($portrait_path."/portrait_for_user_by_user_".$mine->id.".jpeg"));
                        $mine->portrait_img = $portrait_path."/portrait_for_user_by_user_".$mine->id.".jpeg";
                        $mine->save();
                    }
                }

            }
            else throw new Exception("WL_Common_Staff--insert--fail");

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

    // 【员工】管理员-启用
    public function v1_operate__staff_item_enable($post_data)
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


        $operate = $post_data["operate"];
        if($operate != 'item-enable') return response_error([],"参数【operate】有误！");
        $id = $post_data["item_id"];
        if(intval($id) !== 0 && !$id) return response_error([],"参数【ID】有误！");

        $this->get_me();
        $me = $this->me;

        // 判断用户操作权限
        if(!in_array($me->user_type,[0,1,9,11])) return response_error([],"你没有操作权限！");

        // 判断对象是否合法
        $mine = WL_Common_Staff::find($id);
        if(!$mine) return response_error([],"该【员工】不存在，刷新页面重试！");


        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $mine->user_status = 1;
            $mine->login_error_num = 0;
            $mine->timestamps = false;
            $bool = $mine->save();
            if(!$bool) throw new Exception("WL_Common_Staff--update--fail");

            DB::commit();
            return response_success([]);
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
    // 【员工】管理员-禁用
    public function v1_operate__staff_item_disable($post_data)
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


        $operate = $post_data["operate"];
        if($operate != 'item-disable') return response_error([],"参数【operate】有误！");
        $id = $post_data["item_id"];
        if(intval($id) !== 0 && !$id) return response_error([],"参数【ID】有误！");

        $this->get_me();
        $me = $this->me;

        // 判断用户操作权限
        if(!in_array($me->user_type,[0,1,9,11])) return response_error([],"你没有操作权限！");

        // 判断对象是否合法
        $mine = WL_Common_Staff::find($id);
        if(!$mine) return response_error([],"该【员工】不存在，刷新页面重试！");


        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $mine->user_status = 9;
            $mine->timestamps = false;
            $bool = $mine->save();
            if(!$bool) throw new Exception("WL_Common_Staff--update--fail");

            DB::commit();
            return response_success([]);
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




    
    
    
    
    /*
     * 车辆-管理 Car
     */
    // 【车辆】返回-列表-数据
    public function v1_operate__car_datatable_list_query($post_data)
    {
        $this->get_me();
        $me = $this->me;

        $query = WL_Common_Car::withTrashed()->select('*')
            ->with([
                'creator'=>function ($query) { $query->select('id','username'); },
                'driver_er'
            ]);

        if(!empty($post_data['id'])) $query->where('id', $post_data['id']);
        if(!empty($post_data['name'])) $query->where('name', 'like', "%{$post_data['name']}%");
        if(!empty($post_data['title'])) $query->where('title', 'like', "%{$post_data['title']}%");
        if(!empty($post_data['remark'])) $query->where('remark', 'like', "%{$post_data['remark']}%");
        if(!empty($post_data['description'])) $query->where('description', 'like', "%{$post_data['description']}%");
        if(!empty($post_data['keyword'])) $query->where('content', 'like', "%{$post_data['keyword']}%");

        // 状态 [|]
        if(!empty($post_data['item_status']))
        {
            if(!in_array($post_data['item_status'],[-1,0,'-1','0']))
            {
                $query->where('item_status', $post_data['item_status']);
            }
        }
        else
        {
//            $query->where('item_status', 1);
        }


        // 部门
        if(!empty($post_data['department_id']))
        {
            if(!in_array($post_data['department_id'],[-1,0]))
            {
                $query->where('department_id', $post_data['department_id']);
            }
        }


        // 员工类型
        if(!empty($post_data['staff_category']))
        {
            if(!in_array($post_data['staff_category'],[-1,0]))
            {
                $query->where('staff_category', $post_data['staff_category']);
            }
        }

        $total = $query->count();

        $draw  = isset($post_data['draw'])  ? $post_data['draw']  : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start']  : 0;
        $limit = isset($post_data['length']) ? $post_data['length'] : 50;

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

        if($limit == -1) $list = $query->get();
        else $list = $query->skip($skip)->take($limit)->get();

        foreach ($list as $k => $v)
        {
            $list[$k]->encode_id = encode($v->id);
        }
//        dd($total);
//        dd($list->toArray());
        return datatable_response($list, $draw, $total);
    }
    // 【车辆】获取 GET
    public function v1_operate__car_item_get($post_data)
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
        $id = $post_data["item_id"];
        if(intval($id) !== 0 && !$id) return response_error([],"参数[ID]有误！");

        $item = WL_Common_Car::withTrashed()
            ->with([
//                'department_er'=>function($query) { $query->select('id','name'); },
//                'team_er'=>function($query) { $query->select('id','name'); },
//                'group_er'=>function($query) { $query->select('id','name'); }
            ])
            ->find($id);
        if(!$item) return response_error([],"不存在警告，请刷新页面重试！");

        return response_success($item,"");
    }
    // 【车辆】保存数据
    public function v1_operate__car_item_save($post_data)
    {
//        dd($post_data);
        $messages = [
            'operate.required' => '参数有误！',
            'true_name.required' => '请输入用户名！',
            'mobile.required' => '请输入电话！',
//            'mobile.unique' => '电话已存在！',
//            'api_staffNo.required' => '请输入外呼系统坐席ID！',
//            'api_staffNo.numeric' => '坐席用户ID必须为数字！',
//            'api_staffNo.min' => '坐席用户ID必须为数字，并且不小于0！',
        ];
        $v = Validator::make($post_data, [
            'operate' => 'required',
            'true_name' => 'required',
            'mobile' => 'required',
//            'mobile' => 'required|unique:dk_user,mobile',
//            'api_staffNo' => 'required|numeric|min:0',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }


        $operate = $post_data["operate"];
        $operate_type = $operate["type"];
        $operate_id = $operate['id'];


        $post_data['api_staffNo']  = isset($post_data['api_staffNo'])  ? $post_data['api_staffNo'] : 0;

        $this->get_me();
        $me = $this->me;

        // 判断用户操作权限
        if(!in_array($me->staff_type,[0,1,11,21,31,41,61,71,81])) return response_error([],"你没有操作权限！");


        if($post_data['api_staffNo'] > 0)
        {
            $api_staffNo_is_exist = WL_Common_Car::where('api_staffNo',$post_data['api_staffNo'])->where('id','!=',$operate_id)->first();
            if($api_staffNo_is_exist) return response_error([],"坐席用户ID重复，请更换再试一次！");
        }

        if($operate_type == 'create') // 添加 ( $id==0，添加一个新用户 )
        {
            $is_exist = WL_Common_Car::where('mobile',$post_data['mobile'])->first();
            if($is_exist) return response_error([],"工号已存在！");

            $mine = new WL_Common_Staff;
            $post_data["user_status"] = 0;
            $post_data["user_category"] = 11;
            $post_data["active"] = 1;
            $post_data["password"] = password_encode("12345678");
            $post_data["creator_id"] = $me->id;
            $post_data['username'] = $post_data['true_name'];
        }
        else if($operate_type == 'edit') // 编辑
        {
            $mine = WL_Common_Car::find($operate_id);
            if(!$mine) return response_error([],"该用户不存在，刷新页面重试！");
            if($mine->mobile != $post_data['mobile'])
            {
                $is_exist = WL_Common_Staff::where('mobile',$post_data['mobile'])->first();
                if($is_exist) return response_error([],"工号重复，请更换工号再试一次！");
            }
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
            unset($mine_data['operate_id']);
            unset($mine_data['category']);
            unset($mine_data['type']);

            if(in_array($me->staff_type,[41,61,71,81]))
            {
                $mine_data['department_district_id'] = $me->department_district_id;
            }
//            if($me->staff_type == 81)
//            {
//                $mine_data['department_district_id'] = $me->department_district_id;
//            }

            if($post_data["user_type"] == 71 || $post_data["user_type"] == 77)
            {
//                $mine_data['department_district_id'] = $me->department_district_id;
//                unset($mine_data['department_district_id']);
                unset($mine_data['department_group_id']);
            }
            else if($post_data["user_type"] == 81)
            {
                unset($mine_data['department_group_id']);
            }


            $bool = $mine->fill($mine_data)->save();
            if($bool)
            {
                if($operate == 'create') // 添加 ( $id==0，添加一个新用户 )
                {
//                    $user_ext = new WL_Common_Staff_Ext;
//                    $user_ext_create['user_id'] = $mine->id;
//                    $bool_2 = $user_ext->fill($user_ext_create)->save();
//                    if(!$bool_2) throw new Exception("insert--user-ext--failed");
                }

                // 头像
                if(!empty($post_data["portrait"]))
                {
                    // 删除原图片
                    $mine_portrait_img = $mine->portrait_img;
                    if(!empty($mine_portrait_img) && file_exists(storage_resource_path($mine_portrait_img)))
                    {
                        unlink(storage_resource_path($mine_portrait_img));
                    }

//                    $result = upload_storage($post_data["portrait"]);
//                    $result = upload_storage($post_data["portrait"], null, null, 'assign');
                    $result = upload_img_storage($post_data["portrait"],'portrait_for_user_by_user_'.$mine->id,'dk/unique/portrait_for_user','');
                    if($result["result"])
                    {
                        $mine->portrait_img = $result["local"];
                        $mine->save();
                    }
                    else throw new Exception("upload--portrait_img--file--fail");
                }
                else
                {
                    if($operate == 'create')
                    {
                        $portrait_path = "dk/unique/portrait_for_user/".date('Y-m-d');
                        if (!is_dir(storage_resource_path($portrait_path)))
                        {
                            mkdir(storage_resource_path($portrait_path), 0777, true);
                        }
                        copy(storage_resource_path("materials/portrait/user0.jpeg"), storage_resource_path($portrait_path."/portrait_for_user_by_user_".$mine->id.".jpeg"));
                        $mine->portrait_img = $portrait_path."/portrait_for_user_by_user_".$mine->id.".jpeg";
                        $mine->save();
                    }
                }

            }
            else throw new Exception("insert--user--fail");

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

    // 【车辆】启用
    public function v1_operate__car_item_enable($post_data)
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


        $operate = $post_data["operate"];
        if($operate != 'item-enable') return response_error([],"参数【operate】有误！");
        $id = $post_data["item_id"];
        if(intval($id) !== 0 && !$id) return response_error([],"参数【ID】有误！");

        $this->get_me();
        $me = $this->me;

        // 判断用户操作权限
        if(!in_array($me->user_type,[0,1,9,11])) return response_error([],"你没有操作权限！");

        // 判断对象是否合法
        $mine = WL_Common_Car::find($id);
        if(!$mine) return response_error([],"该【车辆】不存在，刷新页面重试！");


        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $mine->item_status = 1;
            $mine->timestamps = false;
            $bool = $mine->save();
            if(!$bool) throw new Exception("WL_Common_Car--update--fail");

            DB::commit();
            return response_success([]);
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
    // 【车辆】禁用
    public function v1_operate__car_item_disable($post_data)
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


        $operate = $post_data["operate"];
        if($operate != 'item-disable') return response_error([],"参数【operate】有误！");
        $id = $post_data["item_id"];
        if(intval($id) !== 0 && !$id) return response_error([],"参数【ID】有误！");

        $this->get_me();
        $me = $this->me;

        // 判断用户操作权限
        if(!in_array($me->user_type,[0,1,9,11])) return response_error([],"你没有操作权限！");

        // 判断对象是否合法
        $mine = WL_Common_Car::find($id);
        if(!$mine) return response_error([],"该【车辆】不存在，刷新页面重试！");


        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $mine->item_status = 9;
            $mine->timestamps = false;
            $bool = $mine->save();
            if(!$bool) throw new Exception("WL_Common_Car--update--fail");

            DB::commit();
            return response_success([]);
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


    /*
     * 驾驶员-管理 Driver
     */
    // 【驾驶员】返回-列表-数据
    public function v1_operate__driver_datatable_list_query($post_data)
    {
        $this->get_me();
        $me = $this->me;

        $query = WL_Common_Driver::withTrashed()->select('*')
            ->with([
                'creator'=>function ($query) { $query->select('id','username'); }
            ]);
//            ->whereIn('staff_category',[11,21,31,81]);


        if(!empty($post_data['id'])) $query->where('id', $post_data['id']);
        if(!empty($post_data['name'])) $query->where('name', 'like', "%{$post_data['name']}%");
        if(!empty($post_data['title'])) $query->where('title', 'like', "%{$post_data['title']}%");
        if(!empty($post_data['remark'])) $query->where('remark', 'like', "%{$post_data['remark']}%");
        if(!empty($post_data['description'])) $query->where('description', 'like', "%{$post_data['description']}%");
        if(!empty($post_data['keyword'])) $query->where('content', 'like', "%{$post_data['keyword']}%");


        // 状态 [|]
        if(!empty($post_data['item_status']))
        {
            if(!in_array($post_data['item_status'],[-1,0,'-1','0']))
            {
                $query->where('item_status', $post_data['item_status']);
            }
        }
        else
        {
//            $query->where('item_status', 1);
        }


        $total = $query->count();

        $draw  = isset($post_data['draw'])  ? $post_data['draw']  : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start']  : 0;
        $limit = isset($post_data['length']) ? $post_data['length'] : 50;

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

        if($limit == -1) $list = $query->get();
        else $list = $query->skip($skip)->take($limit)->get();

        foreach ($list as $k => $v)
        {
            $list[$k]->encode_id = encode($v->id);
        }
//        dd($total);
//        dd($list->toArray());
        return datatable_response($list, $draw, $total);
    }
    // 【驾驶员】获取 GET
    public function v1_operate__driver_item_get($post_data)
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
        $id = $post_data["item_id"];
        if(intval($id) !== 0 && !$id) return response_error([],"参数[ID]有误！");

        $item = WL_Common_Driver::withTrashed()
            ->with([
//                'department_er'=>function($query) { $query->select('id','name'); },
//                'team_er'=>function($query) { $query->select('id','name'); },
//                'group_er'=>function($query) { $query->select('id','name'); }
            ])
            ->find($id);
        if(!$item) return response_error([],"不存在警告，请刷新页面重试！");

        return response_success($item,"");
    }
    // 【驾驶员】保存数据
    public function v1_operate__driver_item_save($post_data)
    {
        $messages = [
            'operate.required' => '参数有误！',
            'true_name.required' => '请输入用户名！',
            'mobile.required' => '请输入电话！',
//            'mobile.unique' => '电话已存在！',
//            'api_staffNo.required' => '请输入外呼系统坐席ID！',
//            'api_staffNo.numeric' => '坐席用户ID必须为数字！',
//            'api_staffNo.min' => '坐席用户ID必须为数字，并且不小于0！',
        ];
        $v = Validator::make($post_data, [
            'operate' => 'required',
            'true_name' => 'required',
            'mobile' => 'required',
//            'mobile' => 'required|unique:dk_user,mobile',
//            'api_staffNo' => 'required|numeric|min:0',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }


        $operate = $post_data["operate"];
        $operate_type = $operate["type"];
        $operate_id = $operate['id'];


        $this->get_me();
        $me = $this->me;

        // 判断用户操作权限
        if(!in_array($me->staff_type,[0,1,11])) return response_error([],"你没有操作权限！");


        if($post_data['api_staffNo'] > 0)
        {
            $api_staffNo_is_exist = WL_Common_Staff::where('api_staffNo',$post_data['api_staffNo'])->where('id','!=',$operate_id)->first();
            if($api_staffNo_is_exist) return response_error([],"坐席用户ID重复，请更换再试一次！");
        }

        if($operate_type == 'create') // 添加 ( $id==0，添加一个新用户 )
        {
            $is_exist = WL_Common_Driver::where('mobile',$post_data['mobile'])->first();
            if($is_exist) return response_error([],"工号已存在！");

            $mine = new WL_Common_Driver;
            $post_data["user_status"] = 0;
            $post_data["user_category"] = 11;
            $post_data["active"] = 1;
            $post_data["password"] = password_encode("12345678");
            $post_data["creator_id"] = $me->id;
            $post_data['username'] = $post_data['true_name'];
        }
        else if($operate_type == 'edit') // 编辑
        {
            $mine = WL_Common_Driver::find($operate_id);
            if(!$mine) return response_error([],"该用户不存在，刷新页面重试！");
            if($mine->mobile != $post_data['mobile'])
            {
                $is_exist = WL_Common_Staff::where('mobile',$post_data['mobile'])->first();
                if($is_exist) return response_error([],"工号重复，请更换工号再试一次！");
            }
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
            unset($mine_data['operate_id']);
            unset($mine_data['category']);
            unset($mine_data['type']);

            if(in_array($me->staff_type,[41,61,71,81]))
            {
                $mine_data['department_district_id'] = $me->department_district_id;
            }
//            if($me->staff_type == 81)
//            {
//                $mine_data['department_district_id'] = $me->department_district_id;
//            }

            if($post_data["user_type"] == 71 || $post_data["user_type"] == 77)
            {
//                $mine_data['department_district_id'] = $me->department_district_id;
//                unset($mine_data['department_district_id']);
                unset($mine_data['department_group_id']);
            }
            else if($post_data["user_type"] == 81)
            {
                unset($mine_data['department_group_id']);
            }


            $bool = $mine->fill($mine_data)->save();
            if($bool)
            {
                if($operate == 'create') // 添加 ( $id==0，添加一个新用户 )
                {
//                    $user_ext = new WL_Common_Staff_Ext;
//                    $user_ext_create['user_id'] = $mine->id;
//                    $bool_2 = $user_ext->fill($user_ext_create)->save();
//                    if(!$bool_2) throw new Exception("insert--user-ext--failed");
                }

                // 头像
                if(!empty($post_data["portrait"]))
                {
                    // 删除原图片
                    $mine_portrait_img = $mine->portrait_img;
                    if(!empty($mine_portrait_img) && file_exists(storage_resource_path($mine_portrait_img)))
                    {
                        unlink(storage_resource_path($mine_portrait_img));
                    }

//                    $result = upload_storage($post_data["portrait"]);
//                    $result = upload_storage($post_data["portrait"], null, null, 'assign');
                    $result = upload_img_storage($post_data["portrait"],'portrait_for_user_by_user_'.$mine->id,'dk/unique/portrait_for_user','');
                    if($result["result"])
                    {
                        $mine->portrait_img = $result["local"];
                        $mine->save();
                    }
                    else throw new Exception("upload--portrait_img--file--fail");
                }
                else
                {
                    if($operate == 'create')
                    {
                        $portrait_path = "dk/unique/portrait_for_user/".date('Y-m-d');
                        if (!is_dir(storage_resource_path($portrait_path)))
                        {
                            mkdir(storage_resource_path($portrait_path), 0777, true);
                        }
                        copy(storage_resource_path("materials/portrait/user0.jpeg"), storage_resource_path($portrait_path."/portrait_for_user_by_user_".$mine->id.".jpeg"));
                        $mine->portrait_img = $portrait_path."/portrait_for_user_by_user_".$mine->id.".jpeg";
                        $mine->save();
                    }
                }

            }
            else throw new Exception("WL_Common_Driver--insert--fail");

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

    // 【驾驶员】启用
    public function v1_operate__driver_item_enable($post_data)
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


        $operate = $post_data["operate"];
        if($operate != 'item-enable') return response_error([],"参数【operate】有误！");
        $id = $post_data["item_id"];
        if(intval($id) !== 0 && !$id) return response_error([],"参数【ID】有误！");

        $this->get_me();
        $me = $this->me;

        // 判断用户操作权限
        if(!in_array($me->user_type,[0,1,9,11])) return response_error([],"你没有操作权限！");

        // 判断对象是否合法
        $mine = WL_Common_Driver::find($id);
        if(!$mine) return response_error([],"该【驾驶员】不存在，刷新页面重试！");


        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $mine->item_status = 1;
            $mine->timestamps = false;
            $bool = $mine->save();
            if(!$bool) throw new Exception("WL_Common_Driver--update--fail");

            DB::commit();
            return response_success([]);
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
    // 【驾驶员】禁用
    public function v1_operate__driver_item_disable($post_data)
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


        $operate = $post_data["operate"];
        if($operate != 'item-disable') return response_error([],"参数【operate】有误！");
        $id = $post_data["item_id"];
        if(intval($id) !== 0 && !$id) return response_error([],"参数【ID】有误！");

        $this->get_me();
        $me = $this->me;

        // 判断用户操作权限
        if(!in_array($me->user_type,[0,1,9,11])) return response_error([],"你没有操作权限！");

        // 判断对象是否合法
        $mine = WL_Common_Driver::find($id);
        if(!$mine) return response_error([],"该【驾驶员】不存在，刷新页面重试！");


        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $mine->item_status = 9;
            $mine->timestamps = false;
            $bool = $mine->save();
            if(!$bool) throw new Exception("WL_Common_Driver--update--fail");

            DB::commit();
            return response_success([]);
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




    /*
     * 客户-管理 Client
     */
    // 【客户】返回-列表-数据
    public function v1_operate__client_datatable_list_query($post_data)
    {
        $this->get_me();
        $me = $this->me;

        $query = WL_Common_Client::select('*')
            ->with([
                'creator'=>function($query) { $query->select(['id','username']); }
            ])
            ->whereIn('client_category',[1,11,31])
            ->whereIn('client_type',[0,1,9,11,19,21,22,41,61]);

        if(!empty($post_data['id'])) $query->where('id', $post_data['id']);
        if(!empty($post_data['name'])) $query->where('name', 'like', "%{$post_data['name']}%");
        if(!empty($post_data['title'])) $query->where('title', 'like', "%{$post_data['title']}%");
        if(!empty($post_data['remark'])) $query->where('remark', 'like', "%{$post_data['remark']}%");
        if(!empty($post_data['description'])) $query->where('description', 'like', "%{$post_data['description']}%");
        if(!empty($post_data['keyword'])) $query->where('content', 'like', "%{$post_data['keyword']}%");


        // 状态 [|]
        if(!empty($post_data['item_status']))
        {
            if(!in_array($post_data['item_status'],[-1,0,'-1','0']))
            {
                $query->where('item_status', $post_data['item_status']);
            }
        }
        else
        {
            $query->where('item_status', 1);
        }




        $total = $query->count();

        $draw  = isset($post_data['draw'])  ? $post_data['draw']  : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start']  : 0;
        $limit = isset($post_data['length']) ? $post_data['length'] : 50;

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

        if($limit == -1) $list = $query->get();
        else $list = $query->skip($skip)->take($limit)->withTrashed()->get();

        foreach ($list as $k => $v)
        {
            $list[$k]->encode_id = encode($v->id);
        }
//        dd($list->toArray());
        return datatable_response($list, $draw, $total);
    }
    // 【客户】获取 GET
    public function v1_operate__client_item_get($post_data)
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
        $id = $post_data["item_id"];
        if(intval($id) !== 0 && !$id) return response_error([],"参数[ID]有误！");

        $item = WL_Common_Client::withTrashed()
            ->with([
//                'company_er'=>function($query) { $query->select('id','name'); },
//                'channel_er'=>function($query) { $query->select('id','name'); },
//                'business_er'=>function($query) { $query->select('id','name'); }
            ])
            ->find($id);
        if(!$item) return response_error([],"不存在警告，请刷新页面重试！");

        return response_success($item,"");
    }
    // 【客户】保存 SAVE
    public function v1_operate__client_item_save($post_data)
    {
        $messages = [
            'operate.required' => 'operate.required.',
            'client_category.required' => '请选择项目种类！',
            'name.required' => '请输入项目名称！',
//            'name.unique' => '该项目已存在！',
        ];
        $v = Validator::make($post_data, [
            'operate' => 'required',
            'client_category' => 'required',
            'name' => 'required',
//            'name' => 'required|unique:dk_project,name',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }


        $operate = $post_data["operate"];
        $operate_type = $operate["type"];
        $operate_id = $operate['id'];


        $this->get_me();
        $me = $this->me;

        // 判断用户操作权限
        if(!in_array($me->staff_type,[0,1,11])) return response_error([],"你没有操作权限！");

        if($operate_type == 'create')
        {
            // 添加 ( $id==0，添加一个项目 )
            $is_exist = WL_Common_Client::select('id')->where('name',$post_data["name"])->count();
            if($is_exist) return response_error([],"该【客户名称】已存在，请勿重复添加！");

            $mine = new WL_Common_Client;
            $post_data["active"] = 1;
            $post_data["creator_id"] = $me->id;
        }
        else if($operate_type == 'edit')
        {
            // 编辑
            $mine = WL_Common_Client::find($operate_id);
            if(!$mine) return response_error([],"该【客户】不存在，刷新页面重试！");
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


            $bool = $mine->fill($mine_data)->save();
            if($bool)
            {
            }
            else throw new Exception("WL_Common_Client--insert--fail");

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
    // 【客户】保存 SAVE
    public function v1_operate__client_item_save_1($post_data)
    {
//        dd($post_data);
        $messages = [
            'operate.required' => 'operate.required.',
            'client_category.required' => '请选择客户类型！',
            'name.required' => '请输入客户名称！',
//            'name.unique' => '该客户已存在！',
            'client_admin_name.required' => '请输入管理员名称！',
            'client_admin_mobile.required' => '请输入管理员登录电话！',
        ];
        $v = Validator::make($post_data, [
            'operate' => 'required',
            'client_category' => 'required',
            'name' => 'required',
//            'name' => 'required|unique:dk_client,username',
            'client_admin_name' => 'required',
            'client_admin_mobile' => 'required',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }


        $operate = $post_data["operate"];
        $operate_type = $operate["type"];
        $operate_id = $operate['id'];


        $this->get_me();
        $me = $this->me;

        // 判断用户操作权限
        if(!in_array($me->staff_type,[0,1,11,19,61])) return response_error([],"你没有操作权限！");

        $channel_id = $post_data["channel_id"];
        if($channel_id > 0)
        {
            $channel = DK_Company::find($channel_id);
            if($channel)
            {
                $client_data["company_id"] = $channel->superior_company_id;
                $client_data["channel_id"] = $channel_id;

                $business_id = $post_data["business_id"];
                if($business_id > 0)
                {
                    $business = DK_Company::find($business_id);
                    if($business)
                    {
                        if($business->superior_company_id == $channel_id)
                        {
                            $client_data["business_id"] = $business_id;
                        }
                        else return response_error([],"选择的【商务】不属于选择的【渠道】，请重新选择！");
                    }
                    else return response_error([],"选择的【商务】不存在，刷新页面重试！");
                }
                else
                {
                    $client_data["business_id"] = 0;
                }
            }
            else return response_error([],"选择的【渠道】不存在，刷新页面重试！");
        }


        if($operate_type == 'create') // 添加 ( $id==0，添加一个新用户 )
        {
            $is_name_exist = WL_Common_Client::select('id')->where('name',$post_data["name"])->count();
            if($is_name_exist) return response_error([],"该【客户名】已存在，请勿重复添加！");

//            $is_mobile_exist = WL_Common_Client::select('id')->where('mobile',$post_data["client_admin_mobile"])->count();
//            if($is_mobile_exist) return response_error([],"该电话已存在，请勿重复添加！");

//            $is_mobile_exist = WL_Client_Staff::select('id')->where('mobile',$post_data["client_admin_mobile"])->count();
//            if($is_mobile_exist) return response_error([],"该电话已存在，请勿重复添加！");

            $client = new WL_Common_Client;
            $client_data["user_category"] = $post_data["user_category"];
            $client_staff_data["user_type"] = 1;
            $client_data["active"] = 1;
            $client_data["creator_id"] = $me->id;
            $client_data["name"] = $post_data["name"];
            $client_data["mobile"] = $post_data["client_admin_mobile"];
            $client_data["client_admin_name"] = $post_data["client_admin_name"];
            $client_data["client_admin_mobile"] = $post_data["client_admin_mobile"];
            $client_data["is_ip"] = $post_data["is_ip"];
            $client_data["ip_whitelist"] = $post_data["ip_whitelist"];
            $client_data["password"] = password_encode("12345678");

//            $client_staff = new WL_Client_Staff;
//            $client_staff_data["user_category"] = 11;
//            $client_staff_data["user_type"] = 11;
//            $client_staff_data["active"] = 1;
//            $client_staff_data["name"] = $post_data["client_admin_name"];
//            $client_staff_data["mobile"] = $post_data["client_admin_mobile"];
//            $client_staff_data["creator_id"] = 0;
//            $client_staff_data["password"] = password_encode("12345678");
        }
        else if($operate_type == 'edit') // 编辑
        {
            // 该客户是否存在
            $client = WL_Common_Client::find($operate_id);
            if(!$client) return response_error([],"该客户不存在，刷新页面重试！");

            $client_data["name"] = $post_data["name"];
            $client_data["mobile"] = $post_data["client_admin_mobile"];
            $client_data["client_admin_name"] = $post_data["client_admin_name"];
            $client_data["client_admin_mobile"] = $post_data["client_admin_mobile"];
            $client_data["is_ip"] = $post_data["is_ip"];
            $client_data["ip_whitelist"] = $post_data["ip_whitelist"];

            // 客户名是否存在
            $is_name_exist = WL_Common_Client::select('id')->where('id','<>',$operate_id)->where('name',$post_data["name"])->count();
            if($is_name_exist) return response_error([],"该客户名已存在，不能修改成此客户名！");

            // 客户管理员是否存在
            $client_staff = WL_Client_Staff::where('id', $client->client_admin_id)->first();
            if($client_staff)
            {
                // 客户管理员存在

                // 判断电话是否重复
                if($post_data["client_admin_mobile"] != $client_staff->mobile)
                {
                    $is_mobile_exist = WL_Client_Staff::select('id')->where('id','<>',$client->client_admin_id)->where('mobile',$post_data["client_admin_mobile"])->count();
                    if($is_mobile_exist) return response_error([],"该电话已存在，不能修改成此电话！");

                    $client_staff_data["mobile"] = $post_data["client_admin_mobile"];
                }
                $client_staff_data["name"] = $post_data["client_admin_name"];
            }
            else
            {
                // 客户管理员不存在

//                $client_staff = new WL_Client_Staff;
//                $client_staff_data["user_category"] = 11;
//                $client_staff_data["user_type"] = 11;
//                $client_staff_data["active"] = 1;
//                $client_staff_data["client_id"] = $client->id;
//                $client_staff_data["name"] = $post_data["client_admin_name"];
//                $client_staff_data["mobile"] = $post_data["client_admin_mobile"];
//                $client_staff_data["creator_id"] = 0;
//                $client_staff_data["password"] = password_encode("12345678");
            }

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


            $bool = $client->fill($client_data)->save();
            if($bool)
            {
                if($operate_type == 'create')
                {
                    $client_staff_data["client_id"] = $client->id;
                }

                $bool_1 = $client_staff->fill($client_staff_data)->save();
                if($bool_1)
                {
                    if($operate_type == 'create')
                    {
                        $client->client_admin_id = $client_staff->id;
                        $bool = $client->save();
                        if(!$bool) throw new Exception("update--client--fail");
                    }
                }
                else throw new Exception("insert--client-staff--fail");
            }
            else throw new Exception("insert--client--fail");

            DB::commit();
            return response_success(['id'=>$client->id]);
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

    // 【客户】启用
    public function v1_operate__client_item_enable($post_data)
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


        $operate = $post_data["operate"];
        if($operate != 'item-enable') return response_error([],"参数【operate】有误！");
        $id = $post_data["item_id"];
        if(intval($id) !== 0 && !$id) return response_error([],"参数【ID】有误！");

        $this->get_me();
        $me = $this->me;

        // 判断用户操作权限
        if(!in_array($me->user_type,[0,1,9,11])) return response_error([],"你没有操作权限！");

        // 判断对象是否合法
        $mine = WL_Common_Client::find($id);
        if(!$mine) return response_error([],"该【客户】不存在，刷新页面重试！");
        if($mine->client_id != $me->client_id) return response_error([],"归属错误，刷新页面重试！");


        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $mine->item_status = 1;
            $mine->timestamps = false;
            $bool = $mine->save();
            if(!$bool) throw new Exception("WL_Common_Client--update--fail");

            DB::commit();
            return response_success([]);
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
    // 【客户】禁用
    public function v1_operate__client_item_disable($post_data)
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


        $operate = $post_data["operate"];
        if($operate != 'item-disable') return response_error([],"参数【operate】有误！");
        $id = $post_data["item_id"];
        if(intval($id) !== 0 && !$id) return response_error([],"参数【ID】有误！");

        $this->get_me();
        $me = $this->me;

        // 判断用户操作权限
        if(!in_array($me->user_type,[0,1,9,11])) return response_error([],"你没有操作权限！");

        // 判断对象是否合法
        $mine = WL_Common_Client::find($id);
        if(!$mine) return response_error([],"该【客户】不存在，刷新页面重试！");
        if($mine->client_id != $me->client_id) return response_error([],"归属错误，刷新页面重试！");


        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $mine->item_status = 9;
            $mine->timestamps = false;
            $bool = $mine->save();
            if(!$bool) throw new Exception("WL_Common_Client--update--fail");

            DB::commit();
            return response_success([]);
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




    /*
     * 项目-管理 Project
     */
    // 【项目】返回-列表-数据
    public function v1_operate__project_datatable_list_query($post_data)
    {
        $this->get_me();
        $me = $this->me;


        $query = WL_Common_Project::select('*')
            ->withTrashed()
            ->with([
                'creator'=>function($query) { $query->select(['id','username']); },
                'client_er'=>function($query) { $query->select(['id','name']); }
            ]);

        if(!empty($post_data['id'])) $query->where('id', $post_data['id']);
        if(!empty($post_data['name'])) $query->where('name', 'like', "%{$post_data['name']}%");
        if(!empty($post_data['title'])) $query->where('title', 'like', "%{$post_data['title']}%");
        if(!empty($post_data['remark'])) $query->where('remark', 'like', "%{$post_data['remark']}%");
        if(!empty($post_data['description'])) $query->where('description', 'like', "%{$post_data['description']}%");
        if(!empty($post_data['keyword'])) $query->where('content', 'like', "%{$post_data['keyword']}%");

        // 状态 [|]
        if(!empty($post_data['item_status']))
        {
            if(!in_array($post_data['item_status'],[-1,0,'-1','0']))
            {
                $query->where('item_status', $post_data['item_status']);
            }
        }
        else
        {
            $query->where('item_status', 1);
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
    // 【项目】获取 GET
    public function v1_operate__project_item_get($post_data)
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

        // 判断用户操作权限
        if(!in_array($me->staff_type,[0,1,9,11,61])) return response_error([],"你没有操作权限！");

        $operate = $post_data["operate"];
        if($operate != 'item-get') return response_error([],"参数[operate]有误！");
        $id = $post_data["item_id"];
        if(intval($id) !== 0 && !$id) return response_error([],"参数[ID]有误！");

        $item = WL_Common_Project::withTrashed()
            ->with([
                'client_er'=>function($query) { $query->select(['id','name']); },
                'inspector_er'=>function($query) { $query->select(['id','username']); },
                'pivot_project_user',
                'pivot_project_team'
            ])
            ->find($id);
        if(!$item) return response_error([],"不存在警告，请刷新页面重试！");

        return response_success($item,"");
    }
    // 【项目】保存 SAVE
    public function v1_operate__project_item_save($post_data)
    {
        $messages = [
            'operate.required' => 'operate.required.',
            'project_category.required' => '请选择项目种类！',
            'name.required' => '请输入项目名称！',
//            'name.unique' => '该项目已存在！',
        ];
        $v = Validator::make($post_data, [
            'operate' => 'required',
            'project_category' => 'required',
            'name' => 'required',
//            'name' => 'required|unique:dk_project,name',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }


        $operate = $post_data["operate"];
        $operate_type = $operate["type"];
        $operate_id = $operate['id'];


        $this->get_me();
        $me = $this->me;

        // 判断用户操作权限
        if(!in_array($me->staff_type,[0,1,11])) return response_error([],"你没有操作权限！");

        if($operate_type == 'create')
        {
            // 添加 ( $id==0，添加一个项目 )
            $is_exist = WL_Common_Project::select('id')->where('name',$post_data["name"])->count();
            if($is_exist) return response_error([],"该【项目名称】已存在，请勿重复添加！");

            $mine = new WL_Common_Project;
            $post_data["active"] = 1;
            $post_data["creator_id"] = $me->id;
        }
        else if($operate_type == 'edit')
        {
            // 编辑
            $mine = WL_Common_Project::find($operate_id);
            if(!$mine) return response_error([],"该【项目】不存在，刷新页面重试！");
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


            $bool = $mine->fill($mine_data)->save();
            if($bool)
            {
            }
            else throw new Exception("WL_Common_Project--insert--fail");

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

    // 【项目】启用
    public function v1_operate__project_item_enable($post_data)
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


        $operate = $post_data["operate"];
        if($operate != 'item-enable') return response_error([],"参数【operate】有误！");
        $id = $post_data["item_id"];
        if(intval($id) !== 0 && !$id) return response_error([],"参数【ID】有误！");

        $this->get_me();
        $me = $this->me;

        // 判断用户操作权限
        if(!in_array($me->user_type,[0,1,9,11])) return response_error([],"你没有操作权限！");

        // 判断对象是否合法
        $mine = WL_Common_Project::find($id);
        if(!$mine) return response_error([],"该【项目】不存在，刷新页面重试！");
        if($mine->client_id != $me->client_id) return response_error([],"归属错误，刷新页面重试！");


        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $mine->item_status = 1;
            $mine->timestamps = false;
            $bool = $mine->save();
            if(!$bool) throw new Exception("WL_Common_Project--update--fail");

            DB::commit();
            return response_success([]);
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
    // 【项目】禁用
    public function v1_operate__project_item_disable($post_data)
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


        $operate = $post_data["operate"];
        if($operate != 'item-disable') return response_error([],"参数【operate】有误！");
        $id = $post_data["item_id"];
        if(intval($id) !== 0 && !$id) return response_error([],"参数【ID】有误！");

        $this->get_me();
        $me = $this->me;

        // 判断用户操作权限
        if(!in_array($me->user_type,[0,1,9,11])) return response_error([],"你没有操作权限！");

        // 判断对象是否合法
        $mine = WL_Common_Project::find($id);
        if(!$mine) return response_error([],"该【项目】不存在，刷新页面重试！");
        if($mine->client_id != $me->client_id) return response_error([],"归属错误，刷新页面重试！");


        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $mine->item_status = 9;
            $mine->timestamps = false;
            $bool = $mine->save();
            if(!$bool) throw new Exception("WL_Common_Project--update--fail");

            DB::commit();
            return response_success([]);
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








    /*
     * 工单-管理 Order
     */
    // 【工单】返回-列表-数据
    public function v1_operate__order_datatable_list_query($post_data)
    {
        $this->get_me();
        $me = $this->me;

        $query = WL_Common_Order::select('*')
            ->with([
                'creator',
                'owner'=>function($query) { $query->select('id','username'); },
                'client_er'=>function($query) { $query->select('id','name'); },
                'project_er'=>function($query) { $query->select('id','name'); },
                'car_er'=>function($query) { $query->select('id','name'); },
                'trailer_er'=>function($query) { $query->select('id','name'); },
                'driver_er'=>function($query) { $query->select('id','driver_name','driver_phone'); },
                'copilot_er'=>function($query) { $query->select('id','driver_name','driver_phone'); }
            ]);
//            ->whereHas('fund', function ($query1) { $query1->where('totalfunds', '>=', 1000); } )
//            ->withCount([
//                'members'=>function ($query) { $query->where('usergroup','Agent2'); },
//                'fans'=>function ($query) { $query->rderwhere('usergroup','Service'); }
//            ]);

//        $me->load(['subordinate_er' => function ($query) {
//            $query->select('id');
//        }]);


        // 部门经理
        if($me->staff_type == 41)
        {
//            $subordinates = WL_Common_Staff::select('id')->where('superior_id',$me->id)->get()->pluck('id')->toArray();
//            $subordinates_subordinates = WL_Common_Staff::select('id')->whereIn('superior_id',$subordinates)->get()->pluck('id')->toArray();
//            $subordinates_list = array_merge($subordinates_subordinates,$subordinates);
//            $subordinates_list[] = $me->id;
//            $query->whereIn('creator_id',$subordinates_list);

//            $district_staff_list = WL_Common_Staff::select('id')->where('department_district_id',$me->department_district_id)->get()->pluck('id')->toArray();
//            $query->whereIn('creator_id',$district_staff_list);

            $query->where('department_district_id',$me->department_district_id);
        }
        // 客服经理
        if($me->staff_type == 81)
        {
//            $subordinates = WL_Common_Staff::select('id')->where('superior_id',$me->id)->get()->pluck('id')->toArray();
//            $subordinates_subordinates = WL_Common_Staff::select('id')->whereIn('superior_id',$subordinates)->get()->pluck('id')->toArray();
//            $subordinates_list = array_merge($subordinates_subordinates,$subordinates);
//            $subordinates_list[] = $me->id;
//            $query->whereIn('creator_id',$subordinates_list);

//            $district_staff_list = WL_Common_Staff::select('id')->where('department_district_id',$me->department_district_id)->get()->pluck('id')->toArray();
//            $query->whereIn('creator_id',$district_staff_list);

            $query->where('department_district_id',$me->department_district_id);
        }
        // 客服主管
        if($me->staff_type == 84)
        {
//            $subordinates = WL_Common_Staff::select('id')->where('superior_id',$me->id)->get()->pluck('id')->toArray();
//            $subordinates[] = $me->id;
//            $query->whereIn('creator_id',$subordinates);

//            $group_staff_list = WL_Common_Staff::select('id')->where('department_group_id',$me->department_group_id)->get()->pluck('id')->toArray();
//            $query->whereIn('creator_id',$group_staff_list);

            $query->where('department_group_id',$me->department_group_id);
        }
        // 客服
        if($me->staff_type == 88)
        {
            $query->where('creator_id', $me->id);
        }

        if(!empty($post_data['id'])) $query->where('id', $post_data['id']);
        if(!empty($post_data['title'])) $query->where('title', 'like', "%{$post_data['title']}%");
        if(!empty($post_data['description'])) $query->where('description', 'like', "%{$post_data['description']}%");
        if(!empty($post_data['keyword'])) $query->where('content', 'like', "%{$post_data['keyword']}%");
        if(!empty($post_data['remark'])) $query->where('remark', 'like', "%{$post_data['remark']}%");
        if(!empty($post_data['tag'])) $query->where('tag', 'like', "%{$post_data['tag']}%");

        if(!empty($post_data['assign'])) $query->where('assign_date', $post_data['assign']);
        if(!empty($post_data['assign_start'])) $query->where('assign_date', '>=', $post_data['assign_start']);
        if(!empty($post_data['assign_ended'])) $query->where('assign_date', '<=', $post_data['assign_ended']);






        // 部门（单选）
        if(!empty($post_data['department']))
        {
            if(!in_array($post_data['department'],[-1,0,'-1','0']))
            {
                $query->where('department_id', $post_data['department']);
            }
        }
        // 部门（多选）
//        if(!empty($post_data['department']))
//        {
//            if(count($post_data['department']))
//            {
//                $query->whereIn('department_id', $post_data['department']);
//            }
//        }

        // 员工
        if(!empty($post_data['staff']))
        {
            if(!in_array($post_data['staff'],[-1,0,'-1','0']))
            {
                $query->where('creator_id', $post_data['staff']);
            }
        }


        // 客户
        if(isset($post_data['client']))
        {
            if(!in_array($post_data['client'],[-1,0,'-1','0']))
            {
                $query->where('client_id', $post_data['client']);
            }
        }

        // 项目
        if(isset($post_data['project']))
        {
            if(!in_array($post_data['project'],[-1,0,'-1','0']))
            {
                $query->where('project_id', $post_data['project']);
            }
        }


        // 工单种类 []
        if(isset($post_data['item_category']))
        {
            if(!in_array($post_data['item_category'],[-1,0,'-1','0']))
            {
                $query->where('item_category', $post_data['item_category']);
            }
        }

        // 工单类型 []
        if(isset($post_data['item_type']))
        {
            if(!in_array($post_data['item_type'],[-1,'-1']))
            {
                $query->where('item_type', $post_data['item_type']);
            }
        }


        // 创建方式 [人工|导入|api]
        if(isset($post_data['created_type']))
        {
            if(!in_array($post_data['created_type'],[-1,'-1']))
            {
                $query->where('created_type', $post_data['created_type']);
            }
        }



        // 是否+V
        if(!empty($post_data['is_wx']))
        {
            if(!in_array($post_data['is_wx'],[-1]))
            {
                $query->where('is_wx', $post_data['is_wx']);
            }
        }

        // 审核状态
        if(!empty($post_data['inspected_status']))
        {
            $inspected_status = $post_data['inspected_status'];
            if(in_array($inspected_status,['待发布','待审核','已审核']))
            {
                if($inspected_status == '待发布')
                {
                    $query->where('is_published', 0);
                }
                else if($inspected_status == '待审核')
                {
                    $query->where('is_published', 1)->whereIn('inspected_status', [0,9]);
                }
                else if($inspected_status == '已审核')
                {
                    $query->where('inspected_status', 1);
                }
            }
        }
        // 审核结果
        if(!empty($post_data['inspected_result']))
        {
//            $inspected_result = $post_data['inspected_result'];
//            if(in_array($inspected_result,config('info.inspected_result')))
//            {
//                $query->where('inspected_result', $inspected_result);
//            }
            if(count($post_data['inspected_result']))
            {
                $query->whereIn('inspected_result', $post_data['inspected_result']);
            }
        }

        // 交付状态
        if(!empty($post_data['delivered_status']))
        {
            $delivered_status = $post_data['delivered_status'];
            if(in_array($delivered_status,['待交付','已交付','已操作','已处理']))
            {
                if($delivered_status == '待交付')
                {
                    $query->where('delivered_status', 0);
                }
                else if($delivered_status == '已交付')
                {
                    $query->where('delivered_status', 1);
                }
                else if($delivered_status == '已操作')
                {
                    $query->where('delivered_status', 1);
                }
                else if($delivered_status == '已处理')
                {
                    $query->where('delivered_status', 1);
                }
            }
        }
        // 交付结果
        if(!empty($post_data['delivered_result']))
        {
            if(count($post_data['delivered_result']))
            {
                $query->whereIn('delivered_result', $post_data['delivered_result']);
            }
        }





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
//            $list[$k]->encode_id = encode($v->id);

            if($v->creator_id == $me->id)
            {
                $list[$k]->is_me = 1;
                $v->is_me = 1;
            }
            else
            {
                $list[$k]->is_me = 0;
                $v->is_me = 0;
            }

        }
//        dd($list->toArray());


        if($me->id > 10000)
        {
            $record["creator_id"] = $me->id;
            $record["record_category"] = 1; // record_category=1 browse/share
            $record["record_type"] = 1; // record_type=1 browse
            $record["page_type"] = 1; // page_type=1 default platform
            $record["page_module"] = 2; // page_module=2 other
            $record["page_num"] = ($skip / $limit) + 1;
            $record["open"] = "order-list";
            $record["from"] = request('from',NULL);
            $this->record_for_user_visit($record);
        }


        return datatable_response($list, $draw, $total);
    }
    // 【工单】获取 GET
    public function v1_operate__order_item_get($post_data)
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

        // 判断用户操作权限
        if(!in_array($me->staff_type,[0,1,9,11,41,61,66,71,77,81,84,88])) return response_error([],"你没有操作权限！");

        $operate = $post_data["operate"];
        if($operate != 'item-get') return response_error([],"参数[operate]有误！");
        $id = $post_data["item_id"];
        if(intval($id) !== 0 && !$id) return response_error([],"参数[ID]有误！");

        $item = WL_Common_Order::withTrashed()
            ->with([
                'owner'=>function($query) { $query->select('id','username'); },
                'client_er'=>function($query) { $query->select('id','name'); },
                'project_er'=>function($query) { $query->select('id','name'); },
                'car_er'=>function($query) { $query->select('id','name'); },
                'trailer_er'=>function($query) { $query->select('id','name'); },
                'driver_er'=>function($query) { $query->select('id','driver_name','driver_phone'); },
                'copilot_er'=>function($query) { $query->select('id','driver_name','driver_phone'); },
            ])
            ->find($id);
        if(!$item) return response_error([],"不存在警告，请刷新页面重试！");

        return response_success($item,"");
    }
    // 【工单】保存 SAVE
    public function v1_operate__order_item_save($post_data)
    {
//        dd($post_data);
        $messages = [
            'operate.required' => 'operate.required.',
            'project_id.required' => '请选择项目！',
            'project_id.numeric' => '选择项目参数有误！',
            'project_id.min' => '请选择项目！',
//            'location_city.required' => '请选择城市！',
//            'location_district.required' => '请选择行政区！',
            'transport_departure_place.required' => '请输入出发地！',
            'transport_destination_place.required' => '请输入目的地！',
//            'description.required' => '请输入备注！',
        ];
        $v = Validator::make($post_data, [
            'operate' => 'required',
            'project_id' => 'required|numeric|min:1',
//            'location_city' => 'required',
//            'location_district' => 'required',
            'transport_departure_place' => 'required',
            'transport_destination_place' => 'required',
//            'description' => 'required',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }


        $operate = $post_data["operate"];
        $operate_type = $operate["type"];
        $operate_id = $operate['id'];



        $this->get_me();
        $me = $this->me;

        // 判断用户操作权限
        if(!in_array($me->staff_type,[0,1,9,11,81,84,88])) return response_error([],"你没有操作权限！");

        $me->load(['department_er','team_er']);


        if($operate_type == 'create') // 添加 ( $id==0，添加一个新用户 )
        {
            $mine = new WL_Common_Order;
            $post_data["item_category"] = 1;
            $post_data["active"] = 1;
            $post_data["creator_id"] = $me->id;
        }
        else if($operate_type == 'edit') // 编辑
        {
            $mine = WL_Common_Order::find($operate_id);
            if(!$mine) return response_error([],"该工单不存在，刷新页面重试！");

            if(in_array($me->staff_type,[84,88]) && $mine->creator_id != $me->id) return response_error([],"该【工单】不是你的，你不能操作！");

        }
        else return response_error([],"参数有误！");

//        $post_data['is_repeat'] = $is_repeat;

        // 判断客户是否存在
        if(!empty($post_data['client_id']))
        {
            $client = WL_Common_Client::find($post_data['client_id']);
            if(!$client) return response_error([],"选择【客户】不存在，刷新页面重试！");
        }
        // 判断项目是否存在
        if(!empty($post_data['project_id']))
        {
            $project = WL_Common_Project::find($post_data['project_id']);
            if(!$project) return response_error([],"选择【项目】不存在，刷新页面重试！");
        }



        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            if(!empty($post_data['custom']))
            {
                $post_data['custom'] = json_encode($post_data['custom']);
            }

            // 指派日期
            if(!empty($post_data['assign_date']))
            {
                $post_data['assign_time'] = strtotime($post_data['assign_date']);
            }
//            else $post_data['assign_time'] = 0;



            $mine_data = $post_data;
            $mine_data['department_district_id'] = $me->department_district_id;
            $mine_data['department_group_id'] = $me->department_group_id;
            if($me->department_district_er) $mine_data['department_manager_id'] = $me->department_district_er->leader_id;
            if($me->department_group_er) $mine_data['department_supervisor_id'] = $me->department_group_er->leader_id;

            if(!empty($custom_location_city) && !empty($custom_location_district))
            {
                $mine_data['location_city'] = $custom_location_city;
                $mine_data['location_district'] = $custom_location_district;
            }

            unset($mine_data['operate']);
            unset($mine_data['operate_id']);
            unset($mine_data['operate_category']);
            unset($mine_data['operate_type']);


            $bool = $mine->fill($mine_data)->save();
            if($bool)
            {
//                if(!empty($post_data['circle_id']))
//                {
//                    $circle_data['order_id'] = $mine->id;
//                    $circle_data['creator_id'] = $me->id;
//                    $circle->pivot_order_list()->attach($circle_data);  //
////                    $circle->pivot_order_list()->syncWithoutDetaching($circle_data);  //
//                }
            }
            else throw new Exception("insert--order--fail");

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

    // 【工单】发布
    public function v1_operate__order_item_publish($post_data)
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

        $time = time();
        $date = date("Y-m-d");

        $operate = $post_data["operate"];
        if($operate != 'order-publish') return response_error([],"参数[operate]有误！");
        $id = $post_data["item_id"];
        if(intval($id) !== 0 && !$id) return response_error([],"参数[ID]有误！");

        $item = WL_Common_Order::withTrashed()->find($id);
        if(!$item) return response_error([],"该内容不存在，刷新页面重试！");

        if($item->is_published != 0)
        {
            return response_error([],"该【工单】已经发布过了，不要重复发布，刷新页面看下！");
        }

        $this->get_me();
        $me = $this->me;
        if(!in_array($me->user_type,[0,1,9,11,81,84,88])) return response_error([],"你没有操作权限！");
        if(in_array($me->user_type,[88]) && $item->creator_id != $me->id) return response_error([],"该内容不是你的，你不能操作！");


        $project_id = $item->project_id;
        $client_phone = $item->client_phone;

//        $is_today_repeat = WL_Common_Order::where(['client_phone'=>(int)$client_phone])
//            ->where('id','<>',$id)
//            ->where('is_published','>',0)
//            ->where('published_date',$date)
//            ->where('item_category',$item->item_category)
//            ->count("*");
//        if($is_today_repeat > 0)
//        {
//            return response_error([],"该号码今日已经提交过，不能重复提交！");
//        }
//
//        $is_repeat = WL_Common_Order::where(['project_id'=>$project_id,'client_phone'=>(int)$client_phone])
//            ->where('id','<>',$id)
//            ->where('is_published','>',0)
//            ->where('item_category',$item->item_category)
//            ->count("*");
//        if($is_repeat == 0)
//        {
//            $is_repeat = DK_Pivot_Client_Delivery::where(['project_id'=>$project_id,'client_phone'=>(int)$client_phone])->count("*");
//        }

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            if($item->inspected_status == 1)
            {
                $item->inspected_status = 9;
            }


            // 二奢直接交付
            if($item->item_category == 99 && false)
            {
                $inspected_result = '通过';
                $delivered_result = '已交付';

                // 审核
                $item->inspector_id = 0;
                $item->inspected_status = 1;
                $item->inspected_result = $inspected_result;
                $item->inspected_at = $time;
                $item->inspected_date = $date;


                $project = WL_Common_Project::find($item->project_id);
                if($project->client_id != 0)
                {
                    $delivered_client_id = $project->client_id;
                    $client = WL_Common_Client::find($delivered_client_id);
                    if(!$client) return response_error([],"客户不存在！");
                }
                else $delivered_client_id = 0;

                $delivered_project_id = $item->project_id;


                // 交付
                $item->is_distributive_condition = 0;
                $item->client_id = $delivered_client_id;
                $item->deliverer_id = 0;
                $item->delivered_status = 1;
                $item->delivered_result = $delivered_result;
                $item->delivered_at = $time;
                $item->delivered_date = $date;

            }


//            $item->is_repeat = $is_repeat;
            $item->is_published = 1;
            $item->published_at = time();
            $item->published_date = $date;
            $bool = $item->save();
            if(!$bool) throw new Exception("WL_Common_Order--update--fail");
            else
            {
                $record = new WL_Staff_Record_Operation;

                $record_data["ip"] = Get_IP();
                $record_data["record_object"] = 21;
                $record_data["record_category"] = 11;
                $record_data["record_type"] = 1;
                $record_data["creator_id"] = $me->id;
                $record_data["order_id"] = $id;
                $record_data["operate_object"] = 71;
                $record_data["operate_category"] = 11;
                $record_data["operate_type"] = 1;

                $bool_1 = $record->fill($record_data)->save();
                if(!$bool_1) throw new Exception("WL_Staff_Record_Operation--insert--fail");
            }

            DB::commit();

            return response_success([],"发布成功!");
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

    // 【工单】字段 修改
    public function v1_operate__order_field_set($post_data)
    {
        $messages = [
            'operate-category.required' => 'operate-category.required.',
            'operate-type.required' => 'operate-type.required.',
            'item-id.required' => 'item-id.required.',
            'column-type.required' => 'column-type.required.',
            'column-key.required' => 'column-key.required.',
        ];
        $v = Validator::make($post_data, [
            'operate-category' => 'required',
            'operate-type' => 'required',
            'item-id' => 'required',
            'column-type' => 'required',
            'column-key' => 'required',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }

        $operate_category = $post_data["operate-category"];
        if($operate_category != 'field-set') return response_error([],"参数[operate]有误！");
        $id = $post_data["item-id"];
        if(intval($id) !== 0 && !$id) return response_error([],"参数[ID]有误！");

        $this->get_me();
        $me = $this->me;

        // 判断用户操作权限
//        if($item->owner_id != $me->id) return response_error([],"该内容不是你的，你不能操作！");


        $item = DK_Order::withTrashed()->find($id);
        if(!$item) return response_error([],"该【工单】不存在，刷新页面重试！");

        // 判断对象是否合法
        if(in_array($me->user_type,[84,88]) && $item->creator_id != $me->id) return response_error([],"该【工单】不是你的，你不能操作！");


        $operate_type = $post_data["operate-type"];

        $column_type = $post_data["column-type"];

        $column_key = $post_data["column-key"];
        $column_key2 = $post_data["column-key2"];

        $column_text_value = $post_data["field-set-text-value"];
        $column_textarea_value = $post_data["field-set-textarea-value"];
        $column_datetime_value = $post_data["field-set-datetime-value"];
        $column_date_value = $post_data["field-set-date-value"];
        $column_select_value = isset($post_data['field-set-select-value']) ? $post_data['field-set-select-value'] : '';
        $column_select_value2 = isset($post_data['field-set-select-value2']) ? $post_data['field-set-select-value2'] : '';
        $column_radio_value  = isset($post_data['field-set-radio-value']) ? $post_data['field-set-radio-value'] : '';

        if($column_type == 'text') $column_value = $column_text_value;
        else if($column_type == 'textarea') $column_value = $column_textarea_value;
        else if($column_type == 'radio')
        {
            $column_value = $column_radio_value;
        }
        else if($column_type == 'select') $column_value = $column_select_value;
        else if($column_type == 'select2') $column_value = $column_select_value;
        else if($column_type == 'datetime') $column_value = $column_datetime_value;
        else if($column_type == 'datetime_timestamp') $column_value = strtotime($column_datetime_value);
        else if($column_type == 'date') $column_value = $column_date_value;
        else if($column_type == 'date_timestamp') $column_value = strtotime($column_date_value);
        else $column_value = '';

        $before = $item->$column_key;
        $after = $column_value;
//        dd((string)$before.'-'.(string)$after.'-'.strlen($before));

        if($column_type == "radio")
        {
            $after = $column_value;
        }

        if($before == $after)
        {
            if($column_key == "client_phone")
            {
                return response_error([],"没有修改1！");
            }
            else if($column_key == "location_city")
            {
                if($item->$column_key2 == $column_select_value2) return response_error([],"没有修改2！");
            }
            else
            {
                return response_error([],"没有修改3！");
            }
        }

        $return['value'] = $column_value;
        $return['text'] = $column_value;


        if($column_key == "client_phone")
        {
            if(!in_array($me->user_type,[0,1,11,61,66,71,77,84,88])) return response_error([],"你没有操作权限！");
        }
        else if($column_key == "inspected_description")
        {
            if(!in_array($me->user_type,[0,1,11,61,66,71,77])) return response_error([],"你没有操作权限！");
        }
        else
        {
            if(!in_array($me->user_type,[0,1,11,61,66,71,77,84,88])) return response_error([],"你没有操作权限！");
        }

        if(in_array($column_key,['client_id','project_id']))
        {
            if(in_array($column_value,[-1,0,'-1','0'])) return response_error([],"选择有误！");
        }



        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $is_repeat = 0;
            if($column_key == "client_phone")
            {
                $project_id = $item->project_id;
                $client_phone = $item->client_phone;
                $column_value = (int)$column_value;

                $is_repeat = DK_Order::where(['project_id'=>$project_id,'client_phone'=>(int)$column_value])
                    ->where('id','<>',$id)->where('is_published','>',0)->count("*");
                if($is_repeat == 0)
                {
                    $is_repeat = DK_Pivot_Client_Delivery::where(['project_id'=>$project_id,'client_phone'=>(int)$column_value])->count("*");
                }
                $item->is_repeat = $is_repeat;
            }
            else if($column_key == "project_id")
            {
                if(in_array($column_value,[-1,0,'-1','0']))
                {
                }
                else
                {
                    $project = DK_Project::withTrashed()->find($column_value);
                    if(!$project) throw new Exception("该【项目】不存在，刷新页面重试！");

                    $project_id = $item->project_id;
                    $client_phone = $item->client_phone;

                    $is_repeat = DK_Order::where(['project_id'=>$column_value,'client_phone'=>(int)$client_phone])
                        ->where('id','<>',$id)->where('is_published','>',0)->count("*");
                    if($is_repeat == 0)
                    {
                        $is_repeat = DK_Pivot_Client_Delivery::where(['project_id'=>$column_value,'client_phone'=>(int)$client_phone])->count("*");
                    }
                    $item->is_repeat = $is_repeat;

                    $return['text'] = $project->name;
                }
            }
            else if($column_key == "location_city")
            {
                $before = $item->location_city.' - '.$item->location_district;

                $column_value2 = $column_select_value2;
                $item->$column_key2 = $column_value2;

                $after = $column_value.' - '.$column_value2;
                $return['value2'] = $column_value2;
                $return['text'] = $after;
            }

            $item->$column_key = $column_value;
            $bool = $item->save();
            if(!$bool) throw new Exception("DK_Order--update--fail");
            else
            {

                $return['item'] = $item;

                // 需要记录(已发布 || 他人修改)
                if($me->id == $item->creator_id && $item->is_published == 0 && false)
                {
                }
                else
                {
                    $record = new DK_Record;

                    $record_data["ip"] = Get_IP();
                    $record_data["record_object"] = 21;
                    $record_data["record_category"] = 11;
                    $record_data["record_type"] = 1;
                    $record_data["creator_id"] = $me->id;
                    $record_data["order_id"] = $id;
                    $record_data["operate_object"] = 71;
                    $record_data["operate_category"] = 1;

                    if($operate_type == "add") $record_data["operate_type"] = 1;
                    else if($operate_type == "edit") $record_data["operate_type"] = 11;

                    $record_data["column_type"] = $column_type;
                    $record_data["column_name"] = $column_key;
                    $record_data["before"] = $before;
                    $record_data["after"] = $after;

                    if(in_array($column_key,['client_id','project_id']))
                    {
                        $record_data["before_id"] = $before;
                        $record_data["after_id"] = $column_value;
                    }


                    if($column_key == 'client_id')
                    {
                        $record_data["before_client_id"] = $before;
                        $record_data["after_client_id"] = $column_value;
                    }
                    else if($column_key == 'project_id')
                    {
                        $record_data["before_project_id"] = $before;
                        $record_data["after_project_id"] = $column_value;
                    }

                    $bool_1 = $record->fill($record_data)->save();
                    if($bool_1)
                    {
                    }
                    else throw new Exception("DK_Record--insert--fail");
                }
            }

            DB::commit();
            return response_success(['data'=>$return]);
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


    // 【工单】【操作记录】返回-列表-数据
    public function v1_operate__order_item_operation_record_datatable_query($post_data)
    {
        $this->get_me();
        $me = $this->me;

        $id  = $post_data["id"];
        $query = WL_Staff_Record_Operation::select('*')
            ->with([
                'creator'=>function($query) { $query->select(['id','username','true_name']); },
            ])
            ->where(['order_id'=>$id]);
//            ->where(['record_object'=>21,'operate_object'=>61,'item_id'=>$id]);

        if(!empty($post_data['name'])) $query->where('name', 'like', "%{$post_data['name']}%");


        $total = $query->count();

        $draw  = isset($post_data['draw'])  ? $post_data['draw']  : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start']  : 0;
        $limit = isset($post_data['length']) ? $post_data['length'] : 50;

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

        if($limit == -1) $list = $query->get();
        else $list = $query->skip($skip)->take($limit)->withTrashed()->get();

        foreach ($list as $k => $v)
        {
            $list[$k]->encode_id = encode($v->id);

            if($v->owner_id == $me->id) $list[$k]->is_me = 1;
            else $list[$k]->is_me = 0;
        }
//        dd($list->toArray());
        return datatable_response($list, $draw, $total);
    }
    // 【工单】【费用记录】返回-列表-数据
    public function v1_operate__order_item_fee_record_datatable_query($post_data)
    {
        $this->get_me();
        $me = $this->me;

        $id  = $post_data["id"];
        $query = WL_Staff_Record_Operation::select('*')
            ->with([
                'creator'=>function($query) { $query->select(['id','username','true_name']); },
            ])
            ->where(['order_id'=>$id]);
//            ->where(['record_object'=>21,'operate_object'=>61,'item_id'=>$id]);

        if(!empty($post_data['name'])) $query->where('name', 'like', "%{$post_data['name']}%");


        $total = $query->count();

        $draw  = isset($post_data['draw'])  ? $post_data['draw']  : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start']  : 0;
        $limit = isset($post_data['length']) ? $post_data['length'] : 50;

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

        if($limit == -1) $list = $query->get();
        else $list = $query->skip($skip)->take($limit)->withTrashed()->get();

        foreach ($list as $k => $v)
        {
            $list[$k]->encode_id = encode($v->id);

            if($v->owner_id == $me->id) $list[$k]->is_me = 1;
            else $list[$k]->is_me = 0;
        }
//        dd($list->toArray());
        return datatable_response($list, $draw, $total);
    }


    // 【工单】保存数据
    public function v1_operate__order_item_customer_save($post_data)
    {
        $messages = [
            'operate.required' => 'operate.required.',
            'is_wx.required' => '请选择是否加微信！',
//            'name.unique' => '该部门号已存在！',
        ];
        $v = Validator::make($post_data, [
            'operate' => 'required',
            'is_wx' => 'required',
            'is_wx' => 'required',
//            'name' => 'required|unique:dk_department,name',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }

        $this->get_me();
        $me = $this->me;
        if(!in_array($me->user_type,[0,1,11,19,81,84,88])) return response_error([],"你没有操作权限！");


        $operate = $post_data["operate"];
        $operate_type = $operate["type"];
        $operate_id = $operate['id'];


        $mine = DK_Pivot_Client_Delivery::with([
            'client_contact_er'=>function($query) { $query->select(['id','name']); }
        ])->withTrashed()->find($operate_id);
        if(!$mine) return response_error([],"不存在警告，请刷新页面重试！");


        $datetime = date('Y-m-d H:i:s');


        $follow_update = [];

        $is_wx = $post_data["is_wx"];
        if($is_wx != $mine->is_wx)
        {
            $update['field'] = 'is_wx';
            $update['before'] = $mine->is_wx;
            $update['after'] = $is_wx;
            $follow_update[] = $update;

            $mine->is_wx = $is_wx;
        }

        $customer_remark = $post_data["customer_remark"];
        if($customer_remark != $mine->customer_remark)
        {
            $update['field'] = 'customer_remark';
            $update['before'] = $mine->customer_remark;
            $update['after'] = $customer_remark;
            $follow_update[] = $update;

            $mine->customer_remark = $customer_remark;
        }

        $client_contact_id = $post_data["client_contact_id"];
        $contact = DK_Client_Contact::select('id','name')->find($client_contact_id);
        if(!$contact) return response_error([],"【联系渠道】不存在，请刷新页面重试！");
        if($client_contact_id != $mine->client_contact_id)
        {
            $update['field'] = 'client_contact_id';
            if($mine->client_contact_er)
            {
                $update['before'] = $mine->client_contact_er->name;
            }
            else
            {
                $update['before'] = '';
            }
            $update['before_id'] = $mine->client_contact_id;
            $update['after'] = $contact->name;
            $update['after_id'] = $client_contact_id;
            $follow_update[] = $update;

            $mine->client_contact_id = $client_contact_id;
        }


        $follow = new DK_Client_Follow_Record;

        $follow_data["follow_category"] = 1;
        $follow_data["follow_type"] = 11;
        $follow_data["client_id"] = $me->client_id;
        $follow_data["delivery_id"] = $operate_id;
        $follow_data["creator_id"] = $me->id;
        $follow_data["custom_text_1"] = json_encode($follow_update);
        $follow_data["follow_datetime"] = $datetime;
        $follow_data["follow_date"] = $datetime;


        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $bool = $follow->fill($follow_data)->save();
            if($bool)
            {
//                $mine->timestamps = false;
                $mine->last_operation_datetime = $datetime;
                $mine->last_operation_date = $datetime;
                $bool_d = $mine->save();
            }
            else throw new Exception("DK_Client_Follow_Record--insert--fail");

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
    // 【工单】保存数据
    public function v1_operate__order_item_callback_save($post_data)
    {
        $messages = [
            'operate.required' => 'operate.required.',
//            'follow-datetime.required' => '请输入跟进时间！',
//            'is_come.required' => '请选择上门状态！',
            'callback-datetime.required' => '请选择回访时间！',
//            'name.unique' => '该部门号已存在！',
        ];
        $v = Validator::make($post_data, [
            'operate' => 'required',
//            'callback-datetime' => 'required',
//            'is_come' => 'required',
            'callback-datetime' => 'required',
//            'name' => 'required|unique:dk_department,name',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }

        $this->get_me();
        $me = $this->me;
        if(!in_array($me->user_type,[0,1,11,19,81,84,88])) return response_error([],"你没有操作权限！");


        $operate = $post_data["operate"];
        $operate_type = $operate["type"];
        $operate_id = $operate['id'];


        $mine = DK_Pivot_Client_Delivery::with([
        ])->withTrashed()->find($operate_id);
        if(!$mine) return response_error([],"不存在警告，请刷新页面重试！");


        $datetime = date('Y-m-d H:i:s');


        $follow_update = [];

        // 回访状态
//        $is_callback = $post_data["is_callback"];
//        if($is_callback != $mine->is_callback)
//        {
//            $update['field'] = 'is_callback';
//            $update['before'] = $mine->is_callback;
//            $update['after'] = $is_callback;
//            $follow_update[] = $update;
//
//            $mine->is_callback = $is_callback;
//        }
        // 回访时间
        $callback_datetime = $post_data['callback-datetime'];
        if(!empty($callback_datetime))
        {
            $update['field'] = 'callback_datetime';
            $update['before'] = '';
            $update['after'] = $callback_datetime;
            $follow_update[] = $update;

            $mine->callback_datetime = $callback_datetime;
            $mine->callback_date = $callback_datetime;
        }
        // 回访备注
        $trade_data["description"] = $post_data['callback-description'];
        if(!empty($trade_data["description"]))
        {
            $update['field'] = 'callback_description';
            $update['before'] = '';
            $update['after'] = $trade_data["description"];
            $follow_update[] = $update;
        }



        $follow = new DK_Client_Follow_Record;

        $follow_data["follow_category"] = 1;
        $follow_data["follow_type"] = 21;
        $follow_data["client_id"] = $me->client_id;
        $follow_data["delivery_id"] = $operate_id;
        $follow_data["creator_id"] = $me->id;
        $follow_data["custom_text_1"] = json_encode($follow_update);
//        $follow_data["follow_datetime"] = $post_data['follow-datetime'];
//        $follow_data["follow_date"] = $post_data['follow-datetime'];


        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $bool = $follow->fill($follow_data)->save();
            if($bool)
            {
//                $mine->timestamps = false;
                $mine->last_operation_datetime = $datetime;
                $mine->last_operation_date = $datetime;
                $bool_d = $mine->save();
            }
            else throw new Exception("DK_Client_Follow_Record--insert--fail");

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
    // 【工单】保存数据
    public function v1_operate__order_item_come_save($post_data)
    {
        $messages = [
            'operate.required' => 'operate.required.',
            'follow-datetime.required' => '请输入跟进时间！',
            'is_come.required' => '请选择上门状态！',
//            'come-datetime.required' => '请选择上门时间！',
//            'name.unique' => '该部门号已存在！',
        ];
        $v = Validator::make($post_data, [
            'operate' => 'required',
            'follow-datetime' => 'required',
            'is_come' => 'required',
//            'come-datetime' => 'required',
//            'name' => 'required|unique:dk_department,name',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }

        $this->get_me();
        $me = $this->me;
        if(!in_array($me->user_type,[0,1,11,19,81,84,88])) return response_error([],"你没有操作权限！");


        $operate = $post_data["operate"];
        $operate_type = $operate["type"];
        $operate_id = $operate['id'];


        $mine = DK_Pivot_Client_Delivery::with([
        ])->withTrashed()->find($operate_id);
        if(!$mine) return response_error([],"不存在警告，请刷新页面重试！");


        $datetime = date('Y-m-d H:i:s');


        $follow_update = [];

        // 上门状态
        $is_come = $post_data["is_come"];
        if($is_come != $mine->is_come)
        {
            $update['field'] = 'is_come';
            $update['before'] = $mine->is_come;
            $update['after'] = $is_come;
            $follow_update[] = $update;

            $mine->is_come = $is_come;
        }
        // 上门时间
        $come_datetime = $post_data['come-datetime'];
        if(!empty($come_datetime))
        {
            $update['field'] = 'come_datetime';
            $update['before'] = '';
            $update['after'] = $come_datetime;
            $follow_update[] = $update;

            $mine->come_datetime = $come_datetime;
            $mine->come_date = $come_datetime;
        }
        // 上门备注
        $trade_data["description"] = $post_data['come-description'];
        if(!empty($trade_data["description"]))
        {
            $update['field'] = 'come_description';
            $update['before'] = '';
            $update['after'] = $trade_data["description"];
            $follow_update[] = $update;
        }



        $follow = new DK_Client_Follow_Record;

        $follow_data["follow_category"] = 1;
        $follow_data["follow_type"] = 31;
        $follow_data["client_id"] = $me->client_id;
        $follow_data["delivery_id"] = $operate_id;
        $follow_data["creator_id"] = $me->id;
        $follow_data["custom_text_1"] = json_encode($follow_update);
        $follow_data["follow_datetime"] = $post_data['follow-datetime'];
        $follow_data["follow_date"] = $post_data['follow-datetime'];


        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $bool = $follow->fill($follow_data)->save();
            if($bool)
            {
//                $mine->timestamps = false;
                $mine->last_operation_datetime = $datetime;
                $mine->last_operation_date = $datetime;
                $bool_d = $mine->save();
            }
            else throw new Exception("DK_Client_Follow_Record--insert--fail");

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
    // 【工单】保存数据
    public function v1_operate__order_item_follow_save($post_data)
    {
        $messages = [
            'operate.required' => 'operate.required.',
            'follow-datetime.required' => '请输入跟进时间！',
//            'name.required' => '请输入联系渠道名称！',
//            'name.unique' => '该部门号已存在！',
        ];
        $v = Validator::make($post_data, [
            'operate' => 'required',
            'follow-datetime' => 'required',
//            'name' => 'required',
//            'name' => 'required|unique:dk_department,name',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }

        $this->get_me();
        $me = $this->me;
        if(!in_array($me->user_type,[0,1,11,19,81,84,88])) return response_error([],"你没有操作权限！");


        $operate = $post_data["operate"];
        $operate_type = $operate["type"];
        $operate_id = $operate['id'];

        $mine = WL_Common_Order::with([])->withTrashed()->find($operate_id);
        if(!$mine) return response_error([],"不存在警告，请刷新页面重试！");


        $datetime = date('Y-m-d H:i:s');


        $follow = new WL_Staff_Record_Operation;

        $follow_data["operate_category"] = 1;
        $follow_data["operate_type"] = 1;
        $follow_data["client_id"] = $mine->client_id;
        $follow_data["project_id"] = $mine->project_id;
        $follow_data["order_id"] = $operate_id;
        $follow_data["creator_id"] = $me->id;
        $follow_data["staff_id"] = $me->id;
        $follow_data["team_id"] = $me->team_id;
        $follow_data["content"] = $post_data['follow-content'];
        $follow_data["custom_datetime"] = $post_data['follow-datetime'];
        $follow_data["custom_date"] = $post_data['follow-datetime'];


        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $bool = $follow->fill($follow_data)->save();
            if($bool)
            {
//                $mine->timestamps = false;
                $mine->follow_content = $post_data['follow-content'];
                $mine->last_operation_datetime = $datetime;
                $mine->last_operation_date = $datetime;
                $bool_d = $mine->save();
                if(!$bool_d) throw new Exception("WL_Common_Order--update--fail");
            }
            else throw new Exception("DK_Client_Follow_Record--insert--fail");

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
    // 【工单】保存数据
    public function v1_operate__order_item_trade_save($post_data)
    {
        $messages = [
            'operate.required' => 'operate.required.',
            'fee-datetime.required' => '请输入成交时间！',
            'fee-amount.required' => '请输入金额！',
            'fee-name.required' => '请输入名目！',
//            'name.unique' => '该部门号已存在！',
        ];
        $v = Validator::make($post_data, [
            'operate' => 'required',
            'fee-datetime' => 'required',
            'fee-amount' => 'required',
            'fee-name' => 'required',
//            'name' => 'required|unique:dk_department,name',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }

        $this->get_me();
        $me = $this->me;
        if(!in_array($me->user_type,[0,1,11,19,81,84,88])) return response_error([],"你没有操作权限！");


        $operate = $post_data["operate"];
        $operate_type = $operate["type"];
        $operate_id = $operate['id'];

        $mine = WL_Common_Order::with([])->withTrashed()->find($operate_id);
        if(!$mine) return response_error([],"不存在警告，请刷新页面重试！");


        $datetime = date('Y-m-d H:i:s');

        $follow_update = [];


        $trade = new DK_Client_Trade_Record;

        $trade_data["fee_category"] = 1;
        $trade_data["trade_type"] = 1;
        $trade_data["client_id"] = $me->client_id;
        $trade_data["delivery_id"] = $operate_id;
        $trade_data["creator_id"] = $me->id;

        $trade_data["title"] = $post_data['transaction-title'];

        $trade_data["transaction_datetime"] = $post_data['transaction-datetime'];
        if(!empty($trade_data["transaction_datetime"]))
        {
            $update['field'] = 'transaction_datetime';
            $update['before'] = '';
            $update['after'] = $trade_data["transaction_datetime"];
            $follow_update[] = $update;
        }
        $trade_data["transaction_date"] = $post_data['transaction-datetime'];

        $trade_data["transaction_count"] = $post_data['transaction-count'];
        if(!empty($trade_data["transaction_count"]))
        {
            $update['field'] = 'transaction_count';
            $update['before'] = '';
            $update['after'] = $trade_data["transaction_count"];
            $follow_update[] = $update;
        }
        $trade_data["transaction_amount"] = $post_data['transaction-amount'];
        if(!empty($trade_data["transaction_amount"]))
        {
            $update['field'] = 'transaction_amount';
            $update['before'] = '';
            $update['after'] = $trade_data["transaction_amount"];
            $follow_update[] = $update;
        }

        $trade_data["transaction_pay_account"] = $post_data['transaction-pay-account'];
        $trade_data["transaction_receipt_account"] = $post_data['transaction-receipt-account'];
        $trade_data["transaction_order_number"] = $post_data['transaction-order-number'];

        $trade_data["description"] = $post_data['transaction-description'];
        if(!empty($trade_data["description"]))
        {
            $update['field'] = 'transaction_description';
            $update['before'] = '';
            $update['after'] = $trade_data["description"];
            $follow_update[] = $update;
        }


        $follow = new DK_Client_Follow_Record;

        $follow_data["follow_category"] = 1;
        $follow_data["follow_type"] = 88;
        $follow_data["client_id"] = $me->client_id;
        $follow_data["delivery_id"] = $operate_id;
        $follow_data["creator_id"] = $me->id;
        $follow_data["custom_text_1"] = json_encode($follow_update);
//        $follow_data["follow_datetime"] = $datetime;
//        $follow_data["follow_date"] = $datetime;


        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $bool_t = $trade->fill($trade_data)->save();
            if($bool_t)
            {
                $follow_data['custom_id'] = $trade->id;
                $bool_f = $follow->fill($follow_data)->save();
                if($bool_f)
                {
                    $trade->follow_id = $follow->id;
                    $bool_t_2 = $trade->save();
                    if(!$bool_t_2) throw new Exception("DK_Client_Trade_Record--update--fail");

//                    $mine = DK_Pivot_Client_Delivery::lockForUpdate()->withTrashed()->find($operate_id);
//
////                $mine->timestamps = false;
//                    $mine->transaction_num += 1;
//                    $mine->transaction_count += $post_data['transaction-count'];
//                    $mine->transaction_amount += $post_data['transaction-amount'];
//                    $mine->transaction_datetime = $post_data['transaction-datetime'];
//                    $mine->transaction_date = $post_data['transaction-datetime'];
                    $mine->last_operation_datetime = $datetime;
                    $mine->last_operation_date = $datetime;
                    $bool_d = $mine->save();
                    if(!$bool_d) throw new Exception("DK_Pivot_Client_Delivery--update--fail");
                }
                else throw new Exception("DK_Client_Follow_Record--insert--fail");
            }
            else throw new Exception("DK_Client_Trade_Record--insert--fail");

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
    // 【工单】保存数据
    public function v1_operate__order_item_fee_save($post_data)
    {
        $messages = [
            'operate.required' => 'operate.required.',
            'fee-title.required' => '请输入名目！',
            'fee-amount.required' => '请输入金额！',
            'fee-datetime.required' => '请输入时间！',
//            'name.unique' => '该部门号已存在！',
        ];
        $v = Validator::make($post_data, [
            'operate' => 'required',
            'fee-title' => 'required',
            'fee-amount' => 'required',
            'fee-datetime' => 'required',
//            'name' => 'required|unique:dk_department,name',
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


        $order = WL_Common_Order::with([])->withTrashed()->find($operate_id);
        if(!$order) return response_error([],"【订单】不存在警告，请刷新页面重试！");


        $datetime = date('Y-m-d H:i:s');

        $operation_record = [];


        $fee_data["fee_category"] = 1;
        $fee_data["fee_type"] = 1;
        $fee_data["client_id"] = $order->client_id;
        $fee_data["project_id"] = $order->project_id;
        $fee_data["order_id"] = $operate_id;
        $fee_data["order_task_date"] = $order->task_date;
        $fee_data["car_id"] = $order->car_id;
        $fee_data["driver_id"] = $order->driver_id;
        $fee_data["creator_id"] = $me->id;
        $fee_data["department_id"] = $me->department_id;
        $fee_data["team_id"] = $me->team_id;


        // 类型
        $fee_data["fee_type"] = $post_data['fee-type'];
        if(!empty($fee_data["fee_type"]))
        {
            $operation['field'] = 'fee_type';
            $operation['title'] = '类型';
            $operation['before'] = '';
            if($fee_data["fee_type"] == 1)
            {
                $operation['after'] = "收入";
            }
            else if($fee_data["fee_type"] == 99)
            {
                $operation['after'] = "费用";
            }
            else if($fee_data["fee_type"] == 101)
            {
                $operation['after'] = "扣款";
            }
            else if($fee_data["fee_type"] == 111)
            {
                $operation['after'] = "罚款";
            }
            else
            {
                $operation['after'] = $fee_data["fee_type"];
            }
            $operation_record[] = $operation;
        }
        // 名目
        $fee_data["fee_title"] = $post_data['fee-title'];
        if(!empty($fee_data["fee_title"]))
        {
            $operation['field'] = 'fee_title';
            $operation['title'] = '名目';
            $operation['before'] = '';
            $operation['after'] = $fee_data["fee_title"];
            $operation_record[] = $operation;
        }
        // 金额
        $fee_data["fee_amount"] = $post_data['fee-amount'];
        if(!empty($fee_data["fee_amount"]))
        {
            $operation['field'] = 'fee_amount';
            $operation['title'] = '金额';
            $operation['before'] = '';
            $operation['after'] = $fee_data["fee_amount"];
            $operation_record[] = $operation;
        }
        // 时间
        $fee_data["fee_date"] = $post_data['fee-datetime'];
        $fee_data["fee_datetime"] = $post_data['fee-datetime'];
        if(!empty($fee_data["fee_datetime"]))
        {
            $operation['field'] = 'fee_datetime';
            $operation['title'] = '时间';
            $operation['before'] = '';
            $operation['after'] = $fee_data["fee_datetime"];
            $operation_record[] = $operation;
        }

//        $fee_data["fee_account"] = $post_data['fee-account'];
        // 付款账号
        $fee_data["fee_account_from"] = $post_data['fee-account-from'];
        if(!empty($fee_data["fee_account_to"]))
        {
            $operation['field'] = 'fee_account_from';
            $operation['title'] = '付款账号';
            $operation['before'] = '';
            $operation['after'] = $fee_data["fee_account_from"];
            $operation_record[] = $operation;
        }
        // 收款账号
        $fee_data["fee_account_to"] = $post_data['fee-account-to'];
        if(!empty($fee_data["fee_account_to"]))
        {
            $operation['field'] = 'fee_account_to';
            $operation['title'] = '收款账号';
            $operation['before'] = '';
            $operation['after'] = $fee_data["fee_account_to"];
            $operation_record[] = $operation;
        }

        // 单号
        $fee_data["fee_reference_no"] = $post_data['fee-reference-no'];
        if(!empty($fee_data["fee_reference_no"]))
        {
            $operation['field'] = 'fee_reference_no';
            $operation['title'] = '费用单号';
            $operation['before'] = '';
            $operation['after'] = $fee_data["fee_reference_no"];
            $operation_record[] = $operation;
        }

        $fee_data["fee_description"] = $post_data['fee-description'];
        if(!empty($fee_data["fee_description"]))
        {
            $operation['field'] = 'fee_description';
            $operation['title'] = '说明';
            $operation['before'] = '';
            $operation['after'] = $fee_data["fee_description"];
            $operation_record[] = $operation;
        }



        $operation_data["operate_category"] = 1;
        $operation_data["operate_type"] = 88;
        $operation_data["client_id"] = $order->client_id;
        $operation_data["project_id"] = $order->project_id;
        $operation_data["order_id"] = $operate_id;
        $operation_data["creator_id"] = $me->id;
        $operation_data["team_id"] = $me->team_id;
        $operation_data["department_id"] = $me->department_id;
//        $operation_data["company_id"] = $me->company_id;
        $operation_data["custom_date"] = $fee_data["fee_datetime"];
        $operation_data["custom_datetime"] = $fee_data["fee_datetime"];
        $operation_data["content"] = json_encode($operation_record);
//        $follow_data["custom_datetime"] = $datetime;
//        $follow_data["custom_date"] = $datetime;


        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $fee = new WL_Common_Fee;
            $bool_fee = $fee->fill($fee_data)->save();
            if($bool_fee)
            {
                $operation_data['custom_id'] = $fee->id;

                $operation = new WL_Staff_Record_Operation;
                $bool_op = $operation->fill($operation_data)->save();
                if($bool_op)
                {
                    $fee->operation_id = $operation->id;
                    $bool_t_2 = $fee->save();
                    if(!$bool_t_2) throw new Exception("WL_Staff_Record_Operation--update--fail");

//                    $mine = DK_Pivot_Client_Delivery::lockForUpdate()->withTrashed()->find($operate_id);
//
////                $mine->timestamps = false;
//                    $mine->transaction_num += 1;
//                    $mine->transaction_count += $post_data['transaction-count'];
//                    $mine->transaction_amount += $post_data['transaction-amount'];
//                    $mine->transaction_datetime = $post_data['transaction-datetime'];
//                    $mine->transaction_date = $post_data['transaction-datetime'];


                    $order = WL_Common_Order::lockForUpdate()->find($operate_id);
                    if($fee_data["fee_type"] == 1)
                    {
                        $order->financial_expense_total += $post_data['fee-amount'];
                    }
                    else if($fee_data["fee_type"] == 91)
                    {
                        $order->financial_deduction_total += $post_data['fee-amount'];
                    }
                    else if($fee_data["fee_type"] == 101)
                    {
                        $order->financial_fine_total += $post_data['fee-amount'];
                    }
                    $order->last_operation_datetime = $datetime;
                    $order->last_operation_date = $datetime;
                    $bool_order = $order->save();
                    if(!$bool_order) throw new Exception("WL_Common_Order--update--fail");
                }
                else throw new Exception("WL_Staff_Record_Operation--insert--fail");
            }
            else throw new Exception("WL_Common_Fee--insert--fail");

            DB::commit();
            return response_success(['id'=>$order->id]);
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




    /*
     * 费用-管理 Fee
     */
    // 【费用】返回-列表-数据
    public function v1_operate__fee_datatable_list_query($post_data)
    {
        $this->get_me();
        $me = $this->me;


        $query = WL_Common_Fee::select('*')
            ->withTrashed()
            ->with([
                'creator'=>function($query) { $query->select(['id','username']); },
                'client_er'=>function($query) { $query->select(['id','name']); },
                'project_er'=>function($query) { $query->select(['id','name']); },
                'order_er'=>function($query) { $query->select(['*']); },
                'car_er'=>function($query) { $query->select(['id','name']); },
                'driver_er'=>function($query) { $query->select(['id','driver_name']); }
            ]);

        if(!empty($post_data['id'])) $query->where('id', $post_data['id']);
        if(!empty($post_data['name'])) $query->where('name', 'like', "%{$post_data['name']}%");
        if(!empty($post_data['title'])) $query->where('title', 'like', "%{$post_data['title']}%");
        if(!empty($post_data['remark'])) $query->where('remark', 'like', "%{$post_data['remark']}%");
        if(!empty($post_data['description'])) $query->where('description', 'like', "%{$post_data['description']}%");
        if(!empty($post_data['keyword'])) $query->where('content', 'like', "%{$post_data['keyword']}%");

        // 状态 [|]
        if(!empty($post_data['item_status']))
        {
            if(!in_array($post_data['item_status'],[-1,0]))
            {
                $query->where('item_status', $post_data['item_status']);
            }
        }
        else
        {
            $query->where('item_status', 1);
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
    // 【费用】保存数据
    public function v1_operate__fee_item_financial_save($post_data)
    {
        $messages = [
            'operate.required' => 'operate.required.',
            'transaction-title.required' => '请输入名目！',
            'transaction-amount.required' => '请输入金额！',
            'transaction-datetime.required' => '请输入时间！',
            'transaction-payment-method.required' => '请输入支付方式！',
//            'name.unique' => '该部门号已存在！',
        ];
        $v = Validator::make($post_data, [
            'operate' => 'required',
            'transaction-title' => 'required',
            'transaction-amount' => 'required',
            'transaction-datetime' => 'required',
            'transaction-payment-method' => 'required',
//            'name' => 'required|unique:dk_department,name',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }

        $time = date("Y-m-d H:i:s"); // 输出当前的日期和时间，格式为 YYYY-MM-DD HH:MM:SS

        $this->get_me();
        $me = $this->me;

        if(!in_array($me->user_type,[0,1,11,19])) return response_error([],"你没有操作权限！");


        $operate = $post_data["operate"];
        $operate_type = $operate["type"];
        $operate_id = $operate['id'];


        $fee = WL_Common_Fee::with([])->withTrashed()->find($operate_id);
        if(!$fee) return response_error([],"【费用】不存在警告，请刷新页面重试！");


        $datetime = date('Y-m-d H:i:s');

        $operation_record = [];


        $financial_data["fee_category"] = 1;
        $financial_data["fee_type"] = 1;
        $financial_data["client_id"] = $fee->client_id;
        $financial_data["project_id"] = $fee->project_id;
        $financial_data["order_id"] = $fee->order_id;
        $financial_data["order_task_date"] = $fee->order_task_date;
        $financial_data["fee_id"] = $operate_id;
        $financial_data["creator_id"] = $me->id;
        $financial_data["department_id"] = $me->department_id;
        $financial_data["team_id"] = $me->team_id;
        if($fee->type == 1) $financial_data["transaction_type"] = 1;
        else if($fee->type == 99) $financial_data["transaction_type"] = 99;


        // 类型
//        $financial_data["financial_type"] = $post_data['financial-type'];
//        if(!empty($financial_data["financial_type"]))
//        {
//            $operation['field'] = 'financial_type';
//            $operation['title'] = '类型';
//            $operation['before'] = '';
//            if($financial_data["financial_type"] == 1)
//            {
//                $operation['after'] = "费用";
//            }
//            else if($financial_data["financial_type"] == 91)
//            {
//                $operation['after'] = "扣款";
//            }
//            else if($financial_data["financial_type"] == 101)
//            {
//                $operation['after'] = "罚款";
//            }
//            else
//            {
//                $operation['after'] = $financial_data["fee_type"];
//            }
//            $operation_record[] = $operation;
//        }
        // 名目
        $financial_data["transaction_title"] = $post_data['transaction-title'];
        if(!empty($financial_data["transaction_title"]))
        {
            $operation['field'] = 'transaction_title';
            $operation['title'] = '名目';
            $operation['before'] = '';
            $operation['after'] = $financial_data["transaction_title"];
            $operation_record[] = $operation;
        }
        // 金额
        $financial_data["transaction_amount"] = $post_data['transaction-amount'];
        if(!empty($financial_data["transaction_amount"]))
        {
            $operation['field'] = 'transaction_amount';
            $operation['title'] = '金额';
            $operation['before'] = '';
            $operation['after'] = $financial_data["transaction_amount"];
            $operation_record[] = $operation;
        }
        // 时间
        $financial_data["transaction_date"] = $post_data['transaction-datetime'];
        $financial_data["transaction_datetime"] = $post_data['transaction-datetime'];
        if(!empty($financial_data["transaction_datetime"]))
        {
            $operation['field'] = 'transaction_datetime';
            $operation['title'] = '时间';
            $operation['before'] = '';
            $operation['after'] = $financial_data["transaction_datetime"];
            $operation_record[] = $operation;
        }

//        $financial_data["transaction_account"] = $post_data['transaction-account'];
        // 付款账号
        $financial_data["transaction_account_from"] = $post_data['transaction-account-from'];
        if(!empty($financial_data["transaction_account_to"]))
        {
            $operation['field'] = 'transaction_account_from';
            $operation['title'] = '付款账号';
            $operation['before'] = '';
            $operation['after'] = $financial_data["transaction_account_from"];
            $operation_record[] = $operation;
        }
        // 收款账号
        $financial_data["transaction_account_to"] = $post_data['transaction-account-to'];
        if(!empty($financial_data["transaction_account_to"]))
        {
            $operation['field'] = 'transaction_account_to';
            $operation['title'] = '收款账号';
            $operation['before'] = '';
            $operation['after'] = $financial_data["transaction_account_to"];
            $operation_record[] = $operation;
        }

        // 单号
        $financial_data["transaction_reference_no"] = $post_data['transaction-reference-no'];
        if(!empty($financial_data["transaction_reference_no"]))
        {
            $operation['field'] = 'transaction_reference_no';
            $operation['title'] = '单号';
            $operation['before'] = '';
            $operation['after'] = $financial_data["transaction_reference_no"];
            $operation_record[] = $operation;
        }

        $financial_data["transaction_description"] = $post_data['transaction-description'];
        if(!empty($financial_data["transaction_description"]))
        {
            $operation['field'] = 'transaction_description';
            $operation['title'] = '说明';
            $operation['before'] = '';
            $operation['after'] = $financial_data["transaction_description"];
            $operation_record[] = $operation;
        }



        $operation_data["operate_category"] = 1;
        $operation_data["operate_type"] = 88;
        $operation_data["client_id"] = $fee->client_id;
        $operation_data["project_id"] = $fee->project_id;
        $operation_data["order_id"] = $fee->order_id;
        $operation_data["fee_id"] = $operate_id;
        $operation_data["creator_id"] = $me->id;
        $operation_data["team_id"] = $me->team_id;
        $operation_data["department_id"] = $me->department_id;
//        $operation_data["company_id"] = $me->company_id;
        $operation_data["custom_date"] = $financial_data["transaction_datetime"];
        $operation_data["custom_datetime"] = $financial_data["transaction_datetime"];
        $operation_data["content"] = json_encode($operation_record);
//        $follow_data["custom_datetime"] = $datetime;
//        $follow_data["custom_date"] = $datetime;


        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $finance = new WL_Common_Finance;
            $bool_finance = $finance->fill($financial_data)->save();
            if($bool_finance)
            {
                $fee->is_completed = 1;
                $fee->completed_date = $time;
                $fee->completed_datetime = $time;
                $fee->finance_id = $finance->id;
                $fee->save();

                $operation_data['custom_id'] = $fee->id;

                $operation = new WL_Staff_Record_Operation;
                $bool_op = $operation->fill($operation_data)->save();
                if($bool_op)
                {
//                    $finance->operation_id = $operation->id;
//                    $bool_t_2 = $finance->save();
//                    if(!$bool_t_2) throw new Exception("WL_Staff_Record_Operation--update--fail");
                }
                else throw new Exception("WL_Staff_Record_Operation--insert--fail");
            }
            else throw new Exception("WL_Common_Finance--insert--fail");

            DB::commit();
            return response_success(['id'=>$fee->id]);
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




    /*
     * 财务-管理 Financial
     */
    // 【费用】返回-列表-数据
    public function v1_operate__finance_datatable_list_query($post_data)
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







    // 【统计】【客户】日报
    public function v1_operate__get_statistic_data_of_statistic_client_by_daily($post_data)
    {
        $this->get_me();
        $me = $this->me;


        $this_month = date('Y-m');
        $this_month_start_date = date('Y-m-01'); // 本月开始日期
        $this_month_ended_date = date('Y-m-t'); // 本月结束日期
        $this_month_start_datetime = date('Y-m-01 00:00:00'); // 本月开始时间
        $this_month_ended_datetime = date('Y-m-t 23:59:59'); // 本月结束时间
        $this_month_start_timestamp = strtotime($this_month_start_date); // 本月开始时间戳
        $this_month_ended_timestamp = strtotime($this_month_ended_datetime); // 本月结束时间戳

        $last_month_start_date = date('Y-m-01',strtotime('last month')); // 上月开始时间
        $last_month_ended_date = date('Y-m-t',strtotime('last month')); // 上月开始时间
        $last_month_start_datetime = date('Y-m-01 00:00:00',strtotime('last month')); // 上月开始时间
        $last_month_ended_datetime = date('Y-m-t 23:59:59',strtotime('last month')); // 上月结束时间
        $last_month_start_timestamp = strtotime($last_month_start_date); // 上月开始时间戳
        $last_month_ended_timestamp = strtotime($last_month_ended_datetime); // 上月月结束时间戳




        $the_month  = isset($post_data['time_month']) ? $post_data['time_month']  : date('Y-m');
        $the_month_timestamp = strtotime($the_month);

        $the_month_start_date = date('Y-m-01',$the_month_timestamp); // 指定月份-开始日期
        $the_month_ended_date = date('Y-m-t',$the_month_timestamp); // 指定月份-结束日期
        $the_month_start_datetime = date('Y-m-01 00:00:00',$the_month_timestamp); // 本月开始时间
        $the_month_ended_datetime = date('Y-m-t 23:59:59',$the_month_timestamp); // 本月结束时间
        $the_month_start_timestamp = strtotime($the_month_start_datetime); // 指定月份-开始时间戳
        $the_month_ended_timestamp = strtotime($the_month_ended_datetime); // 指定月份-结束时间戳

        $the_date  = isset($post_data['time_date']) ? $post_data['time_date']  : date('Y-m-d');


        $query_order = WL_Common_Order::select('task_date')
            ->whereBetween('task_date',[$the_month_start_date,$the_month_ended_date])
            ->groupBy('task_date')
            ->addSelect(DB::raw("
                    DATE_FORMAT(task_date,'%Y-%m-%d') as date_day,
                    DATE_FORMAT(task_date,'%e') as day
                "))
            ->addSelect(DB::raw("
                    COUNT(*) as order_count_for_all,
                    sum(freight_amount) as order_sum_for_freight,
                    COUNT(DISTINCT client_id) AS attendance_client,
                    COUNT(DISTINCT project_id) AS attendance_project
                "))
            ->orderBy("task_date", "desc");

        $total = $query_order->count();


        $query_fee = WL_Common_Fee::select('order_task_date')
            ->whereBetween('order_task_date',[$the_month_start_date,$the_month_ended_date])
            ->groupBy('order_task_date')
            ->addSelect(DB::raw("
                    DATE_FORMAT(order_task_date,'%Y-%m-%d') as date_day,
                    DATE_FORMAT(order_task_date,'%e') as day
                "))
            ->addSelect(DB::raw("
                    count(*) as fee_count_for_all,
                    count(IF(fee_type = 1, TRUE, NULL)) as fee_count_for_income,
                    sum(IF(fee_type = 1, fee_amount, NULL)) as fee_sum_for_income,
                    count(IF(fee_type = 99, TRUE, NULL)) as fee_count_for_expense,
                    sum(IF(fee_type = 99, fee_amount, NULL)) as fee_sum_for_expense,
                    count(IF(fee_type = 101, TRUE, NULL)) as fee_count_for_deduction,
                    sum(IF(fee_type = 101, fee_amount, NULL)) as fee_sum_for_deduction,
                    count(IF(fee_type = 111, TRUE, NULL)) as fee_count_for_fine,
                    sum(IF(fee_type = 111, fee_amount, NULL)) as fee_sum_for_fine
                "))
            ->orderBy("order_task_date", "desc");


        $query_finance = WL_Common_Finance::select('order_task_date')
            ->whereBetween('order_task_date',[$the_month_start_date,$the_month_ended_date])
            ->groupBy('order_task_date')
            ->addSelect(DB::raw("
                    DATE_FORMAT(order_task_date,'%Y-%m-%d') as date_day,
                    DATE_FORMAT(order_task_date,'%e') as day
                "))
            ->addSelect(DB::raw("
                    count(*) as cnt,
                    count(IF(transaction_type = 1, TRUE, NULL)) as finance_count_for_income,
                    sum(IF(transaction_type = 1, TRUE, NULL)) as finance_sum_for_income,
                    count(IF(transaction_type = 99, TRUE, NULL)) as finance_count_for_expense,
                    sum(IF(transaction_type = 99, TRUE, NULL)) as finance_sum_for_expense,
                    count(IF(transaction_type = 101, TRUE, NULL)) as finance_count_for_deduction,
                    sum(IF(transaction_type = 101, TRUE, NULL)) as finance_sum_for_deduction,
                    count(IF(transaction_type = 111, TRUE, NULL)) as finance_count_for_fine,
                    sum(IF(transaction_type = 111, TRUE, NULL)) as finance_sum_for_fine
                "))
            ->orderBy("order_task_date", "desc");

        $draw  = isset($post_data['draw'])  ? $post_data['draw']  : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start']  : 0;
        $limit = isset($post_data['length']) ? $post_data['length'] : 50;

        $order_list = $query_order->get();
        $fee_list = $query_fee->get();
        $finance_list = $query_finance->get();

        // 转换为键值对的集合
        $keyed1 = $order_list->keyBy('date_day');
        $keyed2 = $fee_list->keyBy('date_day');
        $keyed3 = $finance_list->keyBy('date_day');
//        dd($keyed2->keys());

        // 获取所有唯一键
        $allIds = $keyed1->keys()->merge($keyed2->keys())->merge($keyed3->keys())->unique();
//        dd($allIds);

        // 合并对应元素
        $merged = $allIds->map(function ($id) use ($keyed1, $keyed2, $keyed3) {
            if($keyed3->get($id))
            {
                if($keyed1->get($id) && $keyed2->get($id))
                {
//                return $keyed1->get($id)->merge($keyed2->get($id));
                    return collect(array_merge(
                        $keyed1->get($id)->toArray(),
                        $keyed2->get($id)->toArray(),
                        $keyed3->get($id)->toArray()
                    ));
                }
                else if($keyed1->get($id) && !$keyed2->get($id))
                {
//                    return $keyed1->get($id);
                    return collect(array_merge(
                        $keyed1->get($id)->toArray(),
                        $keyed3->get($id)->toArray()
                    ));
                }
                else if(!$keyed1->get($id) && $keyed2->get($id))
                {
//                    return $keyed2->get($id);
                    return collect(array_merge(
                        $keyed2->get($id)->toArray(),
                        $keyed3->get($id)->toArray()
                    ));
                }
                else
                {
                    return $keyed3->get($id);
                }
            }
            else
            {
                if($keyed1->get($id) && $keyed2->get($id))
                {
//                return $keyed1->get($id)->merge($keyed2->get($id));
                    return collect(array_merge(
                        $keyed1->get($id)->toArray(),
                        $keyed2->get($id)->toArray()
                    ));
                }
                else if($keyed1->get($id) && !$keyed2->get($id))
                {
                    return $keyed1->get($id);
                }
                else if(!$keyed1->get($id) && $keyed2->get($id))
                {
                    return $keyed2->get($id);
                }
//            return array_merge(
//                $keyed1->get($id, [])->toArray(),
//                $keyed2->get($id, [])->toArray()
//            );
            }
        })->sortByDesc('date_day')->values(); // 重新索引为数字键

//        dd($merged->toArray());


        $total = $merged->count();



        $total_data = [];
        $total_data['published_at'] = 0;
        $total_data['date_day'] = '统计';
        $total_data['attendance_client'] = 0;
        $total_data['attendance_project'] = 0;
        $total_data['order_count_for_all'] = 0;
        $total_data['order_sum_for_freight'] = 0;
        $total_data['fee_count_for_expense'] = 0;
        $total_data['fee_sum_for_expense'] = 0;
        $total_data['fee_count_for_deduction'] = 0;
        $total_data['fee_sum_for_deduction'] = 0;
        $total_data['fee_count_for_fine'] = 0;
        $total_data['fee_sum_for_fine'] = 0;

        $total_data['cnt'] = 0;
        $total_data['minutes'] = 0;



        foreach ($merged as $k => $v)
        {
//            $total_data['attendance_client'] += $v['attendance_client'];
//            $total_data['attendance_project'] += $v['attendance_project'];

            $total_data['order_count_for_all'] += $v['order_count_for_all'];
            $total_data['order_sum_for_freight'] += $v['order_sum_for_freight'];

            $total_data['fee_count_for_expense'] += $v['fee_count_for_expense'];
            $total_data['fee_sum_for_expense'] += $v['fee_sum_for_expense'];

            $total_data['fee_count_for_deduction'] += $v['fee_count_for_deduction'];
            $total_data['fee_sum_for_deduction'] += $v['fee_sum_for_deduction'];

            $total_data['fee_count_for_fine'] += $v['fee_count_for_fine'];
            $total_data['fee_sum_for_fine'] += $v['fee_sum_for_fine'];

        }

        $merged[] = $total_data;

//        dd($list->toArray());

        return datatable_response($merged, $draw, $total);
    }
    // 【统计】【客户】日报
    public function v1_operate__get_statistic_data_of_statistic_client_by_daily_for_order($post_data)
    {
        $this->get_me();
        $me = $this->me;

        $client_id = $post_data['client_id'];

        $this_month = date('Y-m');
        $this_month_start_date = date('Y-m-01'); // 本月开始日期
        $this_month_ended_date = date('Y-m-t'); // 本月结束日期
        $this_month_start_datetime = date('Y-m-01 00:00:00'); // 本月开始时间
        $this_month_ended_datetime = date('Y-m-t 23:59:59'); // 本月结束时间
        $this_month_start_timestamp = strtotime($this_month_start_date); // 本月开始时间戳
        $this_month_ended_timestamp = strtotime($this_month_ended_datetime); // 本月结束时间戳

        $last_month_start_date = date('Y-m-01',strtotime('last month')); // 上月开始时间
        $last_month_ended_date = date('Y-m-t',strtotime('last month')); // 上月开始时间
        $last_month_start_datetime = date('Y-m-01 00:00:00',strtotime('last month')); // 上月开始时间
        $last_month_ended_datetime = date('Y-m-t 23:59:59',strtotime('last month')); // 上月结束时间
        $last_month_start_timestamp = strtotime($last_month_start_date); // 上月开始时间戳
        $last_month_ended_timestamp = strtotime($last_month_ended_datetime); // 上月月结束时间戳




        $the_month  = isset($post_data['time_month']) ? $post_data['time_month']  : date('Y-m');
        $the_month_timestamp = strtotime($the_month);

        $the_month_start_date = date('Y-m-01',$the_month_timestamp); // 指定月份-开始日期
        $the_month_ended_date = date('Y-m-t',$the_month_timestamp); // 指定月份-结束日期
        $the_month_start_datetime = date('Y-m-01 00:00:00',$the_month_timestamp); // 本月开始时间
        $the_month_ended_datetime = date('Y-m-t 23:59:59',$the_month_timestamp); // 本月结束时间
        $the_month_start_timestamp = strtotime($the_month_start_datetime); // 指定月份-开始时间戳
        $the_month_ended_timestamp = strtotime($the_month_ended_datetime); // 指定月份-结束时间戳

        $the_date  = isset($post_data['time_date']) ? $post_data['time_date']  : date('Y-m-d');


        $query_order = WL_Common_Order::select('*')
            ->with([
                'creator',
                'owner'=>function($query) { $query->select('id','username'); },
                'client_er'=>function($query) { $query->select('id','name'); },
                'project_er'=>function($query) { $query->select('id','name'); },
                'car_er'=>function($query) { $query->select('id','name'); },
                'trailer_er'=>function($query) { $query->select('id','name'); },
                'driver_er'=>function($query) { $query->select('id','driver_name','driver_phone'); },
                'copilot_er'=>function($query) { $query->select('id','driver_name','driver_phone'); }
            ])
            ->where('client_id',$client_id)
            ->whereBetween('task_date',[$the_month_start_date,$the_month_ended_date]);

        $total = $query_order->count();
//        (clone $query)->

        $draw  = isset($post_data['draw'])  ? $post_data['draw']  : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start']  : 0;
        $limit = isset($post_data['length']) ? $post_data['length'] : 50;

        $list = $query_order->get();


        $total_data = [];
        $total_data['id'] = '总计';
        $total_data['task_date'] = '--';
        $total_data['client_id'] = 0;
        $total_data['project_id'] = 0;
        $total_data['driver_id'] = 0;
        $total_data['transport_departure_place'] = '--';
        $total_data['transport_destination_place'] = '--';
        $total_data['transport_mileage'] = 0;
        $total_data['transport_time_limitation'] = 0;
        $total_data['freight_amount'] = 0;
        $total_data['financial_expense_total'] = 0.00;
        $total_data['financial_deduction_total'] = 0.00;

        $total_data['cnt'] = 0;
        $total_data['minutes'] = 0;


        foreach ($list as $k => $v)
        {
            $total_data['transport_mileage'] += $v['transport_mileage'];
            $total_data['transport_time_limitation'] += $v['transport_time_limitation'];

            $total_data['freight_amount'] += $v['freight_amount'];
            $total_data['financial_expense_total'] += $v['financial_expense_total'];
            $total_data['financial_deduction_total'] += $v['financial_deduction_total'];
        }
        $list[] = $total_data;
//        dd($list->toArray());

        return datatable_response($list, $draw, $total);
    }
    // 【统计】【客户】日报
    public function v1_operate__get_statistic_data_of_statistic_client_by_daily_for_fee($post_data)
    {
        $this->get_me();
        $me = $this->me;

        $client_id = $post_data['client_id'];

        $this_month = date('Y-m');
        $this_month_start_date = date('Y-m-01'); // 本月开始日期
        $this_month_ended_date = date('Y-m-t'); // 本月结束日期
        $this_month_start_datetime = date('Y-m-01 00:00:00'); // 本月开始时间
        $this_month_ended_datetime = date('Y-m-t 23:59:59'); // 本月结束时间
        $this_month_start_timestamp = strtotime($this_month_start_date); // 本月开始时间戳
        $this_month_ended_timestamp = strtotime($this_month_ended_datetime); // 本月结束时间戳

        $last_month_start_date = date('Y-m-01',strtotime('last month')); // 上月开始时间
        $last_month_ended_date = date('Y-m-t',strtotime('last month')); // 上月开始时间
        $last_month_start_datetime = date('Y-m-01 00:00:00',strtotime('last month')); // 上月开始时间
        $last_month_ended_datetime = date('Y-m-t 23:59:59',strtotime('last month')); // 上月结束时间
        $last_month_start_timestamp = strtotime($last_month_start_date); // 上月开始时间戳
        $last_month_ended_timestamp = strtotime($last_month_ended_datetime); // 上月月结束时间戳




        $the_month  = isset($post_data['time_month']) ? $post_data['time_month']  : date('Y-m');
        $the_month_timestamp = strtotime($the_month);

        $the_month_start_date = date('Y-m-01',$the_month_timestamp); // 指定月份-开始日期
        $the_month_ended_date = date('Y-m-t',$the_month_timestamp); // 指定月份-结束日期
        $the_month_start_datetime = date('Y-m-01 00:00:00',$the_month_timestamp); // 本月开始时间
        $the_month_ended_datetime = date('Y-m-t 23:59:59',$the_month_timestamp); // 本月结束时间
        $the_month_start_timestamp = strtotime($the_month_start_datetime); // 指定月份-开始时间戳
        $the_month_ended_timestamp = strtotime($the_month_ended_datetime); // 指定月份-结束时间戳

        $the_date  = isset($post_data['time_date']) ? $post_data['time_date']  : date('Y-m-d');


        $query_fee = WL_Common_Fee::select('*')
            ->withTrashed()
            ->with([
                'creator'=>function($query) { $query->select(['id','username']); },
                'client_er'=>function($query) { $query->select(['id','name']); },
                'project_er'=>function($query) { $query->select(['id','name']); },
                'order_er'=>function($query) { $query->select(['*']); },
                'car_er'=>function($query) { $query->select(['id','name']); },
                'driver_er'=>function($query) { $query->select(['id','driver_name']); }
            ])
            ->where('client_id',$client_id)
            ->whereBetween('fee_date',[$the_month_start_date,$the_month_ended_date]);

        $total = $query_fee->count();
//        (clone $query)->

        $draw  = isset($post_data['draw'])  ? $post_data['draw']  : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start']  : 0;
        $limit = isset($post_data['length']) ? $post_data['length'] : 50;

        $list = $query_fee->get();


        $total_data = [];
        $total_data['id'] = '总计';
        $total_data['fee_type'] = '--';
        $total_data['is_completed'] = '--';
        $total_data['fee_datetime'] = '--';
        $total_data['fee_title'] = '--';
        $total_data['fee_amount'] = 0;
        $total_data['client_id'] = '--';
        $total_data['project_id'] = '--';
        $total_data['order_id'] = '--';
        $total_data['car_id'] = '--';
        $total_data['driver_id'] = '--';
        $total_data['remark'] = '--';

        $total_data['cnt'] = 0;
        $total_data['minutes'] = 0;


        foreach ($list as $k => $v)
        {
            $total_data['fee_amount'] += $v['fee_amount'];
        }
        $list[] = $total_data;
//        dd($list->toArray());

        return datatable_response($list, $draw, $total);
    }


    // 【统计】【项目】日报
    public function v1_operate__get_statistic_data_of_statistic_project_by_daily($post_data)
    {
        $this->get_me();
        $me = $this->me;


        $this_month = date('Y-m');
        $this_month_start_date = date('Y-m-01'); // 本月开始日期
        $this_month_ended_date = date('Y-m-t'); // 本月结束日期
        $this_month_start_datetime = date('Y-m-01 00:00:00'); // 本月开始时间
        $this_month_ended_datetime = date('Y-m-t 23:59:59'); // 本月结束时间
        $this_month_start_timestamp = strtotime($this_month_start_date); // 本月开始时间戳
        $this_month_ended_timestamp = strtotime($this_month_ended_datetime); // 本月结束时间戳

        $last_month_start_date = date('Y-m-01',strtotime('last month')); // 上月开始时间
        $last_month_ended_date = date('Y-m-t',strtotime('last month')); // 上月开始时间
        $last_month_start_datetime = date('Y-m-01 00:00:00',strtotime('last month')); // 上月开始时间
        $last_month_ended_datetime = date('Y-m-t 23:59:59',strtotime('last month')); // 上月结束时间
        $last_month_start_timestamp = strtotime($last_month_start_date); // 上月开始时间戳
        $last_month_ended_timestamp = strtotime($last_month_ended_datetime); // 上月月结束时间戳




        $the_month  = isset($post_data['time_month']) ? $post_data['time_month']  : date('Y-m');
        $the_month_timestamp = strtotime($the_month);

        $the_month_start_date = date('Y-m-01',$the_month_timestamp); // 指定月份-开始日期
        $the_month_ended_date = date('Y-m-t',$the_month_timestamp); // 指定月份-结束日期
        $the_month_start_datetime = date('Y-m-01 00:00:00',$the_month_timestamp); // 本月开始时间
        $the_month_ended_datetime = date('Y-m-t 23:59:59',$the_month_timestamp); // 本月结束时间
        $the_month_start_timestamp = strtotime($the_month_start_datetime); // 指定月份-开始时间戳
        $the_month_ended_timestamp = strtotime($the_month_ended_datetime); // 指定月份-结束时间戳

        $the_date  = isset($post_data['time_date']) ? $post_data['time_date']  : date('Y-m-d');


        $query_order = WL_Common_Order::select('task_date')
            ->whereBetween('task_date',[$the_month_start_date,$the_month_ended_date])
            ->groupBy('task_date')
            ->addSelect(DB::raw("
                    DATE_FORMAT(task_date,'%Y-%m-%d') as date_day,
                    DATE_FORMAT(task_date,'%e') as day
                "))
            ->addSelect(DB::raw("
                    COUNT(*) as order_count_for_all,
                    sum(freight_amount) as order_sum_for_freight,
                    COUNT(DISTINCT client_id) AS attendance_client,
                    COUNT(DISTINCT project_id) AS attendance_project
                "))
            ->orderBy("task_date", "desc");

        $total = $query_order->count();


        $query_fee = WL_Common_Fee::select('order_task_date')
            ->whereBetween('order_task_date',[$the_month_start_date,$the_month_ended_date])
            ->groupBy('order_task_date')
            ->addSelect(DB::raw("
                    DATE_FORMAT(order_task_date,'%Y-%m-%d') as date_day,
                    DATE_FORMAT(order_task_date,'%e') as day
                "))
            ->addSelect(DB::raw("
                    count(*) as fee_count_for_all,
                    count(IF(fee_type = 1, TRUE, NULL)) as fee_count_for_income,
                    sum(IF(fee_type = 1, fee_amount, NULL)) as fee_sum_for_income,
                    count(IF(fee_type = 99, TRUE, NULL)) as fee_count_for_expense,
                    sum(IF(fee_type = 99, fee_amount, NULL)) as fee_sum_for_expense,
                    count(IF(fee_type = 101, TRUE, NULL)) as fee_count_for_deduction,
                    sum(IF(fee_type = 101, fee_amount, NULL)) as fee_sum_for_deduction,
                    count(IF(fee_type = 111, TRUE, NULL)) as fee_count_for_fine,
                    sum(IF(fee_type = 111, fee_amount, NULL)) as fee_sum_for_fine
                "))
            ->orderBy("order_task_date", "desc");


        $query_finance = WL_Common_Finance::select('order_task_date')
            ->whereBetween('order_task_date',[$the_month_start_date,$the_month_ended_date])
            ->groupBy('order_task_date')
            ->addSelect(DB::raw("
                    DATE_FORMAT(order_task_date,'%Y-%m-%d') as date_day,
                    DATE_FORMAT(order_task_date,'%e') as day
                "))
            ->addSelect(DB::raw("
                    count(*) as cnt,
                    count(IF(transaction_type = 1, TRUE, NULL)) as finance_count_for_income,
                    sum(IF(transaction_type = 1, TRUE, NULL)) as finance_sum_for_income,
                    count(IF(transaction_type = 99, TRUE, NULL)) as finance_count_for_expense,
                    sum(IF(transaction_type = 99, TRUE, NULL)) as finance_sum_for_expense,
                    count(IF(transaction_type = 101, TRUE, NULL)) as finance_count_for_deduction,
                    sum(IF(transaction_type = 101, TRUE, NULL)) as finance_sum_for_deduction,
                    count(IF(transaction_type = 111, TRUE, NULL)) as finance_count_for_fine,
                    sum(IF(transaction_type = 111, TRUE, NULL)) as finance_sum_for_fine
                "))
            ->orderBy("order_task_date", "desc");

        $draw  = isset($post_data['draw'])  ? $post_data['draw']  : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start']  : 0;
        $limit = isset($post_data['length']) ? $post_data['length'] : 50;

        $order_list = $query_order->get();
        $fee_list = $query_fee->get();
        $finance_list = $query_finance->get();

        // 转换为键值对的集合
        $keyed1 = $order_list->keyBy('date_day');
        $keyed2 = $fee_list->keyBy('date_day');
        $keyed3 = $finance_list->keyBy('date_day');
//        dd($keyed2->keys());

        // 获取所有唯一键
        $allIds = $keyed1->keys()->merge($keyed2->keys())->merge($keyed3->keys())->unique();
//        dd($allIds);

        // 合并对应元素
        $merged = $allIds->map(function ($id) use ($keyed1, $keyed2, $keyed3) {
            if($keyed3->get($id))
            {
                if($keyed1->get($id) && $keyed2->get($id))
                {
//                return $keyed1->get($id)->merge($keyed2->get($id));
                    return collect(array_merge(
                        $keyed1->get($id)->toArray(),
                        $keyed2->get($id)->toArray(),
                        $keyed3->get($id)->toArray()
                    ));
                }
                else if($keyed1->get($id) && !$keyed2->get($id))
                {
//                    return $keyed1->get($id);
                    return collect(array_merge(
                        $keyed1->get($id)->toArray(),
                        $keyed3->get($id)->toArray()
                    ));
                }
                else if(!$keyed1->get($id) && $keyed2->get($id))
                {
//                    return $keyed2->get($id);
                    return collect(array_merge(
                        $keyed2->get($id)->toArray(),
                        $keyed3->get($id)->toArray()
                    ));
                }
                else
                {
                    return $keyed3->get($id);
                }
            }
            else
            {
                if($keyed1->get($id) && $keyed2->get($id))
                {
//                return $keyed1->get($id)->merge($keyed2->get($id));
                    return collect(array_merge(
                        $keyed1->get($id)->toArray(),
                        $keyed2->get($id)->toArray()
                    ));
                }
                else if($keyed1->get($id) && !$keyed2->get($id))
                {
                    return $keyed1->get($id);
                }
                else if(!$keyed1->get($id) && $keyed2->get($id))
                {
                    return $keyed2->get($id);
                }
//            return array_merge(
//                $keyed1->get($id, [])->toArray(),
//                $keyed2->get($id, [])->toArray()
//            );
            }
        })->sortByDesc('date_day')->values(); // 重新索引为数字键

//        dd($merged->toArray());


        $total = $merged->count();



        $total_data = [];
        $total_data['published_at'] = 0;
        $total_data['date_day'] = '统计';
        $total_data['attendance_client'] = 0;
        $total_data['attendance_project'] = 0;
        $total_data['order_count_for_all'] = 0;
        $total_data['order_sum_for_freight'] = 0;
        $total_data['fee_count_for_expense'] = 0;
        $total_data['fee_sum_for_expense'] = 0;
        $total_data['fee_count_for_deduction'] = 0;
        $total_data['fee_sum_for_deduction'] = 0;
        $total_data['fee_count_for_fine'] = 0;
        $total_data['fee_sum_for_fine'] = 0;

        $total_data['cnt'] = 0;
        $total_data['minutes'] = 0;



        foreach ($merged as $k => $v)
        {
//            $total_data['attendance_client'] += $v['attendance_client'];
//            $total_data['attendance_project'] += $v['attendance_project'];

            $total_data['order_count_for_all'] += $v['order_count_for_all'];
            $total_data['order_sum_for_freight'] += $v['order_sum_for_freight'];

            $total_data['fee_count_for_expense'] += $v['fee_count_for_expense'];
            $total_data['fee_sum_for_expense'] += $v['fee_sum_for_expense'];

            $total_data['fee_count_for_deduction'] += $v['fee_count_for_deduction'];
            $total_data['fee_sum_for_deduction'] += $v['fee_sum_for_deduction'];

            $total_data['fee_count_for_fine'] += $v['fee_count_for_fine'];
            $total_data['fee_sum_for_fine'] += $v['fee_sum_for_fine'];

        }

        $merged[] = $total_data;

//        dd($list->toArray());

        return datatable_response($merged, $draw, $total);
    }
    // 【统计】【车辆】日报
    public function v1_operate__get_statistic_data_of_statistic_project_by_daily_for_order($post_data)
    {
        $this->get_me();
        $me = $this->me;

        $project_id = $post_data['project_id'];

        $this_month = date('Y-m');
        $this_month_start_date = date('Y-m-01'); // 本月开始日期
        $this_month_ended_date = date('Y-m-t'); // 本月结束日期
        $this_month_start_datetime = date('Y-m-01 00:00:00'); // 本月开始时间
        $this_month_ended_datetime = date('Y-m-t 23:59:59'); // 本月结束时间
        $this_month_start_timestamp = strtotime($this_month_start_date); // 本月开始时间戳
        $this_month_ended_timestamp = strtotime($this_month_ended_datetime); // 本月结束时间戳

        $last_month_start_date = date('Y-m-01',strtotime('last month')); // 上月开始时间
        $last_month_ended_date = date('Y-m-t',strtotime('last month')); // 上月开始时间
        $last_month_start_datetime = date('Y-m-01 00:00:00',strtotime('last month')); // 上月开始时间
        $last_month_ended_datetime = date('Y-m-t 23:59:59',strtotime('last month')); // 上月结束时间
        $last_month_start_timestamp = strtotime($last_month_start_date); // 上月开始时间戳
        $last_month_ended_timestamp = strtotime($last_month_ended_datetime); // 上月月结束时间戳




        $the_month  = isset($post_data['time_month']) ? $post_data['time_month']  : date('Y-m');
        $the_month_timestamp = strtotime($the_month);

        $the_month_start_date = date('Y-m-01',$the_month_timestamp); // 指定月份-开始日期
        $the_month_ended_date = date('Y-m-t',$the_month_timestamp); // 指定月份-结束日期
        $the_month_start_datetime = date('Y-m-01 00:00:00',$the_month_timestamp); // 本月开始时间
        $the_month_ended_datetime = date('Y-m-t 23:59:59',$the_month_timestamp); // 本月结束时间
        $the_month_start_timestamp = strtotime($the_month_start_datetime); // 指定月份-开始时间戳
        $the_month_ended_timestamp = strtotime($the_month_ended_datetime); // 指定月份-结束时间戳

        $the_date  = isset($post_data['time_date']) ? $post_data['time_date']  : date('Y-m-d');


        $query_order = WL_Common_Order::select('*')
            ->with([
                'creator',
                'owner'=>function($query) { $query->select('id','username'); },
                'client_er'=>function($query) { $query->select('id','name'); },
                'project_er'=>function($query) { $query->select('id','name'); },
                'car_er'=>function($query) { $query->select('id','name'); },
                'trailer_er'=>function($query) { $query->select('id','name'); },
                'driver_er'=>function($query) { $query->select('id','driver_name','driver_phone'); },
                'copilot_er'=>function($query) { $query->select('id','driver_name','driver_phone'); }
            ])
            ->where('project_id',$project_id)
            ->whereBetween('task_date',[$the_month_start_date,$the_month_ended_date]);

        $total = $query_order->count();
//        (clone $query)->

        $draw  = isset($post_data['draw'])  ? $post_data['draw']  : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start']  : 0;
        $limit = isset($post_data['length']) ? $post_data['length'] : 50;

        $list = $query_order->get();


        $total_data = [];
        $total_data['id'] = '总计';
        $total_data['task_date'] = '--';
        $total_data['client_id'] = 0;
        $total_data['project_id'] = 0;
        $total_data['driver_id'] = 0;
        $total_data['transport_departure_place'] = '--';
        $total_data['transport_destination_place'] = '--';
        $total_data['transport_mileage'] = 0;
        $total_data['transport_time_limitation'] = 0;
        $total_data['freight_amount'] = 0;
        $total_data['financial_expense_total'] = 0.00;
        $total_data['financial_deduction_total'] = 0.00;

        $total_data['cnt'] = 0;
        $total_data['minutes'] = 0;


        foreach ($list as $k => $v)
        {
            $total_data['transport_mileage'] += $v['transport_mileage'];
            $total_data['transport_time_limitation'] += $v['transport_time_limitation'];

            $total_data['freight_amount'] += $v['freight_amount'];
            $total_data['financial_expense_total'] += $v['financial_expense_total'];
            $total_data['financial_deduction_total'] += $v['financial_deduction_total'];
        }
        $list[] = $total_data;
//        dd($list->toArray());

        return datatable_response($list, $draw, $total);
    }
    // 【统计】【车辆】日报
    public function v1_operate__get_statistic_data_of_statistic_project_by_daily_for_fee($post_data)
    {
        $this->get_me();
        $me = $this->me;

        $project_id = $post_data['project_id'];

        $this_month = date('Y-m');
        $this_month_start_date = date('Y-m-01'); // 本月开始日期
        $this_month_ended_date = date('Y-m-t'); // 本月结束日期
        $this_month_start_datetime = date('Y-m-01 00:00:00'); // 本月开始时间
        $this_month_ended_datetime = date('Y-m-t 23:59:59'); // 本月结束时间
        $this_month_start_timestamp = strtotime($this_month_start_date); // 本月开始时间戳
        $this_month_ended_timestamp = strtotime($this_month_ended_datetime); // 本月结束时间戳

        $last_month_start_date = date('Y-m-01',strtotime('last month')); // 上月开始时间
        $last_month_ended_date = date('Y-m-t',strtotime('last month')); // 上月开始时间
        $last_month_start_datetime = date('Y-m-01 00:00:00',strtotime('last month')); // 上月开始时间
        $last_month_ended_datetime = date('Y-m-t 23:59:59',strtotime('last month')); // 上月结束时间
        $last_month_start_timestamp = strtotime($last_month_start_date); // 上月开始时间戳
        $last_month_ended_timestamp = strtotime($last_month_ended_datetime); // 上月月结束时间戳




        $the_month  = isset($post_data['time_month']) ? $post_data['time_month']  : date('Y-m');
        $the_month_timestamp = strtotime($the_month);

        $the_month_start_date = date('Y-m-01',$the_month_timestamp); // 指定月份-开始日期
        $the_month_ended_date = date('Y-m-t',$the_month_timestamp); // 指定月份-结束日期
        $the_month_start_datetime = date('Y-m-01 00:00:00',$the_month_timestamp); // 本月开始时间
        $the_month_ended_datetime = date('Y-m-t 23:59:59',$the_month_timestamp); // 本月结束时间
        $the_month_start_timestamp = strtotime($the_month_start_datetime); // 指定月份-开始时间戳
        $the_month_ended_timestamp = strtotime($the_month_ended_datetime); // 指定月份-结束时间戳

        $the_date  = isset($post_data['time_date']) ? $post_data['time_date']  : date('Y-m-d');


        $query_fee = WL_Common_Fee::select('*')
            ->withTrashed()
            ->with([
                'creator'=>function($query) { $query->select(['id','username']); },
                'client_er'=>function($query) { $query->select(['id','name']); },
                'project_er'=>function($query) { $query->select(['id','name']); },
                'order_er'=>function($query) { $query->select(['*']); },
                'car_er'=>function($query) { $query->select(['id','name']); },
                'driver_er'=>function($query) { $query->select(['id','driver_name']); }
            ])
            ->where('project_id',$project_id)
            ->whereBetween('fee_date',[$the_month_start_date,$the_month_ended_date]);

        $total = $query_fee->count();
//        (clone $query)->

        $draw  = isset($post_data['draw'])  ? $post_data['draw']  : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start']  : 0;
        $limit = isset($post_data['length']) ? $post_data['length'] : 50;

        $list = $query_fee->get();


        $total_data = [];
        $total_data['id'] = '总计';
        $total_data['fee_type'] = '--';
        $total_data['is_completed'] = '--';
        $total_data['fee_datetime'] = '--';
        $total_data['fee_title'] = '--';
        $total_data['fee_amount'] = 0;
        $total_data['client_id'] = '--';
        $total_data['project_id'] = '--';
        $total_data['order_id'] = '--';
        $total_data['car_id'] = '--';
        $total_data['driver_id'] = '--';
        $total_data['remark'] = '--';

        $total_data['cnt'] = 0;
        $total_data['minutes'] = 0;


        foreach ($list as $k => $v)
        {
            $total_data['fee_amount'] += $v['fee_amount'];
        }
        $list[] = $total_data;
//        dd($list->toArray());

        return datatable_response($list, $draw, $total);
    }


    // 【统计】【订单】日报
    public function v1_operate__get_statistic_data_of_statistic_order_by_daily($post_data)
    {
        $this->get_me();
        $me = $this->me;


        $this_month = date('Y-m');
        $this_month_start_date = date('Y-m-01'); // 本月开始日期
        $this_month_ended_date = date('Y-m-t'); // 本月结束日期
        $this_month_start_datetime = date('Y-m-01 00:00:00'); // 本月开始时间
        $this_month_ended_datetime = date('Y-m-t 23:59:59'); // 本月结束时间
        $this_month_start_timestamp = strtotime($this_month_start_date); // 本月开始时间戳
        $this_month_ended_timestamp = strtotime($this_month_ended_datetime); // 本月结束时间戳

        $last_month_start_date = date('Y-m-01',strtotime('last month')); // 上月开始时间
        $last_month_ended_date = date('Y-m-t',strtotime('last month')); // 上月开始时间
        $last_month_start_datetime = date('Y-m-01 00:00:00',strtotime('last month')); // 上月开始时间
        $last_month_ended_datetime = date('Y-m-t 23:59:59',strtotime('last month')); // 上月结束时间
        $last_month_start_timestamp = strtotime($last_month_start_date); // 上月开始时间戳
        $last_month_ended_timestamp = strtotime($last_month_ended_datetime); // 上月月结束时间戳




        $the_month  = isset($post_data['time_month']) ? $post_data['time_month']  : date('Y-m');
        $the_month_timestamp = strtotime($the_month);

        $the_month_start_date = date('Y-m-01',$the_month_timestamp); // 指定月份-开始日期
        $the_month_ended_date = date('Y-m-t',$the_month_timestamp); // 指定月份-结束日期
        $the_month_start_datetime = date('Y-m-01 00:00:00',$the_month_timestamp); // 本月开始时间
        $the_month_ended_datetime = date('Y-m-t 23:59:59',$the_month_timestamp); // 本月结束时间
        $the_month_start_timestamp = strtotime($the_month_start_datetime); // 指定月份-开始时间戳
        $the_month_ended_timestamp = strtotime($the_month_ended_datetime); // 指定月份-结束时间戳

        $the_date  = isset($post_data['time_date']) ? $post_data['time_date']  : date('Y-m-d');


        $query_order = WL_Common_Order::select('task_date')
            ->whereBetween('task_date',[$the_month_start_date,$the_month_ended_date])
            ->groupBy('task_date')
            ->addSelect(DB::raw("
                    DATE_FORMAT(task_date,'%Y-%m-%d') as date_day,
                    DATE_FORMAT(task_date,'%e') as day
                "))
            ->addSelect(DB::raw("
                    COUNT(*) as order_count_for_all,
                    sum(freight_amount) as order_sum_for_freight,
                    COUNT(DISTINCT client_id) AS attendance_client,
                    COUNT(DISTINCT project_id) AS attendance_project
                "))
            ->orderBy("task_date", "desc");

        $total = $query_order->count();


        $query_fee = WL_Common_Fee::select('order_task_date')
            ->whereBetween('order_task_date',[$the_month_start_date,$the_month_ended_date])
            ->groupBy('order_task_date')
            ->addSelect(DB::raw("
                    DATE_FORMAT(order_task_date,'%Y-%m-%d') as date_day,
                    DATE_FORMAT(order_task_date,'%e') as day
                "))
            ->addSelect(DB::raw("
                    count(*) as fee_count_for_all,
                    count(IF(fee_type = 1, TRUE, NULL)) as fee_count_for_income,
                    sum(IF(fee_type = 1, fee_amount, NULL)) as fee_sum_for_income,
                    count(IF(fee_type = 99, TRUE, NULL)) as fee_count_for_expense,
                    sum(IF(fee_type = 99, fee_amount, NULL)) as fee_sum_for_expense,
                    count(IF(fee_type = 101, TRUE, NULL)) as fee_count_for_deduction,
                    sum(IF(fee_type = 101, fee_amount, NULL)) as fee_sum_for_deduction,
                    count(IF(fee_type = 111, TRUE, NULL)) as fee_count_for_fine,
                    sum(IF(fee_type = 111, fee_amount, NULL)) as fee_sum_for_fine
                "))
            ->orderBy("order_task_date", "desc");


        $query_finance = WL_Common_Finance::select('order_task_date')
            ->whereBetween('order_task_date',[$the_month_start_date,$the_month_ended_date])
            ->groupBy('order_task_date')
            ->addSelect(DB::raw("
                    DATE_FORMAT(order_task_date,'%Y-%m-%d') as date_day,
                    DATE_FORMAT(order_task_date,'%e') as day
                "))
            ->addSelect(DB::raw("
                    count(*) as cnt,
                    count(IF(transaction_type = 1, TRUE, NULL)) as finance_count_for_income,
                    sum(IF(transaction_type = 1, TRUE, NULL)) as finance_sum_for_income,
                    count(IF(transaction_type = 99, TRUE, NULL)) as finance_count_for_expense,
                    sum(IF(transaction_type = 99, TRUE, NULL)) as finance_sum_for_expense,
                    count(IF(transaction_type = 101, TRUE, NULL)) as finance_count_for_deduction,
                    sum(IF(transaction_type = 101, TRUE, NULL)) as finance_sum_for_deduction,
                    count(IF(transaction_type = 111, TRUE, NULL)) as finance_count_for_fine,
                    sum(IF(transaction_type = 111, TRUE, NULL)) as finance_sum_for_fine
                "))
            ->orderBy("order_task_date", "desc");

        $draw  = isset($post_data['draw'])  ? $post_data['draw']  : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start']  : 0;
        $limit = isset($post_data['length']) ? $post_data['length'] : 50;

        $order_list = $query_order->get();
        $fee_list = $query_fee->get();
        $finance_list = $query_finance->get();

        // 转换为键值对的集合
        $keyed1 = $order_list->keyBy('date_day');
        $keyed2 = $fee_list->keyBy('date_day');
        $keyed3 = $finance_list->keyBy('date_day');
//        dd($keyed2->keys());

        // 获取所有唯一键
        $allIds = $keyed1->keys()->merge($keyed2->keys())->merge($keyed3->keys())->unique();
//        dd($allIds);

        // 合并对应元素
        $merged = $allIds->map(function ($id) use ($keyed1, $keyed2, $keyed3) {
            if($keyed3->get($id))
            {
                if($keyed1->get($id) && $keyed2->get($id))
                {
//                return $keyed1->get($id)->merge($keyed2->get($id));
                    return collect(array_merge(
                        $keyed1->get($id)->toArray(),
                        $keyed2->get($id)->toArray(),
                        $keyed3->get($id)->toArray()
                    ));
                }
                else if($keyed1->get($id) && !$keyed2->get($id))
                {
//                    return $keyed1->get($id);
                    return collect(array_merge(
                        $keyed1->get($id)->toArray(),
                        $keyed3->get($id)->toArray()
                    ));
                }
                else if(!$keyed1->get($id) && $keyed2->get($id))
                {
//                    return $keyed2->get($id);
                    return collect(array_merge(
                        $keyed2->get($id)->toArray(),
                        $keyed3->get($id)->toArray()
                    ));
                }
                else
                {
                    return $keyed3->get($id);
                }
            }
            else
            {
                if($keyed1->get($id) && $keyed2->get($id))
                {
//                return $keyed1->get($id)->merge($keyed2->get($id));
                    return collect(array_merge(
                        $keyed1->get($id)->toArray(),
                        $keyed2->get($id)->toArray()
                    ));
                }
                else if($keyed1->get($id) && !$keyed2->get($id))
                {
                    return $keyed1->get($id);
                }
                else if(!$keyed1->get($id) && $keyed2->get($id))
                {
                    return $keyed2->get($id);
                }
//            return array_merge(
//                $keyed1->get($id, [])->toArray(),
//                $keyed2->get($id, [])->toArray()
//            );
            }
        })->sortByDesc('date_day')->values(); // 重新索引为数字键

//        dd($merged->toArray());


        $total = $merged->count();



        $total_data = [];
        $total_data['published_at'] = 0;
        $total_data['date_day'] = '统计';
        $total_data['attendance_client'] = 0;
        $total_data['attendance_project'] = 0;
        $total_data['order_count_for_all'] = 0;
        $total_data['order_sum_for_freight'] = 0;
        $total_data['fee_count_for_expense'] = 0;
        $total_data['fee_sum_for_expense'] = 0;
        $total_data['fee_count_for_deduction'] = 0;
        $total_data['fee_sum_for_deduction'] = 0;
        $total_data['fee_count_for_fine'] = 0;
        $total_data['fee_sum_for_fine'] = 0;

        $total_data['cnt'] = 0;
        $total_data['minutes'] = 0;



        foreach ($merged as $k => $v)
        {
//            $total_data['attendance_client'] += $v['attendance_client'];
//            $total_data['attendance_project'] += $v['attendance_project'];

            $total_data['order_count_for_all'] += $v['order_count_for_all'];
            $total_data['order_sum_for_freight'] += $v['order_sum_for_freight'];

            $total_data['fee_count_for_expense'] += $v['fee_count_for_expense'];
            $total_data['fee_sum_for_expense'] += $v['fee_sum_for_expense'];

            $total_data['fee_count_for_deduction'] += $v['fee_count_for_deduction'];
            $total_data['fee_sum_for_deduction'] += $v['fee_sum_for_deduction'];

            $total_data['fee_count_for_fine'] += $v['fee_count_for_fine'];
            $total_data['fee_sum_for_fine'] += $v['fee_sum_for_fine'];

        }

        $merged[] = $total_data;

//        dd($list->toArray());

        return datatable_response($merged, $draw, $total);
    }


    // 【统计】【车辆】日报
    public function v1_operate__get_statistic_data_of_statistic_car_by_daily($post_data)
    {
        $this->get_me();
        $me = $this->me;

        $car_id = $post_data['car_id'];

        $this_month = date('Y-m');
        $this_month_start_date = date('Y-m-01'); // 本月开始日期
        $this_month_ended_date = date('Y-m-t'); // 本月结束日期
        $this_month_start_datetime = date('Y-m-01 00:00:00'); // 本月开始时间
        $this_month_ended_datetime = date('Y-m-t 23:59:59'); // 本月结束时间
        $this_month_start_timestamp = strtotime($this_month_start_date); // 本月开始时间戳
        $this_month_ended_timestamp = strtotime($this_month_ended_datetime); // 本月结束时间戳

        $last_month_start_date = date('Y-m-01',strtotime('last month')); // 上月开始时间
        $last_month_ended_date = date('Y-m-t',strtotime('last month')); // 上月开始时间
        $last_month_start_datetime = date('Y-m-01 00:00:00',strtotime('last month')); // 上月开始时间
        $last_month_ended_datetime = date('Y-m-t 23:59:59',strtotime('last month')); // 上月结束时间
        $last_month_start_timestamp = strtotime($last_month_start_date); // 上月开始时间戳
        $last_month_ended_timestamp = strtotime($last_month_ended_datetime); // 上月月结束时间戳




        $the_month  = isset($post_data['time_month']) ? $post_data['time_month']  : date('Y-m');
        $the_month_timestamp = strtotime($the_month);

        $the_month_start_date = date('Y-m-01',$the_month_timestamp); // 指定月份-开始日期
        $the_month_ended_date = date('Y-m-t',$the_month_timestamp); // 指定月份-结束日期
        $the_month_start_datetime = date('Y-m-01 00:00:00',$the_month_timestamp); // 本月开始时间
        $the_month_ended_datetime = date('Y-m-t 23:59:59',$the_month_timestamp); // 本月结束时间
        $the_month_start_timestamp = strtotime($the_month_start_datetime); // 指定月份-开始时间戳
        $the_month_ended_timestamp = strtotime($the_month_ended_datetime); // 指定月份-结束时间戳

        $the_date  = isset($post_data['time_date']) ? $post_data['time_date']  : date('Y-m-d');


        $query_order = WL_Common_Order::select('task_date')
            ->addSelect(DB::raw("
                    DATE_FORMAT(task_date,'%Y-%m-%d') as date_day,
                    DATE_FORMAT(task_date,'%e') as day
                "))
            ->with([
                'creator',
                'owner'=>function($query) { $query->select('id','username'); },
                'client_er'=>function($query) { $query->select('id','name'); },
                'project_er'=>function($query) { $query->select('id','name'); },
                'car_er'=>function($query) { $query->select('id','name'); },
                'trailer_er'=>function($query) { $query->select('id','name'); },
                'driver_er'=>function($query) { $query->select('id','driver_name','driver_phone'); },
                'copilot_er'=>function($query) { $query->select('id','driver_name','driver_phone'); }
            ])
            ->where('car_id',$car_id)
            ->whereBetween('task_date',[$the_month_start_date,$the_month_ended_date])
            ->orderBy("task_date", "desc");

        $total = $query_order->count();


        $query_fee = WL_Common_Fee::select('order_task_date')
            ->addSelect(DB::raw("
                    DATE_FORMAT(order_task_date,'%Y-%m-%d') as date_day,
                    DATE_FORMAT(order_task_date,'%e') as day
                "))
            ->addSelect(DB::raw("
                    count(*) as fee_count_for_all,
                    count(IF(fee_type = 1, TRUE, NULL)) as fee_count_for_income,
                    sum(IF(fee_type = 1, fee_amount, NULL)) as fee_sum_for_income,
                    count(IF(fee_type = 99, TRUE, NULL)) as fee_count_for_expense,
                    sum(IF(fee_type = 99, fee_amount, NULL)) as fee_sum_for_expense,
                    count(IF(fee_type = 101, TRUE, NULL)) as fee_count_for_deduction,
                    sum(IF(fee_type = 101, fee_amount, NULL)) as fee_sum_for_deduction,
                    count(IF(fee_type = 111, TRUE, NULL)) as fee_count_for_fine,
                    sum(IF(fee_type = 111, fee_amount, NULL)) as fee_sum_for_fine
                "))
            ->where('car_id',$car_id)
            ->whereBetween('order_task_date',[$the_month_start_date,$the_month_ended_date])
            ->groupBy('order_task_date')
            ->orderBy("order_task_date", "desc");


        $query_finance = WL_Common_Finance::select('order_task_date')
            ->addSelect(DB::raw("
                    DATE_FORMAT(order_task_date,'%Y-%m-%d') as date_day,
                    DATE_FORMAT(order_task_date,'%e') as day
                "))
            ->addSelect(DB::raw("
                    count(*) as cnt,
                    count(IF(transaction_type = 1, TRUE, NULL)) as finance_count_for_income,
                    sum(IF(transaction_type = 1, TRUE, NULL)) as finance_sum_for_income,
                    count(IF(transaction_type = 99, TRUE, NULL)) as finance_count_for_expense,
                    sum(IF(transaction_type = 99, TRUE, NULL)) as finance_sum_for_expense,
                    count(IF(transaction_type = 101, TRUE, NULL)) as finance_count_for_deduction,
                    sum(IF(transaction_type = 101, TRUE, NULL)) as finance_sum_for_deduction,
                    count(IF(transaction_type = 111, TRUE, NULL)) as finance_count_for_fine,
                    sum(IF(transaction_type = 111, TRUE, NULL)) as finance_sum_for_fine
                "))
            ->where('car_id',$car_id)
            ->whereBetween('order_task_date',[$the_month_start_date,$the_month_ended_date])
            ->groupBy('order_task_date')
            ->orderBy("order_task_date", "desc");

        $draw  = isset($post_data['draw'])  ? $post_data['draw']  : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start']  : 0;
        $limit = isset($post_data['length']) ? $post_data['length'] : 50;

        $order_list = $query_order->get();
        $fee_list = $query_fee->get();
        $finance_list = $query_finance->get();

        // 转换为键值对的集合
        $keyed1 = $order_list->keyBy('date_day');
        $keyed2 = $fee_list->keyBy('date_day');
        $keyed3 = $finance_list->keyBy('date_day');
//        dd($keyed2->keys());

        // 获取所有唯一键
        $allIds = $keyed1->keys()->merge($keyed2->keys())->merge($keyed3->keys())->unique();
//        dd($allIds);

        // 合并对应元素
        $merged = $allIds->map(function ($id) use ($keyed1, $keyed2, $keyed3) {
            if($keyed3->get($id))
            {
                if($keyed1->get($id) && $keyed2->get($id))
                {
//                return $keyed1->get($id)->merge($keyed2->get($id));
                    return collect(array_merge(
                        $keyed1->get($id)->toArray(),
                        $keyed2->get($id)->toArray(),
                        $keyed3->get($id)->toArray()
                    ));
                }
                else if($keyed1->get($id) && !$keyed2->get($id))
                {
//                    return $keyed1->get($id);
                    return collect(array_merge(
                        $keyed1->get($id)->toArray(),
                        $keyed3->get($id)->toArray()
                    ));
                }
                else if(!$keyed1->get($id) && $keyed2->get($id))
                {
//                    return $keyed2->get($id);
                    return collect(array_merge(
                        $keyed2->get($id)->toArray(),
                        $keyed3->get($id)->toArray()
                    ));
                }
                else
                {
                    return $keyed3->get($id);
                }
            }
            else
            {
                if($keyed1->get($id) && $keyed2->get($id))
                {
//                return $keyed1->get($id)->merge($keyed2->get($id));
                    return collect(array_merge(
                        $keyed1->get($id)->toArray(),
                        $keyed2->get($id)->toArray()
                    ));
                }
                else if($keyed1->get($id) && !$keyed2->get($id))
                {
                    return $keyed1->get($id);
                }
                else if(!$keyed1->get($id) && $keyed2->get($id))
                {
                    return $keyed2->get($id);
                }
//            return array_merge(
//                $keyed1->get($id, [])->toArray(),
//                $keyed2->get($id, [])->toArray()
//            );
            }
        })->sortByDesc('date_day')->values(); // 重新索引为数字键

//        dd($merged->toArray());


        $total = $merged->count();



        $total_data = [];
        $total_data['published_at'] = 0;
        $total_data['date_day'] = '统计';
        $total_data['attendance_client'] = 0;
        $total_data['attendance_project'] = 0;
        $total_data['order_count_for_all'] = 0;
        $total_data['order_sum_for_freight'] = 0;
        $total_data['fee_count_for_expense'] = 0;
        $total_data['fee_sum_for_expense'] = 0;
        $total_data['fee_count_for_deduction'] = 0;
        $total_data['fee_sum_for_deduction'] = 0;
        $total_data['fee_count_for_fine'] = 0;
        $total_data['fee_sum_for_fine'] = 0;

        $total_data['cnt'] = 0;
        $total_data['minutes'] = 0;



        foreach ($merged as $k => $v)
        {
//            $total_data['attendance_client'] += $v['attendance_client'];
//            $total_data['attendance_project'] += $v['attendance_project'];

            $total_data['order_count_for_all'] += $v['order_count_for_all'];
            $total_data['order_sum_for_freight'] += $v['order_sum_for_freight'];

            $total_data['fee_count_for_expense'] += $v['fee_count_for_expense'];
            $total_data['fee_sum_for_expense'] += $v['fee_sum_for_expense'];

            $total_data['fee_count_for_deduction'] += $v['fee_count_for_deduction'];
            $total_data['fee_sum_for_deduction'] += $v['fee_sum_for_deduction'];

            $total_data['fee_count_for_fine'] += $v['fee_count_for_fine'];
            $total_data['fee_sum_for_fine'] += $v['fee_sum_for_fine'];

        }

        $merged[] = $total_data;

//        dd($list->toArray());

        return datatable_response($merged, $draw, $total);
    }
    // 【统计】【车辆】日报
    public function v1_operate__get_statistic_data_of_statistic_car_by_daily_for_order($post_data)
    {
        $this->get_me();
        $me = $this->me;

        $car_id = $post_data['car_id'];

        $this_month = date('Y-m');
        $this_month_start_date = date('Y-m-01'); // 本月开始日期
        $this_month_ended_date = date('Y-m-t'); // 本月结束日期
        $this_month_start_datetime = date('Y-m-01 00:00:00'); // 本月开始时间
        $this_month_ended_datetime = date('Y-m-t 23:59:59'); // 本月结束时间
        $this_month_start_timestamp = strtotime($this_month_start_date); // 本月开始时间戳
        $this_month_ended_timestamp = strtotime($this_month_ended_datetime); // 本月结束时间戳

        $last_month_start_date = date('Y-m-01',strtotime('last month')); // 上月开始时间
        $last_month_ended_date = date('Y-m-t',strtotime('last month')); // 上月开始时间
        $last_month_start_datetime = date('Y-m-01 00:00:00',strtotime('last month')); // 上月开始时间
        $last_month_ended_datetime = date('Y-m-t 23:59:59',strtotime('last month')); // 上月结束时间
        $last_month_start_timestamp = strtotime($last_month_start_date); // 上月开始时间戳
        $last_month_ended_timestamp = strtotime($last_month_ended_datetime); // 上月月结束时间戳




        $the_month  = isset($post_data['time_month']) ? $post_data['time_month']  : date('Y-m');
        $the_month_timestamp = strtotime($the_month);

        $the_month_start_date = date('Y-m-01',$the_month_timestamp); // 指定月份-开始日期
        $the_month_ended_date = date('Y-m-t',$the_month_timestamp); // 指定月份-结束日期
        $the_month_start_datetime = date('Y-m-01 00:00:00',$the_month_timestamp); // 本月开始时间
        $the_month_ended_datetime = date('Y-m-t 23:59:59',$the_month_timestamp); // 本月结束时间
        $the_month_start_timestamp = strtotime($the_month_start_datetime); // 指定月份-开始时间戳
        $the_month_ended_timestamp = strtotime($the_month_ended_datetime); // 指定月份-结束时间戳

        $the_date  = isset($post_data['time_date']) ? $post_data['time_date']  : date('Y-m-d');


        $query_order = WL_Common_Order::select('*')
            ->with([
                'creator',
                'owner'=>function($query) { $query->select('id','username'); },
                'client_er'=>function($query) { $query->select('id','name'); },
                'project_er'=>function($query) { $query->select('id','name'); },
                'car_er'=>function($query) { $query->select('id','name'); },
                'trailer_er'=>function($query) { $query->select('id','name'); },
                'driver_er'=>function($query) { $query->select('id','driver_name','driver_phone'); },
                'copilot_er'=>function($query) { $query->select('id','driver_name','driver_phone'); }
            ])
            ->where('car_id',$car_id)
            ->whereBetween('task_date',[$the_month_start_date,$the_month_ended_date]);

        $total = $query_order->count();
//        (clone $query)->

        $draw  = isset($post_data['draw'])  ? $post_data['draw']  : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start']  : 0;
        $limit = isset($post_data['length']) ? $post_data['length'] : 50;

        $list = $query_order->get();


        $total_data = [];
        $total_data['id'] = '总计';
        $total_data['task_date'] = '--';
        $total_data['client_id'] = 0;
        $total_data['project_id'] = 0;
        $total_data['driver_id'] = 0;
        $total_data['transport_departure_place'] = '--';
        $total_data['transport_destination_place'] = '--';
        $total_data['transport_mileage'] = 0;
        $total_data['transport_time_limitation'] = 0;
        $total_data['freight_amount'] = 0;
        $total_data['financial_expense_total'] = 0.00;
        $total_data['financial_deduction_total'] = 0.00;

        $total_data['cnt'] = 0;
        $total_data['minutes'] = 0;


        foreach ($list as $k => $v)
        {
            $total_data['transport_mileage'] += $v['transport_mileage'];
            $total_data['transport_time_limitation'] += $v['transport_time_limitation'];

            $total_data['freight_amount'] += $v['freight_amount'];
            $total_data['financial_expense_total'] += $v['financial_expense_total'];
            $total_data['financial_deduction_total'] += $v['financial_deduction_total'];
        }
        $list[] = $total_data;
//        dd($list->toArray());

        return datatable_response($list, $draw, $total);
    }
    // 【统计】【车辆】日报
    public function v1_operate__get_statistic_data_of_statistic_car_by_daily_for_fee($post_data)
    {
        $this->get_me();
        $me = $this->me;

        $car_id = $post_data['car_id'];

        $this_month = date('Y-m');
        $this_month_start_date = date('Y-m-01'); // 本月开始日期
        $this_month_ended_date = date('Y-m-t'); // 本月结束日期
        $this_month_start_datetime = date('Y-m-01 00:00:00'); // 本月开始时间
        $this_month_ended_datetime = date('Y-m-t 23:59:59'); // 本月结束时间
        $this_month_start_timestamp = strtotime($this_month_start_date); // 本月开始时间戳
        $this_month_ended_timestamp = strtotime($this_month_ended_datetime); // 本月结束时间戳

        $last_month_start_date = date('Y-m-01',strtotime('last month')); // 上月开始时间
        $last_month_ended_date = date('Y-m-t',strtotime('last month')); // 上月开始时间
        $last_month_start_datetime = date('Y-m-01 00:00:00',strtotime('last month')); // 上月开始时间
        $last_month_ended_datetime = date('Y-m-t 23:59:59',strtotime('last month')); // 上月结束时间
        $last_month_start_timestamp = strtotime($last_month_start_date); // 上月开始时间戳
        $last_month_ended_timestamp = strtotime($last_month_ended_datetime); // 上月月结束时间戳




        $the_month  = isset($post_data['time_month']) ? $post_data['time_month']  : date('Y-m');
        $the_month_timestamp = strtotime($the_month);

        $the_month_start_date = date('Y-m-01',$the_month_timestamp); // 指定月份-开始日期
        $the_month_ended_date = date('Y-m-t',$the_month_timestamp); // 指定月份-结束日期
        $the_month_start_datetime = date('Y-m-01 00:00:00',$the_month_timestamp); // 本月开始时间
        $the_month_ended_datetime = date('Y-m-t 23:59:59',$the_month_timestamp); // 本月结束时间
        $the_month_start_timestamp = strtotime($the_month_start_datetime); // 指定月份-开始时间戳
        $the_month_ended_timestamp = strtotime($the_month_ended_datetime); // 指定月份-结束时间戳

        $the_date  = isset($post_data['time_date']) ? $post_data['time_date']  : date('Y-m-d');


        $query_fee = WL_Common_Fee::select('*')
            ->withTrashed()
            ->with([
                'creator'=>function($query) { $query->select(['id','username']); },
                'client_er'=>function($query) { $query->select(['id','name']); },
                'project_er'=>function($query) { $query->select(['id','name']); },
                'order_er'=>function($query) { $query->select(['*']); },
                'car_er'=>function($query) { $query->select(['id','name']); },
                'driver_er'=>function($query) { $query->select(['id','driver_name']); }
            ])
            ->where('car_id',$car_id)
            ->whereBetween('fee_date',[$the_month_start_date,$the_month_ended_date]);

        $total = $query_fee->count();
//        (clone $query)->

        $draw  = isset($post_data['draw'])  ? $post_data['draw']  : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start']  : 0;
        $limit = isset($post_data['length']) ? $post_data['length'] : 50;

        $list = $query_fee->get();


        $total_data = [];
        $total_data['id'] = '总计';
        $total_data['fee_type'] = '--';
        $total_data['is_completed'] = '--';
        $total_data['fee_datetime'] = '--';
        $total_data['fee_title'] = '--';
        $total_data['fee_amount'] = 0;
        $total_data['client_id'] = '--';
        $total_data['project_id'] = '--';
        $total_data['order_id'] = '--';
        $total_data['car_id'] = '--';
        $total_data['driver_id'] = '--';
        $total_data['remark'] = '--';

        $total_data['cnt'] = 0;
        $total_data['minutes'] = 0;


        foreach ($list as $k => $v)
        {
            $total_data['fee_amount'] += $v['fee_amount'];
        }
        $list[] = $total_data;
//        dd($list->toArray());

        return datatable_response($list, $draw, $total);
    }


    // 【统计】【司机】日报
    public function v1_operate__get_statistic_data_of_statistic_driver_by_daily($post_data)
    {
        $this->get_me();
        $me = $this->me;

        $car_id = $post_data['car_id'];

        $this_month = date('Y-m');
        $this_month_start_date = date('Y-m-01'); // 本月开始日期
        $this_month_ended_date = date('Y-m-t'); // 本月结束日期
        $this_month_start_datetime = date('Y-m-01 00:00:00'); // 本月开始时间
        $this_month_ended_datetime = date('Y-m-t 23:59:59'); // 本月结束时间
        $this_month_start_timestamp = strtotime($this_month_start_date); // 本月开始时间戳
        $this_month_ended_timestamp = strtotime($this_month_ended_datetime); // 本月结束时间戳

        $last_month_start_date = date('Y-m-01',strtotime('last month')); // 上月开始时间
        $last_month_ended_date = date('Y-m-t',strtotime('last month')); // 上月开始时间
        $last_month_start_datetime = date('Y-m-01 00:00:00',strtotime('last month')); // 上月开始时间
        $last_month_ended_datetime = date('Y-m-t 23:59:59',strtotime('last month')); // 上月结束时间
        $last_month_start_timestamp = strtotime($last_month_start_date); // 上月开始时间戳
        $last_month_ended_timestamp = strtotime($last_month_ended_datetime); // 上月月结束时间戳




        $the_month  = isset($post_data['time_month']) ? $post_data['time_month']  : date('Y-m');
        $the_month_timestamp = strtotime($the_month);

        $the_month_start_date = date('Y-m-01',$the_month_timestamp); // 指定月份-开始日期
        $the_month_ended_date = date('Y-m-t',$the_month_timestamp); // 指定月份-结束日期
        $the_month_start_datetime = date('Y-m-01 00:00:00',$the_month_timestamp); // 本月开始时间
        $the_month_ended_datetime = date('Y-m-t 23:59:59',$the_month_timestamp); // 本月结束时间
        $the_month_start_timestamp = strtotime($the_month_start_datetime); // 指定月份-开始时间戳
        $the_month_ended_timestamp = strtotime($the_month_ended_datetime); // 指定月份-结束时间戳

        $the_date  = isset($post_data['time_date']) ? $post_data['time_date']  : date('Y-m-d');


        $query_order = WL_Common_Order::select('task_date')
            ->addSelect(DB::raw("
                    DATE_FORMAT(task_date,'%Y-%m-%d') as date_day,
                    DATE_FORMAT(task_date,'%e') as day
                "))
            ->with([
                'creator',
                'owner'=>function($query) { $query->select('id','username'); },
                'client_er'=>function($query) { $query->select('id','name'); },
                'project_er'=>function($query) { $query->select('id','name'); },
                'car_er'=>function($query) { $query->select('id','name'); },
                'trailer_er'=>function($query) { $query->select('id','name'); },
                'driver_er'=>function($query) { $query->select('id','driver_name','driver_phone'); },
                'copilot_er'=>function($query) { $query->select('id','driver_name','driver_phone'); }
            ])
            ->where('car_id',$car_id)
            ->whereBetween('task_date',[$the_month_start_date,$the_month_ended_date])
            ->orderBy("task_date", "desc");

        $total = $query_order->count();


        $query_fee = WL_Common_Fee::select('order_task_date')
            ->addSelect(DB::raw("
                    DATE_FORMAT(order_task_date,'%Y-%m-%d') as date_day,
                    DATE_FORMAT(order_task_date,'%e') as day
                "))
            ->addSelect(DB::raw("
                    count(*) as fee_count_for_all,
                    count(IF(fee_type = 1, TRUE, NULL)) as fee_count_for_income,
                    sum(IF(fee_type = 1, fee_amount, NULL)) as fee_sum_for_income,
                    count(IF(fee_type = 99, TRUE, NULL)) as fee_count_for_expense,
                    sum(IF(fee_type = 99, fee_amount, NULL)) as fee_sum_for_expense,
                    count(IF(fee_type = 101, TRUE, NULL)) as fee_count_for_deduction,
                    sum(IF(fee_type = 101, fee_amount, NULL)) as fee_sum_for_deduction,
                    count(IF(fee_type = 111, TRUE, NULL)) as fee_count_for_fine,
                    sum(IF(fee_type = 111, fee_amount, NULL)) as fee_sum_for_fine
                "))
            ->where('car_id',$car_id)
            ->whereBetween('order_task_date',[$the_month_start_date,$the_month_ended_date])
            ->groupBy('order_task_date')
            ->orderBy("order_task_date", "desc");


        $query_finance = WL_Common_Finance::select('order_task_date')
            ->addSelect(DB::raw("
                    DATE_FORMAT(order_task_date,'%Y-%m-%d') as date_day,
                    DATE_FORMAT(order_task_date,'%e') as day
                "))
            ->addSelect(DB::raw("
                    count(*) as cnt,
                    count(IF(transaction_type = 1, TRUE, NULL)) as finance_count_for_income,
                    sum(IF(transaction_type = 1, TRUE, NULL)) as finance_sum_for_income,
                    count(IF(transaction_type = 99, TRUE, NULL)) as finance_count_for_expense,
                    sum(IF(transaction_type = 99, TRUE, NULL)) as finance_sum_for_expense,
                    count(IF(transaction_type = 101, TRUE, NULL)) as finance_count_for_deduction,
                    sum(IF(transaction_type = 101, TRUE, NULL)) as finance_sum_for_deduction,
                    count(IF(transaction_type = 111, TRUE, NULL)) as finance_count_for_fine,
                    sum(IF(transaction_type = 111, TRUE, NULL)) as finance_sum_for_fine
                "))
            ->where('car_id',$car_id)
            ->whereBetween('order_task_date',[$the_month_start_date,$the_month_ended_date])
            ->groupBy('order_task_date')
            ->orderBy("order_task_date", "desc");

        $draw  = isset($post_data['draw'])  ? $post_data['draw']  : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start']  : 0;
        $limit = isset($post_data['length']) ? $post_data['length'] : 50;

        $order_list = $query_order->get();
        $fee_list = $query_fee->get();
        $finance_list = $query_finance->get();

        // 转换为键值对的集合
        $keyed1 = $order_list->keyBy('date_day');
        $keyed2 = $fee_list->keyBy('date_day');
        $keyed3 = $finance_list->keyBy('date_day');
//        dd($keyed2->keys());

        // 获取所有唯一键
        $allIds = $keyed1->keys()->merge($keyed2->keys())->merge($keyed3->keys())->unique();
//        dd($allIds);

        // 合并对应元素
        $merged = $allIds->map(function ($id) use ($keyed1, $keyed2, $keyed3) {
            if($keyed3->get($id))
            {
                if($keyed1->get($id) && $keyed2->get($id))
                {
//                return $keyed1->get($id)->merge($keyed2->get($id));
                    return collect(array_merge(
                        $keyed1->get($id)->toArray(),
                        $keyed2->get($id)->toArray(),
                        $keyed3->get($id)->toArray()
                    ));
                }
                else if($keyed1->get($id) && !$keyed2->get($id))
                {
//                    return $keyed1->get($id);
                    return collect(array_merge(
                        $keyed1->get($id)->toArray(),
                        $keyed3->get($id)->toArray()
                    ));
                }
                else if(!$keyed1->get($id) && $keyed2->get($id))
                {
//                    return $keyed2->get($id);
                    return collect(array_merge(
                        $keyed2->get($id)->toArray(),
                        $keyed3->get($id)->toArray()
                    ));
                }
                else
                {
                    return $keyed3->get($id);
                }
            }
            else
            {
                if($keyed1->get($id) && $keyed2->get($id))
                {
//                return $keyed1->get($id)->merge($keyed2->get($id));
                    return collect(array_merge(
                        $keyed1->get($id)->toArray(),
                        $keyed2->get($id)->toArray()
                    ));
                }
                else if($keyed1->get($id) && !$keyed2->get($id))
                {
                    return $keyed1->get($id);
                }
                else if(!$keyed1->get($id) && $keyed2->get($id))
                {
                    return $keyed2->get($id);
                }
//            return array_merge(
//                $keyed1->get($id, [])->toArray(),
//                $keyed2->get($id, [])->toArray()
//            );
            }
        })->sortByDesc('date_day')->values(); // 重新索引为数字键

//        dd($merged->toArray());


        $total = $merged->count();



        $total_data = [];
        $total_data['published_at'] = 0;
        $total_data['date_day'] = '统计';
        $total_data['attendance_client'] = 0;
        $total_data['attendance_project'] = 0;
        $total_data['order_count_for_all'] = 0;
        $total_data['order_sum_for_freight'] = 0;
        $total_data['fee_count_for_expense'] = 0;
        $total_data['fee_sum_for_expense'] = 0;
        $total_data['fee_count_for_deduction'] = 0;
        $total_data['fee_sum_for_deduction'] = 0;
        $total_data['fee_count_for_fine'] = 0;
        $total_data['fee_sum_for_fine'] = 0;

        $total_data['cnt'] = 0;
        $total_data['minutes'] = 0;



        foreach ($merged as $k => $v)
        {
//            $total_data['attendance_client'] += $v['attendance_client'];
//            $total_data['attendance_project'] += $v['attendance_project'];

            $total_data['order_count_for_all'] += $v['order_count_for_all'];
            $total_data['order_sum_for_freight'] += $v['order_sum_for_freight'];

            $total_data['fee_count_for_expense'] += $v['fee_count_for_expense'];
            $total_data['fee_sum_for_expense'] += $v['fee_sum_for_expense'];

            $total_data['fee_count_for_deduction'] += $v['fee_count_for_deduction'];
            $total_data['fee_sum_for_deduction'] += $v['fee_sum_for_deduction'];

            $total_data['fee_count_for_fine'] += $v['fee_count_for_fine'];
            $total_data['fee_sum_for_fine'] += $v['fee_sum_for_fine'];

        }

        $merged[] = $total_data;

//        dd($list->toArray());

        return datatable_response($merged, $draw, $total);
    }
    // 【统计】【司机】日报
    public function v1_operate__get_statistic_data_of_statistic_driver_by_daily_for_order($post_data)
    {
        $this->get_me();
        $me = $this->me;

        $driver_id = $post_data['driver_id'];

        $this_month = date('Y-m');
        $this_month_start_date = date('Y-m-01'); // 本月开始日期
        $this_month_ended_date = date('Y-m-t'); // 本月结束日期
        $this_month_start_datetime = date('Y-m-01 00:00:00'); // 本月开始时间
        $this_month_ended_datetime = date('Y-m-t 23:59:59'); // 本月结束时间
        $this_month_start_timestamp = strtotime($this_month_start_date); // 本月开始时间戳
        $this_month_ended_timestamp = strtotime($this_month_ended_datetime); // 本月结束时间戳

        $last_month_start_date = date('Y-m-01',strtotime('last month')); // 上月开始时间
        $last_month_ended_date = date('Y-m-t',strtotime('last month')); // 上月开始时间
        $last_month_start_datetime = date('Y-m-01 00:00:00',strtotime('last month')); // 上月开始时间
        $last_month_ended_datetime = date('Y-m-t 23:59:59',strtotime('last month')); // 上月结束时间
        $last_month_start_timestamp = strtotime($last_month_start_date); // 上月开始时间戳
        $last_month_ended_timestamp = strtotime($last_month_ended_datetime); // 上月月结束时间戳




        $the_month  = isset($post_data['time_month']) ? $post_data['time_month']  : date('Y-m');
        $the_month_timestamp = strtotime($the_month);

        $the_month_start_date = date('Y-m-01',$the_month_timestamp); // 指定月份-开始日期
        $the_month_ended_date = date('Y-m-t',$the_month_timestamp); // 指定月份-结束日期
        $the_month_start_datetime = date('Y-m-01 00:00:00',$the_month_timestamp); // 本月开始时间
        $the_month_ended_datetime = date('Y-m-t 23:59:59',$the_month_timestamp); // 本月结束时间
        $the_month_start_timestamp = strtotime($the_month_start_datetime); // 指定月份-开始时间戳
        $the_month_ended_timestamp = strtotime($the_month_ended_datetime); // 指定月份-结束时间戳

        $the_date  = isset($post_data['time_date']) ? $post_data['time_date']  : date('Y-m-d');


        $query_order = WL_Common_Order::select('*')
            ->with([
                'creator',
                'owner'=>function($query) { $query->select('id','username'); },
                'client_er'=>function($query) { $query->select('id','name'); },
                'project_er'=>function($query) { $query->select('id','name'); },
                'car_er'=>function($query) { $query->select('id','name'); },
                'trailer_er'=>function($query) { $query->select('id','name'); },
                'driver_er'=>function($query) { $query->select('id','driver_name','driver_phone'); },
                'copilot_er'=>function($query) { $query->select('id','driver_name','driver_phone'); }
            ])
            ->where('driver_id',$driver_id)
            ->whereBetween('task_date',[$the_month_start_date,$the_month_ended_date]);

        $total = $query_order->count();
//        (clone $query)->

        $draw  = isset($post_data['draw'])  ? $post_data['draw']  : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start']  : 0;
        $limit = isset($post_data['length']) ? $post_data['length'] : 50;

        $list = $query_order->get();


        $total_data = [];
        $total_data['id'] = '总计';
        $total_data['task_date'] = '--';
        $total_data['client_id'] = 0;
        $total_data['project_id'] = 0;
        $total_data['driver_id'] = 0;
        $total_data['transport_departure_place'] = '--';
        $total_data['transport_destination_place'] = '--';
        $total_data['transport_mileage'] = 0;
        $total_data['transport_time_limitation'] = 0;
        $total_data['freight_amount'] = 0;
        $total_data['financial_expense_total'] = 0.00;
        $total_data['financial_deduction_total'] = 0.00;

        $total_data['cnt'] = 0;
        $total_data['minutes'] = 0;


        foreach ($list as $k => $v)
        {
            $total_data['transport_mileage'] += $v['transport_mileage'];
            $total_data['transport_time_limitation'] += $v['transport_time_limitation'];

            $total_data['freight_amount'] += $v['freight_amount'];
            $total_data['financial_expense_total'] += $v['financial_expense_total'];
            $total_data['financial_deduction_total'] += $v['financial_deduction_total'];
        }
        $list[] = $total_data;
//        dd($list->toArray());

        return datatable_response($list, $draw, $total);
    }
    // 【统计】【司机】日报
    public function v1_operate__get_statistic_data_of_statistic_driver_by_daily_for_fee($post_data)
    {
        $this->get_me();
        $me = $this->me;

        $driver_id = $post_data['driver_id'];

        $this_month = date('Y-m');
        $this_month_start_date = date('Y-m-01'); // 本月开始日期
        $this_month_ended_date = date('Y-m-t'); // 本月结束日期
        $this_month_start_datetime = date('Y-m-01 00:00:00'); // 本月开始时间
        $this_month_ended_datetime = date('Y-m-t 23:59:59'); // 本月结束时间
        $this_month_start_timestamp = strtotime($this_month_start_date); // 本月开始时间戳
        $this_month_ended_timestamp = strtotime($this_month_ended_datetime); // 本月结束时间戳

        $last_month_start_date = date('Y-m-01',strtotime('last month')); // 上月开始时间
        $last_month_ended_date = date('Y-m-t',strtotime('last month')); // 上月开始时间
        $last_month_start_datetime = date('Y-m-01 00:00:00',strtotime('last month')); // 上月开始时间
        $last_month_ended_datetime = date('Y-m-t 23:59:59',strtotime('last month')); // 上月结束时间
        $last_month_start_timestamp = strtotime($last_month_start_date); // 上月开始时间戳
        $last_month_ended_timestamp = strtotime($last_month_ended_datetime); // 上月月结束时间戳




        $the_month  = isset($post_data['time_month']) ? $post_data['time_month']  : date('Y-m');
        $the_month_timestamp = strtotime($the_month);

        $the_month_start_date = date('Y-m-01',$the_month_timestamp); // 指定月份-开始日期
        $the_month_ended_date = date('Y-m-t',$the_month_timestamp); // 指定月份-结束日期
        $the_month_start_datetime = date('Y-m-01 00:00:00',$the_month_timestamp); // 本月开始时间
        $the_month_ended_datetime = date('Y-m-t 23:59:59',$the_month_timestamp); // 本月结束时间
        $the_month_start_timestamp = strtotime($the_month_start_datetime); // 指定月份-开始时间戳
        $the_month_ended_timestamp = strtotime($the_month_ended_datetime); // 指定月份-结束时间戳

        $the_date  = isset($post_data['time_date']) ? $post_data['time_date']  : date('Y-m-d');


        $query_fee = WL_Common_Fee::select('*')
            ->withTrashed()
            ->with([
                'creator'=>function($query) { $query->select(['id','username']); },
                'client_er'=>function($query) { $query->select(['id','name']); },
                'project_er'=>function($query) { $query->select(['id','name']); },
                'order_er'=>function($query) { $query->select(['*']); },
                'car_er'=>function($query) { $query->select(['id','name']); },
                'driver_er'=>function($query) { $query->select(['id','driver_name']); }
            ])
            ->where('driver_id',$driver_id)
            ->whereBetween('fee_date',[$the_month_start_date,$the_month_ended_date]);

        $total = $query_fee->count();
//        (clone $query)->

        $draw  = isset($post_data['draw'])  ? $post_data['draw']  : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start']  : 0;
        $limit = isset($post_data['length']) ? $post_data['length'] : 50;

        $list = $query_fee->get();


        $total_data = [];
        $total_data['id'] = '总计';
        $total_data['fee_type'] = '--';
        $total_data['is_completed'] = '--';
        $total_data['fee_datetime'] = '--';
        $total_data['fee_title'] = '--';
        $total_data['fee_amount'] = 0;
        $total_data['client_id'] = '--';
        $total_data['project_id'] = '--';
        $total_data['order_id'] = '--';
        $total_data['car_id'] = '--';
        $total_data['driver_id'] = '--';
        $total_data['remark'] = '--';

        $total_data['cnt'] = 0;
        $total_data['minutes'] = 0;


        foreach ($list as $k => $v)
        {
            $total_data['fee_amount'] += $v['fee_amount'];
        }
        $list[] = $total_data;
//        dd($list->toArray());

        return datatable_response($list, $draw, $total);
    }


}