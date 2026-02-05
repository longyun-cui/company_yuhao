@extends(env('TEMPLATE_WL_STAFF').'layout.layout')


@section('head_title')
    {{--@if(in_array(env('APP_ENV'),['local']))L.@endif--}}
    {{ $head_title or '员工系统' }}
@endsection




@section('title')<span class="box-title">员工系统</span>@endsection
@section('header')<span class="box-title">员工系统</span>@endsection
@section('header','员工系统')
{{--@section('description')员工系统 - {{ config('info.info.short_name') }}@endsection--}}
{{--@section('breadcrumb')--}}
{{--    <li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i>首页</a></li>--}}
{{--    <li><a href="#"><i class="fa "></i>Here</a></li>--}}
{{--@endsection--}}
@section('content')
<div class="row">
    <div class="col-md-12">


        <div class="nav-tabs-custom" id="index-nav-box">

            {{--nav--}}
            <ul class="nav nav-tabs">
                <li class="nav-item active" id="home">
                    <a href="#tab-home" data-toggle="tab" aria-expanded="true" id="home-default">首页</a>
                </li>
            </ul>


            {{--content--}}
            <div class="tab-content">

                <div class="tab-pane active" id="tab-pane-width" style="width:100%;">
                    &nbsp;
                </div>

            </div>

        </div>


    </div>
</div>




<div class="component-container _none">

    {{--部门--}}
    @include(env('TEMPLATE_WL_STAFF').'component.module.company.company-list')
    {{--部门--}}
    @include(env('TEMPLATE_WL_STAFF').'component.module.department.department-list')
    {{--团队--}}
    @include(env('TEMPLATE_WL_STAFF').'component.module.team.team-list')
    {{--员工--}}
    @include(env('TEMPLATE_WL_STAFF').'component.module.staff.staff-list')

    {{--车队--}}
    @include(env('TEMPLATE_WL_STAFF').'component.module.motorcade.motorcade-list')
    {{--车辆--}}
    @include(env('TEMPLATE_WL_STAFF').'component.module.car.car-list')
    {{--司机--}}
    @include(env('TEMPLATE_WL_STAFF').'component.module.driver.driver-list')

    {{--客户--}}
    @include(env('TEMPLATE_WL_STAFF').'component.module.client.client-list')
    {{--项目--}}
    @include(env('TEMPLATE_WL_STAFF').'component.module.project.project-list')
    {{--订单--}}
    @include(env('TEMPLATE_WL_STAFF').'component.module.order.order-list')

    {{--费用--}}
    @include(env('TEMPLATE_WL_STAFF').'component.fee.fee-list')
    {{--财务--}}
    @include(env('TEMPLATE_WL_STAFF').'component.finance.finance-list')


    {{--统计--}}
    {{--统计【订单】--}}
    @include(env('TEMPLATE_WL_STAFF').'component.statistic-order.statistic-view-of-order-by-daily')
    {{--统计【项目】--}}
    @include(env('TEMPLATE_WL_STAFF').'component.statistic-client.statistic-view-of-client-by-daily')
    {{--统计【项目】--}}
    @include(env('TEMPLATE_WL_STAFF').'component.statistic-project.statistic-view-of-project-by-daily')
    {{--统计【车辆】--}}
    @include(env('TEMPLATE_WL_STAFF').'component.statistic-car.statistic-view-of-car-by-daily')
    {{--统计【司机】--}}
    @include(env('TEMPLATE_WL_STAFF').'component.statistic-driver.statistic-view-of-driver-by-daily')

</div>


    {{--公司--}}
    @include(env('TEMPLATE_WL_STAFF').'component.module.company.company-edit')
    @include(env('TEMPLATE_WL_STAFF').'component.module.company.company--item-operation-record')
    {{--部门--}}
    @include(env('TEMPLATE_WL_STAFF').'component.module.department.department-edit')
    @include(env('TEMPLATE_WL_STAFF').'component.module.department.department--item-operation-record')
    {{--团队--}}
    @include(env('TEMPLATE_WL_STAFF').'component.module.team.team-edit')
    @include(env('TEMPLATE_WL_STAFF').'component.module.team.team--item-operation-record')
    {{--员工--}}
    @include(env('TEMPLATE_WL_STAFF').'component.module.staff.staff-edit')
    @include(env('TEMPLATE_WL_STAFF').'component.module.staff.staff--item-operation-record')

    {{--车队--}}
    @include(env('TEMPLATE_WL_STAFF').'component.module.motorcade.motorcade-edit')
    @include(env('TEMPLATE_WL_STAFF').'component.module.motorcade.motorcade--item-operation-record')
    {{--车辆--}}
    @include(env('TEMPLATE_WL_STAFF').'component.module.car.car-edit')
    @include(env('TEMPLATE_WL_STAFF').'component.module.car.car--item-operation-record')
    {{--司机--}}
    @include(env('TEMPLATE_WL_STAFF').'component.module.driver.driver-edit')
    @include(env('TEMPLATE_WL_STAFF').'component.module.driver.driver--item-operation-record')

    {{--客户--}}
    @include(env('TEMPLATE_WL_STAFF').'component.module.client.client-edit')
    @include(env('TEMPLATE_WL_STAFF').'component.module.client.client--item-operation-record')
    {{--项目--}}
    @include(env('TEMPLATE_WL_STAFF').'component.module.project.project-edit')
    @include(env('TEMPLATE_WL_STAFF').'component.module.project.project--item-operation-record')

    {{--订单--}}
    @include(env('TEMPLATE_WL_STAFF').'component.module.order.order-edit')
    @include(env('TEMPLATE_WL_STAFF').'component.module.order.order--item-operation-record')
    @include(env('TEMPLATE_WL_STAFF').'component.module.order.order--item-follow-create')
    @include(env('TEMPLATE_WL_STAFF').'component.module.order.order--item-journey-create')
    @include(env('TEMPLATE_WL_STAFF').'component.module.order.order--item-fee-create')
    @include(env('TEMPLATE_WL_STAFF').'component.module.order.order--item-fee-record')

    {{--费用--}}
    @include(env('TEMPLATE_WL_STAFF').'component.fee.fee-edit')
    @include(env('TEMPLATE_WL_STAFF').'component.fee.fee--item-finance-bookkeeping')
    @include(env('TEMPLATE_WL_STAFF').'component.fee.fee--item-operation-record')
    {{--财务--}}
    @include(env('TEMPLATE_WL_STAFF').'component.finance.finance-edit')
    @include(env('TEMPLATE_WL_STAFF').'component.finance.finance--item-operation-record')
    @include(env('TEMPLATE_WL_STAFF').'component.finance.finance-operation')


@endsection




@section('custom-style')
<style>
</style>
@endsection



@section('custom-js')
    <script src="{{ asset('/resource/component/js/echarts-5.4.1.min.js') }}"></script>
@endsection
@section('custom-script')


    {{--公司--}}
    @include(env('TEMPLATE_WL_STAFF').'component.module.company.company-edit-script')
    @include(env('TEMPLATE_WL_STAFF').'component.module.company.company-list-datatable')
    @include(env('TEMPLATE_WL_STAFF').'component.module.company.company-list-script')
    @include(env('TEMPLATE_WL_STAFF').'component.module.company.company--item-operation-record-datatable')
    {{--部门--}}
    @include(env('TEMPLATE_WL_STAFF').'component.module.department.department-edit-script')
    @include(env('TEMPLATE_WL_STAFF').'component.module.department.department-list-datatable')
    @include(env('TEMPLATE_WL_STAFF').'component.module.department.department-list-script')
    @include(env('TEMPLATE_WL_STAFF').'component.module.department.department--item-operation-record-datatable')
    {{--团队--}}
    @include(env('TEMPLATE_WL_STAFF').'component.module.team.team-edit-script')
    @include(env('TEMPLATE_WL_STAFF').'component.module.team.team-list-datatable')
    @include(env('TEMPLATE_WL_STAFF').'component.module.team.team-list-script')
    @include(env('TEMPLATE_WL_STAFF').'component.module.team.team--item-operation-record-datatable')
    {{--员工--}}
    @include(env('TEMPLATE_WL_STAFF').'component.module.staff.staff-edit-script')
    @include(env('TEMPLATE_WL_STAFF').'component.module.staff.staff-list-datatable')
    @include(env('TEMPLATE_WL_STAFF').'component.module.staff.staff-list-script')
    @include(env('TEMPLATE_WL_STAFF').'component.module.staff.staff--item-operation-record-datatable')


    {{--车队--}}
{{--    @include(env('TEMPLATE_WL_STAFF').'component.motorcade.motorcade-edit-script')--}}
    @include(env('TEMPLATE_WL_STAFF').'component.module.motorcade.motorcade-list-datatable')
    @include(env('TEMPLATE_WL_STAFF').'component.module.motorcade.motorcade-list-script')
    @include(env('TEMPLATE_WL_STAFF').'component.module.motorcade.motorcade--item-operation-record-datatable')
    {{--车辆--}}
    @include(env('TEMPLATE_WL_STAFF').'component.module.car.car-edit-script')
    @include(env('TEMPLATE_WL_STAFF').'component.module.car.car-list-datatable')
    @include(env('TEMPLATE_WL_STAFF').'component.module.car.car-list-script')
    @include(env('TEMPLATE_WL_STAFF').'component.module.car.car--item-operation-record-datatable')
    {{--司机--}}
    @include(env('TEMPLATE_WL_STAFF').'component.module.driver.driver-edit-script')
    @include(env('TEMPLATE_WL_STAFF').'component.module.driver.driver-list-datatable')
    @include(env('TEMPLATE_WL_STAFF').'component.module.driver.driver-list-script')
    @include(env('TEMPLATE_WL_STAFF').'component.module.driver.driver--item-operation-record-datatable')


    {{--客户--}}
    @include(env('TEMPLATE_WL_STAFF').'component.module.client.client-edit-script')
    @include(env('TEMPLATE_WL_STAFF').'component.module.client.client-list-datatable')
    @include(env('TEMPLATE_WL_STAFF').'component.module.client.client-list-script')
    @include(env('TEMPLATE_WL_STAFF').'component.module.client.client--item-operation-record-datatable')
    {{--项目--}}
    @include(env('TEMPLATE_WL_STAFF').'component.module.project.project-edit-script')
    @include(env('TEMPLATE_WL_STAFF').'component.module.project.project-list-datatable')
    @include(env('TEMPLATE_WL_STAFF').'component.module.project.project-list-script')
    @include(env('TEMPLATE_WL_STAFF').'component.module.project.project--item-operation-record-datatable')


    {{--订单--}}
    @include(env('TEMPLATE_WL_STAFF').'component.module.order.order-edit-script')
    @include(env('TEMPLATE_WL_STAFF').'component.module.order.order-list-datatable')
    @include(env('TEMPLATE_WL_STAFF').'component.module.order.order-list-script')
    @include(env('TEMPLATE_WL_STAFF').'component.module.order.order--item-operation-record-datatable')
    @include(env('TEMPLATE_WL_STAFF').'component.module.order.order--item-journey-record-datatable')
    @include(env('TEMPLATE_WL_STAFF').'component.module.order.order--item-fee-record-datatable')


    {{--费用--}}
    @include(env('TEMPLATE_WL_STAFF').'component.fee.fee-list-datatable')
    @include(env('TEMPLATE_WL_STAFF').'component.fee.fee-list-script')
    @include(env('TEMPLATE_WL_STAFF').'component.fee.fee--item-operation-record-datatable')
    {{--财务--}}
    @include(env('TEMPLATE_WL_STAFF').'component.finance.finance-list-datatable')
    @include(env('TEMPLATE_WL_STAFF').'component.finance.finance-list-script')
    @include(env('TEMPLATE_WL_STAFF').'component.finance.finance--item-operation-record-datatable')


    {{--统计--}}
    {{--统计【客户】--}}
    @include(env('TEMPLATE_WL_STAFF').'component.statistic-client.statistic-datatable-of-client-by-daily-for-order')
    @include(env('TEMPLATE_WL_STAFF').'component.statistic-client.statistic-datatable-of-client-by-daily-for-fee')
    {{--统计【项目】--}}
    @include(env('TEMPLATE_WL_STAFF').'component.statistic-project.statistic-datatable-of-project-by-daily-for-order')
    @include(env('TEMPLATE_WL_STAFF').'component.statistic-project.statistic-datatable-of-project-by-daily-for-fee')
    {{--统计【订单】--}}
    @include(env('TEMPLATE_WL_STAFF').'component.statistic-order.statistic-datatable-of-order-by-daily')
    {{--统计【车辆】--}}
    @include(env('TEMPLATE_WL_STAFF').'component.statistic-car.statistic-datatable-of-car-by-daily-for-order')
    @include(env('TEMPLATE_WL_STAFF').'component.statistic-car.statistic-datatable-of-car-by-daily-for-fee')
    {{--统计【司机】--}}
    @include(env('TEMPLATE_WL_STAFF').'component.statistic-driver.statistic-datatable-of-driver-by-daily-for-order')
    @include(env('TEMPLATE_WL_STAFF').'component.statistic-driver.statistic-datatable-of-driver-by-daily-for-fee')


<script>
</script>
@endsection