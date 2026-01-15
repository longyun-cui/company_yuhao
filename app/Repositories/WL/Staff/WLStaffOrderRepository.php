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


class WLStaffOrderRepository {

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
     * 工单-管理 Order
     */
    // 【工单】返回-列表-数据
    public function o1__order__datatable_list_query($post_data)
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
    public function o1__order__item_get($post_data)
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
    public function o1__order__item_save($post_data)
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
    public function o1__order__item_publish($post_data)
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
    public function o1__order__item_field_set($post_data)
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
    public function o1__order__item_operation_record_list_datatable_query($post_data)
    {
        $this->get_me();
        $me = $this->me;

        $id  = $post_data["id"];
        $query = WL_Common_Order_Operation_Record::select('*')
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
    public function o1__order__item_fee_record_datatable_list_query($post_data)
    {
        $this->get_me();
        $me = $this->me;

        $id  = $post_data["id"];
        $query = WL_Common_Order_Operation_Record::select('*')
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
    public function o1__order__item_follow_save($post_data)
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

        $order = WL_Common_Order::with([])->withTrashed()->find($operate_id);
        if(!$order) return response_error([],"【订单】不存在警告，请刷新页面重试！");


        $datetime = date('Y-m-d H:i:s');

        $operation_record_data = [];

        // 时间
        $operation_data["follow_date"] = $post_data['follow-datetime'];
        $operation_data["follow_datetime"] = $post_data['follow-datetime'];
        if(!empty($operation_data["follow_datetime"]))
        {
            $operation['field'] = 'follow_datetime';
            $operation['title'] = '时间';
            $operation['before'] = '';
            $operation['after'] = $operation_data["follow_datetime"];
            $operation_record_data[] = $operation;
        }
        // 说明
        $operation_data["follow_description"] = $post_data['follow-description'];
        if(!empty($operation_data["follow_description"]))
        {
            $operation['field'] = 'follow_description';
            $operation['title'] = '说明';
            $operation['before'] = '';
            $operation['after'] = $operation_data["follow_description"];
            $operation_record_data[] = $operation;
        }



        $record_data["operate_category"] = 1;
        $record_data["operate_type"] = 1;
        $record_data["client_id"] = $order->client_id;
        $record_data["project_id"] = $order->project_id;
        $record_data["order_id"] = $operate_id;
        $record_data["creator_id"] = $me->id;
        $record_data["team_id"] = $me->team_id;
        $record_data["department_id"] = $me->department_id;
//        $record_data["company_id"] = $me->company_id;
        $record_data["custom_date"] = $operation_data["follow_datetime"];
        $record_data["custom_datetime"] = $operation_data["follow_datetime"];
        $record_data["content"] = json_encode($operation_record_data);
//        $record_data["custom_datetime"] = $datetime;
//        $record_data["custom_date"] = $datetime;





        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $order_operation_record = new WL_Common_Order_Operation_Record;
            $bool_oor = $order_operation_record->fill($record_data)->save();
            if($bool_oor)
            {
//                $mine->timestamps = false;
                $order->last_operation_datetime = $datetime;
                $order->last_operation_date = $datetime;
                $bool_order = $order->save();
                if(!$bool_order) throw new Exception("WL_Common_Order--update--fail");
            }
            else throw new Exception("WL_Common_Order_Operation_Record--insert--fail");

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
    // 【工单】保存数据
    public function o1__order__item_journey_save($post_data)
    {
        $messages = [
            'operate.required' => 'operate.required.',
            'journey-actual-departure-datetime.required' => '请输入实际出发时间！',
            'journey-actual-arrival-datetime.required' => '请输入实际到达时间！',
//            'name.required' => '请输入联系渠道名称！',
//            'name.unique' => '该部门号已存在！',
        ];
        $v = Validator::make($post_data, [
            'operate' => 'required',
            'journey-actual-departure-datetime' => 'required',
            'journey-actual-arrival-datetime' => 'required',
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

        $order = WL_Common_Order::with([])->withTrashed()->find($operate_id);
        if(!$order) return response_error([],"【订单】不存在警告，请刷新页面重试！");


        $datetime = date('Y-m-d H:i:s');

        $operation_record_data = [];

        // 类型
        $operation_data["journey_type"] = $post_data['journey-type'];
        if(!empty($operation_data["journey_type"]))
        {
            $operation['field'] = 'fee_type';
            $operation['title'] = '类型';
            $operation['before'] = '';
            if($operation_data["journey_type"] == 1)
            {
                $operation['after'] = "运输";
            }
            else if($operation_data["journey_type"] == 99)
            {
                $operation['after'] = "卸货";
            }
            else if($operation_data["journey_type"] == 101)
            {
                $operation['after'] = "空单";
            }
            else
            {
                $operation['after'] = $operation_data["fee_type"];
            }
            $operation_record_data[] = $operation;
        }
        // 应出发时间
        $operate_data["journey_should_departure_datetime"] = $post_data['journey-should-departure-datetime'];
        if(!empty($operate_data["journey_should_departure_datetime"]))
        {
            $operation['field'] = 'journey_should_departure_datetime';
            $operation['title'] = '应出发时间';
            $operation['before'] = '';
            $operation['after'] = $operate_data["journey_should_departure_datetime"];
            $operation_record_data[] = $operation;
        }
        // 应到达时间
        $operate_data["journey_should_arrival_datetime"] = $post_data['journey-should-arrival-datetime'];
        if(!empty($operate_data["journey_should_arrival_datetime"]))
        {
            $operation['field'] = 'journey_should_arrival_datetime';
            $operation['title'] = '应到达时间';
            $operation['before'] = '';
            $operation['after'] = $operate_data["journey_should_arrival_datetime"];
            $operation_record_data[] = $operation;
        }
        // 实际出发时间
        $operate_data["journey_actual_departure_datetime"] = $post_data['journey-actual-departure-datetime'];
        if(!empty($operate_data["journey_actual_departure_datetime"]))
        {
            $operation['field'] = 'journey_actual_departure_datetime';
            $operation['title'] = '实际出发时间';
            $operation['before'] = '';
            $operation['after'] = $operate_data["journey_actual_departure_datetime"];
            $operation_record_data[] = $operation;
        }
        // 应到达时间
        $operate_data["journey_actual_arrival_datetime"] = $post_data['journey-actual-arrival-datetime'];
        if(!empty($operate_data["journey_actual_arrival_datetime"]))
        {
            $operation['field'] = 'journey_actual_arrival_datetime';
            $operation['title'] = '应到达时间';
            $operation['before'] = '';
            $operation['after'] = $operate_data["journey_actual_arrival_datetime"];
            $operation_record_data[] = $operation;
        }
        // 里程
        $operate_data["journey_mileage"] = $post_data['journey-mileage'];
        if(!empty($operate_data["journey_mileage"]))
        {
            $operation['field'] = 'journey_mileage';
            $operation['title'] = '里程';
            $operation['before'] = '';
            $operation['after'] = $operate_data["journey_mileage"];
            $operation_record_data[] = $operation;
        }
        // 时效
        $operate_data["journey_time_limitation"] = $post_data['journey-time-limitation'];
        if(!empty($operate_data["journey_time_limitation"]))
        {
            $operation['field'] = 'journey_time_limitation';
            $operation['title'] = '时效';
            $operation['before'] = '';
            $operation['after'] = $operate_data["journey_time_limitation"];
            $operation_record_data[] = $operation;
        }
//        // 时间
//        $operate_data["journey_date"] = $post_data['journey-datetime'];
//        $operate_data["journey_datetime"] = $post_data['journey-datetime'];
//        if(!empty($operate_data["journey_datetime"]))
//        {
//            $operation['field'] = 'journey_datetime';
//            $operation['title'] = '时间';
//            $operation['before'] = '';
//            $operation['after'] = $operate_data["journey_datetime"];
//            $operation_record_data[] = $operation;
//        }
        // 备注
        $operate_data["journey_description"] = $post_data['journey-description'];
        if(!empty($operate_data["follow_description"]))
        {
            $operation['field'] = 'journey_description';
            $operation['title'] = '备注';
            $operation['before'] = '';
            $operation['after'] = $operate_data["journey_description"];
            $operation_record_data[] = $operation;
        }



        $record_data["operate_category"] = 1;
        $record_data["operate_type"] = 71;
        $record_data["client_id"] = $order->client_id;
        $record_data["project_id"] = $order->project_id;
        $record_data["order_id"] = $operate_id;
        $record_data["creator_id"] = $me->id;
        $record_data["team_id"] = $me->team_id;
        $record_data["department_id"] = $me->department_id;
//        $record_data["company_id"] = $me->company_id;
//        $record_data["custom_date"] = $operate_data["follow_datetime"];
//        $record_data["custom_datetime"] = $operate_data["follow_datetime"];
        $record_data["content"] = json_encode($operation_record_data);
//        $record_data["custom_datetime"] = $datetime;
//        $record_data["custom_date"] = $datetime;





        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $order_operation_record = new WL_Common_Order_Operation_Record;
            $bool_oor = $order_operation_record->fill($record_data)->save();
            if($bool_oor)
            {
//                $mine->timestamps = false;
                $order->last_operation_datetime = $datetime;
                $order->last_operation_date = $datetime;
                $bool_order = $order->save();
                if(!$bool_order) throw new Exception("WL_Common_Order--update--fail");
            }
            else throw new Exception("WL_Common_Order_Operation_Record--insert--fail");

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
    // 【工单】保存数据
    public function o1__order__item_fee_save($post_data)
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

        $operation_record_data = [];


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
        $operation_data["fee_type"] = $post_data['fee-type'];
        if(!empty($operation_data["fee_type"]))
        {
            $operation['field'] = 'fee_type';
            $operation['title'] = '类型';
            $operation['before'] = '';
            if($operation_data["fee_type"] == 1)
            {
                $operation['after'] = "收入";
            }
            else if($operation_data["fee_type"] == 99)
            {
                $operation['after'] = "费用";
            }
            else if($operation_data["fee_type"] == 101)
            {
                $operation['after'] = "订单扣款";
            }
            else if($operation_data["fee_type"] == 111)
            {
                $operation['after'] = "司机罚款";
            }
            else
            {
                $operation['after'] = $operation_data["fee_type"];
            }
            $operation_record_data[] = $operation;
        }
        // 名目
        $operation_data["fee_title"] = $post_data['fee-title'];
        if(!empty($operation_data["fee_title"]))
        {
            $operation['field'] = 'fee_title';
            $operation['title'] = '名目';
            $operation['before'] = '';
            $operation['after'] = $operation_data["fee_title"];
            $operation_record_data[] = $operation;
        }
        // 金额
        $fee_data["fee_amount"] = $post_data['fee-amount'];
        if(!empty($fee_data["fee_amount"]))
        {
            $operation['field'] = 'fee_amount';
            $operation['title'] = '金额';
            $operation['before'] = '';
            $operation['after'] = $fee_data["fee_amount"];
            $operation_record_data[] = $operation;
        }
        // 时间
        $operation_data["fee_date"] = $post_data['fee-datetime'];
        $operation_data["fee_datetime"] = $post_data['fee-datetime'];
        if(!empty($operation_data["fee_datetime"]))
        {
            $operation['field'] = 'fee_datetime';
            $operation['title'] = '时间';
            $operation['before'] = '';
            $operation['after'] = $operation_data["fee_datetime"];
            $operation_record_data[] = $operation;
        }

//        $fee_data["fee_account"] = $post_data['fee-account'];
        // 付款账号
        $operation_data["fee_account_from"] = $post_data['fee-account-from'];
        if(!empty($operation_data["fee_account_to"]))
        {
            $operation['field'] = 'fee_account_from';
            $operation['title'] = '付款账号';
            $operation['before'] = '';
            $operation['after'] = $operation_data["fee_account_from"];
            $operation_record_data[] = $operation;
        }
        // 收款账号
        $operation_data["fee_account_to"] = $post_data['fee-account-to'];
        if(!empty($operation_data["fee_account_to"]))
        {
            $operation['field'] = 'fee_account_to';
            $operation['title'] = '收款账号';
            $operation['before'] = '';
            $operation['after'] = $operation_data["fee_account_to"];
            $operation_record_data[] = $operation;
        }
        // 单号
        $operation_data["fee_reference_no"] = $post_data['fee-reference-no'];
        if(!empty($operation_data["fee_reference_no"]))
        {
            $operation['field'] = 'fee_reference_no';
            $operation['title'] = '费用单号';
            $operation['before'] = '';
            $operation['after'] = $operation_data["fee_reference_no"];
            $operation_record_data[] = $operation;
        }
        // 说明
        $operation_data["fee_description"] = $post_data['fee-description'];
        if(!empty($operation_data["fee_description"]))
        {
            $operation['field'] = 'fee_description';
            $operation['title'] = '说明';
            $operation['before'] = '';
            $operation['after'] = $operation_data["fee_description"];
            $operation_record_data[] = $operation;
        }



        $record_data["operate_category"] = 1;
        $record_data["operate_type"] = 81;
        $record_data["client_id"] = $order->client_id;
        $record_data["project_id"] = $order->project_id;
        $record_data["order_id"] = $operate_id;
        $record_data["creator_id"] = $me->id;
        $record_data["team_id"] = $me->team_id;
        $record_data["department_id"] = $me->department_id;
//        $record_data["company_id"] = $me->company_id;
        $record_data["custom_date"] = $fee_data["fee_datetime"];
        $record_data["custom_datetime"] = $fee_data["fee_datetime"];
        $record_data["content"] = json_encode($operation_record);
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
                $record_data['custom_id'] = $fee->id;

                $order_operation_record = new WL_Common_Order_Operation_Record;
                $bool_op = $order_operation_record->fill($record_data)->save();
                if($bool_op)
                {
                    $fee->order_operation_record_id = $order_operation_record->id;
                    $bool_t_2 = $fee->save();
                    if(!$bool_t_2) throw new Exception("WL_Common_Order_Operation_Record--update--fail");

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
                        $order->financial_income_total += $post_data['fee-amount'];
                    }
                    else if($fee_data["fee_type"] == 99)
                    {
                        $order->financial_expense_total += $post_data['fee-amount'];
                    }
                    else if($fee_data["fee_type"] == 101)
                    {
                        $order->financial_deduction_total += $post_data['fee-amount'];
                    }
                    else if($fee_data["fee_type"] == 111)
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
            return response_success(['order'=>$order]);
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
    public function o1__order__item_trade_save($post_data)
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




}