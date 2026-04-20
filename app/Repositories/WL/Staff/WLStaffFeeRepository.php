<?php
namespace App\Repositories\WL\Staff;

use App\Models\WL\Common\WL_Common_Finance;
use App\Models\WL\Common\WL_Common_Fee;

use App\Models\WL\Common\WL_Common_Car;
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




    // 【费用】导入
    public function o1__fee__import__save($post_data)
    {
        $messages = [
            'operate.required' => 'operate.required.',
            'fee-type.required' => '请选择类型！',
            'fee-record-type.required' => '请选择记录类型！',
//            'fee-title.required' => '请输入名目！',
//            'fee-amount.required' => '请输入金额！',
//            'fee-datetime.required' => '请输入时间！',
//            'name.unique' => '该部门号已存在！',
        ];
        $v = Validator::make($post_data, [
            'operate' => 'required',
            'fee-type' => 'required',
            'fee-record-type' => 'required',
//            'fee-title' => 'required',
//            'fee-amount' => 'required',
//            'fee-datetime' => 'required',
//            'name' => 'required|unique:dk_department,name',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }

        $this->get_me();
        $me = $this->me;

//        if(!in_array($me->staff_category,[0,1,11,19])) return response_error([],"你没有操作权限！");


        $timestamp = time();
        $date = date('Y-m-d', $timestamp);
        $datetime = date('Y-m-d H:i:s', $timestamp);


        $operate = $post_data["operate"];
        $operate_type = $operate["type"];
        $operate_id = $operate['id'];


        $fee_type = $post_data['fee-type'];
        if(!in_array($fee_type,[1,99,101,111])) return response_error([],"费用类型有误！");

        $fee_record_type = $post_data['fee-record-type'];


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
            $reader->limitColumns(20);

//            $reader->takeRows(200);
//            $reader->limitRows(200);

            $reader->ignoreEmpty();

            $data = $reader->all();
//            $data = $reader->toArray();

        })->get();
        $data = $data->toArray();
//        dd($data);


        $import_list = [];

        foreach($data as $key => $value)
        {
            $import_data = [];


            $car_name = !empty($value['car_name']) ? trim($value['car_name']) : null;
            $assign_date = !empty($value['assign_date']) ? trim($value['assign_date']) : null;

            $fee_title = !empty($value['fee_title']) ? trim($value['fee_title']) : null;
            $fee_amount = !empty($value['fee_amount']) ? (float)trim($value['fee_amount']) : 0;
            $fee_datetime = !empty($value['fee_datetime']) ? trim($value['fee_datetime']) : null;
            $fee_description = !empty($value['fee_description']) ? trim($value['fee_description']) : null;


            if($car_name && $assign_date && $fee_title && $fee_amount)
            {
                $import_data['car_name'] = $car_name;
                $import_data['assign_date'] = date('Y-m-d', strtotime($assign_date));
            }
            else continue;

            $transaction_payment_method = !empty($value['transaction_payment_method']) ? trim($value['transaction_payment_method']) : null;
            $transaction_datetime = !empty($value['transaction_datetime']) ? trim($value['transaction_datetime']) : null;
            if(empty($transaction_datetime)) $transaction_datetime = $fee_datetime;
            $transaction_account_from = !empty($value['transaction_account_from']) ? trim($value['transaction_account_from']) : null;
            $transaction_account_to = !empty($value['transaction_account_to']) ? trim($value['transaction_account_to']) : null;
            $transaction_reference_no = !empty($value['transaction_reference_no']) ? trim($value['transaction_reference_no']) : null;
            $transaction_description = !empty($value['transaction_description']) ? trim($value['transaction_description']) : null;
            if(empty($transaction_description)) $transaction_description = $fee_description;


            $operation_record_data = [];


            // 操作
            if(true)
            {
                $operation = [];
                $operation['operation'] = 'item.fee';
                $operation['field'] = '';
                $operation['title'] = '操作';
                $operation['before'] = '';
                $operation['after'] = '费用导入';
                $operation_record_data[] = $operation;
            }
            // 类型
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
//        $fee_title = $post_data['fee-title'];
//        if(!empty($fee_title))
//        {
//            $operation = [];
//            $operation['field'] = 'fee_title';
//            $operation['title'] = '名目';
//            $operation['before'] = '';
//            $operation['after'] = $fee_title;
//            $operation_record_data[] = $operation;
//        }
            $fee_title_key = 0;
            if($fee_type == 1)
            {
            }
            else if($fee_type == 99)
            {
                $key = array_search($fee_title,config('wl.common-config.fee_title'));
                if($key) $fee_title_key = $key;
            }
            else if($fee_type == 101)
            {
                $key = array_search($fee_title,config('wl.common-config.deduction_title'));
                if($key) $fee_title_key = $key;
            }
            else if($fee_type == 111)
            {
                $key = array_search($fee_title,config('wl.common-config.fine_title'));
                if($key) $fee_title_key = $key;
            }
            if(!empty($fee_title))
            {
                $operation = [];
                $operation['field'] = 'fee_title';
                $operation['title'] = '名目';
                $operation['before'] = '';
                $operation['after'] = $fee_title;
                $operation_record_data[] = $operation;
            }
            else return response_error([],"【名目】不能为空！");
            // 说明
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
                if(!empty($transaction_payment_method))
                {
                    $operation = [];
                    $operation['field'] = 'transaction_payment_method';
                    $operation['title'] = '付款方式';
                    $operation['before'] = '';
                    $operation['after'] = $transaction_payment_method;
                    $operation_record_data[] = $operation;
                }
                // 交易账号
//            $transaction_account = $post_data['fee-transaction-account'];
                // 付款账号
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
                if(!empty($transaction_account_to))
                {
                    $operation['field'] = 'transaction_account_to';
                    $operation['title'] = '收款账号';
                    $operation['before'] = '';
                    $operation['after'] = $transaction_account_to;
                    $operation_record_data[] = $operation;
                }
                // 交易单号
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



            // 操作记录
            $record_data = [];
            $record_data["operate_category"] = 81;
            $record_data["operate_type"] = 1;
//            $record_data["order_id"] = $order->id;
//            $record_data["client_id"] = $order->client_id;
//            $record_data["project_id"] = $order->project_id;
            $record_data["creator_id"] = $me->id;
            $record_data["company_id"] = $me->company_id;
            $record_data["department_id"] = $me->department_id;
            $record_data["team_id"] = $me->team_id;
            $record_data["custom_date"] = $fee_datetime;
            $record_data["custom_datetime"] = $fee_datetime;
            $record_data["content"] = json_encode($operation_record_data);
            $record_data["created_at"] = $timestamp;
            $record_data["updated_at"] = $timestamp;

            $import_data['record_data'] = $record_data;




            // 费用记录
            $fee_data = [];
            $fee_data["fee_category"] = 1;
            $fee_data["fee_type"] = $fee_type;
            $fee_data["fee_date"] = $fee_datetime;
            $fee_data["fee_datetime"] = $fee_datetime;
            $fee_data["fee_amount"] = $fee_amount;
            $fee_data["fee_title"] = $fee_title;
            $fee_data["fee_title_num"] = $fee_title_key;
            $fee_data["fee_description"] = $fee_description;

//            $fee_data["order_id"] = $order->id;
//            $fee_data["client_id"] = $order->client_id;
//            $fee_data["project_id"] = $order->project_id;
//            $fee_data["order_assign_date"] = $order->assign_date;
//            $fee_data["order_task_date"] = $order->task_date;
//            $fee_data["car_id"] = $order->car_id;
//            $fee_data["driver_id"] = $order->driver_id;
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
            $fee_data["created_at"] = $timestamp;
            $fee_data["updated_at"] = $timestamp;

            $import_data['fee_data'] = $fee_data;




            // 财务记录
            if(in_array($fee_type,[1,99]) && $fee_record_type == 81)
            {
                $finance_data = [];
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

//                $finance_data["order_id"] = $order->id;
//                $finance_data["client_id"] = $order->client_id;
//                $finance_data["project_id"] = $order->project_id;
//                $finance_data["order_assign_date"] = $order->assign_date;
//                $finance_data["order_task_date"] = $order->task_date;
//                $finance_data["car_id"] = $order->car_id;
//                $finance_data["driver_id"] = $order->driver_id;
                $finance_data["creator_id"] = $me->id;
                $finance_data["company_id"] = $me->company_id;
                $finance_data["department_id"] = $me->department_id;
                $finance_data["team_id"] = $me->team_id;
                $finance_data["created_at"] = $timestamp;
                $finance_data["updated_at"] = $timestamp;

                $import_data['finance_data'] = $finance_data;
            }


            $import_list[] = $import_data;

        }
//        dd($import_list);
//        dd(count($import_list));



        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $count = 0;

            foreach($import_list as $k => $v)
            {

                $this_car_name = $v['car_name'];
                $this_assign_date = $v['assign_date'];

                $car =  WL_Common_Car::select('*')->where('car_name',$this_car_name)->first();
                if(!$car) continue;

                $order = WL_Common_Order::select('*')
                    ->where('car_id',$car->id)
                    ->where('assign_date',$this_assign_date)
                    ->first();
                if(!$order) continue;


                $this_fee_data = $v['fee_data'];

                $this_fee_data["created_type"] = 101;
                $this_fee_data["order_id"] = $order->id;
                $this_fee_data["client_id"] = $order->client_id;
                $this_fee_data["project_id"] = $order->project_id;
                $this_fee_data["order_assign_date"] = $order->assign_date;
                $this_fee_data["order_task_date"] = $order->task_date;
                $this_fee_data["car_id"] = $order->car_id;
                $this_fee_data["driver_id"] = $order->driver_id;

                $fee = new WL_Common_Fee;
                $bool_fee = $fee->fill($this_fee_data)->save();
                if($bool_fee)
                {
                    $this_record_data = $v['record_data'];

                    $this_record_data["order_id"] = $order->id;
                    $this_record_data["client_id"] = $order->id;
                    $this_record_data["project_id"] = $order->id;

                    $this_record_data["fee_id"] = $fee->id;

                    // 财务记录
                    if(in_array($fee_type,[1,99]) && $fee_record_type == 81)
                    {
                        $this_finance_data = $v['finance_data'];

                        $this_finance_data["created_type"] = 101;
                        $this_finance_data["order_id"] = $order->id;
                        $this_finance_data["client_id"] = $order->client_id;
                        $this_finance_data["project_id"] = $order->project_id;
                        $this_finance_data["order_assign_date"] = $order->assign_date;
                        $this_finance_data["order_task_date"] = $order->task_date;
                        $this_finance_data["car_id"] = $order->car_id;
                        $this_finance_data["driver_id"] = $order->driver_id;

                        $finance = new WL_Common_Finance;
                        $this_finance_data["fee_id"] = $fee->id;
                        $bool_finance = $finance->fill($this_finance_data)->save();
                        if($bool_finance)
                        {
                            $this_record_data["finance_id"] = $finance->id;

                            $fee->finance_id = $finance->id;
                            $bool_fee_2 = $fee->save();
                            if(!$bool_fee_2) throw new Exception("WL_Common_Fee--update--fail");
                        }
                    }

                    $order_operation_record = new WL_Common_Order_Operation_Record;
                    $bool_oor = $order_operation_record->fill($this_record_data)->save();
                    if($bool_oor)
                    {
                        $fee->order_operation_record_id = $order_operation_record->id;
                        $bool_fee_3 = $fee->save();
                        if(!$bool_fee_3) throw new Exception("WL_Common_Fee--update--fail");


                        $lock_order = WL_Common_Order::lockForUpdate()->find($order->id);
                        if($fee_type == 1)
                        {
                            $lock_order->financial_income_total += $this_fee_data['fee_amount'];
                        }
                        else if($fee_type == 99)
                        {
                            $lock_order->financial_expense_total += $this_fee_data['fee_amount'];
                        }
                        else if($fee_type == 101)
                        {
                            $lock_order->financial_deduction_total += $this_fee_data['fee_amount'];
                        }
                        else if($fee_type == 111)
                        {
                            $lock_order->financial_fine_total += $this_fee_data['fee_amount'];
                        }
                        $lock_order->last_operation_datetime = $datetime;
                        $lock_order->last_operation_date = $datetime;
                        $bool_order = $lock_order->save();
                        if(!$bool_order) throw new Exception("WL_Common_Order--update--fail");
                    }
                    else throw new Exception("WL_Staff_Record_Operation--insert--fail");
                }
                else throw new Exception("WL_Common_Fee--insert--fail");

                $count += 1;

            }


            DB::commit();
            return response_success(['count'=>$count]);
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
    public function o1__fee__list__datatable_query($post_data)
    {
        $this->get_me();
        $me = $this->me;


        $query = WL_Common_Fee::select('*')
            ->withTrashed()
            ->with([
                'creator'=>function($query) { $query->select(['id','name']); },
                'client_er'=>function($query) { $query->select(['id','name']); },
                'project_er'=>function($query) { $query->select(['id','name']); },
                'order_er'=>function($query) { $query->select(['*']); },
                'car_er'=>function($query) { $query->select(['id','name']); },
                'driver_er'=>function($query) { $query->select(['id','driver_name']); }
            ]);

        if(!empty($post_data['id'])) $query->where('id', $post_data['id']);
        if(!empty($post_data['name'])) $query->where('name', 'like', "%{$post_data['name']}%");
        if(!empty($post_data['title'])) $query->where('fee_title', 'like', "%{$post_data['title']}%");
        if(!empty($post_data['remark'])) $query->where('remark', 'like', "%{$post_data['remark']}%");
        if(!empty($post_data['description'])) $query->where('description', 'like', "%{$post_data['description']}%");
        if(!empty($post_data['keyword'])) $query->where('content', 'like', "%{$post_data['keyword']}%");

        if(!empty($post_data['order_id'])) $query->where('order_id', "{$post_data['order_id']}");

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

        // 类型 [|]
        if(!empty($post_data['fee_type']))
        {
            $fee_type_int = intval($post_data['fee_type']);
            if(!in_array($fee_type_int,[-1,0]))
            {
                $query->where('fee_type', $fee_type_int);
            }
        }

        // 是否入账 [|]
        if(isset($post_data['is_recorded']))
        {
            $is_recorded_int = intval($post_data['is_recorded']);
            if($is_recorded_int == 0)
            {
                $query->whereIn('fee_type', [1,99])->where('is_recorded', 0);
            }
            else if($is_recorded_int == 1)
            {
                $query->where('is_recorded', 1);
            }
        }

        $total = $query->count();

        $draw  = isset($post_data['draw']) ? $post_data['draw'] : 1;
        $skip  = isset($post_data['start']) ? $post_data['start'] : 0;
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


    // 【订单管理】删除
    public function o1__fee__item_delete($post_data)
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

        $timestamp = time();
        $datetime = date('Y-m-d H:i:s', $timestamp);


        $this->get_me();
        $me = $this->me;

//        if(!in_array($me->staff_category,[0,1,11,19])) return response_error([],"你没有操作权限！");

        $operate = $post_data["operate"];
        if($operate != 'fee--item-delete') return response_error([],"参数[operate]有误！");
        $item_id = $post_data["item_id"];
        if(intval($item_id) !== 0 && !$item_id) return response_error([],"参数[ID]有误！");


        $mine = WL_Common_Fee::find($item_id);
        if(!$mine) return response_error([],"该【费用记录】不存在或已删除，刷新页面重试！");

        $order = WL_Common_Order::find($mine->order_id);
        if(!$order) return response_error([],"该【费用记录】的【订单】不存在或已删除，刷新页面重试！");


        $operation_record_data = [];

        // 操作
        if(true)
        {
            $operation = [];
            $operation['operation'] = 'item.fee.delete';
            $operation['field'] = '';
            $operation['title'] = '操作';
            $operation['before'] = '';
            $operation['after'] = '费用删除';
            $operation_record_data[] = $operation;
        }
        if(true)
        {
            $operation = [];
            $operation['field'] = '';
            $operation['title'] = 'ID';
            $operation['before'] = '';
            $operation['after'] = $item_id;
            $operation_record_data[] = $operation;
        }
        if(true)
        {
            $operation = [];
            $operation['field'] = '';
            $operation['title'] = '类型';
            $operation['before'] = '';

            $fee_type = $mine->fee_type;
            if($fee_type == 1) $operation['after'] = "收入";
            else if($fee_type == 99) $operation['after'] = "费用";
            else if($fee_type == 101) $operation['after'] = "订单扣款";
            else if($fee_type == 111) $operation['after'] = "司机罚款";
            else $operation['after'] = $fee_type;

            $operation_record_data[] = $operation;
        }
        if(true)
        {
            $operation = [];
            $operation['field'] = '';
            $operation['title'] = '金额';
            $operation['before'] = '';
            $operation['after'] = $mine->fee_amount;
            $operation_record_data[] = $operation;
        }
        if(true)
        {
            $operation = [];
            $operation['field'] = '';
            $operation['title'] = '名目';
            $operation['before'] = '';
            $operation['after'] = $mine->fee_title;
            $operation_record_data[] = $operation;
        }

        $record_data["operate_category"] = 81;
        $record_data["operate_type"] = 11;
        $record_data["client_id"] = $mine->client_id;
        $record_data["project_id"] = $mine->project_id;
        $record_data["order_id"] = $mine->order_id;
        $record_data["fee_id"] = $mine->id;
        $record_data["finance_id"] = $mine->finance_id;
        $record_data["creator_id"] = $me->id;
        $record_data["company_id"] = $me->company_id;
        $record_data["department_id"] = $me->department_id;
        $record_data["team_id"] = $me->team_id;
        $record_data["custom_date"] = $datetime;
        $record_data["custom_datetime"] = $datetime;
        $record_data["content"] = json_encode($operation_record_data);


        // 启动数据库事务
        DB::beginTransaction();
        try
        {

            $order = WL_Common_Order::withTrashed()->lockForUpdate()->find($mine->order_id);
            if(!$order) throw new Exception("该订单不存在，刷新页面重试！");

            if($mine->fee_type == 1)
            {
                $order->financial_income_total -= $mine->fee_amount;
            }
            else if($mine->fee_type == 99)
            {
                $order->financial_expense_total -= $mine->fee_amount;
            }
            else if($mine->fee_type == 101)
            {
                $order->financial_deduction_total += $mine->fee_amount;
            }
            else if($mine->fee_type == 111)
            {
                $order->financial_fine_total += $mine->fee_amount;
            }

            $bool_order = $order->save();
            if(!$bool_order) throw new Exception("WL_Common_Order--update--fail");

            if($mine->is_recorded == 1)
            {
                $finance = WL_Common_Finance::withTrashed()->lockForUpdate()->find($mine->finance_id);
                if($finance)
                {
                    $finance->timestamps = false;
                    $bool_finance = $finance->delete();  // 普通删除
                    if(!$bool_finance) throw new Exception("WL_Common_Finance--delete--fail");
                }
            }

            $mine->timestamps = false;
            $bool = $mine->delete();  // 普通删除
            if(!$bool) throw new Exception("WL_Common_Fee--delete--fail");


            $order_operation_record = new WL_Common_Order_Operation_Record;
            $bool_oor = $order_operation_record->fill($record_data)->save();
            if(!$bool_oor) throw new Exception("WL_Common_Order_Operation_Record--insert--fail");

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


    // 【费用】【操作记录】返回-列表-数据
    public function o1__fee__item_operation_record_list__datatable_query($post_data)
    {
        $this->get_me();
        $me = $this->me;

        $id  = $post_data["id"];
        $query = WL_Common_Order_Operation_Record::select('*')
            ->with([
                'creator'=>function($query) { $query->select(['id','name']); },
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