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
    Route::post('/v1/operate/select2/select2-company', $controller.'@v1_operate_select2_company');
    Route::post('/v1/operate/select2/select2-department', $controller.'@v1_operate_select2_department');
    Route::post('/v1/operate/select2/select2-team', $controller.'@v1_operate_select2_team');
    Route::post('/v1/operate/select2/select2-staff', $controller.'@v1_operate_select2_staff');
    Route::post('/v1/operate/select2/select2-car', $controller.'@v1_operate_select2_car');
    Route::post('/v1/operate/select2/select2-driver', $controller.'@v1_operate_select2_driver');
    Route::post('/v1/operate/select2/select2-client', $controller.'@v1_operate_select2_client');
    Route::post('/v1/operate/select2/select2-project', $controller.'@v1_operate_select2_project');
    Route::post('/v1/operate/select2/select2-order', $controller.'@v1_operate_select2_order');


    // 【公司】
    Route::post('/v1/operate/company/datatable-list-query', $controller.'@v1_operate__company_datatable_list_query');
    Route::post('/v1/operate/company/item-get', $controller.'@v1_operate__company_item_get');
    Route::post('/v1/operate/company/item-save', $controller.'@v1_operate__company_item_save');


    // 【部门】
    Route::post('/v1/operate/department/datatable-list-query', $controller.'@v1_operate__department_datatable_list_query');
    Route::post('/v1/operate/department/item-get', $controller.'@v1_operate__department_item_get');
    Route::post('/v1/operate/department/item-save', $controller.'@v1_operate__department_item_save');


    // 【团队】
    Route::post('/v1/operate/team/datatable-list-query', $controller.'@v1_operate__team_datatable_list_query');
    Route::post('/v1/operate/team/item-get', $controller.'@v1_operate__team_item_get');
    Route::post('/v1/operate/team/item-save', $controller.'@v1_operate__team_item_save');


    // 【员工】
    Route::post('/v1/operate/staff/datatable-list-query', $controller.'@v1_operate__staff_datatable_list_query');
    Route::post('/v1/operate/staff/item-get', $controller.'@v1_operate__staff_item_get');
    Route::post('/v1/operate/staff/item-save', $controller.'@v1_operate__staff_item_save');


    // 【车辆】
    Route::post('/v1/operate/car/datatable-list-query', $controller.'@v1_operate__car_datatable_list_query');
    Route::post('/v1/operate/car/item-get', $controller.'@v1_operate__car_item_get');
    Route::post('/v1/operate/car/item-save', $controller.'@v1_operate__car_item_save');


    // 【司机】
    Route::post('/v1/operate/driver/datatable-list-query', $controller.'@v1_operate__driver_datatable_list_query');
    Route::post('/v1/operate/driver/item-get', $controller.'@v1_operate__driver_item_get');
    Route::post('/v1/operate/driver/item-save', $controller.'@v1_operate__driver_item_save');


    // 【客户】
    Route::post('/v1/operate/client/datatable-list-query', $controller.'@v1_operate__client_datatable_list_query');
    Route::post('/v1/operate/client/item-get', $controller.'@v1_operate__client_item_get');
    Route::post('/v1/operate/client/item-save', $controller.'@v1_operate__client_item_save');


    // 【项目】
    Route::post('/v1/operate/project/datatable-list-query', $controller.'@v1_operate__project_datatable_list_query');
    Route::post('/v1/operate/project/item-get', $controller.'@v1_operate__project_item_get');
    Route::post('/v1/operate/project/item-save', $controller.'@v1_operate__project_item_save');


    // 【工单】列表
    Route::post('/v1/operate/order/datatable-list-query', $controller.'@v1_operate__order_datatable_list_query');
    // 【工单】操作
    Route::post('/v1/operate/order/item-get', $controller.'@v1_operate__order_item_get');
    Route::post('/v1/operate/order/item-save', $controller.'@v1_operate__order_item_save');
    Route::post('/v1/operate/order/item-publish', $controller.'@v1_operate__order_item_publish');
    // 【工单】操作记录
    Route::post('/v1/operate/order/item-operation-record-datatable-query', $controller.'@v1_operate__order_item_operation_record_datatable_query');
    Route::post('/v1/operate/order/item-fee-record-datatable-query', $controller.'@v1_operate__order_item_fee_record_datatable_query');
    // 【工单】跟进
    Route::post('/v1/operate/order/item-customer-save', $controller.'@v1_operate__order_item_customer_save');
    Route::post('/v1/operate/order/item-callback-save', $controller.'@v1_operate__order_item_callback_save');
    Route::post('/v1/operate/order/item-come-save', $controller.'@v1_operate__order_item_come_save');
    Route::post('/v1/operate/order/item-follow-save', $controller.'@v1_operate__order_item_follow_save');
    Route::post('/v1/operate/order/item-fee-save', $controller.'@v1_operate__order_item_fee_save');
    Route::post('/v1/operate/order/item-trade-save', $controller.'@v1_operate__order_item_trade_save');


    // 【费用】
    Route::post('/v1/operate/fee/datatable-list-query', $controller.'@v1_operate__fee_datatable_list_query');
    Route::post('/v1/operate/fee/item-get', $controller.'@v1_operate__fee_item_get');
    Route::post('/v1/operate/fee/item-save', $controller.'@v1_operate__fee_item_save');
    // 【费用】跟进
    Route::post('/v1/operate/fee/item-financial-save', $controller.'@v1_operate__fee_item_financial_save');


    // 【财务】
    Route::post('/v1/operate/finance/datatable-list-query', $controller.'@v1_operate__finance_datatable_list_query');
    Route::post('/v1/operate/finance/item-get', $controller.'@v1_operate__finance_item_get');
    Route::post('/v1/operate/finance/item-save', $controller.'@v1_operate__finance_item_save');




    // 【通用】启用 & 禁用
    Route::post('/v1/operate/universal/item-enable', $controller.'@v1_operate__universal_item_enable');
    Route::post('/v1/operate/universal/item-disable', $controller.'@v1_operate__universal_item_disable');
    // 【通用】字段修改
    Route::post('/v1/operate/universal/field-set', $controller.'@v1_operate__universal_field_set');




    // 【统计】客户
    Route::post('/v1/operate/statistic/statistic-client-by-daily',
        $controller.'@v1_operate__get_statistic_data_of_statistic_client_by_daily');
    Route::post('/v1/operate/statistic/statistic-client-by-daily-for-order',
        $controller.'@v1_operate__get_statistic_data_of_statistic_client_by_daily_for_order');
    Route::post('/v1/operate/statistic/statistic-client-by-daily-for-fee',
        $controller.'@v1_operate__get_statistic_data_of_statistic_client_by_daily_for_fee');

    // 【统计】项目
    Route::post('/v1/operate/statistic/statistic-project-by-daily',
        $controller.'@v1_operate__get_statistic_data_of_statistic_project_by_daily');
    Route::post('/v1/operate/statistic/statistic-project-by-daily-for-order',
        $controller.'@v1_operate__get_statistic_data_of_statistic_project_by_daily_for_order');
    Route::post('/v1/operate/statistic/statistic-project-by-daily-for-fee',
        $controller.'@v1_operate__get_statistic_data_of_statistic_project_by_daily_for_fee');

    // 【统计】订单
    Route::post('/v1/operate/statistic/statistic-order-by-daily',
        $controller.'@v1_operate__get_statistic_data_of_statistic_order_by_daily');

    // 【统计】车辆
    Route::post('/v1/operate/statistic/statistic-car-by-daily',
        $controller.'@v1_operate__get_statistic_data_of_statistic_car_by_daily');
    Route::post('/v1/operate/statistic/statistic-car-by-daily-for-order',
        $controller.'@v1_operate__get_statistic_data_of_statistic_car_by_daily_for_order');
    Route::post('/v1/operate/statistic/statistic-car-by-daily-for-fee',
        $controller.'@v1_operate__get_statistic_data_of_statistic_car_by_daily_for_fee');

    // 【统计】司机
    Route::post('/v1/operate/statistic/statistic-driver-by-daily',
        $controller.'@v1_operate__get_statistic_data_of_statistic_driver_by_daily');
    Route::post('/v1/operate/statistic/statistic-driver-by-daily-for-order',
        $controller.'@v1_operate__get_statistic_data_of_statistic_driver_by_daily_for_order');
    Route::post('/v1/operate/statistic/statistic-driver-by-daily-for-fee',
        $controller.'@v1_operate__get_statistic_data_of_statistic_driver_by_daily_for_fee');

    // 【统计】员工
    Route::post('/v1/operate/statistic/statistic-staff-by-daily',
        $controller.'@v1_operate__get_statistic_data_of_statistic_staff_by_daily');

    // 【统计】费用
    Route::post('/v1/operate/statistic/statistic-fee-by-daily',
        $controller.'@v1_operate__get_statistic_data_of_statistic_fee_by_daily');
    Route::post('/v1/operate/statistic/statistic-fee-by-monthly',
        $controller.'@v1_operate__get_statistic_data_of_statistic_fee_by_monthly');

    // 【统计】财务
    Route::post('/v1/operate/statistic/statistic-finance-by-daily',
        $controller.'@v1_operate__get_statistic_data_of_statistic_finance_by_daily');
    Route::post('/v1/operate/statistic/statistic-finance-by-monthly',
        $controller.'@v1_operate__get_statistic_data_of_statistic_finance_by_monthly');


});

