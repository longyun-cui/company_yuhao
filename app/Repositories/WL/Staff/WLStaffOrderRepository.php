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


class WLStaffOrderRepository {

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
     * 工单-管理 Order
     */
    // 【工单】返回-列表-数据
    public function o1__order__list__datatable_query($post_data)
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
            $department_int = intval($post_data['department']);
            if(!in_array($department_int,[-1,0]))
            {
                $query->where('department_id', $department_int);
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
            $staff_int = intval($post_data['staff']);
            if(!in_array($staff_int,[-1,0]))
            {
                $query->where('creator_id', $staff_int);
            }
        }


        // 客户
        if(isset($post_data['client']))
        {
            $client_int = intval($post_data['client']);
            if(!in_array($client_int,[-1,0]))
            {
                $query->where('client_id', $client_int);
            }
        }

        // 项目
        if(isset($post_data['project']))
        {
            $project_int = intval($post_data['project']);
            if(!in_array($project_int,[-1,0]))
            {
                $query->where('project_id', $project_int);
            }
        }


        // 工单种类 []
        if(isset($post_data['item_category']))
        {
            $item_category_int = intval($post_data['item_category']);
            if(!in_array($item_category_int,[-1,0]))
            {
                $query->where('item_category', $item_category_int);
            }
        }

        // 工单类型 []
        if(isset($post_data['item_type']))
        {
            $item_status_int = intval($post_data['item_status']);
            if(!in_array($item_status_int,[-1]))
            {
                $query->where('item_type', $item_status_int);
            }
        }


        // 创建方式 [人工|导入|api]
        if(isset($post_data['created_type']))
        {
            $created_type_int = intval($post_data['created_type']);
            if(!in_array($created_type_int,[-1]))
            {
                $query->where('created_type', $created_type_int);
            }
        }



        // 是否+V
        if(!empty($post_data['is_wx']))
        {
            $is_wx_int = intval($post_data['is_wx']);
            if(!in_array($is_wx_int,[-1]))
            {
                $query->where('is_wx', $is_wx_int);
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
                'car_er'=>function($query) {
                    $query->select('id','name','driver_id')
                        ->with([
                            'driver_er'=>function($query) { $query->select('id','driver_name','driver_phone'); },
                        ]);
                    },
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
            'transport_departure_place.required' => '请输入出发地！',
            'transport_destination_place.required' => '请输入目的地！',
//            'description.required' => '请输入备注！',
        ];
        $v = Validator::make($post_data, [
            'operate' => 'required',
            'project_id' => 'required|numeric|min:1',
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

        // 判断【客户】是否存在
        if(!empty($post_data['client_id']))
        {
            $client = WL_Common_Client::find($post_data['client_id']);
            if(!$client) return response_error([],"选择【客户】不存在，刷新页面重试！");
        }
        // 判断【项目】是否存在
        if(!empty($post_data['project_id']))
        {
            $project = WL_Common_Project::find($post_data['project_id']);
            if(!$project) return response_error([],"选择【项目】不存在，刷新页面重试！");
        }
        // 判断【车辆】是否存在
        if(!empty($post_data['car_id']))
        {
            $car = WL_Common_Car::find($post_data['car_id']);
            if(!$car) return response_error([],"选择【车】不存在，刷新页面重试！");
        }
        // 判断【车挂】是否存在
        if(!empty($post_data['trailer_id']))
        {
            $trailer = WL_Common_Car::find($post_data['trailer_id']);
            if(!$trailer) return response_error([],"选择【挂】不存在，刷新页面重试！");
        }
        // 判断【主驾】是否存在
        if(!empty($post_data['driver_id']))
        {
            $driver = WL_Common_Driver::find($post_data['driver_id']);
            if(!$driver) return response_error([],"选择【主驾】不存在，刷新页面重试！");
        }
        // 判断【副驾】是否存在
        if(!empty($post_data['copilot_id']))
        {
            $copilot = WL_Common_Driver::find($post_data['copilot_id']);
            if(!$copilot) return response_error([],"选择【副驾】不存在，刷新页面重试！");
        }



        if($post_data['car_owner_type'] == 1)
        {
            if(!empty($driver))
            {
                $post_data['driver_name'] = $driver->driver_name;
                $post_data['driver_phone'] = $driver->driver_phone;
            }
            if(!empty($copilot))
            {
                $post_data['copilot_name'] = $copilot->driver_name;
                $post_data['copilot_phone'] = $copilot->driver_phone;
            }
        }
        else
        {
            $post_data['driver_id'] = 0;
            $post_data['copilot_id'] = 0;

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
            unset($mine_data['operate']);

            $mine_data['company_id'] = $me->company_id;
            $mine_data['department_id'] = $me->department_id;
            $mine_data['team_id'] = $me->team_id;

            if(!empty($project))
            {
                $mine_data['client_id'] = $project->client_id;
                $mine_data['settlement_period'] = $project->settlement_period;
            }

            $transport_time_limitation = $post_data["transport_time_limitation"];
            $mine_data["transport_time_limitation"] = !empty($transport_time_limitation) ? ($transport_time_limitation * 60) : 0;



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
            else throw new Exception("WL_Common_Order--insert--fail");

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





    // 【工单】删除
    public function o1__order__item_delete($post_data)
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
        if($operate != 'order--item-delete') return response_error([],"参数【operate】有误！");
        $item_id = $post_data["item_id"];
        if(intval($item_id) !== 0 && !$item_id) return response_error([],"参数[ID]有误！");

        $this->get_me();
        $me = $this->me;

        // 判断用户操作权限
        if(!in_array($me->user_type,[0,1,9,11])) return response_error([],"你没有操作权限！");

        // 判断对象是否合法
        $mine = WL_Common_Order::withTrashed()->find($item_id);
        if(!$mine) return response_error([],"该【工单】不存在，刷新页面重试！");


        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $mine->timestamps = false;
            $bool = $mine->delete();  // 普通删除
            if(!$bool) throw new Exception("WL_Common_Order--delete--fail");

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
    // 【工单】恢复
    public function o1__order__item_restore($post_data)
    {
        $messages = [
            'operate.required' => 'operate.required.',
            'item_id.required' => 'operate.required.',
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
        if($operate != 'order--item-restore') return response_error([],"参数【operate】有误！");
        $id = $post_data["item_id"];
        if(intval($id) !== 0 && !$id) return response_error([],"参数[ID]有误！");

        $this->get_me();
        $me = $this->me;

        // 判断用户操作权限
        if(!in_array($me->user_type,[0,1,9,11,19])) return response_error([],"你没有操作权限！");

        // 判断对象是否合法
        $mine = WL_Common_Order::withTrashed()->find($id);
        if(!$mine) return response_error([],"该【工单】不存在，刷新页面重试！");

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $mine->timestamps = false;
            $bool = $mine->restore();
            if(!$bool) throw new Exception("WL_Common_Order--restore--fail");

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
    // 【工单】彻底删除
    public function o1__order__item_delete_permanently($post_data)
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
        if($operate != 'order--item-delete-permanently') return response_error([],"参数【operate】有误！");
        $id = $post_data["item_id"];
        if(intval($id) !== 0 && !$id) return response_error([],"参数[ID]有误！");

        $this->get_me();
        $me = $this->me;

        // 判断用户操作权限
        if(!in_array($me->user_type,[0,1,9,11,19])) return response_error([],"你没有操作权限！");

        // 判断对象是否合法
        $mine = WL_Common_Order::withTrashed()->find($id);
        if(!$mine) return response_error([],"该【工单】不存在，刷新页面重试！");

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $mine_copy = $mine;
            $bool = $mine->forceDelete();
            if(!$bool) throw new Exception("WL_Common_Order--delete--fail");

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


    // 【工单】启用
    public function o1__order__item_enable($post_data)
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
        if($operate != 'order--item-enable') return response_error([],"参数【operate】有误！");
        $id = $post_data["item_id"];
        if(intval($id) !== 0 && !$id) return response_error([],"参数[ID]有误！");

        $this->get_me();
        $me = $this->me;

        // 判断用户操作权限
        if(!in_array($me->user_type,[0,1,9,11])) return response_error([],"你没有操作权限！");

        // 判断对象是否合法
        $mine = WL_Common_Order::find($id);
        if(!$mine) return response_error([],"该【工单】不存在，刷新页面重试！");
//        if($mine->client_id != $me->client_id) return response_error([],"归属错误，刷新页面重试！");


        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $mine->item_status = 1;
            $mine->timestamps = false;
            $bool = $mine->save();
            if(!$bool) throw new Exception("WL_Common_Order--update--fail");

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
    // 【工单】禁用
    public function o1__order__item_disable($post_data)
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
        if($operate != 'order--item-disable') return response_error([],"参数【operate】有误！");
        $id = $post_data["item_id"];
        if(intval($id) !== 0 && !$id) return response_error([],"参数[ID]有误！");

        $this->get_me();
        $me = $this->me;

        // 判断用户操作权限
        if(!in_array($me->user_type,[0,1,9,11])) return response_error([],"你没有操作权限！");

        // 判断对象是否合法
        $mine = WL_Common_Order::find($id);
        if(!$mine) return response_error([],"该【工单】不存在，刷新页面重试！");
//        if($mine->client_id != $me->client_id) return response_error([],"归属错误，刷新页面重试！");


        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $mine->item_status = 9;
            $mine->timestamps = false;
            $bool = $mine->save();
            if(!$bool) throw new Exception("WL_Common_Order--update--fail");

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


        $operate = $post_data["operate"];
        if($operate != 'order--item-publish') return response_error([],"参数【operate】有误！");
        $id = $post_data["item_id"];
        if(intval($id) !== 0 && !$id) return response_error([],"参数[ID]有误！");

        $this->get_me();
        $me = $this->me;

        // 判断用户操作权限
        if(!in_array($me->user_type,[0,1,9,11])) return response_error([],"你没有操作权限！");

        // 判断对象是否合法
        $mine = WL_Common_Order::find($id);
        if(!$mine) return response_error([],"该【工单】不存在，刷新页面重试！");
        if(in_array($me->user_type,[88]) && $mine->creator_id != $me->id) return response_error([],"该内容不是你的，你不能操作！");
        if($mine->is_published != 0)
        {
            return response_error([],"该【工单】已经发布过了，不要重复发布，刷新页面看下！");
        }


        $datetime = date('Y-m-d H:i:s');
        $date = date("Y-m-d");
        $time = time();


        $operation_record_data = [];

        $record_data["operate_category"] = 1;
        $record_data["operate_type"] = 11;
        $record_data["client_id"] = $mine->client_id;
        $record_data["project_id"] = $mine->project_id;
        $record_data["order_id"] = $id;
        $record_data["creator_id"] = $me->id;
        $record_data["team_id"] = $me->team_id;
        $record_data["department_id"] = $me->department_id;
        $record_data["company_id"] = $me->company_id;
//        $record_data["custom_date"] = $fee_data["fee_datetime"];
//        $record_data["custom_datetime"] = $fee_data["fee_datetime"];

        $operation = [];
        $operation['operation'] = 'item.publish';
        $operation['field'] = 'is_published';
        $operation['title'] = '操作';
        $operation['before'] = '';
        $operation['after'] = '发布';
        $operation_record_data[] = $operation;

        $record_data["content"] = json_encode($operation_record_data);



        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $mine->is_published = 1;
            $mine->published_at = time();
            $mine->published_date = $date;
            $bool = $mine->save();
            if(!$bool) throw new Exception("WL_Common_Order--update--fail");
            else
            {
                $order_operation_record = new WL_Common_Order_Operation_Record;
                $bool_op = $order_operation_record->fill($record_data)->save();
                if($bool_op)
                {
                    $mine->last_operation_datetime = $datetime;
                    $mine->last_operation_date = $datetime;
                    $bool_order = $mine->save();
                    if(!$bool_order) throw new Exception("WL_Common_Order--update--fail");
                }
                else throw new Exception("WL_Common_Order_Operation_Record--insert--fail");
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




    // 【工单】【操作记录】返回-列表-数据
    public function o1__order__item_operation_record_list__datatable_query($post_data)
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
    // 【工单】【行程记录】返回-列表-数据
    public function o1__order__item_journey_record_list__datatable_query($post_data)
    {
        $this->get_me();
        $me = $this->me;

        $id  = $post_data["id"];
        $query = WL_Common_Transport_Journey::select('*')
            ->with([
                'creator'=>function($query) { $query->select(['id','username','true_name']); },
            ])
//            ->where('operate_type',88)
            ->where('order_id',$id);
//            ->where(['record_object'=>21,'operate_object'=>61,'item_id'=>$id]);

        if(!empty($post_data['name'])) $query->where('name', 'like', "%{$post_data['name']}%");


        $total = $query->count();

        $draw  = isset($post_data['draw'])  ? $post_data['draw'] : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start'] : 0;
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
    public function o1__order__item_fee_record_list__datatable_query($post_data)
    {
        $this->get_me();
        $me = $this->me;

        $id  = $post_data["id"];
        $query = WL_Common_Fee::select('*')
            ->with([
                'creator'=>function($query) { $query->select(['id','username','true_name']); },
            ])
//            ->where('operate_type',88)
            ->where('order_id',$id);
//            ->where(['record_object'=>21,'operate_object'=>61,'item_id'=>$id]);

        if(!empty($post_data['name'])) $query->where('name', 'like', "%{$post_data['name']}%");


        $total = $query->count();

        $draw  = isset($post_data['draw'])  ? $post_data['draw'] : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start'] : 0;
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

        // 操作
        if(true)
        {
            $operation = [];
            $operation['operation'] = 'item.follow';
            $operation['field'] = '';
            $operation['title'] = '操作';
            $operation['before'] = '';
            $operation['after'] = '跟进';
            $operation_record_data[] = $operation;
        }
        // 时间
        $operation_data["follow_date"] = $post_data['follow-datetime'];
        $operation_data["follow_datetime"] = $post_data['follow-datetime'];
        if(!empty($operation_data["follow_datetime"]))
        {
            $operation = [];
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
            $operation = [];
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
        $journey_type = $post_data['journey-type'];
        if(!empty($journey_type))
        {
            $operation['field'] = 'journey_type';
            $operation['title'] = '类型';
            $operation['before'] = '';
            if($journey_type == 1)
            {
                $operation['after'] = "运输";
            }
            else if($journey_type == 99)
            {
                $operation['after'] = "卸货";
            }
            else if($journey_type == 101)
            {
                $operation['after'] = "空单";
            }
            else
            {
                $operation['after'] = $journey_type;
            }
            $operation_record_data[] = $operation;
        }
        // 出发地
        $journey_departure_place = $post_data['journey-departure-place'];
        if(!empty($journey_departure_place))
        {
            $operation['field'] = 'journey_departure_place';
            $operation['title'] = '出发地';
            $operation['before'] = '';
            $operation['after'] = $journey_departure_place;
            $operation_record_data[] = $operation;
        }
        // 经停地
        $journey_stopover_place = $post_data['journey-stopover-place'];
        if(!empty($journey_stopover_place))
        {
            $operation['field'] = 'journey_stopover_place';
            $operation['title'] = '经停地';
            $operation['before'] = '';
            $operation['after'] = $journey_stopover_place;
            $operation_record_data[] = $operation;
        }
        // 目的地
        $journey_destination_place = $post_data['journey-destination-place'];
        if(!empty($journey_destination_place))
        {
            $operation['field'] = 'journey_destination_place';
            $operation['title'] = '目的地';
            $operation['before'] = '';
            $operation['after'] = $journey_destination_place;
            $operation_record_data[] = $operation;
        }
        // 应出发时间
        $journey_should_departure_datetime = $post_data['journey-should-departure-datetime'];
        if(!empty($journey_should_departure_datetime))
        {
            $operation['field'] = 'journey_should_departure_datetime';
            $operation['title'] = '应出发时间';
            $operation['before'] = '';
            $operation['after'] = $journey_should_departure_datetime;
            $operation_record_data[] = $operation;
        }
        // 应到达时间
        $journey_should_arrival_datetime = $post_data['journey-should-arrival-datetime'];
        if(!empty($journey_should_arrival_datetime))
        {
            $operation['field'] = 'journey_should_arrival_datetime';
            $operation['title'] = '应到达时间';
            $operation['before'] = '';
            $operation['after'] = $journey_should_arrival_datetime;
            $operation_record_data[] = $operation;
        }
        // 实际出发时间
        $journey_actual_departure_datetime = $post_data['journey-actual-departure-datetime'];
        if(!empty($journey_actual_departure_datetime))
        {
            $operation['field'] = 'journey_actual_departure_datetime';
            $operation['title'] = '实际出发时间';
            $operation['before'] = '';
            $operation['after'] = $journey_actual_departure_datetime;
            $operation_record_data[] = $operation;
        }
        // 应到达时间
        $journey_actual_arrival_datetime = $post_data['journey-actual-arrival-datetime'];
        if(!empty($journey_actual_arrival_datetime))
        {
            $operation['field'] = 'journey_actual_arrival_datetime';
            $operation['title'] = '应到达时间';
            $operation['before'] = '';
            $operation['after'] = $journey_actual_arrival_datetime;
            $operation_record_data[] = $operation;
        }
        // 距离
        $journey_distance = $post_data['journey-distance'];
        if(!empty($journey_distance))
        {
            $operation['field'] = 'journey_distance';
            $operation['title'] = '距离';
            $operation['before'] = '';
            $operation['after'] = $journey_distance;
            $operation_record_data[] = $operation;
        }
        // 时效
        $journey_time_limitation = $post_data['journey-time-limitation'];
        if(!empty($journey_time_limitation))
        {
            $operation['field'] = 'journey_time_limitation';
            $operation['title'] = '时效';
            $operation['before'] = '';
            $operation['after'] = $journey_time_limitation;
            $operation_record_data[] = $operation;
        }
        // 实际里程
        $journey_actual_mileage = $post_data['journey-actual-mileage'];
        if(!empty($journey_actual_mileage))
        {
            $operation['field'] = 'journey_actual_mileage';
            $operation['title'] = '实际里程';
            $operation['before'] = '';
            $operation['after'] = $journey_actual_mileage;
            $operation_record_data[] = $operation;
        }
//        // 时间
//        $journey_date = $post_data['journey-datetime'];
//        $journey_datetime = $post_data['journey-datetime'];
//        if(!empty($journey_datetime))
//        {
//            $operation['field'] = 'journey_datetime';
//            $operation['title'] = '时间';
//            $operation['before'] = '';
//            $operation['after'] = $journey_datetime;
//            $operation_record_data[] = $operation;
//        }
        // 备注
        $journey_description = $post_data['journey-description'];
        if(!empty($journey_description))
        {
            $operation['field'] = 'journey_description';
            $operation['title'] = '备注';
            $operation['before'] = '';
            $operation['after'] = $journey_description;
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
        $record_data["company_id"] = $me->company_id;
//        $record_data["custom_date"] = $journey_date;
//        $record_data["custom_datetime"] = $journey_datetime;
        $record_data["content"] = json_encode($operation_record_data);
//        $record_data["custom_datetime"] = $datetime;
//        $record_data["custom_date"] = $datetime;




        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $journey_data["journey_category"] = 1;
            $journey_data["journey_type"] = $journey_type;
            $journey_data["client_id"] = $order->client_id;
            $journey_data["project_id"] = $order->project_id;
            $journey_data["order_id"] = $operate_id;
            $journey_data["order_task_date"] = $order->task_date;
            $journey_data["car_id"] = $order->car_id;
            $journey_data["driver_id"] = $order->driver_id;
            $journey_data["creator_id"] = $me->id;
            $journey_data["department_id"] = $me->department_id;
            $journey_data["team_id"] = $me->team_id;

            $journey_data["transport_departure_place"] = $journey_departure_place;
            $journey_data["transport_stopover_place"] = $journey_stopover_place;
            $journey_data["transport_destination_place"] = $journey_destination_place;
            $journey_data["transport_distance"] = !empty($journey_distance) ? $journey_distance : 0;
            $journey_data["transport_time_limitation"] = !empty($journey_time_limitation) ? ($journey_time_limitation * 60) : 0;
            $journey_data["transport_actual_mileage"] = !empty($journey_actual_mileage) ? $journey_actual_mileage : 0;
            $journey_data["should_departure_datetime"] = $journey_should_departure_datetime;
            $journey_data["should_arrival_datetime"] = $journey_should_arrival_datetime;
            $journey_data["actual_departure_datetime"] = $journey_actual_departure_datetime;
            $journey_data["actual_arrival_datetime"] = $journey_actual_arrival_datetime;
            $journey_data["description"] = $journey_description;

            $journey = new WL_Common_Transport_Journey();
            $bool_journey = $journey->fill($journey_data)->save();
            if($bool_journey)
            {
                $record_data['custom_id'] = $journey->id;

                $order_operation_record = new WL_Common_Order_Operation_Record;
                $bool_oor = $order_operation_record->fill($record_data)->save();
                if($bool_oor)
                {
                    $journey->order_operation_record_id = $order_operation_record->id;
                    $bool_journey_2 = $journey->save();
                    if(!$bool_journey_2) throw new Exception("WL_Common_Transport_Journey--update--fail");

                    $order = WL_Common_Order::lockForUpdate()->find($operate_id);

                    $order->transport_actual_mileage += $journey_actual_mileage;

                    $order->last_operation_datetime = $datetime;
                    $order->last_operation_date = $datetime;
                    $bool_order = $order->save();
                    if(!$bool_order) throw new Exception("WL_Common_Order--update--fail");
                }
                else throw new Exception("WL_Common_Order_Operation_Record--insert--fail");
            }
            else throw new Exception("WL_Common_Transport_Journey--insert--fail");



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

        $timestamp = time();
        $datetime = date('Y-m-d H:i:s', $timestamp);


        $operation_record_data = [];


        // 操作
        if(true)
        {
            $operation = [];
            $operation['operation'] = 'item.fee';
            $operation['field'] = '';
            $operation['title'] = '操作';
            $operation['before'] = '';
            $operation['after'] = '费用记录';
            $operation_record_data[] = $operation;
        }
        // 类型
        $fee_type = $post_data['fee-type'];
        if(!empty($fee_type))
        {
            $operation = [];
            $operation['field'] = 'fee_type';
            $operation['title'] = '类型';
            $operation['before'] = '';
            if($fee_type == 1)
            {
                $operation['after'] = "收入";
            }
            else if($fee_type == 99)
            {
                $operation['after'] = "费用";
            }
            else if($fee_type == 101)
            {
                $operation['after'] = "订单扣款";
            }
            else if($fee_type == 111)
            {
                $operation['after'] = "司机罚款";
            }
            else
            {
                $operation['after'] = $fee_type;
            }
            $operation_record_data[] = $operation;
        }
        // 时间
        $fee_datetime = $post_data['fee-datetime'];
        if(!empty($fee_datetime))
        {
            $operation = [];
            $operation['field'] = 'fee_datetime';
            $operation['title'] = '时间';
            $operation['before'] = '';
            $operation['after'] = $fee_datetime;
            $operation_record_data[] = $operation;
        }
        // 金额
        $fee_amount = $post_data['fee-amount'];
        if(!empty($fee_amount))
        {
            $operation = [];
            $operation['field'] = 'fee_amount';
            $operation['title'] = '金额';
            $operation['before'] = '';
            $operation['after'] = $fee_amount;
            $operation_record_data[] = $operation;
        }
        // 名目
        $fee_title = $post_data['fee-title'];
        if(!empty($fee_title))
        {
            $operation = [];
            $operation['field'] = 'fee_title';
            $operation['title'] = '名目';
            $operation['before'] = '';
            $operation['after'] = $fee_title;
            $operation_record_data[] = $operation;
        }
        // 说明
        $fee_description = $post_data['fee-description'];
        if(!empty($fee_description))
        {
            $operation = [];
            $operation['field'] = 'fee_description';
            $operation['title'] = '说明';
            $operation['before'] = '';
            $operation['after'] = $fee_description;
            $operation_record_data[] = $operation;
        }


        // 记录类型
        $fee_record_type = $post_data['fee-record-type'];
        if(!empty($fee_record_type))
        {
            $operation = [];
            $operation['field'] = 'fee_record_type';
            $operation['title'] = '记录类型';
            $operation['before'] = '';
            if($fee_record_type == 1)
            {
                $operation['after'] = "普通记录";
            }
            else if($fee_record_type == 81)
            {
                $operation['after'] = "财务入账";
            }
            else if($fee_record_type == 41)
            {
                $operation['after'] = "代收";
            }
            else if($fee_record_type == 49)
            {
                $operation['after'] = "垫付";
            }
            else
            {
                $operation['after'] = $fee_record_type;
            }
            $operation_record_data[] = $operation;
        }

        // 当记录类型为[财务入账时]
        if(in_array($fee_type,[1,99]) && $fee_record_type == 81)
        {
            // 交易时间
            $transaction_datetime = $post_data['fee-transaction-datetime'];
            if(!empty($transaction_datetime))
            {
                $operation = [];
                $operation['field'] = 'transaction_datetime';
                $operation['title'] = '交易时间';
                $operation['before'] = '';
                $operation['after'] = $transaction_datetime;
                $operation_record_data[] = $operation;
            }
            // 付款方式
            $transaction_payment_method = $post_data['fee-transaction-payment-method'];
            if(!empty($transaction_payment_method))
            {
                $operation = [];
                $operation['field'] = 'fee_payment_method';
                $operation['title'] = '付款方式';
                $operation['before'] = '';
                $operation['after'] = $transaction_payment_method;
                $operation_record_data[] = $operation;
            }
            // 交易账号
//            $transaction_account = $post_data['fee-transaction-account'];
            // 付款账号
            $transaction_account_from = $post_data['fee-transaction-account-from'];
            if(!empty($transaction_account_from))
            {
                $operation = [];
                $operation['field'] = 'transaction_account_from';
                $operation['title'] = '付款账号';
                $operation['before'] = '';
                $operation['after'] = $transaction_account_from;
                $operation_record_data[] = $operation;
            }
            // 收款账号
            $transaction_account_to = $post_data['fee-transaction-account-to'];
            if(!empty($transaction_account_to))
            {
                $operation['field'] = 'transaction_account_to';
                $operation['title'] = '收款账号';
                $operation['before'] = '';
                $operation['after'] = $transaction_account_to;
                $operation_record_data[] = $operation;
            }
            // 交易单号
            $transaction_reference_no = $post_data['fee-transaction-reference-no'];
            if(!empty($transaction_reference_no))
            {
                $operation = [];
                $operation['field'] = 'transaction_reference_no';
                $operation['title'] = '交易单号';
                $operation['before'] = '';
                $operation['after'] = $transaction_reference_no;
                $operation_record_data[] = $operation;
            }
            // 交易说明
            $transaction_description = $post_data['fee-transaction-description'];
            if(!empty($transaction_description))
            {
                $operation = [];
                $operation['field'] = 'transaction_description';
                $operation['title'] = '交易说明';
                $operation['before'] = '';
                $operation['after'] = $transaction_description;
                $operation_record_data[] = $operation;
            }
        }


        $record_data["operate_category"] = 81;
        $record_data["operate_type"] = 1;
        $record_data["client_id"] = $order->client_id;
        $record_data["project_id"] = $order->project_id;
        $record_data["order_id"] = $operate_id;
        $record_data["creator_id"] = $me->id;
        $record_data["company_id"] = $me->company_id;
        $record_data["department_id"] = $me->department_id;
        $record_data["team_id"] = $me->team_id;
        $record_data["custom_date"] = $fee_datetime;
        $record_data["custom_datetime"] = $fee_datetime;
        $record_data["content"] = json_encode($operation_record_data);


        // 费用记录
//        $fee_data = $operation_data;
        $fee_data["fee_category"] = 1;
        $fee_data["fee_type"] = $fee_type;
        $fee_data["fee_date"] = $fee_datetime;
        $fee_data["fee_datetime"] = $fee_datetime;
        $fee_data["fee_amount"] = $fee_amount;
        $fee_data["fee_title"] = $fee_title;
        $fee_data["fee_description"] = $fee_description;

        $fee_data["client_id"] = $order->client_id;
        $fee_data["project_id"] = $order->project_id;
        $fee_data["order_id"] = $operate_id;
        $fee_data["order_task_date"] = $order->task_date;
        $fee_data["car_id"] = $order->car_id;
        $fee_data["driver_id"] = $order->driver_id;
        $fee_data["creator_id"] = $me->id;
        $fee_data["company_id"] = $me->company_id;
        $fee_data["department_id"] = $me->department_id;
        $fee_data["team_id"] = $me->team_id;
        if(in_array($fee_type,[1,99]) && $fee_record_type == 81)
        {
            $record_data["operate_type"] = 88;

            $fee_data["is_recorded"] = 1;
            $fee_data["recorder_id"] = $me->id;
            $fee_data["recorded_date"] = $datetime;
            $fee_data["recorded_datetime"] = $datetime;
        }


        // 财务记录
        if(in_array($fee_type,[1,99]) && $fee_record_type == 81)
        {
            $finance_data["transaction_category"] = 1;
            $finance_data["transaction_type"] = $fee_type;
            $finance_data["transaction_date"] = $transaction_datetime;
            $finance_data["transaction_datetime"] = $transaction_datetime;
            $finance_data["transaction_amount"] = $fee_amount;
            $finance_data["transaction_title"] = $fee_title;
            $finance_data["transaction_description"] = $transaction_description;
            $finance_data["transaction_payment_method"] = $transaction_payment_method;
            $finance_data["transaction_account_from"] = $transaction_account_from;
            $finance_data["transaction_account_to"] = $transaction_account_to;
            $finance_data["transaction_reference_no"] = $transaction_reference_no;

            $finance_data["client_id"] = $order->client_id;
            $finance_data["project_id"] = $order->project_id;
            $finance_data["order_id"] = $operate_id;
            $finance_data["order_task_date"] = $order->task_date;
            $finance_data["car_id"] = $order->car_id;
            $finance_data["driver_id"] = $order->driver_id;
            $finance_data["creator_id"] = $me->id;
            $finance_data["company_id"] = $me->company_id;
            $finance_data["department_id"] = $me->department_id;
            $finance_data["team_id"] = $me->team_id;
        }


        // 启动数据库事务
        DB::beginTransaction();
        try
        {



            $fee = new WL_Common_Fee;
            $bool_fee = $fee->fill($fee_data)->save();
            if($bool_fee)
            {
                $record_data["fee_id"] = $fee->id;

                // 财务记录
                if(in_array($fee_type,[1,99]) && $fee_record_type == 81)
                {
                    $finance = new WL_Common_Finance;
                    $finance_data["fee_id"] = $fee->id;
                    $bool_finance = $finance->fill($finance_data)->save();
                    if($bool_finance)
                    {
                        $record_data["finance_id"] = $finance->id;

                        $fee->finance_id = $finance->id;
                        $bool_fee_2 = $fee->save();
                        if(!$bool_fee_2) throw new Exception("WL_Common_Fee--update--fail");
                    }
                }

                $order_operation_record = new WL_Common_Order_Operation_Record;
                $bool_oor = $order_operation_record->fill($record_data)->save();
                if($bool_oor)
                {
                    $fee->order_operation_record_id = $order_operation_record->id;
                    $bool_fee_3 = $fee->save();
                    if(!$bool_fee_3) throw new Exception("WL_Common_Fee--update--fail");


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