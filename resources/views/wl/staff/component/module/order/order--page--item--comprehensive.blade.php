<div class="row datatable-body page-wrapper order--item-page order--item-page--clone" data-item-category="order--item-page" data-item-name="工单详情"
    style="padding-left:8px;padding-right:8px;"
>


    {{--订单信息--}}
    <div class="col-xs-12 form-wrapper">
        <div class="box box-success box-solid form-box">

            <div class="box-header">
                <h3 class="box-title">订单编辑</h3>

                <div class="box-tools">
                    <div class="input-group input-group-sm _none" style="width:150px;">
                        <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">

                        <div class="input-group-btn">s
                            <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-info pull-right submit--for--order--item-edit">
                        <i class="fa fa-check"></i> 提交
                    </button>
                </div>
            </div>


            <form action="" method="post" class="form-horizontal form-bordered form--for--info">

            <div class="box-body table-responsive no-padding-">

                {{ csrf_field() }}
                <input readonly type="hidden" name="operate[type]" value="edit" data-default="edit">
                <input readonly type="hidden" name="operate[id]" value="0" data-default="0">
                <input readonly type="hidden" name="operate[item_category]" value="order" data-default="order">
                <input readonly type="hidden" name="operate[item_type]" value="item" data-default="item">

                <table class="table table-hover-" style="width:100%;border-collapse:collapse;">

                    <tbody>
                    <tr>
                        <th colspan="2" style="width:200px;">订单类型</th>
                        <th>派车日期</th>
                        <th>任务日期</th>
                        <th>项目</th>
                        <th style="width:160px;">出发地</th>
                        <th style="width:160px;">目的地</th>
                        <th>里程</th>
                        <th>时效</th>
                        <th>任务编号</th>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <div class="btn-group">
                                <button type="button" class="btn radio radio-department-type" style="padding:4px 2px;margin-top:0;">
                                    <label>
                                        <input type="radio" name="order_type" value="1" checked="checked" data-default="default"> 固定线路
                                    </label>
                                </button>

                                <button type="button" class="btn radio radio-department-type" style="padding:4px 2px;margin-top:0;">
                                    <label>
                                        <input type="radio" name="order_type" value="11"> 配货
                                    </label>
                                </button>
                            </div>
                        </td>
                        <td>
                            <input type="text" class="form-control date-picker-c" name="assign_date" placeholder="派车日期"
                                   value="{{ date('Y-m-d') }}"
                                   data-default="{{ date('Y-m-d') }}"
                                   readonly="readonly"
                            >
                        </td>
                        <td>
                            <input type="text" class="form-control date-picker-c" name="task_date" placeholder="任务日期"
                                   value="{{ date('Y-m-d') }}"
                                   data-default="{{ date('Y-m-d') }}"
                                   readonly="readonly"
                            >
                        </td>
                        <td>
                            <select class="form-control select2-box-c select2--project-c" name="project_id" style="width:100%;">
                                <option data-id="0" value="0">选择项目</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" class="form-control" name="transport_departure_place" placeholder="出发地" value="" data-default="" list="_transport_departure_place_title">
                        </td>
                        <td>
                            <input type="text" class="form-control" name="transport_destination_place" placeholder="目的地" value="" data-default="" list="_transport_destination_place_title">
                        </td>
                        <td>
                            <input type="text" class="form-control" name="transport_distance" placeholder="里程" value="0" data-default="">
                        </td>
                        <td>
                            <input type="text" class="form-control" name="transport_time_limitation" placeholder="时效" value="0" data-default="">
                        </td>
                        <td>
                            <input type="text" class="form-control" name="task_number" placeholder="任务编号" value="" data-default="">
                        </td>

                    </tr>

                    <tr>
                        <th colspan="2">车辆所属</th>
                        <th>车型</th>
                        <th class="internal-car">车头</th>
                        <th class="internal-car">车挂</th>
                        <th class="internal-car">主驾</th>
                        <th class="internal-car">副驾</th>
                        <th class="external-car">外请车价</th>
                        <th class="external-car">外请车辆</th>
                        <th class="external-car">外请车挂</th>
                        <th>主驾姓名</th>
                        <th>主驾电话</th>
                        <th>副驾姓名</th>
                        <th>副驾电话</th>
                    </tr>

                    <tr>
                        <td colspan="2">
                            <div class="btn-group">
                                <button type="button" class="btn radio radio-car-type" style="padding:4px 2px;margin-top:0;">
                                    <label>
                                        <input type="radio" name="car_owner_type" value="1" checked="checked"> 自有车
                                    </label>
                                </button>

                                <button type="button" class="btn radio radio-car-type" style="padding:4px 2px;margin-top:0;">
                                    <label>
                                        <input type="radio" name="car_owner_type" value="9"> 共建车
                                    </label>
                                </button>

                                <button type="button" class="btn radio radio-department-type" style="padding:4px 2px;margin-top:0;">
                                    <label>
                                        <input type="radio" name="car_owner_type" value="11"> 外请车
                                    </label>
                                </button>
                            </div>
                        </td>
                        <td>
                            <select class="form-control select2-box-c select2-reset" name="car_type" style="width:100%;">
                                <option value="">选择车型</option>
                                @if(!empty(config('wl.common-config.car_type')))
                                    @foreach(config('wl.common-config.car_type') as $k => $v)
                                        <option value ="{{ $v }}">{{ $v }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </td>
                        <td class="internal-car">
                            <select class="form-control select2-reset select2--car-c"
                                    name="car_id"
                                    data-car-category="1"
                                    {{--data-car-type="1"--}}
                                    data-with="order"
                                    style="width:100%;"
                            >
                                <option value="0">选择车辆</option>
                            </select>
                        </td>
                        <td class="internal-car">
                            <select class="form-control select2-reset select2--car-c"
                                    name="trailer_id"
                                    data-car-category="21"
                                    data-car-type="21"
                                    style="width:100%;"
                            >
                                <option value="0">选择车挂</option>
                            </select>
                        </td>
                        <td class="internal-car">
                            <select class="form-control select2-reset select2--driver-c"
                                    name="driver_id"
                                    data-item-category=""
                                    data-item-type=""
                                    data-driver-type="1"
                                    style="width:100%;"
                            >
                                <option value="0">选择主驾</option>
                            </select>
                        </td>
                        <td class="internal-car">
                            <select class="form-control select2-reset select2--driver-c"
                                    name="copilot_id"
                                    data-item-category=""
                                    data-item-type=""
                                    data-driver-type="11"
                                    style="width:100%;"
                            >
                                <option value="0">选择副驾</option>
                            </select>
                        </td>
                        <td class="external-car">
                            <input type="text" class="form-control" name="external_car_price" placeholder="外请车价" value="0" data-default="0">
                        </td>
                        <td class="external-car">
                            <input type="text" class="form-control" name="external_car" placeholder="外请车辆" value="" data-default="">
                        </td>
                        <td class="external-car">
                            <input type="text" class="form-control" name="external_trailer" placeholder="外请车挂" value="" data-default="">
                        </td>
                        <td>
                            <input type="text" class="form-control" name="driver_name" placeholder="主驾" value="" data-default="">
                        </td>
                        <td>
                            <input type="text" class="form-control" name="driver_phone" placeholder="主驾电话" value="" data-default="">
                        </td>
                        <td>
                            <input type="text" class="form-control" name="copilot_name" placeholder="副驾" value="" data-default="">
                        </td>
                        <td>
                            <input type="text" class="form-control" name="copilot_phone" placeholder="副驾电话" value="" data-default="">
                        </td>
                    </tr>




                    <tr>
                        <th>运费(收)</th>
                        <th>油卡(收)</th>
                        <th>串点运费 (收)</th>
                        <th>共建车费 (支)</th>
                        <th>开票金额 (收)</th>
                        <th>票点 (收)</th>
                        <th>信息费 (支)</th>
                        <th>安排人</th>
                        <th>收款人</th>
                        <th>车货源</th>
                    </tr>

                    <tr>
                        <td>
                            <input type="text" class="form-control" name="freight_amount" placeholder="运费" value="0" data-default="0">
                        </td>
                        <td>
                            <input type="text" class="form-control" name="freight_oil_card_amount" placeholder="油卡" value="0" data-default="0">
                        </td>
                        <td>
                            <input type="text" class="form-control" name="freight_extra_amount" placeholder="串点运费" value="0" data-default="0">
                        </td>
                        <td>
                            <input type="text" class="form-control" name="cooperative_vehicle_amount" placeholder="共建车费" value="0" data-default="0">
                        </td>
                        <td>
                            <input type="text" class="form-control" name="financial_receipt_for_invoice_amount" placeholder="开票金额" value="0" data-default="0">
                        </td>
                        <td>
                            <input type="text" class="form-control" name="financial_receipt_for_invoice_point" placeholder="开票点" value="0.00" data-default="0.00">
                        </td>
                        <td>
                            <input type="text" class="form-control" name="financial_fee_for_information" placeholder="信息费" value="0" data-default="0">
                        </td>
                        <td>
                            <input type="text" class="form-control" name="arrange_people" placeholder="安排人" value="" data-default="">
                        </td>
                        <td>
                            <input type="text" class="form-control" name="payee_name" placeholder="收款人" value="" data-default="">
                        </td>
                        <td>
                            <input type="text" class="form-control" name="car_supply" placeholder="车货源" value="" data-default="">
                        </td>
                    </tr>


                    </tbody>
                </table>
            </div>
            </form>


            <div class="box-footer _none">
            </div>

        </div>
    </div>


    {{--财务核算--}}
    <div class="col-xs-12 form-wrapper">
        <div class="box box-warning box-solid form-box">

            <div class="box-header">
                <h3 class="box-title">财务核算</h3>

                <div class="box-tools">
                    <div class="input-group input-group-sm _none" style="width: 150px;">
                        <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">

                        <div class="input-group-btn">
                            <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-info pull-right submit--for--order--item-one-click-calculation" style="margin-left:4px;">
                        <i class="fa fa-calculator"></i> 自动核算
                    </button>

                    <button type="submit" class="btn btn-info pull-right submit--for--order--item-accounting-set">
                        <i class="fa fa-check"></i> 手动提交
                    </button>
                </div>
            </div>

            <form action="" method="post" class="form-horizontal form-bordered form--for--accounting">
            <div class="box-body table-responsive no-padding-">

                {{ csrf_field() }}
                <input readonly type="hidden" name="operate[type]" value="edit" data-default="edit">
                <input readonly type="hidden" name="operate[id]" value="0" data-default="0">
                <input readonly type="hidden" name="operate[item_category]" value="order" data-default="order">
                <input readonly type="hidden" name="operate[item_type]" value="item" data-default="item">

                <table class="table table-hover-">
                    <tbody>
                    <tr>
                        <th>运费(收)</th>
                        <th>运费油卡(收)</th>
                        <th>串点车费(收)</th>
                        <th>共建车费(支)</th>
                        <th>外请车费(支)</th>
                        <th>信息费(支)</th>
                        <th>开票收入(收)</th>
                        <th>开票金额(收)</th>
                        <th>开票点数(收)</th>
                        <th>开票费用(支)</th>
                        <th>开票金额(支)</th>
                        <th>开票点数(支)</th>
                    </tr>
                    <tr>
                        <td>
                            <input type="text" class="form-control" name="accounting_freight_cash" placeholder="运费">
                        </td>
                        <td>
                            <input type="text" class="form-control" name="accounting_freight_oil_card" placeholder="运费油卡" />
                        </td>
                        <td>
                            <input type="text" class="form-control" name="accounting_freight_extra_amount" placeholder="串点运费">
                        </td>
                        <td>
                            <input type="text" class="form-control" name="accounting_cooperative_vehicle_amount" placeholder="共建车费">
                        </td>
                        <td>
                            <input type="text" class="form-control" name="accounting_external_car_price" placeholder="外请车价">
                        </td>
                        <td>
                            <input type="text" class="form-control" name="accounting_information" placeholder="信息费" />
                        </td>
                        <td class="_none">
                            <input type="text" class="form-control" name="accounting_administrative" placeholder="管理费" />
                        </td>
                        <td>
                            <input type="text" class="form-control" name="accounting_invoice_total" placeholder="开票费用(收)" />
                        </td>
                        <td>
                            <input type="text" class="form-control" name="accounting_invoice_amount" placeholder="开票金额(收)" />
                        </td>
                        <td>
                            <input type="text" class="form-control" name="accounting_invoice_point" placeholder="开票点数(收)" />
                        </td>
                        <td>
                            <input type="text" class="form-control" name="accounting_fee_invoice_total" placeholder="开票费用(支)" />
                        </td>
                        <td>
                            <input type="text" class="form-control" name="accounting_fee_invoice_amount" placeholder="开票金额(支)" />
                        </td>
                        <td>
                            <input type="text" class="form-control" name="accounting_fee_invoice_point" placeholder="票点(支)" />
                        </td>
{{--                        <td>--}}
{{--                            <input type="text" class="form-control" name="accounting_administrative" placeholder="管理费" />--}}
{{--                        </td>--}}

                    </tr>
                    <tr style="margin-top:8px;">
                        <th>总油费</th>
                        <th>油卡</th>
                        <th>油费现金</th>
                        <th>公里数</th>
                        <th>油耗</th>
                        <th>油费单价</th>
                        <th>总气费</th>
                        <th>气卡</th>
                        <th>气费现金</th>
                        <th>公里数</th>
                        <th>气耗</th>
                        <th>气费单价</th>
                    </tr>
                    <tr>
                        <td>
                            <input type="text" class="form-control" name="accounting_oil_total" placeholder="总油费" />
                        </td>
                        <td>
                            <input type="text" class="form-control" name="accounting_oil_card" placeholder="油卡" />
                        </td>
                        <td>
                            <input type="text" class="form-control" name="accounting_oil_cash" placeholder="油费现金" />
                        </td>
                        <td>
                            <input type="text" class="form-control" name="accounting_oil_mileage" placeholder="公里数" />
                        </td>
                        <td>
                            <input type="text" class="form-control" name="accounting_oil_consumption" placeholder="油耗" />
                        </td>
                        <td>
                            <input type="text" class="form-control" name="accounting_oil_unit_price" placeholder="油费单价" />
                        </td>
                        <td>
                            <input type="text" class="form-control" name="accounting_gas_total" placeholder="总气费" />
                        </td>
                        <td>
                            <input type="text" class="form-control" name="accounting_gas_card" placeholder="气卡" />
                        </td>
                        <td>
                            <input type="text" class="form-control" name="accounting_gas_cash" placeholder="气费现金" />
                        </td>
                        <td>
                            <input type="text" class="form-control" name="accounting_gas_mileage" placeholder="公里数" />
                        </td>
                        <td>
                            <input type="text" class="form-control" name="accounting_gas_consumption" placeholder="气耗" />
                        </td>
                        <td>
                            <input type="text" class="form-control" name="accounting_gas_unit_price" placeholder="气费单价" />
                        </td>

                    </tr>

                    <tr>
                        <th>路费-ETC</th>
                        <th>路费-现金</th>
                        <th>停车费</th>
                        <th>工资</th>
                        <th>奖金</th>
                        <th>维修费</th>
                        <th>保养费</th>
                        <th>审车费</th>
                        <th>过户费</th>
                        <th>保险费</th>
                        <th>贷款费用</th>
                        <th>其他费用</th>
                    </tr>

                    <tr>
                        <td>
                            <input type="text" class="form-control" name="accounting_toll_etc" placeholder="路费-ETC" />
                        </td>
                        <td>
                            <input type="text" class="form-control" name="accounting_toll_cash" placeholder="路费-现金" />
                        </td>
                        <td>
                            <input type="text" class="form-control" name="accounting_parking" placeholder="停车费">
                        </td>
                        <td>
                            <input type="text" class="form-control" name="accounting_salary" placeholder="工资" />
                        </td>
                        <td>
                            <input type="text" class="form-control" name="accounting_bonus" placeholder="奖金" />
                        </td>
                        <td>
                            <input type="text" class="form-control" name="accounting_repair" placeholder="维修费" />
                        </td>
                        <td>
                            <input type="text" class="form-control" name="accounting_maintenance" placeholder="保养费" />
                        </td>
                        <td>
                            <input type="text" class="form-control" name="accounting_inspection" placeholder="审车费" />
                        </td>
                        <td>
                            <input type="text" class="form-control" name="accounting_transfer" placeholder="过户费" />
                        </td>
                        <td>
                            <input type="text" class="form-control" name="accounting_insurance" placeholder="保险费" />
                        </td>
                        <td>
                            <input type="text" class="form-control" name="accounting_loan" placeholder="贷款费用" />
                        </td>
                        <td>
                            <input type="text" class="form-control" name="accounting_others" placeholder="其他费用" />
                        </td>
                        <td class="_none">
                            <input type="text" class="form-control" name="accounting_description" placeholder="描述" />
                        </td>
                    </tr>

                    <tr>
                        <th>统计</th>
                        <th>总运费(收)</th>
                        <th><i class="fa fa-info-circle text-red"></i> 订单扣款</th>
                        <th><i class="fa fa-times-circle text-orange"></i> 司机罚款</th>
                        <th>应收款</th>
                        <th><i class="fa fa-sign-in text-green"></i> 已收款</th>
                        <th><i class="fa fa-calendar-minus-o text-red"></i> 待收款</th>
                        <th><i class="fa fa-sign-out text-red"></i> 总支出</th>
                        <th>利润</th>
                        <th>共建利润</th>
                        <th></th>
                        <th></th>
                    </tr>

                    <tr class='statistics-row'>
                        <td>
                            <input readonly type="text" class="form-control" value="统计" placeholder="统计" />
                        </td>
                        <td>
                            <input readonly type="text" class="form-control" name="accounting_statistics_freight_total" style="color:#0073b7;" />
                        </td>
                        <td>
                            <input readonly type="text" class="form-control" name="accounting_statistics_deduction_total" style="color:#dd4b39 ;" />
                        </td>
                        <td>
                            <input readonly type="text" class="form-control" name="accounting_statistics_fine_total" style="color:orange;" />
                        </td>
                        <td>
                            <input readonly type="text" class="form-control" name="accounting_statistics_income_should" style="color:#0073b7;" />
                        </td>
                        <td>
                            <input readonly type="text" class="form-control" name="accounting_statistics_income_total" style="color:#0073b7;" />
                        </td>
                        <td>
                            <input readonly type="text" class="form-control" name="accounting_statistics_income_pending" style="color:#dd4b39;" />
                        </td>
                        <td>
                            <input readonly type="text" class="form-control" name="accounting_statistics_fee_total" style="color:#dd4b39;" />
                        </td>
                        <td>
                            <input readonly type="text" class="form-control" name="accounting_statistics_profile" />
                        </td>
                        <td>
                            <input readonly type="text" class="form-control" name="accounting_statistics_cooperative_profile" />
                        </td>
                        <td>
                        </td>
                        <td>
                        </td>
                    </tr>

                    </tbody>
                </table>
            </div>
            </form>

            <div class="box-footer _none">
                <button type="submit" class="btn btn-default">一键核算</button>
                <button type="submit" class="btn btn-success pull-right">提交</button>
            </div>

        </div>
    </div>


    {{--添加费用--}}
    <div class="col-xs-12 form-wrapper">
        <div class="box box-danger box-solid form-box">

            <div class="box-header">
                <h3 class="box-title">添加费用</h3>

                <div class="box-tools">
                    <div class="input-group input-group-sm _none" style="width:150px;">
                        <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">

                        <div class="input-group-btn">
                            <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-info pull-right submit--for--order--item-fee-create">
                        <i class="fa fa-check"></i> 提交
                    </button>
                </div>
            </div>

            <form action="" method="post" class="form-horizontal form-bordered form--for--fee">
            <div class="box-body table-responsive no-padding-">

                {{ csrf_field() }}
                <input readonly type="hidden" name="operate[type]" value="edit" data-default="edit">
                <input readonly type="hidden" name="operate[id]" value="0" data-default="0">
                <input readonly type="hidden" name="operate[item_category]" value="order" data-default="order">
                <input readonly type="hidden" name="operate[item_type]" value="fee" data-default="fee">

                <table class="table table-hover-">
                    <tbody>
                    <tr>
                        <th style="width:280px;">费用类型</th>
                        <th>费用日期</th>
                        <th>名目</th>
                        <th>金额</th>
                        <th colspan="3">说明</th>
                    </tr>
                    <tr>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn radio" style="padding:4px 2px;margin-top:0;">
                                    <label>
                                        <input type="radio" name="fee-type" value="1"> 收款
                                    </label>
                                </button>
                                <button type="button" class="btn radio" style="padding:4px 2px;margin-top:0;">
                                    <label>
                                        <input type="radio" name="fee-type" value="99" checked="checked" data-default="default"> 费用
                                    </label>
                                </button>
                                <button type="button" class="btn radio" style="padding:4px 2px;margin-top:0;">
                                    <label>
                                        <input type="radio" name="fee-type" value="101"> 订单扣款
                                    </label>
                                </button>
                                <button type="button" class="btn radio" style="padding:4px 2px;margin-top:0;">
                                    <label>
                                        <input type="radio" name="fee-type" value="111"> 员工罚款
                                    </label>
                                </button>
                            </div>
                        </td>
                        <td>
                            <input type="text" class="form-control form-filter time-picker-c" name="fee-datetime" readonly="readonly" />
                        </td>
                        <td>
                            {{--费用名目--}}
                            <div class="fee-title-box fee-box">
                            <select class="form-control select2-box-c select2-reset" name="fee-title-for-fee" style="width:100%;">
                                <option value="">费用名目</option>
                                @if(!empty(config('wl.common-config.fee_title')))
                                    @foreach(config('wl.common-config.fee_title') as $k => $v)
                                        <option value ="{{ $v }}">{{ $v }}</option>
                                    @endforeach
                                @endif
                            </select>
                            </div>
                            {{--收款名目--}}
                            <div class="fee-title-box receipt-box">
                            <input type="text" class="form-control" name="fee-title-for-receipt" placeholder="请输入收款名目" value="">
                            </div>
                            {{--扣款名目--}}
                            <div class="fee-title-box deduction-box">
                            <select class="form-control select2-box-c select2-reset" name="fee-title-for-deduction" style="width:100%;">
                                <option value="">扣款名目</option>
                                @if(!empty(config('wl.common-config.deduction_title')))
                                    @foreach(config('wl.common-config.deduction_title') as $k => $v)
                                        <option value ="{{ $v }}">{{ $v }}</option>
                                    @endforeach
                                @endif
                            </select>
                            </div>
                            {{--罚款名目--}}
                            <div class="fee-title-box fine-box">
                                <select class="form-control select2-box-c select2-reset" name="fee-title-for-fine" style="width:100%;">
                                    <option value="">请选择罚款名目</option>
                                    @if(!empty(config('wl.common-config.fine_title')))
                                        @foreach(config('wl.common-config.fine_title') as $k => $v)
                                            <option value ="{{ $v }}">{{ $v }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </td>
                        <td>
                            <input type="text" class="form-control" name="fee-amount" placeholder="金额" value="" data-default="">
                        </td>
                        <td colspan="3">
                            <input type="text" class="form-control" name="fee-description" placeholder="说明" value="" data-default="">
{{--                            <textarea class="form-control" name="fee-description" rows="3" cols="100%"></textarea>--}}
                        </td>

                    </tr>

                    <tr>
                        <th>记录类型</th>
                        <th>交易时间</th>
                        <th>支付方式</th>
                        <th>付款账号</th>
                        <th>收款账号</th>
                        <th>交易单号</th>
                        <th>交易说明</th>
                    </tr>

                    <tr>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn radio" style="padding:4px 2px;margin-top:0;">
                                    <label>
                                        <input type="radio" name="fee-record-type" value=1> 普通记录
                                    </label>
                                </button>
                                <button type="button" class="btn radio" style="padding:4px 2px;margin-top:0;">
                                    <label>
                                        <input type="radio" name="fee-record-type" value=81 checked="checked"> 财务入账
                                    </label>
                                </button>
                                <button type="button" class="btn radio advance-box" style="padding:4px 2px;margin-top:0;">
                                    <label>
                                        <input type="radio" name="fee-record-type" value=49> 垫付
                                    </label>
                                </button>
                                <button type="button" class="btn radio collection-box" style="padding:4px 2px;margin-top:0;">
                                    <label>
                                        <input type="radio" name="fee-record-type" value=41> 代收
                                    </label>
                                </button>
                            </div>
                        </td>
                        <td class="payment-show">
                            <input type="text" class="form-control time-picker-c" name="fee-transaction-datetime" readonly="readonly" />
                        </td>
                        <td class="payment-show">
                            <input type="text" class="form-control" name="fee-transaction-payment-method" placeholder="支付方式" value="" data-default="" list="_payment_method_2">
                        </td>
                        <datalist id="_payment_method_2">
                            <option value="微信" />
                            <option value="支付宝" />
                            <option value="银行卡" />
                            <option value="现金" />
                            <option value="其他" />
                        </datalist>
                        <td class="payment-show">
                            <input type="text" class="form-control search-input" name="fee-transaction-account-from" placeholder="付款账号" value="" data-default="" list="_fee_account_from" autocomplete="on">
                        </td>
                        <td class="payment-show">
                            <input type="text" class="form-control search-input" name="fee-transaction-account-to" placeholder="收款账号" value="" data-default="" list="_fee_account_to" autocomplete="on">
                        </td>
                        <td class="payment-show">
                            <input type="text" class="form-control" name="fee-transaction-reference-no" placeholder="交易单号" value="" data-default="">
                        </td>
                        <td class="payment-show">
                            <input type="text" class="form-control" name="fee-transaction-description" placeholder="交易说明" value="" data-default="">
                            {{--<textarea class="form-control" name="fee-transaction-description" rows="3" cols="100%"></textarea>--}}
                        </td>
                    </tr>





                    </tbody>
                </table>
            </div>
            </form>

            <div class="box-footer _none">
            </div>

        </div>
    </div>


    {{--订单信息--}}
    <div class="col-md-4 form-wrapper _none" style="padding:2px 8px;">

        <div class="box box-success box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">订单信息</h3>
            </div>


            <form action="" method="post" class="form-horizontal form-bordered form--for--info-">
            <div class="box-body">

                {{ csrf_field() }}
                <input readonly type="hidden" class="form-control" name="operate[type]" value="create" data-default="create">
                <input readonly type="hidden" class="form-control" name="operate[id]" value="0" data-default="0">
                <input readonly type="hidden" class="form-control" name="operate[item_category]" value="item" data-default="item">
                <input readonly type="hidden" class="form-control" name="operate[item_type]" value="order" data-default="order">



                {{--自定义标题--}}
                <div class="form-group _none">
                    <label class="control-label col-md-4"><sup class="text-red">*</sup> 自定义标题</label>
                    <div class="col-md-8 ">
                        <input type="text" class="form-control" name="title" placeholder="自定义订单标题" value="">
                    </div>
                </div>

                {{--派车日期 & 任务日期--}}
                <div class="form-group" >
                    <label class="control-label col-md-4"><sup class="text-red">*</sup> 派车日期 & 任务日期</label>
                    <div class="col-md-8 ">
                        <div class="col-sm-6 col-md-6 padding-0">
                            <input type="text" class="form-control date-picker-c" name="assign_date" placeholder="派车日期"
                                   value="{{ date('Y-m-d') }}"
                                   data-default="{{ date('Y-m-d') }}"
                                   readonly="readonly"
                            >
                        </div>
                        <div class="col-sm-6 col-md-6 padding-0">
                            <input type="text" class="form-control date-picker-c" name="task_date" placeholder="任务日期"
                                   value="{{ date('Y-m-d') }}"
                                   data-default="{{ date('Y-m-d') }}"
                                   readonly="readonly"
                            >
                        </div>
                    </div>
                </div>

                {{--订单类型--}}
                <div class="form-group form-category">
                    <label class="control-label col-md-4"><sup class="text-red">*</sup> 订单类型</label>
                    <div class="col-md-8 control-label" style="text-align:left;">
                        <div class="btn-group">

                            <button type="button" class="btn radio radio-department-type">
                                <label>
                                    <input type="radio" name="order_type" value="1" checked="checked" data-default="default"> 固定线路
                                </label>
                            </button>

                            <button type="button" class="btn radio radio-department-type">
                                <label>
                                    <input type="radio" name="order_type" value="11"> 配货
                                </label>
                            </button>

                        </div>
                    </div>
                </div>

                {{--客户--}}
{{--                <div class="form-group">--}}
{{--                    <label class="control-label col-md-4"><sup class="text-red">*</sup> 客户</label>--}}
{{--                    <div class="col-md-8 ">--}}
{{--                        <select class="form-control select2--client-c" name="client_id">--}}
{{--                            <option data-id="0" value="0">选择客户</option>--}}
{{--                        </select>--}}
{{--                    </div>--}}
{{--                </div>--}}
                {{--项目--}}
                <div class="form-group">
                    <label class="control-label col-md-4"><sup class="text-red">*</sup> 项目</label>
                    <div class="col-md-8 ">
                        <select class="form-control select2--project-c" name="project_id">
                            <option data-id="0" value="0">选择项目</option>
                        </select>
                    </div>
                </div>
                {{--出发地 & 目的地--}}
                {{--                <div class="form-group" >--}}
                {{--                    <label class="control-label col-md-4"><sup class="text-red">*</sup> 出发地 & 目的地 & 线路</label>--}}
                {{--                    <div class="col-md-8 ">--}}
                {{--                        <div class="col-sm-3 col-md-3 padding-0">--}}
                {{--                            <input type="text" class="form-control" name="transport_departure_place" placeholder="出发地" value="" data-default="" list="_transport_departure_place_title">--}}
                {{--                        </div>--}}
                {{--                        <div class="col-sm-3 col-md-3 padding-0">--}}
                {{--                            <input type="text" class="form-control" name="transport_destination_place" placeholder="目的地" value="" data-default="" list="_transport_destination_place_title">--}}
                {{--                        </div>--}}
                {{--                        <div class="col-sm-6 col-md-6 padding-0">--}}
                {{--                            <input type="text" class="form-control" name="transport_route" placeholder="线路" value="" data-default="" list="_transport_route_title">--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                </div>--}}
                <div class="form-group" >
                    <label class="control-label col-md-4"><sup class="text-red">*</sup> 出发地</label>
                    <div class="col-md-8 ">
                        <div class="col-sm-12 col-md-12 padding-0">
                            <input type="text" class="form-control" name="transport_departure_place" placeholder="出发地" value="" data-default="" list="_transport_departure_place_title">
                        </div>
                    </div>
                </div>
                {{--出发地 & 目的地--}}
                <div class="form-group" >
                    <label class="control-label col-md-4"><sup class="text-red">*</sup> 目的地</label>
                    <div class="col-md-8 ">
                        <div class="col-sm-12 col-md-12 padding-0">
                            <input type="text" class="form-control" name="transport_destination_place" placeholder="目的地" value="" data-default="" list="_transport_destination_place_title">
                        </div>
                    </div>
                </div>
                <div class="form-group _none">
                    <label class="control-label col-md-4"><sup class="text-red">*</sup> 线路</label>
                    <div class="col-md-8 ">
                        <div class="col-sm-12 col-md-12 padding-0">
                            <input type="text" class="form-control" name="transport_route" placeholder="线路" value="" data-default="" list="_transport_route_title">
                        </div>
                    </div>
                </div>
                <datalist id="_transport_departure_place_title">
                    <option value="质检3号仓-菜鸟8号仓" />
                    <option value="质检1号仓-菜鸟6号仓" />
                    <option value="质检2号仓-菜鸟7号仓" />
                </datalist>
                <datalist id="_transport_destination_place_title">
                    <option value="南大区（鹤山、高明）" />
                    <option value="北大区（三、芦、旺、肇）" />
                </datalist>
                <datalist id="_transport_route_title">
                </datalist>
                {{--里程 & 时效--}}
                <div class="form-group" >
                    <label class="control-label col-md-4"><sup class="text-red">*</sup> 里程 & 时效</label>
                    <div class="col-md-8 ">
                        <div class="col-sm-6 col-md-6 padding-0">
                            <input type="text" class="form-control" name="transport_distance" placeholder="里程" value="0" data-default="">
                        </div>
                        <div class="col-sm-6 col-md-6 padding-0">
                            <input type="text" class="form-control" name="transport_time_limitation" placeholder="时效" value="0" data-default="">
                        </div>
                    </div>
                </div>

                {{--运费 & 油卡 & 串点运费--}}
                <div class="form-group">
                    <label class="control-label col-md-4"><sup class="text-red">*</sup> 运费 & 油卡</label>
                    <div class="col-md-8 ">
                        <div class="col-sm-6 col-md-6 padding-0">
                            <input type="text" class="form-control" name="freight_amount" placeholder="运费" value="0" data-default="0">
                        </div>
                        <div class="col-sm-6 col-md-6 padding-0">
                            <input type="text" class="form-control" name="freight_oil_card_amount" placeholder="油卡" value="0" data-default="0">
                        </div>
                    </div>
                </div>

                {{--串点运费--}}
                <div class="form-group">
                    <label class="control-label col-md-4"><sup class="text-red">*</sup> 串点运费(收)</label>
                    <div class="col-md-8 ">
                        <div class="col-sm-12 col-md-12 padding-0">
                            <input type="text" class="form-control" name="freight_extra_amount" placeholder="串点运费" value="0" data-default="0">
                        </div>
                    </div>
                </div>

                {{--开票--}}
                <div class="form-group">
                    <label class="control-label col-md-4"><sup class="text-red">*</sup> 开票金额 & 票点 (收)</label>
                    <div class="col-md-8 ">
                        <div class="col-sm-6 col-md-6 padding-0">
                            <input type="text" class="form-control" name="financial_receipt_for_invoice_amount" placeholder="开票金额" value="0" data-default="0">
                        </div>
                        <div class="col-sm-6 col-md-6 padding-0">
                            <input type="text" class="form-control" name="financial_receipt_for_invoice_point" placeholder="开票点" value="0.00" data-default="0.00">
                        </div>
                    </div>
                </div>
                {{--共建车费 & 信息费--}}
                <div class="form-group">
                    <label class="control-label col-md-4"><sup class="text-red">*</sup> 共建车费(支)</label>
                    <div class="col-md-8 ">
                        <div class="col-sm-12 col-md-12 padding-0">
                            <input type="text" class="form-control" name="cooperative_vehicle_amount" placeholder="共建车费" value="0" data-default="0">
                        </div>
                    </div>
                </div>
                {{--信息费--}}
                <div class="form-group">
                    <label class="control-label col-md-4"><sup class="text-red">*</sup> 信息费 (支)</label>
                    <div class="col-md-8 ">
                        <div class="col-sm-12 col-md-12 padding-0">
                            <input type="text" class="form-control" name="financial_fee_for_information" placeholder="信息费" value="0" data-default="0">
                        </div>
                    </div>
                </div>

                {{--任务编号--}}
                <div class="form-group">
                    <label class="control-label col-md-4">任务编号</label>
                    <div class="col-md-8 ">
                        <input type="text" class="form-control" name="task_number" placeholder="任务编号" value="" data-default="">
                    </div>
                </div>

                {{--车辆所属--}}
                <div class="form-group form-category">
                    <label class="control-label col-md-4"><sup class="text-red">*</sup> 车辆所属</label>
                    <div class="col-md-8 ">
                        <div class="btn-group">

                            <button type="button" class="btn radio radio-car-type">
                                <label>
                                    <input type="radio" name="car_owner_type" value="1" checked="checked"> 自有车
                                </label>
                            </button>

                            <button type="button" class="btn radio radio-car-type">
                                <label>
                                    <input type="radio" name="car_owner_type" value="9"> 共建车
                                </label>
                            </button>

                            <button type="button" class="btn radio radio-department-type">
                                <label>
                                    <input type="radio" name="car_owner_type" value="11"> 外请车
                                </label>
                            </button>

                        </div>
                    </div>
                </div>

                {{--车型--}}
                <div class="form-group">
                    <label class="control-label col-md-4"><sup class="text-red">*</sup> 车型</label>
                    <div class="col-md-8 ">
                        <select class="form-control select2-box-c select2-reset"
                                name="car_type"
                        >
                            <option value="">选择车型</option>
                            @if(!empty(config('wl.common-config.car_type')))
                                @foreach(config('wl.common-config.car_type') as $k => $v)
                                    <option value ="{{ $v }}">{{ $v }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                {{--自有车辆--}}
                <div class="form-group internal-car">
                    <label class="control-label col-md-4"><sup class="text-red">*</sup> 自有车辆</label>
                    <div class="col-md-8 ">
                        <div class="col-sm-6 col-md-6 padding-0">
                            <select class="form-control select2-reset select2--car-c"
                                    name="car_id"
                                    data-car-category="1"
                                    {{--data-car-type="1"--}}
                                    data-with="order"
                            >
                                <option value="0">选择车辆</option>
                            </select>
                        </div>
                        <div class="col-sm-6 col-md-6 padding-0">
                            <select class="form-control select2-reset select2--car-c"
                                    name="trailer_id"
                                    data-car-category="21"
                                    data-car-type="21"
                            >
                                <option value="0">选择车挂</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{--外请车--}}
                <div class="form-group external-car" style="display:none;">
                    <label class="control-label col-md-4"><sup class="text-red">*</sup> 外请车</label>
                    <div class="col-md-8 ">
                        <div class="col-sm-6 col-md-6 padding-0" style="width:50%;">
                            <input type="text" class="form-control" name="external_car_price" placeholder="请车价" value="0" data-default="0">
                        </div>
                        <div class="col-sm-3 col-md-3 padding-0" style="width:25%;">
                            <input type="text" class="form-control" name="external_car" placeholder="车辆" value="" data-default="">
                        </div>
                        <div class="col-sm-3 col-md-3 padding-0" style="width:25%;">
                            <input type="text" class="form-control" name="external_trailer" placeholder="车挂" value="" data-default="">
                        </div>
                    </div>
                </div>

                {{--自家司机--}}
                <div class="form-group internal-car">
                    <label class="control-label col-md-4"><sup class="text-red">*</sup> 自家司机</label>
                    <div class="col-md-8 ">
                        <div class="col-sm-6 col-md-6 padding-0">
                            <select class="form-control select2-reset select2--driver-c"
                                    name="driver_id"
                                    data-item-category=""
                                    data-item-type=""
                                    data-driver-type="1"
                            >
                                <option value="0">选择主驾</option>
                            </select>
                        </div>
                        <div class="col-sm-6 col-md-6 padding-0">
                            <select class="form-control select2-reset select2--driver-c"
                                    name="copilot_id"
                                    data-item-category=""
                                    data-item-type=""
                                    data-driver-type="11"
                            >
                                <option value="0">选择副驾</option>
                            </select>
                        </div>
                    </div>
                </div>
                {{--驾驶员信息--}}
                <div class="form-group">
                    <label class="control-label col-md-4"><sup class="text-red">*</sup> 驾驶员信息</label>
                    <div class="col-md-8 ">
                        <div class="col-sm-6 col-md-6 padding-0" style="width:50%;">
                            <input type="text" class="form-control" name="driver_name" placeholder="主驾" value="" data-default="">
                        </div>
                        <div class="col-sm-6 col-md-6 padding-0" style="width:50%;">
                            <input type="text" class="form-control" name="driver_phone" placeholder="主驾电话" value="" data-default="">
                        </div>
                        <div class="col-sm-6 col-md-6 padding-0" style="width:50%;">
                            <input type="text" class="form-control" name="copilot_name" placeholder="副驾" value="" data-default="">
                        </div>
                        <div class="col-sm-6 col-md-6 padding-0" style="width:50%;">
                            <input type="text" class="form-control" name="copilot_phone" placeholder="副驾电话" value="" data-default="">
                        </div>
                    </div>
                </div>

                {{--安排人 & 收款人 & 车货源--}}
                <div class="form-group">
                    <label class="control-label col-md-4">安排人 & 收款人 & 车货源</label>
                    <div class="col-md-8 ">
                        <div class="col-sm-4 col-md-4 padding-0">
                            <input type="text" class="form-control" name="arrange_people" placeholder="安排人" value="" data-default="">
                        </div>
                        <div class="col-sm-4 col-md-4 padding-0">
                            <input type="text" class="form-control" name="payee_name" placeholder="收款人" value="" data-default="">
                        </div>
                        <div class="col-sm-4 col-md-4 padding-0">
                            <input type="text" class="form-control" name="car_supply" placeholder="车货源" value="" data-default="">
                        </div>
                    </div>
                </div>


                {{--备注--}}
                <div class="form-group">
                    <label class="control-label col-md-4">备注</label>
                    <div class="col-md-8 ">
                        {{--<input type="text" class="form-control" name="description" placeholder="描述" value="{{$data->description or ''}}">--}}
                        <textarea class="form-control" name="description" rows="3" cols="100%"></textarea>
                    </div>
                </div>



            </div>
            </form>


            <div class="box-footer">
                <button type="submit" class="btn btn-success pull-right">提交</button>
            </div>
        </div>

    </div>


    {{--添加费用--}}
    <div class="col-md-4 form-wrapper _none" style="padding:2px 8px;">

        <div class="box box-danger box-solid modal-content">
            <div class="box-header with-border">
                <h3 class="box-title">添加费用</h3>
            </div>


            <form action="" method="post" class="form-horizontal form-bordered form--for--fee-">
                <div class="box-body">

                    {{ csrf_field() }}
                    <input readonly type="hidden" name="operate[type]" value="edit" data-default="edit">
                    <input readonly type="hidden" name="operate[id]" value="0" data-default="0">
                    <input readonly type="hidden" name="operate[item_category]" value="order" data-default="order">
                    <input readonly type="hidden" name="operate[item_type]" value="fee" data-default="fee">

                    {{--交易类型--}}
                    <div class="form-group">
                        <label class="control-label col-md-3"><sup class="text-red">*</sup> 费用类型</label>
                        <div class="col-md-9 control-label" style="text-align:left;">
                            <button type="button" class="btn radio">
                                <label>
                                    <input type="radio" name="fee-type" value="1"> 收款
                                </label>
                            </button>
                            <button type="button" class="btn radio">
                                <label>
                                    <input type="radio" name="fee-type" value="99" checked="checked" data-default="default"> 费用
                                </label>
                            </button>
                            <button type="button" class="btn radio">
                                <label>
                                    <input type="radio" name="fee-type" value="101"> 订单扣款
                                </label>
                            </button>
                            <button type="button" class="btn radio">
                                <label>
                                    <input type="radio" name="fee-type" value="111"> 员工罚款
                                </label>
                            </button>
                        </div>
                    </div>
                    {{--时间--}}
                    <div class="form-group">
                        <label class="control-label col-md-3"><sup class="text-red">*</sup> 时间</label>
                        <div class="col-md-9 ">
                            <input type="text" class="form-control form-filter time-picker-c" name="fee-datetime" readonly="readonly" />
                        </div>
                    </div>
                    {{--名目--}}
                    <div class="form-group fee-title-box fee-box">
                        <label class="control-label col-md-3"><sup class="text-red">*</sup> 费用名目</label>
                        {{--<div class="col-md-8 ">--}}
                        {{--<input type="text" class="form-control" name="fee-title-for-fee" placeholder="请输入费用名目" value="">--}}
                        {{--</div>--}}
                        <div class="col-md-9 ">
                            <select class="form-control select2-box-c select2-reset" name="fee-title-for-fee">
                                <option value="">请选择费用名目</option>
                                @if(!empty(config('wl.common-config.fee_title')))
                                    @foreach(config('wl.common-config.fee_title') as $k => $v)
                                        <option value ="{{ $v }}">{{ $v }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    {{--收款名目--}}
                    <div class="form-group fee-title-box receipt-box">
                        <label class="control-label col-md-3"><sup class="text-red">*</sup> 收款名目</label>
                        <div class="col-md-9 ">
                            <input type="text" class="form-control" name="fee-title-for-receipt" placeholder="请输入收款名目" value="">
                        </div>
                    </div>
                    {{--扣款名目--}}
                    <div class="form-group fee-title-box deduction-box">
                        <label class="control-label col-md-3"><sup class="text-red">*</sup> 扣款名目</label>
                        {{--<div class="col-md-8 ">--}}
                        {{--<input type="text" class="form-control" name="fee-title-for-deduction" placeholder="请输入扣款名目" value="">--}}
                        {{--</div>--}}
                        <div class="col-md-9 ">
                            <select class="form-control select2-box-c select2-reset" name="fee-title-for-deduction">
                                <option value="">请选择扣款名目</option>
                                @if(!empty(config('wl.common-config.deduction_title')))
                                    @foreach(config('wl.common-config.deduction_title') as $k => $v)
                                        <option value ="{{ $v }}">{{ $v }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    {{--罚款名目--}}
                    <div class="form-group fee-title-box fine-box">
                        <label class="control-label col-md-3"><sup class="text-red">*</sup> 罚款名目</label>
                        {{--<div class="col-md-9 ">--}}
                        {{--<input type="text" class="form-control" name="fee-title-for-fine" placeholder="请输入罚款名目" value="">--}}
                        {{--</div>--}}
                        <div class="col-md-9 ">
                            <select class="form-control select2-box-c select2-reset" name="fee-title-for-fine">
                                <option value="">请选择罚款名目</option>
                                @if(!empty(config('wl.common-config.fine_title')))
                                    @foreach(config('wl.common-config.fine_title') as $k => $v)
                                        <option value ="{{ $v }}">{{ $v }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    {{--                    <datalist id="_fee_title">--}}
                    {{--                        <option value="油费" />--}}
                    {{--                        <option value="过路费" />--}}
                    {{--                        <option value="尿酸" />--}}
                    {{--                        <option value="迪奥" />--}}
                    {{--                        <option value="其他" />--}}
                    {{--                    </datalist>--}}
                    {{--金额--}}
                    <div class="form-group">
                        <label class="control-label col-md-3"><sup class="text-red">*</sup> 金额</label>
                        <div class="col-md-9 ">
                            <input type="text" class="form-control" name="fee-amount" placeholder="请输入金额" value="">
                        </div>
                    </div>
                    {{--说明--}}
                    <div class="form-group">
                        <label class="control-label col-md-3">说明</label>
                        <div class="col-md-9 ">
                            <textarea class="form-control" name="fee-description" rows="3" cols="100%"></textarea>
                        </div>
                    </div>
                    {{--记录类型--}}
                    <div class="form-group fee-record-type-box">
                        <label class="control-label col-md-3"><sup class="text-red">*</sup> 记录类型</label>
                        <div class="col-md-9 control-label" style="text-align:left;">
                            <button type="button" class="btn radio">
                                <label>
                                    <input type="radio" name="fee-record-type" value=1> 普通记录
                                </label>
                            </button>
                            <button type="button" class="btn radio">
                                <label>
                                    <input type="radio" name="fee-record-type" value=81 checked="checked"> 财务入账
                                </label>
                            </button>
                            <button type="button" class="btn radio advance-box">
                                <label>
                                    <input type="radio" name="fee-record-type" value=49> 垫付
                                </label>
                            </button>
                            <button type="button" class="btn radio collection-box">
                                <label>
                                    <input type="radio" name="fee-record-type" value=41> 代收
                                </label>
                            </button>
                        </div>
                    </div>
                    {{--交易时间--}}
                    <div class="form-group payment-show">
                        <label class="control-label col-md-3"><sup class="text-red">*</sup> 交易时间</label>
                        <div class="col-md-9 ">
                            <input type="text" class="form-control time-picker-c" name="fee-transaction-datetime" readonly="readonly" />
                        </div>
                    </div>
                    {{--支付方式--}}
                    <div class="form-group payment-show">
                        <label class="control-label col-md-3">支付方式</label>
                        <div class="col-md-9 ">
                            <input type="text" class="form-control" name="fee-transaction-payment-method" placeholder="支付方式" value="" list="_payment_method">
                        </div>
                    </div>
                    <datalist id="_payment_method">
                        <option value="微信" />
                        <option value="支付宝" />
                        <option value="银行卡" />
                        <option value="现金" />
                        <option value="其他" />
                    </datalist>
                    {{--付款账号--}}
                    <div class="form-group payment-show">
                        <label class="control-label col-md-3">付款账号</label>
                        <div class="col-md-9 ">
                            <input type="text" class="form-control search-input" name="fee-transaction-account-from" placeholder="付款账号" value="" list="_fee_account_from" autocomplete="on">
                        </div>
                    </div>
                    <datalist id="_fee_account_from">
                    </datalist>
                    {{--收款账号--}}
                    <div class="form-group payment-show">
                        <label class="control-label col-md-3">收款账号</label>
                        <div class="col-md-9 ">
                            <input type="text" class="form-control search-input" name="fee-transaction-account-to" placeholder="收款账号" value="" list="_fee_account_to" autocomplete="on">
                        </div>
                    </div>
                    <datalist id="_fee_account_to">
                    </datalist>
                    {{--交易单号--}}
                    <div class="form-group payment-show">
                        <label class="control-label col-md-3">交易单号</label>
                        <div class="col-md-9 ">
                            <input type="text" class="form-control" name="fee-transaction-reference-no" placeholder="交易单号" value="">
                        </div>
                    </div>
                    {{--交易说明--}}
                    <div class="form-group payment-show">
                        <label class="control-label col-md-3">交易说明</label>
                        <div class="col-md-9 ">
                            <textarea class="form-control" name="fee-transaction-description" rows="3" cols="100%"></textarea>
                        </div>
                    </div>

                </div>
            </form>


            <div class="box-footer">
                <button type="submit" class="btn btn-success pull-right">提交</button>
            </div>
        </div>

    </div>


    {{--费用核算--}}
    <div class="col-md-4 form-wrapper _none" style="padding:2px 8px;">

        <div class="box box-warning box-solid modal-content">
            <div class="box-header with-border">
                <h3 class="box-title">费用核算</h3>
            </div>

            <form action="" method="post" class="form-horizontal form-bordered form--for--accounting-">
            <div class="box-body">

                {{ csrf_field() }}
                <input readonly type="hidden" name="operate[type]" value="edit" data-default="edit">
                <input readonly type="hidden" name="operate[id]">
                <input readonly type="hidden" name="operate[item_category]" value="order" data-default="order">
                <input readonly type="hidden" name="operate[item_type]" value="accounting" data-default="accounting">

                {{--运费--}}
                <div class="form-group">
                    <label class="control-label col-md-4"><sup class="text-red">*</sup> 运费现金 & 运费油卡</label>
                    <div class="col-md-8 ">
                        <div class="col-sm-6 col-md-6 padding-0">
                            <input type="text" class="form-control form-filter" name="accounting_freight_cash" placeholder="运费现金" />
                        </div>
                        <div class="col-sm-6 col-md-6 padding-0">
                            <input type="text" class="form-control form-filter" name="accounting_freight_oil_card" placeholder="运费油卡" />
                        </div>
                    </div>
                </div>
                {{--开票--}}
                <div class="form-group">
                    <label class="control-label col-md-4"><sup class="text-red">*</sup> 开票费用 & 金额 & 点数(收)</label>
                    <div class="col-md-8 ">
                        <div class="col-sm-4 col-md-4 padding-0">
                            <input type="text" class="form-control form-filter" name="accounting_invoice_total" placeholder="开票金额(收)" />
                        </div>
                        <div class="col-sm-4 col-md-4 padding-0">
                            <input type="text" class="form-control form-filter" name="accounting_invoice_amount" placeholder="开票金额(收)" />
                        </div>
                        <div class="col-sm-4 col-md-4 padding-0">
                            <input type="text" class="form-control form-filter" name="accounting_invoice_point" placeholder="开票点数(收)" />
                        </div>
                    </div>
                </div>
                {{--开票--}}
                <div class="form-group">
                    <label class="control-label col-md-4"><sup class="text-red">*</sup> 开票费用 & 金额 & 票点(支)</label>
                    <div class="col-md-8 ">
                        <div class="col-sm-4 col-md-4 padding-0">
                            <input type="text" class="form-control form-filter" name="accounting_fee_invoice_total" placeholder="开票费用(支)" />
                        </div>
                        <div class="col-sm-4 col-md-4 padding-0">
                            <input type="text" class="form-control form-filter" name="accounting_fee_invoice_amount" placeholder="开票金额(支)" />
                        </div>
                        <div class="col-sm-4 col-md-4 padding-0">
                            <input type="text" class="form-control form-filter" name="accounting_fee_invoice_point" placeholder="票点(支)" />
                        </div>
                    </div>
                </div>
                {{--油费--}}
                <div class="form-group">
                    <div class="clear-both">
                        <label class="control-label col-md-4"><sup class="text-red">*</sup> 总油费 & 油卡 & 现金</label>
                        <div class="col-md-8 ">
                            <div class="col-sm-4 col-md-4 padding-0">
                                <input type="text" class="form-control form-filter" name="accounting_oil_total" placeholder="总油费" />
                            </div>
                            <div class="col-sm-4 col-md-4 padding-0">
                                <input type="text" class="form-control form-filter" name="accounting_oil_card" placeholder="油卡" />
                            </div>
                            <div class="col-sm-4 col-md-4 padding-0">
                                <input type="text" class="form-control form-filter" name="accounting_oil_cash" placeholder="现金" />
                            </div>
                        </div>
                    </div>
                    <div class="clear-both">
                        <label class="control-label col-md-4"><sup class="text-red">*</sup> 公里数 & 油耗 & 单价</label>
                        <div class="col-md-8 ">
                            <div class="col-sm-4 col-md-4 padding-0">
                                <input type="text" class="form-control form-filter" name="accounting_oil_mileage" placeholder="公里数" />
                            </div>
                            <div class="col-sm-4 col-md-4 padding-0">
                                <input type="text" class="form-control form-filter" name="accounting_oil_consumption" placeholder="油耗" />
                            </div>
                            <div class="col-sm-4 col-md-4 padding-0">
                                <input type="text" class="form-control form-filter" name="accounting_oil_unit_price" placeholder="单价" />
                            </div>
                        </div>
                    </div>
                </div>
                {{--气费--}}
                <div class="form-group">
                    <div class="clear-both">
                        <label class="control-label col-md-4"><sup class="text-red">*</sup> 总气费 & 气卡 & 现金</label>
                        <div class="col-md-8 ">
                            <div class="col-sm-4 col-md-4 padding-0">
                                <input type="text" class="form-control form-filter" name="accounting_gas_total" placeholder="总气费" />
                            </div>
                            <div class="col-sm-4 col-md-4 padding-0">
                                <input type="text" class="form-control form-filter" name="accounting_gas_card" placeholder="气卡" />
                            </div>
                            <div class="col-sm-4 col-md-4 padding-0">
                                <input type="text" class="form-control form-filter" name="accounting_gas_cash" placeholder="现金" />
                            </div>
                        </div>
                    </div>
                    <div class="clear-both">
                        <label class="control-label col-md-4"><sup class="text-red">*</sup> 公里数 & 气耗 & 单价</label>
                        <div class="col-md-8 ">
                            <div class="col-sm-4 col-md-4 padding-0">
                                <input type="text" class="form-control form-filter" name="accounting_gas_mileage" placeholder="公里数" />
                            </div>
                            <div class="col-sm-4 col-md-4 padding-0">
                                <input type="text" class="form-control form-filter" name="accounting_gas_consumption" placeholder="气耗" />
                            </div>
                            <div class="col-sm-4 col-md-4 padding-0">
                                <input type="text" class="form-control form-filter" name="accounting_gas_unit_price" placeholder="单价" />
                            </div>
                        </div>
                    </div>
                </div>
                {{--过路费--}}
                <div class="form-group">
                    <label class="control-label col-md-4"><sup class="text-red">*</sup> 路费-ETC & 路费-现金</label>
                    <div class="col-md-8 ">
                        <div class="col-sm-6 col-md-6 padding-0">
                            <input type="text" class="form-control form-filter" name="accounting_toll_etc" placeholder="路费-ETC" />
                        </div>
                        <div class="col-sm-6 col-md-6 padding-0">
                            <input type="text" class="form-control form-filter" name="accounting_toll_cash" placeholder="路费-现金" />
                        </div>
                    </div>
                </div>
                {{--停车费--}}
                <div class="form-group">
                    <label class="control-label col-md-4"><sup class="text-red">*</sup> 停车费</label>
                    <div class="col-md-8 ">
                        <input type="text" class="form-control" name="accounting_parking" placeholder="停车费" value="">
                    </div>
                </div>
                {{--费用--}}
                <div class="form-group">
                    <label class="control-label col-md-4"><sup class="text-red">*</sup> 工资 & 奖金</label>
                    <div class="col-md-8 ">
                        <div class="col-sm-6 col-md-6 padding-0">
                            <input type="text" class="form-control form-filter" name="accounting_salary" placeholder="工资" />
                        </div>
                        <div class="col-sm-6 col-md-6 padding-0">
                            <input type="text" class="form-control form-filter" name="accounting_bonus" placeholder="奖金" />
                        </div>
                    </div>
                </div>
                {{--费用--}}
                <div class="form-group">
                    <label class="control-label col-md-4"><sup class="text-red">*</sup> 信息费 & 管理费</label>
                    <div class="col-md-8 ">
                        <div class="col-sm-6 col-md-6 padding-0">
                            <input type="text" class="form-control form-filter" name="accounting_information" placeholder="信息费" />
                        </div>
                        <div class="col-sm-6 col-md-6 padding-0">
                            <input type="text" class="form-control form-filter" name="accounting_administrative" placeholder="管理费" />
                        </div>
                    </div>
                </div>
                {{--费用--}}
                <div class="form-group">
                    <label class="control-label col-md-4"><sup class="text-red">*</sup> 维修费 & 保养费</label>
                    <div class="col-md-8 ">
                        <div class="col-sm-6 col-md-6 padding-0">
                            <input type="text" class="form-control form-filter" name="accounting_repair" placeholder="维修费" />
                        </div>
                        <div class="col-sm-6 col-md-6 padding-0">
                            <input type="text" class="form-control form-filter" name="accounting_maintenance" placeholder="保养费" />
                        </div>
                    </div>
                </div>
                {{--费用--}}
                <div class="form-group">
                    <label class="control-label col-md-4"><sup class="text-red">*</sup> 审车费 & 过户费</label>
                    <div class="col-md-8 ">
                        <div class="col-sm-6 col-md-6 padding-0">
                            <input type="text" class="form-control form-filter" name="accounting_inspection" placeholder="审车费" />
                        </div>
                        <div class="col-sm-6 col-md-6 padding-0">
                            <input type="text" class="form-control form-filter" name="accounting_transfer" placeholder="过户费" />
                        </div>
                    </div>
                </div>
                {{--费用--}}
                <div class="form-group">
                    <label class="control-label col-md-4"><sup class="text-red">*</sup> 保险费 & 贷款费用</label>
                    <div class="col-md-8 ">
                        <div class="col-sm-6 col-md-6 padding-0">
                            <input type="text" class="form-control form-filter" name="accounting_insurance" placeholder="保险费" />
                        </div>
                        <div class="col-sm-6 col-md-6 padding-0">
                            <input type="text" class="form-control form-filter" name="accounting_loan" placeholder="贷款费用" />
                        </div>
                    </div>
                </div>
                {{--其他费用--}}
                <div class="form-group">
                    <label class="control-label col-md-4"><sup class="text-red">*</sup> 其他费用</label>
                    <div class="col-md-8 ">
                        <input type="text" class="form-control" name="accounting_others" placeholder="其他费用">
                    </div>
                </div>
                {{--备注--}}
                <div class="form-group">
                    <label class="control-label col-md-4">备注</label>
                    <div class="col-md-8 ">
                        <textarea class="form-control" name="accounting_description" rows="3" cols="100%"></textarea>
                    </div>
                </div>


            </div>
            </form>


            <div class="box-footer">
                <button type="submit" class="btn btn-default">一键核算</button>
                <button type="submit" class="btn btn-success pull-right">提交</button>
            </div>
        </div>

    </div>


    {{--费用列表--}}
    <div class="col-md-12 datatable-body">
        <div class="box box-danger box-solid" style="box-shadow:0 0;">

            <div class="box-header with-border">
                <h3 class="box-title ">费用列表</h3>
            </div>
            <div class="box-body no-padding-">
                <div class="tableArea full margin-top-0">
                    <table class='table table-striped table-bordered table-hover order-column datatable--for--fee'>
                        <thead>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                        </tfoot>
                    </table>
                </div>
            </div>

        </div>
    </div>


    {{--操作列表--}}
    <div class="col-md-12 datatable-body">
        <div class="box box-primary box-solid" style="box-shadow:0 0;">

            <div class="box-header with-border-">
                <h3 class="box-title ">操作记录</h3>
            </div>
            <div class="box-body no-padding-">
                <div class="tableArea full margin-top-0">
                    <table class='table table-striped table-bordered table-hover order-column datatable--for--operation'>
                        <thead>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                        </tfoot>
                    </table>
                </div>
            </div>

        </div>
    </div>


</div>