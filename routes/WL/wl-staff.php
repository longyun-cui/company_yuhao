<?php


Route::get('/', function () {
    dd('route-wl-staff');
});


$controller = "WLStaffController";

Route::match(['get','post'], 'login', $controller.'@login');
Route::match(['get','post'], 'logout', $controller.'@logout');


/*
 * 超级管理员系统（后台）
 * 需要登录
 */
Route::group(['middleware' => ['wl.staff.login']], function () {

    $controller = 'WLStaffController';


    Route::get('/', $controller.'@view_staff_index');
    Route::get('/404', $controller.'@view_staff_404');


    /*
     * 个人信息管理
     */




    // select2
    Route::post('/o1/select2/select2-company', $controller.'@v1_operate_select2_company');
    Route::post('/o1/select2/select2-department', $controller.'@v1_operate_select2_department');
    Route::post('/o1/select2/select2-team', $controller.'@v1_operate_select2_team');
    Route::post('/o1/select2/select2-staff', $controller.'@v1_operate_select2_staff');
    Route::post('/o1/select2/select2-car', $controller.'@v1_operate_select2_car');
    Route::post('/o1/select2/select2-driver', $controller.'@v1_operate_select2_driver');
    Route::post('/o1/select2/select2-client', $controller.'@v1_operate_select2_client');
    Route::post('/o1/select2/select2-project', $controller.'@v1_operate_select2_project');
    Route::post('/o1/select2/select2-order', $controller.'@v1_operate_select2_order');




    // 【通用】启用 & 禁用
    Route::post('/o1/universal/item-enable', $controller.'@v1_operate__universal_item_enable');
    Route::post('/o1/universal/item-disable', $controller.'@v1_operate__universal_item_disable');
    // 【通用】字段修改
    Route::post('/o1/universal/field-set', $controller.'@v1_operate__universal_field_set');




    // 【公司】
    Route::post('/o1/company/datatable-list-query', $controller.'@o1__company__datatable_list_query');
    Route::post('/o1/company/item-get', $controller.'@o1__company__item_get');
    Route::post('/o1/company/item-save', $controller.'@o1__company__item_save');
    // 【公司】启用 & 禁用
    Route::post('/o1/company/item-enable', $controller.'@o1__company__item_enable');
    Route::post('/o1/company/item-disable', $controller.'@o1__company__item_disable');




    // 【部门】
    Route::post('/o1/department/datatable-list-query', $controller.'@o1__department__datatable_list_query');
    Route::post('/o1/department/item-get', $controller.'@o1__department__item_get');
    Route::post('/o1/department/item-save', $controller.'@o1__department__item_save');
    // 【部门】启用 & 禁用
    Route::post('/o1/department/item-enable', $controller.'@o1__department__item_enable');
    Route::post('/o1/department/item-disable', $controller.'@o1__department__item_disable');




    // 【团队】
    Route::post('/o1/team/datatable-list-query', $controller.'@o1__team__datatable_list_query');
    Route::post('/o1/team/item-get', $controller.'@o1__team__item_get');
    Route::post('/o1/team/item-save', $controller.'@o1__team__item_save');
    // 【团队】启用 & 禁用
    Route::post('/o1/team/item-enable', $controller.'@o1__team__item_enable');
    Route::post('/o1/team/item-disable', $controller.'@o1__team__item_disable');




    // 【员工】
    Route::post('/o1/staff/datatable-list-query', $controller.'@o1__staff__datatable_list_query');
    Route::post('/o1/staff/item-get', $controller.'@o1__staff__item_get');
    Route::post('/o1/staff/item-save', $controller.'@o1__staff__item_save');
    // 【员工】启用 & 禁用
    Route::post('/o1/staff/item-enable', $controller.'@o1__staff__item_enable');
    Route::post('/o1/staff/item-disable', $controller.'@o1__staff__item_disable');




    // 【车辆】
    Route::post('/o1/car/datatable-list-query', $controller.'@o1__car__datatable_list_query');
    Route::post('/o1/car/item-get', $controller.'@o1__car__item_get');
    Route::post('/o1/car/item-save', $controller.'@o1__car__item_save');
    // 【车辆】启用 & 禁用
    Route::post('/o1/car/item-enable', $controller.'@o1__car__item_enable');
    Route::post('/o1/car/item-disable', $controller.'@o1__car__item_disable');




    // 【司机】
    Route::post('/o1/driver/datatable-list-query', $controller.'@o1__driver__datatable_list_query');
    Route::post('/o1/driver/item-get', $controller.'@o1__driver__item_get');
    Route::post('/o1/driver/item-save', $controller.'@o1__driver__item_save');
    // 【司机】启用 & 禁用
    Route::post('/o1/driver/item-enable', $controller.'@o1__driver__item_enable');
    Route::post('/o1/driver/item-disable', $controller.'@o1__driver__item_disable');




    // 【客户】
    Route::post('/o1/client/datatable-list-query', $controller.'@o1__client__datatable_list_query');
    Route::post('/o1/client/item-get', $controller.'@o1__client__item_get');
    Route::post('/o1/client/item-save', $controller.'@o1__client__item_save');
    // 【客户】启用 & 禁用
    Route::post('/o1/client/item-enable', $controller.'@o1__client__item_enable');
    Route::post('/o1/client/item-disable', $controller.'@o1__client__item_disable');




    // 【项目】
    Route::post('/o1/project/datatable-list-query', $controller.'@o1__project__datatable_list_query');
    Route::post('/o1/project/item-get', $controller.'@o1__project__item_get');
    Route::post('/o1/project/item-save', $controller.'@o1__project__item_save');
    // 【项目】启用 & 禁用
    Route::post('/o1/project/item-enable', $controller.'@o1__project__item_enable');
    Route::post('/o1/project/item-disable', $controller.'@o1__project__item_disable');




    // 【工单】列表
    Route::post('/o1/order/datatable-list-query', $controller.'@o1__order__datatable_list_query');
    // 【工单】操作
    Route::post('/o1/order/item-get', $controller.'@o1__order__item_get');
    Route::post('/o1/order/item-save', $controller.'@o1__order__item_save');
    // 【工单】启用 & 禁用
    Route::post('/o1/project/item-enable', $controller.'@o1__order__item_enable');
    Route::post('/o1/project/item-disable', $controller.'@o1__order__item_disable');
    // 【工单】发布
    Route::post('/o1/order/item-publish', $controller.'@o1__order__item_publish');
    // 【工单】操作记录
    Route::post('/o1/order/item-operation-record-list/datatable-query', $controller.'@o1__order__item_operation_record_list_datatable_query');
    Route::post('/o1/order/item-fee-record-datatable-query', $controller.'@o1__order__item_fee_record_datatable_query');
    // 【工单】跟进
    Route::post('/o1/order/item-follow-save', $controller.'@o1__order__item_follow_save');
    Route::post('/o1/order/item-journey-save', $controller.'@o1__order__item_journey_save');
    Route::post('/o1/order/item-fee-save', $controller.'@o1__order__item_fee_save');
    Route::post('/o1/order/item-trade-save', $controller.'@o1__order__item_trade_save');




    // 【费用】
    Route::post('/o1/fee/datatable-list-query', $controller.'@o1__fee__datatable_list_query');
    Route::post('/o1/fee/item-get', $controller.'@o1__fee__item_get');
    Route::post('/o1/fee/item-save', $controller.'@o1__fee__item_save');
    // 【费用】财务
    Route::post('/o1/fee/item-financial-save', $controller.'@o1__fee__item_financial_save');




    // 【财务】
    Route::post('/o1/finance/datatable-list-query', $controller.'@o1__finance__datatable_list_query');
    Route::post('/o1/finance/item-get', $controller.'@o1__finance__item_get');
    Route::post('/o1/finance/item-save', $controller.'@o1__finance__item_save');




    // 【统计】客户
    Route::post('/o1/statistic/statistic-client-by-daily',
        $controller.'@o1__get_statistic_data__of__statistic_client_by_daily');
    Route::post('/o1/statistic/statistic-client-by-daily-for-order',
        $controller.'@o1__get_statistic_data__of__statistic_client_by_daily_for_order');
    Route::post('/o1/statistic/statistic-client-by-daily-for-fee',
        $controller.'@o1__get_statistic_data__of__statistic_client_by_daily_for_fee');

    // 【统计】项目
    Route::post('/o1/statistic/statistic-project-by-daily',
        $controller.'@o1__get_statistic_data__of__statistic_project_by_daily');
    Route::post('/o1/statistic/statistic-project-by-daily-for-order',
        $controller.'@o1__get_statistic_data__of__statistic_project_by_daily_for_order');
    Route::post('/o1/statistic/statistic-project-by-daily-for-fee',
        $controller.'@o1__get_statistic_data__of__statistic_project_by_daily_for_fee');

    // 【统计】订单
    Route::post('/o1/statistic/statistic-order-by-daily',
        $controller.'@o1__get_statistic_data__of__statistic_order_by_daily');

    // 【统计】车辆
    Route::post('/o1/statistic/statistic-car-by-daily',
        $controller.'@o1__get_statistic_data__of__statistic_car_by_daily');
    Route::post('/o1/statistic/statistic-car-by-daily-for-order',
        $controller.'@o1__get_statistic_data__of__statistic_car_by_daily_for_order');
    Route::post('/o1/statistic/statistic-car-by-daily-for-fee',
        $controller.'@o1__get_statistic_data__of__statistic_car_by_daily_for_fee');

    // 【统计】司机
    Route::post('/o1/statistic/statistic-driver-by-daily',
        $controller.'@o1__get_statistic_data__of__statistic_driver_by_daily');
    Route::post('/o1/statistic/statistic-driver-by-daily-for-order',
        $controller.'@o1__get_statistic_data__of__statistic_driver_by_daily_for_order');
    Route::post('/o1/statistic/statistic-driver-by-daily-for-fee',
        $controller.'@o1__get_statistic_data__of__statistic_driver_by_daily_for_fee');

    // 【统计】员工
    Route::post('/o1/statistic/statistic-staff-by-daily',
        $controller.'@o1__get_statistic_data__of__statistic_staff_by_daily');

    // 【统计】费用
    Route::post('/o1/statistic/statistic-fee-by-daily',
        $controller.'@o1__get_statistic_data__of__statistic_fee_by_daily');
    Route::post('/o1/statistic/statistic-fee-by-monthly',
        $controller.'@o1__get_statistic_data__of__statistic_fee_by_monthly');

    // 【统计】财务
    Route::post('/o1/statistic/statistic-finance-by-daily',
        $controller.'@o1__get_statistic_data__of__statistic_finance_by_daily');
    Route::post('/o1/statistic/statistic-finance-by-monthly',
        $controller.'@o1__get_statistic_data__of__statistic_finance_by_monthly');


});

