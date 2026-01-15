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


class WLStaffFeeRepository {

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
     * 费用-管理 Fee
     */
    // 【费用】返回-列表-数据
    public function v1__fee__datatable_list_query($post_data)
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
    public function v1__fee__item_financial_save($post_data)
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




}