{{--添加-费用-信息--}}
<div class="modal fade modal-wrapper" id="modal--for--order-item-fee-create">
    <div class="col-md-6 col-md-offset-3 margin-top-32px margin-bottom-64px bg-white">

        <div class="box- box-info- form-container">

            <div class="box-header with-border margin-top-16px">
                <h3 class="box-title">
                    <span class="">添加财务信息</span>
                    <span class="id-title"></span>
                </h3>
                <div class="box-tools pull-right">
                </div>
            </div>

            <form action="" method="post" class="form-horizontal form-bordered " id="form--for--order-item-fee-create">
                <div class="box-body">

                    {{ csrf_field() }}
                    <input readonly type="hidden" name="operate[type]" value="edit" data-default="edit">
                    <input readonly type="hidden" name="operate[id]" value="0" data-default="0">
                    <input readonly type="hidden" name="operate[item_category]" value="order" data-default="order">
                    <input readonly type="hidden" name="operate[item_type]" value="fee" data-default="fee">

                    {{--交易类型--}}
                    <div class="form-group">
                        <label class="control-label col-md-2">类型</label>
                        <div class="col-md-8 control-label" style="text-align:left;">
                            <button type="button" class="btn radio">
                                <label>
                                    <input type="radio" name="fee-type" value="1"> 收入
                                </label>
                            </button>
                            <button type="button" class="btn radio">
                                <label>
                                    <input type="radio" name="fee-type" value="99" checked="checked"> 费用
                                </label>
                            </button>
                            <button type="button" class="btn radio">
                                <label>
                                    <input type="radio" name="fee-type" value="101"> 订单扣款
                                </label>
                            </button>
                            <button type="button" class="btn radio">
                                <label>
                                    <input type="radio" name="fee-type" value="111"> 司机罚款
                                </label>
                            </button>
                        </div>
                    </div>
                    {{--交易日期--}}
                    <div class="form-group">
                        <label class="control-label col-md-2"><sup class="text-red">*</sup> 时间</label>
                        <div class="col-md-8 ">
                            <input type="text" class="form-control form-filter time-picker" name="fee-datetime" readonly="readonly" />
                        </div>
                    </div>
                    {{--品牌--}}
                    <div class="form-group">
                        <label class="control-label col-md-2"><sup class="text-red">*</sup> 名目</label>
                        <div class="col-md-8 ">
                            <input type="text" class="form-control" name="fee-title" placeholder="请输入名目" value="" list="_fee_title">
                        </div>
                    </div>
                    <datalist id="_fee_title">
                        <option value="油费" />
                        <option value="过路费" />
                        <option value="尿酸" />
                        <option value="迪奥" />
                        <option value="其他" />
                    </datalist>
                    {{--金额--}}
                    <div class="form-group">
                        <label class="control-label col-md-2"><sup class="text-red">*</sup> 金额</label>
                        <div class="col-md-8 ">
                            <input type="text" class="form-control" name="fee-amount" placeholder="请输入金额" value="">
                        </div>
                    </div>
                    {{--交易类型--}}
                    <div class="form-group">
                        <label class="control-label col-md-2">记录</label>
                        <div class="col-md-8 control-label" style="text-align:left;">
                            <button type="button" class="btn radio">
                                <label>
                                    <input type="radio" name="fee-pay-type" value=1 checked="checked"> 记录
                                </label>
                            </button>
                            <button type="button" class="btn radio">
                                <label>
                                    <input type="radio" name="fee-pay-type" value=21> 财务
                                </label>
                            </button>
                            <button type="button" class="btn radio">
                                <label>
                                    <input type="radio" name="fee-pay-type" value=21> 垫付
                                </label>
                            </button>
                            <button type="button" class="btn radio">
                                <label>
                                    <input type="radio" name="fee-pay-type" value=21> 代收
                                </label>
                            </button>
                        </div>
                    </div>
                    {{--支付方式--}}
                    <div class="form-group payment-show" style="display:none;">
                        <label class="control-label col-md-2">支付方式</label>
                        <div class="col-md-8 ">
                            <input type="text" class="form-control" name="fee-payment-method" placeholder="支付方式" value="" list="_payment_method">
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
                    <div class="form-group payment-show" style="display:none;">
                        <label class="control-label col-md-2">付款账号</label>
                        <div class="col-md-8 ">
                            <input type="text" class="form-control search-input" id="keyword" name="fee-account-from" placeholder="付款账号" value="" list="_fee_account_from" autocomplete="on">
                        </div>
                    </div>
                    <datalist id="_fee_account_from">
                    </datalist>
                    {{--收款账号--}}
                    <div class="form-group payment-show" style="display:none;">
                        <label class="control-label col-md-2">收款账号</label>
                        <div class="col-md-8 ">
                            <input type="text" class="form-control search-input" id="keyword" name="fee-account-to" placeholder="收款账号" value="" list="_transaction_receipt_account" autocomplete="on">
                        </div>
                    </div>
                    <datalist id="_fee_account_to">
                    </datalist>
                    {{--交易单号--}}
                    <div class="form-group payment-show" style="display:none;">
                        <label class="control-label col-md-2">交易单号</label>
                        <div class="col-md-8 ">
                            <input type="text" class="form-control" name="fee-reference-no" placeholder="交易单号" value="">
                        </div>
                    </div>
                    {{--备注--}}
                    <div class="form-group">
                        <label class="control-label col-md-2">备注</label>
                        <div class="col-md-8 ">
                            <textarea class="form-control" name="fee-description" rows="3" cols="100%"></textarea>
                        </div>
                    </div>


                </div>
            </form>

            <div class="box-footer">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <button type="button" class="btn btn-success" id="item-submit--for--order-item-fee-create"><i class="fa fa-check"></i> 提交</button>
                        <button type="button" class="btn btn-default modal-cancel">取消</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>