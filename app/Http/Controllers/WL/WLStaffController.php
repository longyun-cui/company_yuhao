<?php
namespace App\Http\Controllers\WL;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\WL\Common\WL_Common_Staff;

use App\Repositories\WL\WLStaffRepository;

use Response, Auth, Validator, DB, Exception;
use QrCode, Excel;

class WLStaffController extends Controller
{
    //
    private $repo;
    public function __construct()
    {
        $this->repo = new WLStaffRepository;
    }




    // 登陆
    public function login()
    {
        if(request()->isMethod('get'))
        {
            $view_blade = env('TEMPLATE_WL_STAFF').'entrance.login';
            return view($view_blade);
        }
        else if(request()->isMethod('post'))
        {
            $where['login_number'] = request()->get('login_number');
            $where['password'] = request()->get('password');

//            $email = request()->get('email');
//            $admin = SuperAdministrator::whereEmail($email)->first();

            $login_number = request()->get('login_number');
            $admin = WL_Common_Staff::where('login_number',$login_number)->first();

            if($admin)
            {
                if($admin->item_status == 1)
                {
                    $password = request()->get('password');
                    if(password_check($password,$admin->password))
                    {
                        $remember = request()->get('remember');
                        if($remember) Auth::guard('wl_staff')->login($admin,true);
                        else Auth::guard('wl_staff')->login($admin,true);
                        return response_success();
                    }
                    else return response_error([],'账户or密码不正确！');
                }
                else return response_error([],'账户已禁用！');
            }
            else return response_error([],'账户不存在！');
        }
    }

    // 退出
    public function logout()
    {
        Auth::guard('wl_staff')->logout();
        return redirect('/login');
    }




    // 返回主页视图
    public function view_staff_index()
    {
        return $this->repo->view_staff_index();
    }

    // 返回404视图
    public function view_staff_404()
    {
        return $this->repo->view_staff_404();
    }










    // 公司
    public function v1_operate_select2_company()
    {
        return $this->repo->v1_operate_select2_company(request()->all());
    }
    // 部门
    public function v1_operate_select2_department()
    {
        return $this->repo->v1_operate_select2_department(request()->all());
    }
    // 团队
    public function v1_operate_select2_team()
    {
        return $this->repo->v1_operate_select2_team(request()->all());
    }
    // 员工
    public function v1_operate_select2_staff()
    {
        return $this->repo->v1_operate_select2_staff(request()->all());
    }
    // 车辆
    public function v1_operate_select2_car()
    {
        return $this->repo->v1_operate_select2_car(request()->all());
    }
    // 司机
    public function v1_operate_select2_driver()
    {
        return $this->repo->v1_operate_select2_driver(request()->all());
    }
    // 客户
    public function v1_operate_select2_client()
    {
        return $this->repo->v1_operate_select2_client(request()->all());
    }
    // 项目
    public function v1_operate_select2_project()
    {
        return $this->repo->v1_operate_select2_project(request()->all());
    }
    // 订单
    public function v1_operate_select2_order()
    {
        return $this->repo->v1_operate_select2_order(request()->all());
    }




    // 【公司】datatable
    public function v1_operate__company_datatable_list_query()
    {
        return $this->repo->v1_operate__company_datatable_list_query(request()->all());
    }
    // 【公司】获取
    public function v1_operate__company_item_get()
    {
        return $this->repo->v1_operate__company_item_get(request()->all());
    }
    // 【公司】编辑-保存
    public function v1_operate__company_item_save()
    {
        return $this->repo->v1_operate__company_item_save(request()->all());
    }




    // 【部门】datatable
    public function v1_operate__department_datatable_list_query()
    {
        return $this->repo->v1_operate__department_datatable_list_query(request()->all());
    }
    // 【部门】获取
    public function v1_operate__department_item_get()
    {
        return $this->repo->v1_operate__department_item_get(request()->all());
    }
    // 【部门】编辑-保存
    public function v1_operate__department_item_save()
    {
        return $this->repo->v1_operate__department_item_save(request()->all());
    }




    // 【团队】datatable
    public function v1_operate__team_datatable_list_query()
    {
        return $this->repo->v1_operate__team_datatable_list_query(request()->all());
    }
    // 【团队】获取
    public function v1_operate__team_item_get()
    {
        return $this->repo->v1_operate__team_item_get(request()->all());
    }
    // 【团队】编辑-保存
    public function v1_operate__team_item_save()
    {
        return $this->repo->v1_operate__team_item_save(request()->all());
    }




    // 【员工】datatable
    public function v1_operate__staff_datatable_list_query()
    {
        return $this->repo->v1_operate__staff_datatable_list_query(request()->all());
    }
    // 【员工】获取
    public function v1_operate__staff_item_get()
    {
        return $this->repo->v1_operate__staff_item_get(request()->all());
    }
    // 【员工】编辑-保存
    public function v1_operate__staff_item_save()
    {
        return $this->repo->v1_operate__staff_item_save(request()->all());
    }




    // 【车辆】datatable
    public function v1_operate__car_datatable_list_query()
    {
        return $this->repo->v1_operate__car_datatable_list_query(request()->all());
    }
    // 【车辆】获取
    public function v1_operate__car_item_get()
    {
        return $this->repo->v1_operate__car_item_get(request()->all());
    }
    // 【车辆】编辑-保存
    public function v1_operate__car_item_save()
    {
        return $this->repo->v1_operate__car_item_save(request()->all());
    }




    // 【司机】datatable
    public function v1_operate__driver_datatable_list_query()
    {
        return $this->repo->v1_operate__driver_datatable_list_query(request()->all());
    }
    // 【司机】获取
    public function v1_operate__driver_item_get()
    {
        return $this->repo->v1_operate__driver_item_get(request()->all());
    }
    // 【司机】编辑-保存
    public function v1_operate__driver_item_save()
    {
        return $this->repo->v1_operate__driver_item_save(request()->all());
    }




    // 【客户】datatable
    public function v1_operate__client_datatable_list_query()
    {
        return $this->repo->v1_operate__client_datatable_list_query(request()->all());
    }
    // 【客户】获取
    public function v1_operate__client_item_get()
    {
        return $this->repo->v1_operate__client_item_get(request()->all());
    }
    // 【客户】编辑-保存
    public function v1_operate__client_item_save()
    {
        return $this->repo->v1_operate__client_item_save(request()->all());
    }




    // 【项目】datatable
    public function v1_operate__project_datatable_list_query()
    {
        return $this->repo->v1_operate__project_datatable_list_query(request()->all());
    }
    // 【项目】获取
    public function v1_operate__project_item_get()
    {
        return $this->repo->v1_operate__project_item_get(request()->all());
    }
    // 【项目】编辑-保存
    public function v1_operate__project_item_save()
    {
        return $this->repo->v1_operate__project_item_save(request()->all());
    }








    // 【工单】datatable
    public function v1_operate__order_datatable_list_query()
    {
        return $this->repo->v1_operate__order_datatable_list_query(request()->all());
    }
    // 【工单】获取
    public function v1_operate__order_item_get()
    {
        return $this->repo->v1_operate__order_item_get(request()->all());
    }
    // 【工单】编辑-保存
    public function v1_operate__order_item_save()
    {
        return $this->repo->v1_operate__order_item_save(request()->all());
    }


    // 【工单】操作
    public function v1_operate__order_item_publish()
    {
        return $this->repo->v1_operate__order_item_publish(request()->all());
    }

    // 【工单】操作记录
    public function v1_operate__order_item_operation_record_datatable_query()
    {
        return $this->repo->v1_operate__order_item_operation_record_datatable_query(request()->all());
    }
    // 【工单】操作记录
    public function v1_operate__order_item_fee_record_datatable_query()
    {
        return $this->repo->v1_operate__order_item_fee_record_datatable_query(request()->all());
    }


    // 【工单】客户编辑
    public function v1_operate__order_item_customer_save()
    {
        return $this->repo->v1_operate__order_item_customer_save(request()->all());
    }
    // 【工单】编辑-保存
    public function v1_operate__order_item_callback_save()
    {
        return $this->repo->v1_operate__order_item_callback_save(request()->all());
    }
    // 【工单】编辑-保存
    public function v1_operate__order_item_come_save()
    {
        return $this->repo->v1_operate__order_item_come_save(request()->all());
    }
    // 【工单】编辑-保存
    public function v1_operate__order_item_follow_save()
    {
        return $this->repo->v1_operate__order_item_follow_save(request()->all());
    }
    // 【工单】编辑-保存
    public function v1_operate__order_item_trade_save()
    {
        return $this->repo->v1_operate__order_item_trade_save(request()->all());
    }
    // 【工单】费用-保存
    public function v1_operate__order_item_fee_save()
    {
        return $this->repo->v1_operate__order_item_fee_save(request()->all());
    }




    // 【费用】datatable
    public function v1_operate__fee_datatable_list_query()
    {
        return $this->repo->v1_operate__fee_datatable_list_query(request()->all());
    }
    // 【费用】获取
    public function v1_operate__fee_item_get()
    {
        return $this->repo->v1_operate__fee_item_get(request()->all());
    }
    // 【费用】编辑-保存
    public function v1_operate__fee_item_save()
    {
        return $this->repo->v1_operate__fee_item_save(request()->all());
    }
    // 【费用】财务-保存
    public function v1_operate__fee_item_financial_save()
    {
        return $this->repo->v1_operate__fee_item_financial_save(request()->all());
    }




    // 【费用】datatable
    public function v1_operate__finance_datatable_list_query()
    {
        return $this->repo->v1_operate__finance_datatable_list_query(request()->all());
    }
    // 【费用】获取
    public function v1_operate__finance_item_get()
    {
        return $this->repo->v1_operate__finance_item_get(request()->all());
    }
    // 【费用】编辑-保存
    public function v1_operate__finance_item_save()
    {
        return $this->repo->v1_operate__finance_item_save(request()->all());
    }







    // 【通用】启用
    public function v1_operate__universal_item_enable()
    {
        $item_category = request('item_category','');

        if($item_category == 'company')
        {
            return $this->repo->v1_operate__company_item_enable(request()->all());
        }
        else if($item_category == 'department')
        {
            return $this->repo->v1_operate__department_item_enable(request()->all());
        }
        else if($item_category == 'team')
        {
            return $this->repo->v1_operate__team_item_enable(request()->all());
        }
        else if($item_category == 'staff')
        {
            return $this->repo->v1_operate__staff_item_enable(request()->all());
        }
        else if($item_category == 'car')
        {
            return $this->repo->v1_operate__car_item_enable(request()->all());
        }
        else if($item_category == 'driver')
        {
            return $this->repo->v1_operate__driver_item_enable(request()->all());
        }
        else if($item_category == 'client')
        {
            return $this->repo->v1_operate__client_item_enable(request()->all());
        }
        else if($item_category == 'project')
        {
            return $this->repo->v1_operate__project_item_enable(request()->all());
        }
        else
        {
            return response_fail([]);
        }
    }
    // 【通用】禁用
    public function v1_operate__universal_item_disable()
    {
        $item_category = request('item_category','');

        if($item_category == 'company')
        {
            return $this->repo->v1_operate__company_item_disable(request()->all());
        }
        else if($item_category == 'department')
        {
            return $this->repo->v1_operate__department_item_disable(request()->all());
        }
        else if($item_category == 'team')
        {
            return $this->repo->v1_operate__team_item_disable(request()->all());
        }
        else if($item_category == 'staff')
        {
            return $this->repo->v1_operate__staff_item_disable(request()->all());
        }
        else if($item_category == 'car')
        {
            return $this->repo->v1_operate__car_item_disable(request()->all());
        }
        else if($item_category == 'driver')
        {
            return $this->repo->v1_operate__driver_item_disable(request()->all());
        }
        else if($item_category == 'client')
        {
            return $this->repo->v1_operate__client_item_disable(request()->all());
        }
        else if($item_category == 'project')
        {
            return $this->repo->v1_operate__project_item_disable(request()->all());
        }
        else if($item_category == 'location')
        {
            return $this->repo->v1_operate__location_item_disable(request()->all());
        }
        else
        {
            return response_fail([]);
        }
    }






    // 【统计】【客户】日报
    public function v1_operate__get_statistic_data_of_statistic_client_by_daily()
    {
        return $this->repo->v1_operate__get_statistic_data_of_statistic_client_by_daily(request()->all());
    }
    // 【统计】【客户】订单-日报
    public function v1_operate__get_statistic_data_of_statistic_client_by_daily_for_order()
    {
        return $this->repo->v1_operate__get_statistic_data_of_statistic_client_by_daily_for_order(request()->all());
    }
    // 【统计】【客户】费用-日报
    public function v1_operate__get_statistic_data_of_statistic_client_by_daily_for_fee()
    {
        return $this->repo->v1_operate__get_statistic_data_of_statistic_client_by_daily_for_fee(request()->all());
    }


    // 【统计】【项目】日报
    public function v1_operate__get_statistic_data_of_statistic_project_by_daily()
    {
        return $this->repo->v1_operate__get_statistic_data_of_statistic_project_by_daily(request()->all());
    }
    // 【统计】【项目】订单-日报
    public function v1_operate__get_statistic_data_of_statistic_project_by_daily_for_order()
    {
        return $this->repo->v1_operate__get_statistic_data_of_statistic_project_by_daily_for_order(request()->all());
    }
    // 【统计】【项目】费用-日报
    public function v1_operate__get_statistic_data_of_statistic_project_by_daily_for_fee()
    {
        return $this->repo->v1_operate__get_statistic_data_of_statistic_project_by_daily_for_fee(request()->all());
    }


    // 【统计】【订单】日报
    public function v1_operate__get_statistic_data_of_statistic_order_by_daily()
    {
        return $this->repo->v1_operate__get_statistic_data_of_statistic_order_by_daily(request()->all());
    }


    // 【统计】【车辆】日报
    public function v1_operate__get_statistic_data_of_statistic_car_by_daily()
    {
        return $this->repo->v1_operate__get_statistic_data_of_statistic_car_by_daily(request()->all());
    }
    // 【统计】【车辆】订单-日报
    public function v1_operate__get_statistic_data_of_statistic_car_by_daily_for_order()
    {
        return $this->repo->v1_operate__get_statistic_data_of_statistic_car_by_daily_for_order(request()->all());
    }
    // 【统计】【车辆】费用-日报
    public function v1_operate__get_statistic_data_of_statistic_car_by_daily_for_fee()
    {
        return $this->repo->v1_operate__get_statistic_data_of_statistic_car_by_daily_for_fee(request()->all());
    }


    // 【统计】【司机】日报
    public function v1_operate__get_statistic_data_of_statistic_driver_by_daily()
    {
        return $this->repo->v1_operate__get_statistic_data_of_statistic_driver_by_daily(request()->all());
    }
    // 【统计】【司机】订单-日报
    public function v1_operate__get_statistic_data_of_statistic_driver_by_daily_for_order()
    {
        return $this->repo->v1_operate__get_statistic_data_of_statistic_driver_by_daily_for_order(request()->all());
    }
    // 【统计】【司机】费用-日报
    public function v1_operate__get_statistic_data_of_statistic_driver_by_daily_for_fee()
    {
        return $this->repo->v1_operate__get_statistic_data_of_statistic_driver_by_daily_for_fee(request()->all());
    }


    // 【统计】【员工】日报
    public function v1_operate__get_statistic_data_of_statistic_staff_by_daily()
    {
        return $this->repo->v1_operate__get_statistic_data_of_statistic_staff_by_daily(request()->all());
    }

}
