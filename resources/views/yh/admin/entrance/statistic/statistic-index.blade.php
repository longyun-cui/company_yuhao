@extends(env('TEMPLATE_YH_ADMIN').'layout.layout')


@section('head_title')
    {{ $title_text or '统计' }} - 管理员系统 - {{ config('info.info.short_name') }}
@endsection




@section('header','')
@section('description'){{ $title_text or '统计' }} - 管理员系统 - {{ config('info.info.short_name') }}@endsection
@section('breadcrumb')
    <li><a href="{{url('/')}}"><i class="fa fa-home"></i>首页</a></li>
    <li><a href="#"><i class="fa "></i>Here</a></li>
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="box box-info main-list-body">


            <div class="box-body datatable-body item-main-body" id="statistic-for-comprehensive">

                <div class="row col-md-12 datatable-search-row">
                    <div class="input-group">

                        <button type="button" class="form-control btn btn-flat btn-default month-picker-btn month-pick-pre-for-comprehensive">
                            <i class="fa fa-chevron-left"></i>
                        </button>

                        <input type="text" class="form-control form-filter filter-keyup month_picker" name="comprehensive-month" placeholder="选择月份" readonly="readonly" value="{{ date('Y-m') }}" data-default="{{ date('Y-m') }}" style="" />

                        <button type="button" class="form-control btn btn-flat btn-default month-picker-btn month-pick-next-for-comprehensive">
                            <i class="fa fa-chevron-right"></i>
                        </button>


                        <button type="button" class="form-control btn btn-flat btn-success filter-submit" id="filter-submit-for-comprehensive">
                            <i class="fa fa-search"></i> 搜索
                        </button>
                        <button type="button" class="form-control btn btn-flat btn-default filter-cancel" id="filter-cancel-for-comprehensive">
                            <i class="fa fa-circle-o-notch"></i> 重置
                        </button>

                    </div>
                </div>

            </div>


            <div class="box-header with-border" style="margin:8px 0;">
                <h3 class="box-title">【综合概览】</h3>
            </div>
            <div class="box-body">

                <div class="col-xs-12 col-sm-6 col-md-4">
                    <div class="box box-success box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title">订单量概览</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body">
                            <ul class="nav nav-stacked">
{{--                                <li class="order_count_for_all">--}}
{{--                                    <a href="javascript:void(0);">订单总量 <span class="pull-right badge- bg-blue-"></span></a>--}}
{{--                                </li>--}}
                                <li class="order_count_for_not_null">
                                    <a href="javascript:void(0);">订单总量 <span class="pull-right"><b class="badge- bg-blue-"></b> 单</span></a>
                                </li>
                                <li class="order_count_for_null">
                                    <a href="javascript:void(0);">空单量 <span class="pull-right"><b class="badge- bg-blue-"></b> 单</span></a>
                                </li>
                                <li class="order_count_for_unpublished">
                                    <a href="javascript:void(0);">已发布订单 <span class="pull-right"><b class="badge- bg-blue-"></b> 单</span></a>
                                </li>
                                <li class="order_count_for_published">
                                    <a href="javascript:void(0);">未发布订单 <span class="pull-right"><b class="badge- bg-blue-"></b> 单</span></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6 col-md-4">
                    <div class="box box-success box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title">订单金额概览</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body">
                            <ul class="nav nav-stacked">
                                <li class="amount_sum">
                                    <a href="javascript:void(0);">运价总金额 <span class="pull-right"><b class="badge- bg-blue-"></b> 元</span></a>
                                </li>
                                <li class="income_receivable_sum">
                                    <a href="javascript:void(0);">应收账 <span class="pull-right"><b class="badge- bg-aqua-"></b> 元</span></a>
                                </li>
                                <li class="income_receipts_sum">
                                    <a href="javascript:void(0);">实收款 <span class="pull-right"><b class="badge- bg-green-"></b> 元</span></a>
                                </li>
                                <li class="income_waiting_sum">
                                    <a href="javascript:void(0);">待收款 <span class="pull-right"><b class="badge bg-red"></b> 元</span></a>
                                </li>
                                <li class="expanse_sum">
                                    <a href="javascript:void(0);">订单总支出 <span class="pull-right"><b class="badge- bg-aqua-"></b> 元</span></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6 col-md-4">
                    <div class="box box-warning box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title">财务概览</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body">
                            <ul class="nav nav-stacked">
                                <li class="finance_income_sum">
                                    <a href="javascript:void(0);">当月总收入 <span class="pull-right"><b class="badge- bg-blue-"></b> 元</span></a>
                                </li>
                                <li class="finance_expense_sum">
                                    <a href="javascript:void(0);">当月总支出 <span class="pull-right"><b class="badge- bg-aqua-"></b> 元</span></a>
                                </li>
                                <li class="finance_profile_sum">
                                    <a href="javascript:void(0);">当月收支盈余 <span class="pull-right"><b class="badge- bg-green-"></b> 元</span></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
            <div class="box-footer with-border" style="margin:8px 0;">
            </div>




            <div class="box-header with-border" style="margin:8px 0;">
                <h3 class="box-title">【订单统计】</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="myChart" id="myChart-for-comprehensive-order" style="height:600px;"></div>
                    </div>
                </div>
                <div class="row margin-top-32px _none">
                    <div class="col-md-12">
                        <div class="myChart" id="myChart-for-comprehensive-order-quantity"></div>
                    </div>
                </div>
                <div class="row margin-top-32px _none">
                    <div class="col-md-12">
                        <div class="myChart" id="myChart-for-comprehensive-order-income"></div>
                    </div>
                </div>
            </div>
            <div class="box-footer with-border" style="margin:8px 0;">
            </div>




            <div class="box-header with-border" style="margin:8px 0;">
                <h3 class="box-title">【财务统计】</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="myChart" id="myChart-for-comprehensive-finance" style="height:500px;"></div>
                    </div>
                </div>
            </div>
            <div class="box-footer with-border" style="margin:8px 0;">
            </div>



            <div class="box-body _none">


                <div class="box-header with-border" style="margin:0;text-align:center;">
                    <h3 class="box-title statistic-title"></h3>
                </div>

            </div>

            <div class="box-footer with-border" style="margin:8px 0;">
            </div>

        </div>
    </div>
</div>


{{--订单统计--}}
<div class="row">
    <div class="col-md-12">
        <div class="box box-warning">

            <div class="box-header with-border" style="margin:8px 0;">
                <h3 class="box-title">订单统计</h3>
            </div>

            <div class="box-body datatable-body item-main-body" id="statistic-for-order">

                <div class="row col-md-12 datatable-search-row">
                    <div class="input-group">

                        <button type="button" class="form-control btn btn-flat btn-default month-picker-btn month-pick-pre-for-order">
                            <i class="fa fa-chevron-left"></i>
                        </button>

                        <input type="text" class="form-control form-filter filter-keyup month_picker" name="order-month" placeholder="选择月份" readonly="readonly" value="{{ date('Y-m') }}" data-default="{{ date('Y-m') }}" />

                        <button type="button" class="form-control btn btn-flat btn-default month-picker-btn month-pick-next-for-order">
                            <i class="fa fa-chevron-right"></i>
                        </button>


                        <select class="form-control form-filter" name="order-staff">
                            <option value ="-1">选择员工</option>
                            @foreach($staff_list as $v)
                                <option value ="{{ $v->id }}">{{ $v->true_name }}</option>
                            @endforeach
                        </select>

                        <select class="form-control form-filter" name="order-client">
                            <option value ="-1">选择客户</option>
                            @foreach($client_list as $v)
                                <option value ="{{ $v->id }}">{{ $v->username }}</option>
                            @endforeach
                        </select>

                        <select class="form-control form-filter" name="order-route">
                            <option value="-1">选择线路</option>
                            @foreach($route_list as $v)
                                <option value ="{{ $v->id }}">{{ $v->title }}</option>
                            @endforeach
                        </select>

                        <select class="form-control form-filter" name="order-pricing">
                            <option value="-1">选择定价</option>
                            @foreach($pricing_list as $v)
                                <option value ="{{ $v->id }}">{{ $v->title }}</option>
                            @endforeach
                        </select>

                        <select class="form-control form-filter select2-container select2-car" name="order-car">
                            <option value="-1">选择车辆</option>
                        </select>

                        <select class="form-control form-filter select2-container select2-trailer" name="order-trailer">
                            <option value="-1">选择车挂</option>
                        </select>

                        <select class="form-control form-filter select2-container select2-driver" name="order-driver">
                            <option value="-1">选择驾驶员</option>
                        </select>

                        <select class="form-control form-filter" name="order-type">
                            <option value ="-1">订单类型</option>
                            <option value ="1">自有</option>
                            <option value ="11">空单</option>
                            <option value ="41">外配·配货</option>
                            <option value ="61">外请·调车</option>
                        </select>


                        <button type="button" class="form-control btn btn-flat btn-success filter-submit" id="filter-submit-for-order">
                            <i class="fa fa-search"></i> 搜索
                        </button>
                        <button type="button" class="form-control btn btn-flat btn-default filter-cancel" id="filter-cancel-for-order">
                            <i class="fa fa-circle-o-notch"></i> 重置
                        </button>

                    </div>
                </div>

            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="myChart" id="myChart-for-order-quantity"></div>
                    </div>
                    <div class="col-md-12">
                        <div class="myChart" id="myChart-for-order-income"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


{{--财务统计--}}
<div class="row _none">
    <div class="col-md-12">
        <div class="box box-warning">

            <div class="box-header with-border" style="margin:8px 0;">
                <h3 class="box-title">财务统计</h3>
            </div>

            <div class="box-body datatable-body item-main-body" id="statistic-for-finance">

                <div class="row col-md-12 datatable-search-row">
                    <div class="input-group">

                        <button type="button" class="form-control btn btn-flat btn-default month-picker-btn month-pick-pre-for-finance">
                            <i class="fa fa-chevron-left"></i>
                        </button>

                        <input type="text" class="form-control form-filter filter-keyup month_picker" name="finance-month" placeholder="选择月份" readonly="readonly" value="{{ date('Y-m') }}" data-default="{{ date('Y-m') }}" />

                        <button type="button" class="form-control btn btn-flat btn-default month-picker-btn month-pick-next-for-finance">
                            <i class="fa fa-chevron-right"></i>
                        </button>


                        <button type="button" class="form-control btn btn-flat btn-success filter-submit" id="filter-submit-for-finance">
                            <i class="fa fa-search"></i> 搜索
                        </button>
                        <button type="button" class="form-control btn btn-flat btn-default filter-cancel" id="filter-cancel-for-finance">
                            <i class="fa fa-circle-o-notch"></i> 重置
                        </button>

                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
@endsection




@section('custom-css')
    {{--<link rel="stylesheet" href="https://cdn.bootcss.com/select2/4.0.5/css/select2.min.css">--}}
    <link rel="stylesheet" href="{{ asset('/resource/component/css/select2-4.0.5.min.css') }}">
@endsection
@section('custom-style')
<style>
    .myChart { width:100%;height:240px; }
    .datatable-search-row .input-group .month-picker-btn { width:30px; }
    .datatable-search-row .input-group .month_picker { width:90px;text-align:center; }
    .datatable-search-row .input-group select { width:100px; }
    .datatable-search-row .input-group .select2-container { width:120px; }
</style>
@endsection




@section('custom-js')
    {{--<script src="https://cdn.bootcss.com/select2/4.0.5/js/select2.min.js"></script>--}}
    <script src="{{ asset('/lib/js/select2-4.0.5.min.js') }}"></script>
    <script src="{{ asset('/resource/component/js/echarts-5.4.1.min.js') }}"></script>
@endsection
@section('custom-script')
@include(env('TEMPLATE_YH_ADMIN').'entrance.statistic.statistic-index-script')
<script>
    $(function(){

        // 初始化
        $("#filter-submit-for-comprehensive").click();

    });
</script>
@endsection


