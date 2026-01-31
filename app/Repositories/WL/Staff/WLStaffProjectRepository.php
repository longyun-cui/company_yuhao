<?php
namespace App\Repositories\WL\Staff;

use App\Models\WL\Common\WL_Common_Project;
use App\Models\WL\Staff\WL_Staff_Record_Operation;

use App\Repositories\Common\CommonRepository;

use Response, Auth, Validator, DB, Exception, Cache, Blade, Carbon;
use QrCode, Excel;


class WLStaffProjectRepository{

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
     * 项目-管理 Project
     */
    // 【项目】返回-列表-数据
    public function o1__project__list__datatable_query($post_data)
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
    public function o1__project__item_get($post_data)
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
        $item_id = $post_data["item_id"];
        if(intval($item_id) !== 0 && !$item_id) return response_error([],"参数[ID]有误！");

        $item = WL_Common_Project::withTrashed()
            ->with([
                'client_er'=>function($query) { $query->select(['id','name']); },
//                'inspector_er'=>function($query) { $query->select(['id','username']); },
//                'pivot_project_user',
//                'pivot_project_team'
            ])
            ->find($item_id);
        if(!$item) return response_error([],"不存在警告，请刷新页面重试！");

        return response_success($item,"");
    }
    // 【项目】保存 SAVE
    public function o1__project__item_save($post_data)
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

            $transport_time_limitation = $post_data["transport_time_limitation"];
            $mine_data["transport_time_limitation"] = !empty($transport_time_limitation) ? ($transport_time_limitation * 60) : 0;


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


    // 【项目】删除
    public function o1__project__item_delete($post_data)
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
        if($operate != 'project--item-delete') return response_error([],"参数【operate】有误！");
        $item_id = $post_data["item_id"];
        if(intval($item_id) !== 0 && !$item_id) return response_error([],"参数【ID】有误！");

        $this->get_me();
        $me = $this->me;

        // 判断用户操作权限
        if(!in_array($me->user_type,[0,1,9,11])) return response_error([],"你没有操作权限！");

        // 判断对象是否合法
        $mine = WL_Common_Project::withTrashed()->find($item_id);
        if(!$mine) return response_error([],"该【项目】不存在，刷新页面重试！");


        // 记录
        $operation_record_data = [];

        $record_data["operate_object"] = 'staff';
        $record_data["operate_module"] = 'project';
        $record_data["operate_category"] = 1;
        $record_data["operate_type"] = 11;
        $record_data["item_id"] = $item_id;
        $record_data["project_id"] = $item_id;
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
            if(!$bool) throw new Exception("WL_Common_Project--delete--fail");
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
    // 【项目】恢复
    public function o1__project__item_restore($post_data)
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
        if($operate != 'project--item-restore') return response_error([],"参数【operate】有误！");
        $item_id = $post_data["item_id"];
        if(intval($item_id) !== 0 && !$item_id) return response_error([],"参数【ID】有误！");

        $this->get_me();
        $me = $this->me;

        // 判断用户操作权限
        if(!in_array($me->user_type,[0,1,9,11,19])) return response_error([],"你没有操作权限！");

        // 判断对象是否合法
        $mine = WL_Common_Project::withTrashed()->find($item_id);
        if(!$mine) return response_error([],"该【项目】不存在，刷新页面重试！");


        // 记录
        $operation_record_data = [];

        $record_data["operate_object"] = 'staff';
        $record_data["operate_module"] = 'project';
        $record_data["operate_category"] = 1;
        $record_data["operate_type"] = 12;
        $record_data["item_id"] = $item_id;
        $record_data["project_id"] = $item_id;
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
            if(!$bool) throw new Exception("WL_Common_Project--restore--fail");
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
    // 【项目】彻底删除
    public function o1__project__item_delete_permanently($post_data)
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
        if($operate != 'project--item-delete-permanently') return response_error([],"参数【operate】有误！");
        $item_id = $post_data["item_id"];
        if(intval($item_id) !== 0 && !$item_id) return response_error([],"参数【ID】有误！");

        $this->get_me();
        $me = $this->me;

        // 判断用户操作权限
        if(!in_array($me->user_type,[0,1,9,11,19])) return response_error([],"你没有操作权限！");

        // 判断对象是否合法
        $mine = WL_Common_Project::withTrashed()->find($item_id);
        if(!$mine) return response_error([],"该【项目】不存在，刷新页面重试！");


        // 记录
        $operation_record_data = [];

        $record_data["operate_object"] = 'staff';
        $record_data["operate_module"] = 'project';
        $record_data["operate_category"] = 1;
        $record_data["operate_type"] = 13;
        $record_data["item_id"] = $item_id;
        $record_data["project_id"] = $item_id;
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
            if(!$bool) throw new Exception("WL_Common_Project--delete--fail");
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


    // 【项目】启用
    public function o1__project__item_enable($post_data)
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
        if($operate != 'project--item-enable') return response_error([],"参数【operate】有误！");
        $item_id = $post_data["item_id"];
        if(intval($item_id) !== 0 && !$item_id) return response_error([],"参数【ID】有误！");

        $this->get_me();
        $me = $this->me;

        // 判断用户操作权限
        if(!in_array($me->user_type,[0,1,9,11])) return response_error([],"你没有操作权限！");

        // 判断对象是否合法
        $mine = WL_Common_Project::find($item_id);
        if(!$mine) return response_error([],"该【项目】不存在，刷新页面重试！");


        // 记录
        $operation_record_data = [];

        $record_data["operate_object"] = 'staff';
        $record_data["operate_module"] = 'project';
        $record_data["operate_category"] = 1;
        $record_data["operate_type"] = 21;
        $record_data["item_id"] = $item_id;
        $record_data["project_id"] = $item_id;
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
            if(!$bool) throw new Exception("WL_Common_Project--update--fail");
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
    // 【项目】禁用
    public function o1__project__item_disable($post_data)
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
        if($operate != 'project--item-disable') return response_error([],"参数【operate】有误！");
        $item_id = $post_data["item_id"];
        if(intval($item_id) !== 0 && !$item_id) return response_error([],"参数【ID】有误！");

        $this->get_me();
        $me = $this->me;

        // 判断用户操作权限
        if(!in_array($me->user_type,[0,1,9,11])) return response_error([],"你没有操作权限！");

        // 判断对象是否合法
        $mine = WL_Common_Project::find($item_id);
        if(!$mine) return response_error([],"该【项目】不存在，刷新页面重试！");


        // 记录
        $operation_record_data = [];

        $record_data["operate_object"] = 'staff';
        $record_data["operate_module"] = 'project';
        $record_data["operate_category"] = 1;
        $record_data["operate_type"] = 22;
        $record_data["item_id"] = $item_id;
        $record_data["project_id"] = $item_id;
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
            if(!$bool) throw new Exception("WL_Common_Project--update--fail");
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


    // 【项目】【操作记录】返回-列表-数据
    public function o1__project__item_operation_record_list__datatable_query($post_data)
    {
        $this->get_me();
        $me = $this->me;

        $id  = $post_data["id"];
        $query = WL_Staff_Record_Operation::select('*')
            ->with([
                'creator'=>function($query) { $query->select(['id','username','true_name']); },
            ])
            ->where(['project_id'=>$id]);

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