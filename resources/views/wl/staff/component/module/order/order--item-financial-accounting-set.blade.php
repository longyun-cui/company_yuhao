{{--添加-行程-信息--}}
<div class="modal fade modal-wrapper" id="modal--for--order--item-financial-accounting-set">
    <div class="col-md-8 col-md-offset-2 margin-top-32px margin-bottom-64px bg-white">


        <div class="box- box-info- form-container">

            <div class="box-header with-border margin-top-16px">
                <h3 class="box-title">
                    <span class="">财务核算</span>
                    <span class="id-title"></span>
                </h3>
                <div class="box-tools pull-right">
                </div>
            </div>

            <form action="" method="post" class="form-horizontal form-bordered " id="form--for--order--item-financial-accounting-set">
                <div class="box-body">

                    {{ csrf_field() }}
                    <input readonly type="hidden" name="operate[type]" value="edit" data-default="edit">
                    <input readonly type="hidden" name="operate[id]">
                    <input readonly type="hidden" name="operate[item_category]" value="order" data-default="order">
                    <input readonly type="hidden" name="operate[item_type]" value="accounting" data-default="accounting">

                    {{--运费--}}
                    <div class="form-group">
                        <label class="control-label col-md-3"><sup class="text-red">*</sup> 运费现金 & 运费油卡</label>
                        <div class="col-md-9 ">
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
                        <label class="control-label col-md-3"><sup class="text-red">*</sup> 开票费用 & 金额 & 点数(收)</label>
                        <div class="col-md-9 ">
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
                        <label class="control-label col-md-3"><sup class="text-red">*</sup> 开票费用 & 金额 & 票点(支)</label>
                        <div class="col-md-9 ">
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
                        <label class="control-label col-md-3"><sup class="text-red">*</sup> 总油费 & 油卡 & 现金</label>
                        <div class="col-md-9 ">
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
                        <label class="control-label col-md-3"><sup class="text-red">*</sup> 公里数 & 油耗 & 单价</label>
                        <div class="col-md-9 ">
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
                    {{--气费--}}
                    <div class="form-group">
                        <label class="control-label col-md-3"><sup class="text-red">*</sup> 总气费 & 气卡 & 现金</label>
                        <div class="col-md-9 ">
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
                        <label class="control-label col-md-3"><sup class="text-red">*</sup> 公里数 & 气耗 & 单价</label>
                        <div class="col-md-9 ">
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
                    {{--过路费--}}
                    <div class="form-group">
                        <label class="control-label col-md-3"><sup class="text-red">*</sup> 路费-ETC & 路费-现金</label>
                        <div class="col-md-9 ">
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
                        <label class="control-label col-md-3"><sup class="text-red">*</sup> 停车费</label>
                        <div class="col-md-9 ">
                            <input type="text" class="form-control" name="accounting_parking" placeholder="停车费" value="">
                        </div>
                    </div>
                    {{--费用--}}
                    <div class="form-group">
                        <label class="control-label col-md-3"><sup class="text-red">*</sup> 工资 & 奖金</label>
                        <div class="col-md-9 ">
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
                        <label class="control-label col-md-3"><sup class="text-red">*</sup> 信息费 & 管理费</label>
                        <div class="col-md-9 ">
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
                        <label class="control-label col-md-3"><sup class="text-red">*</sup> 维修费 & 保养费</label>
                        <div class="col-md-9 ">
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
                        <label class="control-label col-md-3"><sup class="text-red">*</sup> 审车费 & 过户费</label>
                        <div class="col-md-9 ">
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
                        <label class="control-label col-md-3"><sup class="text-red">*</sup> 保险费 & 贷款费用</label>
                        <div class="col-md-9 ">
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
                        <label class="control-label col-md-3"><sup class="text-red">*</sup> 其他费用</label>
                        <div class="col-md-9 ">
                            <input type="text" class="form-control" name="accounting_others" placeholder="其他费用">
                        </div>
                    </div>
                    {{--备注--}}
                    <div class="form-group">
                        <label class="control-label col-md-3">备注</label>
                        <div class="col-md-9 ">
                            <textarea class="form-control" name="accounting_description" rows="3" cols="100%"></textarea>
                        </div>
                    </div>


                </div>
            </form>

            <div class="box-footer">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <button type="button" class="btn btn-success"
                                id="item-submit--for--order--item-financial-accounting-set">
                            <i class="fa fa-check"></i> 提交
                        </button>
                        <button type="button" class="btn btn-default modal-cancel">取消</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>