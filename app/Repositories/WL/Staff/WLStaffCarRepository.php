<?php
namespace App\Repositories\WL\Staff;

use App\Models\WL\Common\WL_Common_Car;
use App\Models\WL\Staff\WL_Staff_Record_Operation;

use App\Repositories\Common\CommonRepository;

use Response, Auth, Validator, DB, Exception, Cache, Blade, Carbon;
use QrCode, Excel;


class WLStaffCarRepository {

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
    public function o1__car__list__datatable_query($post_data)
    {
        $this->get_me();
        $me = $this->me;

        $query = WL_Common_Car::withTrashed()->select('*')
            ->with([
                'creator'=>function ($query) { $query->select('id','name'); },
                'motorcade_er'=>function ($query) { $query->select('id','name'); },
                'trailer_er'=>function ($query) { $query->select('id','name','sub_name'); },
                'driver_er'=>function ($query) { $query->select('id','driver_name','driver_phone','copilot_name','copilot_phone'); },
                'copilot_er'=>function ($query) { $query->select('id','driver_name','driver_phone','copilot_name','copilot_phone'); },
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
            $item_status_int = intval($post_data['item_status']);
            if(!in_array($item_status_int,[-1,0]))
            {
                $query->where('item_status', $item_status_int);
            }
        }
        else
        {
//            $query->where('item_status', 1);
        }


        // 车辆种类
        if(!empty($post_data['car_category']))
        {
            $car_category_int = intval($post_data['car_category']);
            if(!in_array($car_category_int,[-1,0]))
            {
                $query->where('car_category', $car_category_int);
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


    // 【车辆】导入-数据
    public function o1__car__import__save($post_data)
    {
        $messages = [
            'operate.required' => 'operate.required',
            'car_type.required' => '请选择导入类型！',
        ];
        $v = Validator::make($post_data, [
            'operate' => 'required',
            'car_type' => 'required',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }

        $this->get_me();
        $me = $this->me;

        if(!in_array($me->staff_position,[0,1,9])) return response_error([],"你没有操作权限！");

        $car_category = $post_data["car_category"];
        $car_type = $post_data["car_type"];


        // 附件
        if(!empty($post_data["upload-file"]))
        {

//            $result = upload_storage($post_data["attachment"]);
//            $result = upload_storage($post_data["attachment"], null, null, 'assign');
            $result = upload_file_storage($post_data["upload-file"],null,'wl/unique/attachment','');
            if($result["result"])
            {
//                $mine->attachment_name = $result["name"];
//                $mine->attachment_src = $result["local"];
//                $mine->save();
            }
            else throw new Exception("file--upload--fail");
        }

        $upload_file = storage_resource_path($result["local"]);

        $data = Excel::load($upload_file, function($reader) {

//            $reader->takeColumns(50);
            $reader->limitColumns(30);

//            $reader->takeRows(100);
            $reader->limitRows(200);

//            $reader->ignoreEmpty();

//            $data = $reader->all();
//            $data = $reader->toArray();

        })->get();
        $data = $data->toArray();


        $item_data = [];

        foreach($data as $key => $value)
        {
            $temp_date = [];
//            $temp_date['id'] = $key;

            $car_number = !empty($value['car_number']) ? (int)trim($value['car_number']) : 0;
            $name = !empty($value['name']) ? trim($value['name']) : null;
            $pre_name = !empty($value['pre_name']) ? trim($value['pre_name']) : null;
            $sub_name = !empty($value['sub_name']) ? trim($value['sub_name']) : null;

            $car_info_owner = !empty($value['car_info_owner']) ? trim($value['car_info_owner']) : null;
            $car_info_type = !empty($value['car_info_type']) ? trim($value['car_info_type']) : null;
            $car_info_brand = !empty($value['car_info_brand']) ? trim($value['car_info_brand']) : null;
            $car_info_model = !empty($value['car_info_model']) ? trim($value['car_info_model']) : null;
            $car_info_size = !empty($value['car_info_size']) ? trim($value['car_info_size']) : null;
            $car_info_identification_number = !empty($value['car_info_identification_number']) ? trim($value['car_info_identification_number']) : null;
            $car_info_engine_number = !empty($value['car_info_engine_number']) ? trim($value['car_info_engine_number']) : null;
            $car_info_vehicle_axles_count = !empty($value['car_info_vehicle_axles_count']) ? trim($value['car_info_vehicle_axles_count']) : null;
            $car_info_main_fuel_tank = !empty($value['car_info_main_fuel_tank']) ? trim($value['car_info_main_fuel_tank']) : null;
            $car_info_total_mass = !empty($value['car_info_total_mass']) ? trim($value['car_info_total_mass']) : null;

            $car_info_issue_date = !empty($value['car_info_issue_date']) ? trim($value['car_info_issue_date']) : null;
            $car_info_registration_date = !empty($value['car_info_registration_date']) ? trim($value['car_info_registration_date']) : null;
            $car_info_inspection_validity = !empty($value['car_info_inspection_validity']) ? trim($value['car_info_inspection_validity']) : null;
            $car_info_transportation_license_validity = !empty($value['car_info_transportation_license_validity']) ? trim($value['car_info_transportation_license_validity']) : null;
            $car_info_transportation_license_change_time = !empty($value['car_info_transportation_license_change_time']) ? trim($value['car_info_transportation_license_change_time']) : null;

            $description = !empty($value['description']) ? trim($value['description']) : null;


            if($name)
            {
                $temp_date['name'] = $name;
                $temp_date['car_category'] = $car_category;
                $temp_date['car_type'] = $car_type;
            }
            else continue;

            if($car_number) $temp_date['car_number'] = $car_number;
            if($pre_name) $temp_date['pre_name'] = $pre_name;
            if($sub_name) $temp_date['sub_name'] = $sub_name;

            if($car_info_owner) $temp_date['car_info_owner'] = $car_info_owner;
            if($car_info_type) $temp_date['car_info_type'] = $car_info_type;
            if($car_info_brand) $temp_date['car_info_brand'] = $car_info_brand;
            if($car_info_model) $temp_date['car_info_model'] = $car_info_model;
            if($car_info_size) $temp_date['car_info_size'] = $car_info_size;
            if($car_info_identification_number) $temp_date['car_info_identification_number'] = $car_info_identification_number;
            if($car_info_engine_number) $temp_date['car_info_engine_number'] = $car_info_engine_number;
            if($car_info_vehicle_axles_count) $temp_date['car_info_vehicle_axles_count'] = $car_info_vehicle_axles_count;
            if($car_info_main_fuel_tank) $temp_date['car_info_main_fuel_tank'] = $car_info_main_fuel_tank;
            if($car_info_total_mass) $temp_date['car_info_total_mass'] = $car_info_total_mass;

            if($car_info_issue_date) $temp_date['car_info_issue_date'] = $car_info_issue_date;
            if($car_info_registration_date) $temp_date['car_info_registration_date'] = $car_info_registration_date;
            if($car_info_inspection_validity) $temp_date['car_info_inspection_validity'] = $car_info_inspection_validity;
            if($car_info_transportation_license_validity) $temp_date['car_info_transportation_license_validity'] = $car_info_transportation_license_validity;
            if($car_info_transportation_license_change_time) $temp_date['car_info_transportation_license_change_time'] = $car_info_transportation_license_change_time;

            if($description) $temp_date['description'] = $description;


            $item_data[] = $temp_date;
        }
//        dd($item_data);


        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            foreach($item_data as $key => $value)
            {
                $item = new WL_Common_Car;

                $bool = $item->fill($value)->save();
                if(!$bool) throw new Exception("WL_Common_Driver--insert--fail");
            }

            DB::commit();
            return response_success(['count'=>count($item_data)]);
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


    // 【车辆】删除
    public function o1__car__item_delete($post_data)
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
        if($operate != 'car--item-delete') return response_error([],"参数【operate】有误！");
        $item_id = $post_data["item_id"];
        if(intval($item_id) !== 0 && !$item_id) return response_error([],"参数【ID】有误！");

        $this->get_me();
        $me = $this->me;

        // 判断用户操作权限
        if(!in_array($me->user_type,[0,1,9,11])) return response_error([],"你没有操作权限！");

        // 判断对象是否合法
        $mine = WL_Common_Car::withTrashed()->find($item_id);
        if(!$mine) return response_error([],"该【车辆】不存在，刷新页面重试！");


        // 记录
        $operation_record_data = [];

        $record_data["operate_object"] = 'staff';
        $record_data["operate_module"] = 'car';
        $record_data["operate_category"] = 1;
        $record_data["operate_type"] = 11;
        $record_data["item_id"] = $item_id;
        $record_data["car_id"] = $item_id;
        $record_data["creator_id"] = $me->id;
        $record_data["creator_company_id"] = $me->company_id;
        $record_data["creator_department_id"] = $me->department_id;
        $record_data["creator_team_id"] = $me->team_id;

        $operation = [];
        $operation['operation'] = $operate;
        $operation['field'] = 'deleted_at';
        $operation['title'] = '操作';
        $operation['before'] = '';
        $operation['after'] = '删除';
        $operation_record_data[] = $operation;

        $record_data["content"] = json_encode($operation_record_data);


        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $mine->timestamps = false;
            $bool = $mine->delete();  // 普通删除
            if(!$bool) throw new Exception("WL_Common_Car--delete--fail");
            else
            {
                $staff_operation_record = new WL_Staff_Record_Operation;
                $bool_sop = $staff_operation_record->fill($record_data)->save();
                if(!$bool_sop) throw new Exception("WL_Staff_Record_Operation--insert--fail");
            }

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
    // 【车辆】恢复
    public function o1__car__item_restore($post_data)
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
        if($operate != 'car--item-restore') return response_error([],"参数【operate】有误！");
        $item_id = $post_data["item_id"];
        if(intval($item_id) !== 0 && !$item_id) return response_error([],"参数【ID】有误！");

        $this->get_me();
        $me = $this->me;

        // 判断用户操作权限
        if(!in_array($me->user_type,[0,1,9,11,19])) return response_error([],"你没有操作权限！");

        // 判断对象是否合法
        $mine = WL_Common_Car::withTrashed()->find($item_id);
        if(!$mine) return response_error([],"该【车辆】不存在，刷新页面重试！");


        // 记录
        $operation_record_data = [];

        $record_data["operate_object"] = 'staff';
        $record_data["operate_module"] = 'car';
        $record_data["operate_category"] = 1;
        $record_data["operate_type"] = 12;
        $record_data["item_id"] = $item_id;
        $record_data["car_id"] = $item_id;
        $record_data["creator_id"] = $me->id;
        $record_data["creator_company_id"] = $me->company_id;
        $record_data["creator_department_id"] = $me->department_id;
        $record_data["creator_team_id"] = $me->team_id;

        $operation = [];
        $operation['operation'] = $operate;
        $operation['field'] = 'deleted_at';
        $operation['title'] = '操作';
        $operation['before'] = '';
        $operation['after'] = '恢复';
        $operation_record_data[] = $operation;

        $record_data["content"] = json_encode($operation_record_data);


        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $mine->timestamps = false;
            $bool = $mine->restore();
            if(!$bool) throw new Exception("WL_Common_Car--restore--fail");
            else
            {
                $staff_operation_record = new WL_Staff_Record_Operation;
                $bool_sop = $staff_operation_record->fill($record_data)->save();
                if(!$bool_sop) throw new Exception("WL_Staff_Record_Operation--insert--fail");
            }

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
    // 【车辆】彻底删除
    public function o1__car__item_delete_permanently($post_data)
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
        if($operate != 'car--item-delete-permanently') return response_error([],"参数【operate】有误！");
        $item_id = $post_data["item_id"];
        if(intval($item_id) !== 0 && !$item_id) return response_error([],"参数【ID】有误！");

        $this->get_me();
        $me = $this->me;

        // 判断用户操作权限
        if(!in_array($me->user_type,[0,1,9,11,19])) return response_error([],"你没有操作权限！");

        // 判断对象是否合法
        $mine = WL_Common_Car::withTrashed()->find($item_id);
        if(!$mine) return response_error([],"该【车辆】不存在，刷新页面重试！");


        // 记录
        $operation_record_data = [];

        $record_data["operate_object"] = 'staff';
        $record_data["operate_module"] = 'car';
        $record_data["operate_category"] = 1;
        $record_data["operate_type"] = 13;
        $record_data["item_id"] = $item_id;
        $record_data["car_id"] = $item_id;
        $record_data["creator_id"] = $me->id;
        $record_data["creator_company_id"] = $me->company_id;
        $record_data["creator_department_id"] = $me->department_id;
        $record_data["creator_team_id"] = $me->team_id;

        $operation = [];
        $operation['operation'] = $operate;
        $operation['field'] = 'deleted_at';
        $operation['title'] = '操作';
        $operation['before'] = '';
        $operation['after'] = '彻底删除';
        $operation_record_data[] = $operation;

        $record_data["content"] = json_encode($operation_record_data);


        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $mine_copy = $mine;
            $bool = $mine->forceDelete();
            if(!$bool) throw new Exception("WL_Common_Car--delete--fail");
            else
            {
                $staff_operation_record = new WL_Staff_Record_Operation;
                $bool_sop = $staff_operation_record->fill($record_data)->save();
                if(!$bool_sop) throw new Exception("WL_Staff_Record_Operation--insert--fail");
            }

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


    // 【车辆】启用
    public function o1__car__item_enable($post_data)
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
        if($operate != 'car--item-enable') return response_error([],"参数【operate】有误！");
        $item_id = $post_data["item_id"];
        if(intval($item_id) !== 0 && !$item_id) return response_error([],"参数【ID】有误！");

        $this->get_me();
        $me = $this->me;

        // 判断用户操作权限
        if(!in_array($me->user_type,[0,1,9,11])) return response_error([],"你没有操作权限！");

        // 判断对象是否合法
        $mine = WL_Common_Car::find($item_id);
        if(!$mine) return response_error([],"该【车辆】不存在，刷新页面重试！");


        // 记录
        $operation_record_data = [];

        $record_data["operate_object"] = 'staff';
        $record_data["operate_module"] = 'car';
        $record_data["operate_category"] = 1;
        $record_data["operate_type"] = 21;
        $record_data["item_id"] = $item_id;
        $record_data["car_id"] = $item_id;
        $record_data["creator_id"] = $me->id;
        $record_data["creator_company_id"] = $me->company_id;
        $record_data["creator_department_id"] = $me->department_id;
        $record_data["creator_team_id"] = $me->team_id;

        $operation = [];
        $operation['operation'] = $operate;
        $operation['field'] = 'item_status';
        $operation['title'] = '操作';
        $operation['before'] = '';
        $operation['after'] = '启用';
        $operation_record_data[] = $operation;

        $record_data["content"] = json_encode($operation_record_data);


        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $mine->item_status = 1;
            $mine->timestamps = false;
            $bool = $mine->save();
            if(!$bool) throw new Exception("WL_Common_Car--update--fail");
            else
            {
                $staff_operation_record = new WL_Staff_Record_Operation;
                $bool_sop = $staff_operation_record->fill($record_data)->save();
                if(!$bool_sop) throw new Exception("WL_Staff_Record_Operation--insert--fail");
            }

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
    public function o1__car__item_disable($post_data)
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
        if($operate != 'car--item-disable') return response_error([],"参数【operate】有误！");
        $item_id = $post_data["item_id"];
        if(intval($item_id) !== 0 && !$item_id) return response_error([],"参数【ID】有误！");

        $this->get_me();
        $me = $this->me;

        // 判断用户操作权限
        if(!in_array($me->user_type,[0,1,9,11])) return response_error([],"你没有操作权限！");

        // 判断对象是否合法
        $mine = WL_Common_Car::find($item_id);
        if(!$mine) return response_error([],"该【车辆】不存在，刷新页面重试！");


        // 记录
        $operation_record_data = [];

        $record_data["operate_object"] = 'staff';
        $record_data["operate_module"] = 'car';
        $record_data["operate_category"] = 1;
        $record_data["operate_type"] = 22;
        $record_data["item_id"] = $item_id;
        $record_data["car_id"] = $item_id;
        $record_data["creator_id"] = $me->id;
        $record_data["creator_company_id"] = $me->company_id;
        $record_data["creator_department_id"] = $me->department_id;
        $record_data["creator_team_id"] = $me->team_id;

        $operation = [];
        $operation['operation'] = $operate;
        $operation['field'] = 'item_status';
        $operation['title'] = '操作';
        $operation['before'] = '';
        $operation['after'] = '禁用';
        $operation_record_data[] = $operation;

        $record_data["content"] = json_encode($operation_record_data);


        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $mine->item_status = 9;
            $mine->timestamps = false;
            $bool = $mine->save();
            if(!$bool) throw new Exception("WL_Common_Car--update--fail");
            else
            {
                $staff_operation_record = new WL_Staff_Record_Operation;
                $bool_sop = $staff_operation_record->fill($record_data)->save();
                if(!$bool_sop) throw new Exception("WL_Staff_Record_Operation--insert--fail");
            }

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


    // 【车辆】【操作记录】返回-列表-数据
    public function o1__car__item_operation_record_list__datatable_query($post_data)
    {
        $this->get_me();
        $me = $this->me;

        $id  = $post_data["id"];
        $query = WL_Staff_Record_Operation::select('*')
            ->with([
                'creator'=>function($query) { $query->select(['id','name']); },
            ])
            ->where(['car_id'=>$id]);

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


}