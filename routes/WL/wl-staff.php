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
    Route::post('/o1/select2/select2--company', $controller.'@o1__select2__company');
    Route::post('/o1/select2/select2--department', $controller.'@o1__select2__department');
    Route::post('/o1/select2/select2--team', $controller.'@o1__select2__team');
    Route::post('/o1/select2/select2--staff', $controller.'@o1__select2__staff');
    Route::post('/o1/select2/select2--motorcade', $controller.'@o1__select2__motorcade');
    Route::post('/o1/select2/select2--car', $controller.'@o1__select2__car');
    Route::post('/o1/select2/select2--driver', $controller.'@o1__select2__driver');
    Route::post('/o1/select2/select2--client', $controller.'@o1__select2__client');
    Route::post('/o1/select2/select2--project', $controller.'@o1__select2__project');
    Route::post('/o1/select2/select2--order', $controller.'@o1__select2__order');




    // 【通用】删除 & 恢复 & 永久删除
    Route::post('/o1/universal/item-delete-by-admin', $controller.'@v1_operate_for_universal_item_delete_by_admin');
    Route::post('/o1/universal/item-restore-by-admin', $controller.'@v1_operate_for_universal_item_restore_by_admin');
    Route::post('/o1/universal/item-delete-permanently-by-admin', $controller.'@v1_operate_for_universal_item_delete_permanently_by_admin');
    // 【通用】启用 & 禁用
    Route::post('/o1/universal/item-enable', $controller.'@v1_operate__universal_item_enable');
    Route::post('/o1/universal/item-disable', $controller.'@v1_operate__universal_item_disable');
    // 【通用】字段修改
    Route::post('/o1/universal/field-set', $controller.'@v1_operate__universal_field_set');




    // 【公司】
    Route::post('/o1/company/company-list/datatable-query', $controller.'@o1__company__list__datatable_query');
    Route::post('/o1/company/item-get', $controller.'@o1__company__item_get');
    Route::post('/o1/company/item-save', $controller.'@o1__company__item_save');
    // 【公司】删除 & 恢复 & 永久删除
    Route::post('/o1/company/item-delete', $controller.'@o1__company__item_delete');
    Route::post('/o1/company/item-restore', $controller.'@o1__company__item_restore');
    Route::post('/o1/company/item-delete-permanently', $controller.'@o1__company__item_delete_permanently');
    // 【公司】启用 & 禁用
    Route::post('/o1/company/item-enable', $controller.'@o1__company__item_enable');
    Route::post('/o1/company/item-disable', $controller.'@o1__company__item_disable');
    // 【公司】操作记录
    Route::post('/o1/company/item-operation-record-list/datatable-query', $controller.'@o1__company__item_operation_record_list__datatable_query');


    // 【部门】
    Route::post('/o1/department/department-list/datatable-query', $controller.'@o1__department__list__datatable_query');
    Route::post('/o1/department/item-get', $controller.'@o1__department__item_get');
    Route::post('/o1/department/item-save', $controller.'@o1__department__item_save');
    // 【部门】删除 & 恢复 & 永久删除
    Route::post('/o1/department/item-delete', $controller.'@o1__department__item_delete');
    Route::post('/o1/department/item-restore', $controller.'@o1__department__item_restore');
    Route::post('/o1/department/item-delete-permanently', $controller.'@o1__department__item_delete_permanently');
    // 【部门】启用 & 禁用
    Route::post('/o1/department/item-enable', $controller.'@o1__department__item_enable');
    Route::post('/o1/department/item-disable', $controller.'@o1__department__item_disable');
    // 【部门】操作记录
    Route::post('/o1/department/item-operation-record-list/datatable-query', $controller.'@o1__department__item_operation_record_list__datatable_query');


    // 【团队】
    Route::post('/o1/team/team-list/datatable-query', $controller.'@o1__team__list__datatable_query');
    Route::post('/o1/team/item-get', $controller.'@o1__team__item_get');
    Route::post('/o1/team/item-save', $controller.'@o1__team__item_save');
    // 【团队】删除 & 恢复 & 永久删除
    Route::post('/o1/team/item-delete', $controller.'@o1__team__item_delete');
    Route::post('/o1/team/item-restore', $controller.'@o1__team__item_restore');
    Route::post('/o1/team/item-delete-permanently', $controller.'@o1__team__item_delete_permanently');
    // 【团队】启用 & 禁用
    Route::post('/o1/team/item-enable', $controller.'@o1__team__item_enable');
    Route::post('/o1/team/item-disable', $controller.'@o1__team__item_disable');
    // 【团队】操作记录
    Route::post('/o1/team/item-operation-record-list/datatable-query', $controller.'@o1__team__item_operation_record_list__datatable_query');


    // 【员工】
    Route::post('/o1/staff/staff-list/datatable-query', $controller.'@o1__staff__list__datatable_query');
    Route::post('/o1/staff/item-get', $controller.'@o1__staff__item_get');
    Route::post('/o1/staff/item-save', $controller.'@o1__staff__item_save');
    // 【员工】删除 & 恢复 & 永久删除
    Route::post('/o1/staff/item-delete', $controller.'@o1__staff__item_delete');
    Route::post('/o1/staff/item-restore', $controller.'@o1__staff__item_restore');
    Route::post('/o1/staff/item-delete-permanently', $controller.'@o1__staff__item_delete_permanently');
    // 【员工】启用 & 禁用
    Route::post('/o1/staff/item-enable', $controller.'@o1__staff__item_enable');
    Route::post('/o1/staff/item-disable', $controller.'@o1__staff__item_disable');
    // 【员工】操作记录
    Route::post('/o1/staff/item-operation-record-list/datatable-query', $controller.'@o1__staff__item_operation_record_list__datatable_query');




    // 【车队】
    Route::post('/o1/motorcade/motorcade-list/datatable-query', $controller.'@o1__motorcade__list__datatable_query');
    Route::post('/o1/motorcade/item-get', $controller.'@o1__motorcade__item_get');
    Route::post('/o1/motorcade/item-save', $controller.'@o1__motorcade__item_save');
    // 【车队】删除 & 恢复 & 永久删除
    Route::post('/o1/motorcade/item-delete', $controller.'@o1__motorcade__item_delete');
    Route::post('/o1/motorcade/item-restore', $controller.'@o1__motorcade__item_restore');
    Route::post('/o1/motorcade/item-delete-permanently', $controller.'@o1__motorcade__item_delete_permanently');
    // 【车队】启用 & 禁用
    Route::post('/o1/motorcade/item-enable', $controller.'@o1__motorcade__item_enable');
    Route::post('/o1/motorcade/item-disable', $controller.'@o1__motorcade__item_disable');
    // 【车队】操作记录
    Route::post('/o1/motorcade/item-operation-record-list/datatable-query', $controller.'@o1__motorcade__item_operation_record_list__datatable_query');


    // 【车辆】
    Route::post('/o1/car/car-list/datatable-query', $controller.'@o1__car__list__datatable_query');
    Route::post('/o1/car/item-get', $controller.'@o1__car__item_get');
    Route::post('/o1/car/item-save', $controller.'@o1__car__item_save');
    // 【车辆】删除 & 恢复 & 永久删除
    Route::post('/o1/car/item-delete', $controller.'@o1__car__item_delete');
    Route::post('/o1/car/item-restore', $controller.'@o1__car__item_restore');
    Route::post('/o1/car/item-delete-permanently', $controller.'@o1__car__item_delete_permanently');
    // 【车辆】启用 & 禁用
    Route::post('/o1/car/item-enable', $controller.'@o1__car__item_enable');
    Route::post('/o1/car/item-disable', $controller.'@o1__car__item_disable');
    // 【车辆】操作记录
    Route::post('/o1/car/item-operation-record-list/datatable-query', $controller.'@o1__car__item_operation_record_list__datatable_query');


    // 【司机】
    Route::post('/o1/driver/driver-list/datatable-query', $controller.'@o1__driver__list__datatable_query');
    Route::post('/o1/driver/item-get', $controller.'@o1__driver__item_get');
    Route::post('/o1/driver/item-save', $controller.'@o1__driver__item_save');
    // 【司机】删除 & 恢复 & 永久删除
    Route::post('/o1/driver/item-delete', $controller.'@o1__driver__item_delete');
    Route::post('/o1/driver/item-restore', $controller.'@o1__driver__item_restore');
    Route::post('/o1/driver/item-delete-permanently', $controller.'@o1__driver__item_delete_permanently');
    // 【司机】启用 & 禁用
    Route::post('/o1/driver/item-enable', $controller.'@o1__driver__item_enable');
    Route::post('/o1/driver/item-disable', $controller.'@o1__driver__item_disable');
    // 【司机】操作记录
    Route::post('/o1/driver/item-operation-record-list/datatable-query', $controller.'@o1__driver__item_operation_record_list__datatable_query');




    // 【客户】
    Route::post('/o1/client/client-list/datatable-query', $controller.'@o1__client__list__datatable_query');
    Route::post('/o1/client/item-get', $controller.'@o1__client__item_get');
    Route::post('/o1/client/item-save', $controller.'@o1__client__item_save');
    // 【客户】删除 & 恢复 & 永久删除
    Route::post('/o1/client/item-delete', $controller.'@o1__client__item_delete');
    Route::post('/o1/client/item-restore', $controller.'@o1__client__item_restore');
    Route::post('/o1/client/item-delete-permanently', $controller.'@o1__client__item_delete_permanently');
    // 【客户】启用 & 禁用
    Route::post('/o1/client/item-enable', $controller.'@o1__client__item_enable');
    Route::post('/o1/client/item-disable', $controller.'@o1__client__item_disable');
    // 【客户】操作记录
    Route::post('/o1/client/item-operation-record-list/datatable-query', $controller.'@o1__client__item_operation_record_list__datatable_query');


    // 【项目】
    Route::post('/o1/project/project-list/datatable-query', $controller.'@o1__project__list__datatable_query');
    Route::post('/o1/project/item-get', $controller.'@o1__project__item_get');
    Route::post('/o1/project/item-save', $controller.'@o1__project__item_save');
    // 【项目】删除 & 恢复 & 永久删除
    Route::post('/o1/project/item-delete', $controller.'@o1__project__item_delete');
    Route::post('/o1/project/item-restore', $controller.'@o1__project__item_restore');
    Route::post('/o1/project/item-delete-permanently', $controller.'@o1__project__item_delete_permanently');
    // 【项目】启用 & 禁用
    Route::post('/o1/project/item-enable', $controller.'@o1__project__item_enable');
    Route::post('/o1/project/item-disable', $controller.'@o1__project__item_disable');
    // 【项目】操作记录
    Route::post('/o1/project/item-operation-record-list/datatable-query', $controller.'@o1__project__item_operation_record_list__datatable_query');








    // 【工单】列表
    Route::post('/o1/order/order-list/datatable-query', $controller.'@o1__order__list__datatable_query');
    // 【工单】操作
    Route::post('/o1/order/item-get', $controller.'@o1__order__item_get');
    Route::post('/o1/order/item-save', $controller.'@o1__order__item_save');
    // 【工单】删除 & 恢复 & 永久删除
    Route::post('/o1/order/item-delete', $controller.'@o1__order__item_delete');
    Route::post('/o1/order/item-restore', $controller.'@o1__order__item_restore');
    Route::post('/o1/order/item-delete-permanently', $controller.'@o1__order__item_delete_permanently');
    // 【工单】启用 & 禁用
    Route::post('/o1/order/item-enable', $controller.'@o1__order__item_enable');
    Route::post('/o1/order/item-disable', $controller.'@o1__order__item_disable');
    // 【工单】发布
    Route::post('/o1/order/item-publish', $controller.'@o1__order__item_publish');
    // 【工单】完成
    Route::post('/o1/order/item-publish', $controller.'@o1__order__item_publish');
    // 【工单】操作记录
    Route::post('/o1/order/item-operation-record-list/datatable-query', $controller.'@o1__order__item_operation_record_list__datatable_query');
    Route::post('/o1/order/item-journey-record-list/datatable-query', $controller.'@o1__order__item_journey_record_list__datatable_query');
    Route::post('/o1/order/item-fee-record-list/datatable-query', $controller.'@o1__order__item_fee_record_list__datatable_query');
    // 【工单】跟进
    Route::post('/o1/order/item-follow-save', $controller.'@o1__order__item_follow_save');
    Route::post('/o1/order/item-journey-save', $controller.'@o1__order__item_journey_save');
    Route::post('/o1/order/item-fee-save', $controller.'@o1__order__item_fee_save');
    Route::post('/o1/order/item-trade-save', $controller.'@o1__order__item_trade_save');




    // 【费用】
    Route::post('/o1/fee/fee-list/datatable-query', $controller.'@o1__fee__list__datatable_query');
    Route::post('/o1/fee/item-get', $controller.'@o1__fee__item_get');
    Route::post('/o1/fee/item-save', $controller.'@o1__fee__item_save');
    // 【费用】财务
    Route::post('/o1/fee/item-financial-save', $controller.'@o1__fee__item_financial_save');




    // 【财务】
    Route::post('/o1/finance/finance-list/datatable-query', $controller.'@o1__finance__list__datatable_query');
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

