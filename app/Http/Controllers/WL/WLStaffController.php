<?php
namespace App\Http\Controllers\WL;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\WL\Common\WL_Common_Staff;

use App\Repositories\WL\WLStaffRepository;

use App\Repositories\WL\Staff\WLStaffCommonRepository;

use App\Repositories\WL\Staff\WLStaffCompanyRepository;
use App\Repositories\WL\Staff\WLStaffDepartmentRepository;
use App\Repositories\WL\Staff\WLStaffTeamRepository;
use App\Repositories\WL\Staff\WLStaffStaffRepository;

use App\Repositories\WL\Staff\WLStaffMotorcadeRepository;
use App\Repositories\WL\Staff\WLStaffCarRepository;
use App\Repositories\WL\Staff\WLStaffDriverRepository;

use App\Repositories\WL\Staff\WLStaffClientRepository;
use App\Repositories\WL\Staff\WLStaffProjectRepository;

use App\Repositories\WL\Staff\WLStaffOrderRepository;

use Response, Auth, Validator, DB, Exception;
use QrCode, Excel;

class WLStaffController extends Controller
{
    //
    private $repo;

    private $common_repo;

    private $company_repo;
    private $department_repo;
    private $team_repo;
    private $staff_repo;

    private $motorcade_repo;
    private $car_repo;
    private $driver_repo;

    private $client_repo;
    private $project_repo;

    private $order_repo;

    public function __construct()
    {
        $this->repo = new WLStaffRepository;

        $this->common_repo = new WLStaffCommonRepository;

        $this->company_repo = new WLStaffCompanyRepository;
        $this->department_repo = new WLStaffDepartmentRepository;
        $this->team_repo = new WLStaffTeamRepository;
        $this->staff_repo = new WLStaffStaffRepository;

        $this->motorcade_repo = new WLStaffMotorcadeRepository;
        $this->car_repo = new WLStaffCarRepository;
        $this->driver_repo = new WLStaffDriverRepository;

        $this->client_repo = new WLStaffClientRepository;
        $this->project_repo = new WLStaffProjectRepository;

        $this->order_repo = new WLStaffOrderRepository;
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
    public function o1__select2__company()
    {
        return $this->common_repo->o1__select2__company(request()->all());
    }
    // 部门
    public function o1__select2__department()
    {
        return $this->common_repo->o1__select2__department(request()->all());
    }
    // 团队
    public function o1__select2__team()
    {
        return $this->common_repo->o1__select2__team(request()->all());
    }
    // 员工
    public function o1__select2__staff()
    {
        return $this->common_repo->o1__select2__staff(request()->all());
    }
    // 车队
    public function o1__select2__motorcade()
    {
        return $this->common_repo->o1__select2__motorcade(request()->all());
    }
    // 车辆
    public function o1__select2__car()
    {
        return $this->common_repo->o1__select2__car(request()->all());
    }
    // 驾驶员
    public function o1__select2__driver()
    {
        return $this->common_repo->o1__select2__driver(request()->all());
    }
    // 客户
    public function o1__select2__client()
    {
        return $this->common_repo->o1__select2__client(request()->all());
    }
    // 项目
    public function o1__select2__project()
    {
        return $this->common_repo->o1__select2__project(request()->all());
    }
    // 订单
    public function o1__select2__order()
    {
        return $this->common_repo->o1__select2__order(request()->all());
    }







    // 【通用】启用
    public function v1_operate__universal_item_enable()
    {
        $item_category = request('item_category','');

        if($item_category == 'company')
        {
            return $this->repo->o1__company__item_enable(request()->all());
        }
        else if($item_category == 'department')
        {
            return $this->repo->o1__department__item_enable(request()->all());
        }
        else if($item_category == 'team')
        {
            return $this->repo->o1__team__item_enable(request()->all());
        }
        else if($item_category == 'staff')
        {
            return $this->repo->o1__staff__item_enable(request()->all());
        }
        else if($item_category == 'car')
        {
            return $this->repo->o1__car__item_enable(request()->all());
        }
        else if($item_category == 'driver')
        {
            return $this->repo->o1__driver__item_enable(request()->all());
        }
        else if($item_category == 'client')
        {
            return $this->repo->o1__client__item_enable(request()->all());
        }
        else if($item_category == 'project')
        {
            return $this->repo->o1__project__item_enable(request()->all());
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
            return $this->repo->o1__company__item_disable(request()->all());
        }
        else if($item_category == 'department')
        {
            return $this->repo->o1__department__item_disable(request()->all());
        }
        else if($item_category == 'team')
        {
            return $this->repo->o1__team__item_disable(request()->all());
        }
        else if($item_category == 'staff')
        {
            return $this->repo->o1__staff__item_disable(request()->all());
        }
        else if($item_category == 'car')
        {
            return $this->repo->o1__car__item_disable(request()->all());
        }
        else if($item_category == 'driver')
        {
            return $this->repo->o1__driver__item_disable(request()->all());
        }
        else if($item_category == 'client')
        {
            return $this->repo->o1__client__item_disable(request()->all());
        }
        else if($item_category == 'project')
        {
            return $this->repo->o1__project__item_disable(request()->all());
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








    // 【公司】datatable
    public function o1__company__list__datatable_query()
    {
        return $this->company_repo->o1__company__list__datatable_query(request()->all());
    }
    // 【公司】获取
    public function o1__company__item_get()
    {
        return $this->company_repo->o1__company__item_get(request()->all());
    }
    // 【公司】编辑-保存
    public function o1__company__item_save()
    {
        return $this->company_repo->o1__company__item_save(request()->all());
    }
    // 【公司】删除
    public function o1__company__item_delete()
    {
        return $this->company_repo->o1__company__item_delete(request()->all());
    }
    // 【公司】恢复
    public function o1__company__item_restore()
    {
        return $this->company_repo->o1__company__item_restore(request()->all());
    }
    // 【公司】彻底删除
    public function o1__company__item_delete_permanently()
    {
        return $this->company_repo->o1__company__item_delete_permanently(request()->all());
    }
    // 【公司】启用
    public function o1__company__item_enable()
    {
        return $this->company_repo->o1__company__item_enable(request()->all());
    }
    // 【公司】禁用
    public function o1__company__item_disable()
    {
        return $this->company_repo->o1__company__item_disable(request()->all());
    }
    // 【公司】操作记录
    public function o1__company__item_operation_record_list__datatable_query()
    {
        return $this->company_repo->o1__company__item_operation_record_list__datatable_query(request()->all());
    }








    // 【部门】datatable
    public function o1__department__list__datatable_query()
    {
        return $this->department_repo->o1__department__list__datatable_query(request()->all());
    }
    // 【部门】获取
    public function o1__department__item_get()
    {
        return $this->department_repo->o1__department__item_get(request()->all());
    }
    // 【部门】编辑-保存
    public function o1__department__item_save()
    {
        return $this->department_repo->o1__department__item_save(request()->all());
    }
    // 【部门】删除
    public function o1__department__item_delete()
    {
        return $this->department_repo->o1__department__item_delete(request()->all());
    }
    // 【部门】恢复
    public function o1__department__item_restore()
    {
        return $this->department_repo->o1__department__item_restore(request()->all());
    }
    // 【部门】彻底删除
    public function o1__department__item_delete_permanently()
    {
        return $this->department_repo->o1__department__item_delete_permanently(request()->all());
    }
    // 【部门】启用
    public function o1__department__item_enable()
    {
        return $this->department_repo->o1__department__item_enable(request()->all());
    }
    // 【部门】禁用
    public function o1__department__item_disable()
    {
        return $this->department_repo->o1__department__item_disable(request()->all());
    }
    // 【部门】操作记录
    public function o1__department__item_operation_record_list__datatable_query()
    {
        return $this->department_repo->o1__department__item_operation_record_list__datatable_query(request()->all());
    }








    // 【团队】datatable
    public function o1__team__list__datatable_query()
    {
        return $this->team_repo->o1__team__list__datatable_query(request()->all());
    }
    // 【团队】获取
    public function o1__team__item_get()
    {
        return $this->team_repo->o1__team__item_get(request()->all());
    }
    // 【团队】编辑-保存
    public function o1__team__item_save()
    {
        return $this->team_repo->o1__team__item_save(request()->all());
    }
    // 【团队】删除
    public function o1__team__item_delete()
    {
        return $this->team_repo->o1__team__item_delete(request()->all());
    }
    // 【团队】恢复
    public function o1__team__item_restore()
    {
        return $this->team_repo->o1__team__item_restore(request()->all());
    }
    // 【团队】彻底删除
    public function o1__team__item_delete_permanently()
    {
        return $this->team_repo->o1__team__item_delete_permanently(request()->all());
    }
    // 【团队】启用
    public function o1__team__item_enable()
    {
        return $this->team_repo->o1__team__item_enable(request()->all());
    }
    // 【团队】禁用
    public function o1__team__item_disable()
    {
        return $this->team_repo->o1__team__item_disable(request()->all());
    }
    // 【团队】操作记录
    public function o1__team__item_operation_record_list__datatable_query()
    {
        return $this->team_repo->o1__team__item_operation_record_list__datatable_query(request()->all());
    }








    // 【员工】datatable
    public function o1__staff__list__datatable_query()
    {
        return $this->staff_repo->o1__staff__list__datatable_query(request()->all());
    }
    // 【员工】获取
    public function o1__staff__item_get()
    {
        return $this->staff_repo->o1__staff__item_get(request()->all());
    }
    // 【员工】编辑-保存
    public function o1__staff__item_save()
    {
        return $this->staff_repo->o1__staff__item_save(request()->all());
    }
    // 【团队】删除
    public function o1__staff__item_delete()
    {
        return $this->staff_repo->o1__staff__item_delete(request()->all());
    }
    // 【团队】恢复
    public function o1__staff__item_restore()
    {
        return $this->staff_repo->o1__staff__item_restore(request()->all());
    }
    // 【团队】彻底删除
    public function o1__staff__item_delete_permanently()
    {
        return $this->staff_repo->o1__staff__item_delete_permanently(request()->all());
    }
    // 【员工】启用
    public function o1__staff__item_enable()
    {
        return $this->staff_repo->o1__staff__item_enable(request()->all());
    }
    // 【员工】禁用
    public function o1__staff__item_disable()
    {
        return $this->staff_repo->o1__staff__item_disable(request()->all());
    }
    // 【员工】操作记录
    public function o1__staff__item_operation_record_list__datatable_query()
    {
        return $this->staff_repo->o1__staff__item_operation_record_list__datatable_query(request()->all());
    }








    // 【车队】datatable
    public function o1__motorcade__list__datatable_query()
    {
        return $this->motorcade_repo->o1__motorcade__list__datatable_query(request()->all());
    }
    // 【车队】获取
    public function o1__motorcade__item_get()
    {
        return $this->motorcade_repo->o1__motorcade__item_get(request()->all());
    }
    // 【车队】编辑-保存
    public function o1__motorcade__item_save()
    {
        return $this->motorcade_repo->o1__motorcade__item_save(request()->all());
    }
    // 【车队】删除
    public function o1__motorcade__item_delete()
    {
        return $this->motorcade_repo->o1__motorcade__item_delete(request()->all());
    }
    // 【车队】恢复
    public function o1__motorcade__item_restore()
    {
        return $this->motorcade_repo->o1__motorcade__item_restore(request()->all());
    }
    // 【车队】彻底删除
    public function o1__motorcade__item_delete_permanently()
    {
        return $this->motorcade_repo->o1__motorcade__item_delete_permanently(request()->all());
    }
    // 【车队】启用
    public function o1__motorcade__item_enable()
    {
        return $this->motorcade_repo->o1__motorcade__item_enable(request()->all());
    }
    // 【车队】禁用
    public function o1__motorcade__item_disable()
    {
        return $this->motorcade_repo->o1__motorcade__item_disable(request()->all());
    }
    // 【车队】操作记录
    public function o1__motorcade__item_operation_record_list__datatable_query()
    {
        return $this->motorcade_repo->o1__motorcade__item_operation_record_list__datatable_query(request()->all());
    }








    // 【车辆】datatable
    public function o1__car__list__datatable_query()
    {
        return $this->car_repo->o1__car__list__datatable_query(request()->all());
    }
    // 【车辆】获取
    public function o1__car__item_get()
    {
        return $this->car_repo->o1__car__item_get(request()->all());
    }
    // 【车辆】编辑-保存
    public function o1__car__item_save()
    {
        return $this->car_repo->o1__car__item_save(request()->all());
    }
    // 【车辆】删除
    public function o1__car__item_delete()
    {
        return $this->car_repo->o1__car__item_delete(request()->all());
    }
    // 【车辆】恢复
    public function o1__car__item_restore()
    {
        return $this->car_repo->o1__car__item_restore(request()->all());
    }
    // 【车辆】彻底删除
    public function o1__car__item_delete_permanently()
    {
        return $this->car_repo->o1__car__item_delete_permanently(request()->all());
    }
    // 【车辆】启用
    public function o1__car__item_enable()
    {
        return $this->car_repo->o1__car__item_enable(request()->all());
    }
    // 【车辆】禁用
    public function o1__car__item_disable()
    {
        return $this->car_repo->o1__car__item_disable(request()->all());
    }
    // 【车辆】操作记录
    public function o1__car__item_operation_record_list__datatable_query()
    {
        return $this->car_repo->o1__car__item_operation_record_list__datatable_query(request()->all());
    }








    // 【驾驶员】datatable
    public function o1__driver__list__datatable_query()
    {
        return $this->driver_repo->o1__driver__list__datatable_query(request()->all());
    }
    // 【驾驶员】获取
    public function o1__driver__item_get()
    {
        return $this->driver_repo->o1__driver__item_get(request()->all());
    }
    // 【驾驶员】编辑-保存
    public function o1__driver__item_save()
    {
        return $this->driver_repo->o1__driver__item_save(request()->all());
    }
    // 【驾驶员】删除
    public function o1__driver__item_delete()
    {
        return $this->driver_repo->o1__driver__item_delete(request()->all());
    }
    // 【驾驶员】恢复
    public function o1__driver__item_restore()
    {
        return $this->driver_repo->o1__driver__item_restore(request()->all());
    }
    // 【驾驶员】彻底删除
    public function o1__driver__item_delete_permanently()
    {
        return $this->driver_repo->o1__driver__item_delete_permanently(request()->all());
    }
    // 【驾驶员】启用
    public function o1__driver__item_enable()
    {
        return $this->driver_repo->o1__driver__item_enable(request()->all());
    }
    // 【驾驶员】禁用
    public function o1__driver__item_disable()
    {
        return $this->driver_repo->o1__driver__item_disable(request()->all());
    }
    // 【驾驶员】操作记录
    public function o1__driver__item_operation_record_list__datatable_query()
    {
        return $this->driver_repo->o1__driver__item_operation_record_list__datatable_query(request()->all());
    }








    // 【客户】datatable
    public function o1__client__list__datatable_query()
    {
        return $this->client_repo->o1__client__list__datatable_query(request()->all());
    }
    // 【客户】获取
    public function o1__client__item_get()
    {
        return $this->client_repo->o1__client__item_get(request()->all());
    }
    // 【客户】编辑-保存
    public function o1__client__item_save()
    {
        return $this->client_repo->o1__client__item_save(request()->all());
    }
    // 【客户】删除
    public function o1__client__item_delete()
    {
        return $this->client_repo->o1__client__item_delete(request()->all());
    }
    // 【客户】恢复
    public function o1__client__item_restore()
    {
        return $this->client_repo->o1__client__item_restore(request()->all());
    }
    // 【客户】彻底删除
    public function o1__client__item_delete_permanently()
    {
        return $this->client_repo->o1__client__item_delete_permanently(request()->all());
    }
    // 【客户】启用
    public function o1__client__item_enable()
    {
        return $this->client_repo->o1__client__item_enable(request()->all());
    }
    // 【客户】禁用
    public function o1__client__item_disable()
    {
        return $this->client_repo->o1__client__item_disable(request()->all());
    }
    // 【客户】操作记录
    public function o1__client__item_operation_record_list__datatable_query()
    {
        return $this->client_repo->o1__client__item_operation_record_list__datatable_query(request()->all());
    }




    // 【项目】datatable
    public function o1__project__list__datatable_query()
    {
        return $this->project_repo->o1__project__list__datatable_query(request()->all());
    }
    // 【项目】获取
    public function o1__project__item_get()
    {
        return $this->project_repo->o1__project__item_get(request()->all());
    }
    // 【项目】编辑-保存
    public function o1__project__item_save()
    {
        return $this->project_repo->o1__project__item_save(request()->all());
    }
    // 【项目】删除
    public function o1__project__item_delete()
    {
        return $this->project_repo->o1__project__item_delete(request()->all());
    }
    // 【项目】恢复
    public function o1__project__item_restore()
    {
        return $this->project_repo->o1__project__item_restore(request()->all());
    }
    // 【项目】彻底删除
    public function o1__project__item_delete_permanently()
    {
        return $this->project_repo->o1__project__item_delete_permanently(request()->all());
    }
    // 【项目】启用
    public function o1__project__item_enable()
    {
        return $this->project_repo->o1__project__item_enable(request()->all());
    }
    // 【项目】禁用
    public function o1__project__item_disable()
    {
        return $this->project_repo->o1__project__item_disable(request()->all());
    }
    // 【项目】操作记录
    public function o1__project__item_operation_record_list__datatable_query()
    {
        return $this->project_repo->o1__project__item_operation_record_list__datatable_query(request()->all());
    }








    /*
     * ORDER - 工单
     */
    // 【工单】datatable
    public function o1__order__list__datatable_query()
    {
        return $this->order_repo->o1__order__list__datatable_query(request()->all());
    }
    // 【工单】获取
    public function o1__order__item_get()
    {
        return $this->order_repo->o1__order__item_get(request()->all());
    }
    // 【工单】保存
    public function o1__order__item_save()
    {
        return $this->order_repo->o1__order__item_save(request()->all());
    }


    // 【工单】操作
    public function o1__order__item_publish()
    {
        return $this->order_repo->o1__order__item_publish(request()->all());
    }


    // 【工单】【全部操作】操作记录
    public function o1__order__item_operation_record_list__datatable_query()
    {
        return $this->order_repo->o1__order__item_operation_record_list__datatable_query(request()->all());
    }
    // 【工单】【行程】列表
    public function o1__order__item_journey_record_list__datatable_query()
    {
        return $this->order_repo->o1__order__item_journey_record_list__datatable_query(request()->all());
    }
    // 【工单】【费用】记录
    public function o1__order__item_fee_record_list__datatable_query()
    {
        return $this->order_repo->o1__order__item_fee_record_list__datatable_query(request()->all());
    }


    // 【工单】【跟进】保存
    public function o1__order__item_follow_save()
    {
        return $this->order_repo->o1__order__item_follow_save(request()->all());
    }
    // 【工单】【行程】保存
    public function o1__order__item_journey_save()
    {
        return $this->order_repo->o1__order__item_journey_save(request()->all());
    }
    // 【工单】【费用】保存
    public function o1__order__item_fee_save()
    {
        return $this->order_repo->o1__order__item_fee_save(request()->all());
    }
    // 【工单】【交费易】保存
    public function o1__order__item_trade_save()
    {
        return $this->order_repo->o1__order__item_trade_save(request()->all());
    }








    // 【费用】datatable
    public function o1__fee__datatable_list_query()
    {
        return $this->repo->o1__fee__datatable_list_query(request()->all());
    }
    // 【费用】获取
    public function o1__fee__item_get()
    {
        return $this->repo->o1__fee__item_get(request()->all());
    }
    // 【费用】编辑-保存
    public function o1__fee__item_save()
    {
        return $this->repo->o1__fee__item_save(request()->all());
    }
    // 【费用】财务-保存
    public function o1__fee__item_financial_save()
    {
        return $this->repo->o1__fee__item_financial_save(request()->all());
    }




    // 【费用】datatable
    public function o1__finance__datatable_list_query()
    {
        return $this->repo->o1__finance__datatable_list_query(request()->all());
    }
    // 【费用】获取
    public function o1__finance__item_get()
    {
        return $this->repo->o1__finance__item_get(request()->all());
    }
    // 【费用】编辑-保存
    public function o1__finance__item_save()
    {
        return $this->repo->o1__finance__item_save(request()->all());
    }






    // 【统计】【客户】日报
    public function o1__get_statistic_data__of__statistic_client_by_daily()
    {
        return $this->repo->o1__get_statistic_data__of__statistic_client_by_daily(request()->all());
    }
    // 【统计】【客户】订单-日报
    public function o1__get_statistic_data__of__statistic_client_by_daily_for_order()
    {
        return $this->repo->o1__get_statistic_data__of__statistic_client_by_daily_for_order(request()->all());
    }
    // 【统计】【客户】费用-日报
    public function o1__get_statistic_data__of__statistic_client_by_daily_for_fee()
    {
        return $this->repo->o1__get_statistic_data__of__statistic_client_by_daily_for_fee(request()->all());
    }


    // 【统计】【项目】日报
    public function o1__get_statistic_data__of__statistic_project_by_daily()
    {
        return $this->repo->o1__get_statistic_data__of__statistic_project_by_daily(request()->all());
    }
    // 【统计】【项目】订单-日报
    public function o1__get_statistic_data__of__statistic_project_by_daily_for_order()
    {
        return $this->repo->o1__get_statistic_data__of__statistic_project_by_daily_for_order(request()->all());
    }
    // 【统计】【项目】费用-日报
    public function o1__get_statistic_data__of__statistic_project_by_daily_for_fee()
    {
        return $this->repo->o1__get_statistic_data__of__statistic_project_by_daily_for_fee(request()->all());
    }


    // 【统计】【订单】日报
    public function o1__get_statistic_data__of__statistic_order_by_daily()
    {
        return $this->repo->o1__get_statistic_data__of__statistic_order_by_daily(request()->all());
    }


    // 【统计】【车辆】日报
    public function o1__get_statistic_data__of__statistic_car_by_daily()
    {
        return $this->repo->o1__get_statistic_data__of__statistic_car_by_daily(request()->all());
    }
    // 【统计】【车辆】订单-日报
    public function o1__get_statistic_data__of__statistic_car_by_daily_for_order()
    {
        return $this->repo->o1__get_statistic_data__of__statistic_car_by_daily_for_order(request()->all());
    }
    // 【统计】【车辆】费用-日报
    public function o1__get_statistic_data__of__statistic_car_by_daily_for_fee()
    {
        return $this->repo->o1__get_statistic_data__of__statistic_car_by_daily_for_fee(request()->all());
    }


    // 【统计】【驾驶员】日报
    public function o1__get_statistic_data__of__statistic_driver_by_daily()
    {
        return $this->repo->o1__get_statistic_data__of__statistic_driver_by_daily(request()->all());
    }
    // 【统计】【驾驶员】订单-日报
    public function o1__get_statistic_data__of__statistic_driver_by_daily_for_order()
    {
        return $this->repo->o1__get_statistic_data__of__statistic_driver_by_daily_for_order(request()->all());
    }
    // 【统计】【驾驶员】费用-日报
    public function o1__get_statistic_data__of__statistic_driver_by_daily_for_fee()
    {
        return $this->repo->o1__get_statistic_data__of__statistic_driver_by_daily_for_fee(request()->all());
    }


    // 【统计】【员工】日报
    public function o1__get_statistic_data__of__statistic_staff_by_daily()
    {
        return $this->repo->o1__get_statistic_data__of__statistic_staff_by_daily(request()->all());
    }

}
