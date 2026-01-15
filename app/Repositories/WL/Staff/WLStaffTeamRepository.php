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


class WLStaffTeamRepository {

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
     * 团队-管理 Department
     */
    // 【团队】返回-列表-数据
    public function v1__team__datatable_list_query($post_data)
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
    public function v1__team__item_get($post_data)
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
    public function v1__team__item_save($post_data)
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
    public function v1__team__item_enable($post_data)
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
    public function v1__team__item_disable($post_data)
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






}