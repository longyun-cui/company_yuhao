<?php
namespace App\Repositories\WL\Staff;

use App\Models\WL\Common\WL_Common_Finance;
use App\Models\WL\Common\WL_Common_Fee;

use App\Models\WL\Common\WL_Common_Order;
use App\Models\WL\Common\WL_Common_Order_Operation_Record;

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
    public function o1__fee__list__datatable_query($post_data)
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
            $item_status_int = intval($post_data['item_status']);
            if(!in_array($item_status_int,[-1,0]))
            {
                $query->where('item_status', $item_status_int);
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
//            'finance-transaction-title.required' => '请输入名目！',
//            'finance-transaction-amount.required' => '请输入金额！',
            'finance-transaction-datetime.required' => '请输入时间！',
            'finance-transaction-payment-method.required' => '请输入支付方式！',
//            'name.unique' => '该部门号已存在！',
        ];
        $v = Validator::make($post_data, [
            'operate' => 'required',
//            'finance-transaction-title' => 'required',
//            'finance-transaction-amount' => 'required',
            'finance-transaction-datetime' => 'required',
            'finance-transaction-payment-method' => 'required',
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


        $timestamp = time();
        $datetime = date('Y-m-d H:i:s', $timestamp);


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
    // 【费用】保存数据
    public function o1__fee__item_finance_bookkeeping($post_data)
    {
        $messages = [
            'operate.required' => 'operate.required.',
//            'finance-transaction-title.required' => '请输入名目！',
//            'finance-transaction-amount.required' => '请输入金额！',
            'finance-transaction-datetime.required' => '请输入时间！',
//            'name.unique' => '该部门号已存在！',
        ];
        $v = Validator::make($post_data, [
            'operate' => 'required',
//            'finance-transaction-title' => 'required',
//            'finance-transaction-amount' => 'required',
            'finance-transaction-datetime' => 'required',
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


        $fee = WL_Common_Fee::with([])->withTrashed()->find($operate_id);
        if(!$fee) return response_error([],"【费用】不存在警告，请刷新页面重试！");


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
            $operation['after'] = '费用入账';
            $operation_record_data[] = $operation;
        }


        // 交易时间
        $transaction_datetime = $post_data['finance-transaction-datetime'];
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
        $transaction_payment_method = $post_data['finance-transaction-payment-method'];
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
        $transaction_account_from = $post_data['finance-transaction-account-from'];
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
        $transaction_account_to = $post_data['finance-transaction-account-to'];
        if(!empty($transaction_account_to))
        {
            $operation['field'] = 'transaction_account_to';
            $operation['title'] = '收款账号';
            $operation['before'] = '';
            $operation['after'] = $transaction_account_to;
            $operation_record_data[] = $operation;
        }
        // 交易单号
        $transaction_reference_no = $post_data['finance-transaction-reference-no'];
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
        $transaction_description = $post_data['finance-transaction-description'];
        if(!empty($transaction_description))
        {
            $operation = [];
            $operation['field'] = 'transaction_description';
            $operation['title'] = '交易说明';
            $operation['before'] = '';
            $operation['after'] = $transaction_description;
            $operation_record_data[] = $operation;
        }


        $record_data["operate_category"] = 88;
        $record_data["operate_type"] = 1;
        $record_data["client_id"] = $fee->client_id;
        $record_data["project_id"] = $fee->project_id;
        $record_data["order_id"] = $fee->order_id;
        $record_data["fee_id"] = $operate_id;
        $record_data["creator_id"] = $me->id;
        $record_data["company_id"] = $me->company_id;
        $record_data["department_id"] = $me->department_id;
        $record_data["team_id"] = $me->team_id;
        $record_data["custom_date"] = $transaction_datetime;
        $record_data["custom_datetime"] = $transaction_datetime;
        $record_data["content"] = json_encode($operation_record_data);


        // 财务记录
        $finance_data["transaction_category"] = 1;
        $finance_data["transaction_type"] = $fee->fee_type;
        $finance_data["transaction_date"] = $transaction_datetime;
        $finance_data["transaction_datetime"] = $transaction_datetime;
        $finance_data["transaction_amount"] = $fee->fee_amount;
        $finance_data["transaction_title"] = $fee->fee_title;
        $finance_data["transaction_description"] = $transaction_description;
        $finance_data["transaction_payment_method"] = $transaction_payment_method;
        $finance_data["transaction_account_from"] = $transaction_account_from;
        $finance_data["transaction_account_to"] = $transaction_account_to;
        $finance_data["transaction_reference_no"] = $transaction_reference_no;

        $finance_data["fee_id"] = $operate_id;
        $finance_data["client_id"] = $fee->client_id;
        $finance_data["project_id"] = $fee->project_id;
        $finance_data["order_id"] = $fee->order_id;
        $finance_data["order_task_date"] = $fee->order_task_date;
        $finance_data["car_id"] = $fee->car_id;
        $finance_data["driver_id"] = $fee->driver_id;
        $finance_data["creator_id"] = $me->id;
        $finance_data["company_id"] = $me->company_id;
        $finance_data["department_id"] = $me->department_id;
        $finance_data["team_id"] = $me->team_id;


        // 启动数据库事务
        DB::beginTransaction();
        try
        {

            $finance = new WL_Common_Finance;
            $finance_data["fee_id"] = $fee->id;
            $bool_finance = $finance->fill($finance_data)->save();
            if($bool_finance)
            {
                $fee->finance_id = $finance->id;
                $fee->is_recorded = 1;
                $fee->recorder_id = $me->id;
                $fee->recorded_date = $datetime;
                $fee->recorded_datetime = $datetime;
                $bool_fee = $fee->save();
                if(!$bool_fee) throw new Exception("WL_Common_Fee--update--fail");

                $record_data['fee_id'] = $fee->id;
                $record_data['finance_id'] = $finance->id;

                $order_operation_record = new WL_Common_Order_Operation_Record;
                $bool_oor = $order_operation_record->fill($record_data)->save();
                if(!$bool_oor) throw new Exception("WL_Common_Order_Operation_Record--insert--fail");
            }
            else throw new Exception("WL_Common_Finance--insert--fail");

            DB::commit();
            return response_success(['fee'=>$fee]);
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


    // 【费用】【操作记录】返回-列表-数据
    public function o1__fee__item_operation_record_list__datatable_query($post_data)
    {
        $this->get_me();
        $me = $this->me;

        $id  = $post_data["id"];
        $query = WL_Common_Order_Operation_Record::select('*')
            ->with([
                'creator'=>function($query) { $query->select(['id','username','true_name']); },
            ])
            ->where(['fee_id'=>$id]);
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




}