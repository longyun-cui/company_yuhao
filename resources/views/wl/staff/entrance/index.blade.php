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
        @include(env('TEMPLATE_WL_STAFF').'component.department.department-list')
        {{--团队--}}
        @include(env('TEMPLATE_WL_STAFF').'component.team.team-list')
        {{--员工--}}
        @include(env('TEMPLATE_WL_STAFF').'component.staff.staff-list')

        {{--车辆--}}
        @include(env('TEMPLATE_WL_STAFF').'component.car.car-list')
        {{--司机--}}
        @include(env('TEMPLATE_WL_STAFF').'component.driver.driver-list')

        {{--客户--}}
        @include(env('TEMPLATE_WL_STAFF').'component.client.client-list')
        {{--项目--}}
        @include(env('TEMPLATE_WL_STAFF').'component.project.project-list')
        {{--订单--}}
        @include(env('TEMPLATE_WL_STAFF').'component.order.order-list')

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


    {{--部门--}}
    @include(env('TEMPLATE_WL_STAFF').'component.department.department-edit')
    {{--团队--}}
    @include(env('TEMPLATE_WL_STAFF').'component.team.team-edit')
    {{--员工--}}
    @include(env('TEMPLATE_WL_STAFF').'component.staff.staff-edit')

    {{--车辆--}}
    @include(env('TEMPLATE_WL_STAFF').'component.car.car-edit')
    {{--司机--}}
    @include(env('TEMPLATE_WL_STAFF').'component.driver.driver-edit')

    {{--客户--}}
    @include(env('TEMPLATE_WL_STAFF').'component.client.client-edit')
    {{--项目--}}
    @include(env('TEMPLATE_WL_STAFF').'component.project.project-edit')

    {{--订单--}}
    @include(env('TEMPLATE_WL_STAFF').'component.order.order-edit')
{{--    @include(env('TEMPLATE_WL_STAFF').'component.order.order-operation')--}}
    @include(env('TEMPLATE_WL_STAFF').'component.order.order--item-follow-create')
    @include(env('TEMPLATE_WL_STAFF').'component.order.order--item-journey-create')
    @include(env('TEMPLATE_WL_STAFF').'component.order.order--item-fee-create')
    @include(env('TEMPLATE_WL_STAFF').'component.order.order--item-fee-record')
    @include(env('TEMPLATE_WL_STAFF').'component.order.order--item-operation-record')

    {{--费用--}}
    @include(env('TEMPLATE_WL_STAFF').'component.fee.fee-operation')
    {{--财务--}}
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


    {{--部门--}}
    @include(env('TEMPLATE_WL_STAFF').'component.department.department-list-datatable')
    {{--团队--}}
    @include(env('TEMPLATE_WL_STAFF').'component.team.team-edit-script')
    @include(env('TEMPLATE_WL_STAFF').'component.team.team-list-datatable')
    {{--员工--}}
    @include(env('TEMPLATE_WL_STAFF').'component.staff.staff-edit-script')
    @include(env('TEMPLATE_WL_STAFF').'component.staff.staff-list-datatable')

    {{--车辆--}}
    @include(env('TEMPLATE_WL_STAFF').'component.car.car-edit-script')
    @include(env('TEMPLATE_WL_STAFF').'component.car.car-list-datatable')
    {{--司机--}}
    @include(env('TEMPLATE_WL_STAFF').'component.driver.driver-edit-script')
    @include(env('TEMPLATE_WL_STAFF').'component.driver.driver-list-datatable')

    {{--客户--}}
    @include(env('TEMPLATE_WL_STAFF').'component.client.client-edit-script')
    @include(env('TEMPLATE_WL_STAFF').'component.client.client-list-datatable')
    {{--项目--}}
    @include(env('TEMPLATE_WL_STAFF').'component.project.project-edit-script')
    @include(env('TEMPLATE_WL_STAFF').'component.project.project-list-datatable')
    {{--订单--}}
    @include(env('TEMPLATE_WL_STAFF').'component.order.order-edit-script')
    @include(env('TEMPLATE_WL_STAFF').'component.order.order-list-datatable')
    @include(env('TEMPLATE_WL_STAFF').'component.order.order-list-script')
    @include(env('TEMPLATE_WL_STAFF').'component.order.order--item-operation-record-datatable')
    @include(env('TEMPLATE_WL_STAFF').'component.order.order--item-fee-record-datatable')

    {{--费用--}}
    @include(env('TEMPLATE_WL_STAFF').'component.fee.fee-list-datatable')
    @include(env('TEMPLATE_WL_STAFF').'component.fee.fee-list-script')
    {{--财务--}}
    @include(env('TEMPLATE_WL_STAFF').'component.finance.finance-list-datatable')
    @include(env('TEMPLATE_WL_STAFF').'component.finance.finance-list-script')


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