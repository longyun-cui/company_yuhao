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


class WLStaffExportRepository {

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
     * 导出
     */
    // 【导出】工单
    public function o1__order__export($post_data)
    {
//        dd($post_data);
        $this->get_me();
        $me = $this->me;

        if(!in_array($me->staff_category,[0,1,9,71])) return view($this->view_blade_403);

        $time = time();

        $record_operate_type = 1;
        $record_column_type = null;
        $record_before = '';
        $record_after = '';

        $export_type = isset($post_data['export_type']) ? $post_data['export_type']  : '';
        if($export_type == "month")
        {
            $the_month  = isset($post_data['month']) ? $post_data['month']  : date('Y-m');
            $the_month_timestamp = strtotime($the_month);

            $the_month_start_date = date('Y-m-01',$the_month_timestamp); // 指定月份-开始日期
            $the_month_ended_date = date('Y-m-t',$the_month_timestamp); // 指定月份-结束日期
            $the_month_start_datetime = date('Y-m-01 00:00:00',$the_month_timestamp); // 本月开始时间
            $the_month_ended_datetime = date('Y-m-t 23:59:59',$the_month_timestamp); // 本月结束时间

            $record_operate_type = 11;
            $record_column_type = 'month';
            $record_before = $the_month;
            $record_after = $the_month;
        }
        else if($export_type == "date")
        {
            $the_date  = isset($post_data['date']) ? $post_data['date']  : date('Y-m-d');

            $record_operate_type = 31;
            $record_column_type = 'date';
            $record_before = $the_date;
            $record_after = $the_date;
        }
        else if($export_type == "period")
        {
            $the_start  = isset($post_data['order_start']) ? $post_data['order_start']  : date('Y-m-d');
            $the_ended  = isset($post_data['order_ended']) ? $post_data['order_ended']  : date('Y-m-d');

            $record_operate_type = 21;
            $record_column_type = 'period';
            $record_before = $the_start;
            $record_after = $the_ended;
        }
        else
        {
        }


        $client_id = 0;
        $staff_id = 0;
        $project_id = 0;

        // 员工
        if(!empty($post_data['staff']))
        {
            if(!in_array($post_data['staff'],[-1,0,'-1','0']))
            {
                $staff_id = $post_data['staff'];
            }
        }

        // 客户
        if(!empty($post_data['client']))
        {
            if(!in_array($post_data['client'],[-1,0,'-1','0']))
            {
                $client_id = $post_data['client'];
            }
        }

        // 项目
        $project_title = '';
        $record_data_title = '';
        if(!empty($post_data['project']))
        {
            $project = (int)$post_data['project'];
            if(!in_array($project,[-1,0]))
            {
                $project_id = $project;
                $project_er = WL_Common_Project::find($project_id);
                if($project_er)
                {
                    $project_title = '【'.$project_er->name.'】';
                    $record_data_title = $project_er->name;
                }
            }
        }


        $the_month = isset($post_data['month']) ? $post_data['month'] : date('Y-m');
        $the_date = isset($post_data['date']) ? $post_data['date'] : date('Y-m-d');


        // 工单
        $query = WL_Common_Order::select('*')
            ->with([
                'creator'=>function($query) { $query->select('id','name'); },
                'client_er'=>function($query) { $query->select('id','name'); },
                'project_er'=>function($query) { $query->select('id','name'); },
                'car_er'=>function($query) { $query->select('id','name','car_name'); },
                'trailer_er'=>function($query) { $query->select('id','name','sub_name'); },
                'driver_er'=>function($query) { $query->select('id','driver_name','driver_phone'); },
                'copilot_er'=>function($query) { $query->select('id','driver_name','driver_phone'); }
            ]);


        if($export_type == "all")
        {
        }
        else if($export_type == "month")
        {
            $query->whereBetween('assign_date',[$the_month_start_date,$the_month_ended_date]);
        }
        else if($export_type == "date")
        {
            $query->where('assign_date',$the_date);
        }
        else if($export_type == "period")
        {
            $query->whereBetween('assign_date',[$the_start,$the_ended]);
        }
        else
        {
//            if(!empty($post_data['order_start']))
//            {
//                $query->where('assign_date', '>=', $the_start);
//            }
//            if(!empty($post_data['order_ended']))
//            {
//                $query->where('assign_date', '<=', $the_ended);
//            }
        }


        if($staff_id) $query->where('creator_id',$staff_id);
        if($client_id) $query->where('client_id',$client_id);
        if($project_id) $query->where('project_id',$project_id);


        $data = $query->orderBy('assign_date','asc')->get();
        $data = $data->toArray();


        $cellData = [];
        foreach($data as $k => $v)
        {
            $cellData[$k]['id'] = $v['id'];


            // 类型
            if($v['car_owner_type'] == 1) $cellData[$k]['order_type'] = '自有';
            else if($v['car_owner_type'] == 9) $cellData[$k]['order_type'] = '共建';
            else if($v['car_owner_type'] == 11) $cellData[$k]['order_type'] = '外请';
            else $cellData[$k]['order_type'] = "有误";


            // 派单日期
            $cellData[$k]['assign_date'] = $v['assign_date'];
            // 任务日期
            $cellData[$k]['task_date'] = $v['task_date'];

            // 客户
//            $cellData[$k]['client_er_name'] = $v['client_er']['name'];
            // 项目
            $cellData[$k]['project_er_name'] = $v['project_er']['name'];



            // 车头
            $car_name = '';
            if($v['car_owner_type'] == 1 || $v['car_owner_type'] == 9)
            {
                $car_name = $v['car_er']['car_name'];
            }
            else
            {
                $car_name = $v['external_car'];
            }
            $cellData[$k]['car_er_name'] = $car_name;
            // 车挂
            $trailer_name = '';
            if($v['car_owner_type'] == 1 || $v['car_owner_type'] == 9)
            {
                $trailer_name = $v['trailer_er']['name'];
//                if($v['trailer_er']['sub_name']) $trailer_name .= ' '.$v['trailer_er']['sub_name'].'';
            }
            else
            {
                $trailer_name = $v['external_trailer'];
            }
            $cellData[$k]['trailer_er_name'] = $trailer_name;

            // 主驾
//            $cellData[$k]['driver_er_name'] = $v['driver_name'].''.$v['driver_phone'].'';
            // 副驾
//            $cellData[$k]['copilot_er_name'] = $v['copilot_name'].''.$v['copilot_phone'].'';
            // 驾驶员
//            $cellData[$k]['driver_er_list'] = $v['driver_name'].''.$v['driver_phone'].''."\r\n".$v['copilot_name'].''.$v['copilot_phone'].'';
            if($v['copilot_name']) $cellData[$k]['driver_er_list'] = $v['driver_name']." / ".$v['copilot_name'];
            else $cellData[$k]['driver_er_list'] = $v['driver_name'];

            // 车型
            $cellData[$k]['car_type'] = $v['car_type'];
            // 任务编号
            $cellData[$k]['task_number'] = $v['task_number'];
            // 出发地
            $cellData[$k]['transport_departure_place'] = $v['transport_departure_place'];
            // 目的地
            $cellData[$k]['transport_destination_place'] = $v['transport_destination_place'];
            // 线路
            $cellData[$k]['transport_route'] = $v['transport_route'];

            //
            $settlement_period_name = '';
            if($v['settlement_period'] == 1) $settlement_period_name = '单结';
            else if($v['settlement_period'] == 3) $settlement_period_name = '多结';
            else if($v['settlement_period'] == 7) $settlement_period_name = '周结';
            else if($v['settlement_period'] == 31) $settlement_period_name = '月结';
            else $settlement_period_name = '';
            $cellData[$k]['settlement_period_name'] = $settlement_period_name;

            // 运费·收
            $cellData[$k]['freight_amount'] = $v['freight_amount'];
            // 油卡·收
            $cellData[$k]['freight_oil_card_amount'] = $v['freight_oil_card_amount'];
            // 串点费·收
            $cellData[$k]['freight_extra_amount'] = $v['freight_extra_amount'];
            // 开票金额
            $cellData[$k]['financial_receipt_for_invoice_amount'] = $v['financial_receipt_for_invoice_amount'];
            // 票点
            $cellData[$k]['financial_receipt_for_invoice_point'] = $v['financial_receipt_for_invoice_point'];
            // 共建车费
            $cellData[$k]['cooperative_vehicle_amount'] = $v['cooperative_vehicle_amount'];
            // 请车费
            $cellData[$k]['external_car_price'] = $v['external_car_price'];
            // 信息费
            $cellData[$k]['financial_fee_for_information'] = $v['financial_fee_for_information'];
            // 安排人
            $cellData[$k]['arrange_people'] = $v['arrange_people'];
            // 收款人
            $cellData[$k]['payee_name'] = $v['payee_name'];
            // 车货源
            $cellData[$k]['car_supply'] = $v['car_supply'];
            // 备注
            $cellData[$k]['description'] = $v['description'];
            // 创建人
            $cellData[$k]['creator_name'] = $v['creator']['name'];
            // 创建时间
            $cellData[$k]['created_time'] = date('Y-m-d H:i:s', $v['created_at']);


        }


        $title_row = [
            'id'=>'ID',
            'order_type'=>'类型',
            'assign_date'=>'派单日期',
            'task_date'=>'任务日期',
            'project_er_name'=>'项目',
            'car_er_name'=>'车辆',
            'trailer_er_name'=>'车挂',
            'driver_er_list'=>'驾驶员',
//            'driver_er_name'=>'主驾',
//            'copilot_er_name'=>'副驾',
            'car_type'=>'车型',
            'task_number'=>'任务编号',
            'transport_departure_place'=>'出发地',
            'transport_destination_place'=>'目的地',
            'transport_route'=>'线路',
            'settlement_period_name'=>'账期',
            'freight_amount'=>'运费·收',
            'freight_oil_card_amount'=>'油卡·收',
            'freight_extra_amount'=>'串点费·收',
            'financial_receipt_for_invoice_amount'=>'开票金额',
            'financial_receipt_for_invoice_point'=>'票点',
            'cooperative_vehicle_amount'=>'共建车费',
            'external_car_price'=>'请车费',
            'financial_fee_for_information'=>'信息费',
            'arrange_people'=>'安排人',
            'payee_name'=>'收款人',
            'car_supply'=>'车货源',
            'description'=>'备注',
            'creator_name'=>'创建人',
            'created_time'=>'创建时间',
        ];
        array_unshift($cellData, $title_row);


//        $record = new DK_Common__Record__by_Operation;
//
//        $record_data["ip"] = Get_IP();
//        $record_data["record_object"] = 21;
//        $record_data["record_category"] = 11;
//        $record_data["record_type"] = 1;
//        $record_data["creator_id"] = $me->id;
//        $record_data["operate_object"] = 71;
//        $record_data["operate_category"] = 109;
//        $record_data["operate_type"] = $record_operate_type;
//        $record_data["column_type"] = $record_column_type;
//        $record_data["before"] = $record_before;
//        $record_data["after"] = $record_after;
//        if($project_id)
//        {
//            $record_data["item_id"] = $project_id;
//            $record_data["title"] = $record_data_title;
//        }
//
//        $record->fill($record_data)->save();


        $type_title = '';
        $time_title = '';
        if($export_type == "all")
        {
            $type_title = '【全部】';
        }
        elseif($export_type == "month")
        {
            $type_title = '【按月】';
            $time_title = $the_month;
        }
        else if($export_type == "date")
        {
            $type_title = '【按日】';
            $time_title = $the_date;
        }
        else if($export_type == "period")
        {
            $type_title = '【时间段】';
            $time_title = $the_start.' - '.$the_ended;
        }
        else
        {
        }


        $title = '【订单】'.date('Ymd.His').$project_title.$type_title.$time_title;

        $file = Excel::create($title, function($excel) use($cellData) {
            $excel->sheet('订单', function($sheet) use($cellData) {

                $sheet->rows($cellData);

                $sheet->setWidth(array(
                    'A'=>10,
                    'B'=>6,
                    'C'=>10,
                    'D'=>10,
                    'E'=>10,
                    'F'=>10,
                    'G'=>10,
                    'H'=>16,
                    'I'=>6,
                    'J'=>16,
                    'K'=>30,
                    'L'=>30,
                    'M'=>12,
                    'N'=>8,
                    'O'=>8,
                    'P'=>8,
                    'Q'=>8,
                    'R'=>8,
                    'S'=>8,
                    'T'=>8,
                    'U'=>8,
                    'V'=>8,
                    'W'=>10,
                    'X'=>10,
                    'Y'=>10,
                    'Z'=>30,
                    'AA'=>10,
                    'AB'=>20,
                    'AC'=>20
                ));
                $sheet->setAutoSize(false);
                $sheet->freezeFirstRow();


                $totalRows = count($cellData);

                if ($totalRows > 1) {
                    // 正确方法：通过 getStyle() 设置自动换行
                    $sheet->getStyle('H2:H' . $totalRows)->getAlignment()->setWrapText(true);

                    // 设置垂直对齐
                    $sheet->getStyle('H2:H' . $totalRows)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);


                    // 设置行高
//                    for ($i = 2; $i <= $totalRows; $i++) {
//                        $sheet->setHeight($i, 40);
//                    }
                }

            });
        })->export('xlsx');

    }




}