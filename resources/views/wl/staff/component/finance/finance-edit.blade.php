{{--财务-编辑--}}
<div class="modal fade modal-wrapper" id="modal--for--finance-item-edit">
    <div class="col-md-8 col-md-offset-2 margin-top-16px margin-bottom-64px bg-white">


        <div class="box- box-info- form-container">

            <div class="box-header with-border margin-top-16px">
                <h3 class="box-title">
                    <span class="">财务入账</span>
                    <span class="id-title"></span>
                </h3>
                <div class="box-tools pull-right">
                </div>
            </div>

            <form action="" method="post" class="form-horizontal form-bordered " id="form--for--finance-item-edit">
                <div class="box-body">

                    {{ csrf_field() }}
                    <input readonly type="hidden" name="operate[type]" value="edit" data-default="edit">
                    <input readonly type="hidden" name="operate[id]" value="0" data-default="0">
                    <input readonly type="hidden" name="operate[item_category]" value="order" data-default="order">
                    <input readonly type="hidden" name="operate[item_type]" value="finance" data-default="finance">

                    {{--交易类型--}}
                    <div class="form-group">
                        <label class="control-label col-md-2"><sup class="text-red">*</sup> 类型</label>
                        <div class="col-md-9 control-label" style="text-align:left;">
                            <button type="button" class="btn radio">
                                <label>
                                    <input type="radio" name="finance-type" value="1" checked="checked" data-default="default"> 收入
                                </label>
                            </button>
                            <button type="button" class="btn radio">
                                <label>
                                    <input type="radio" name="finance-type" value="99"> 费用
                                </label>
                            </button>
                        </div>
                    </div>
                    {{--交易时间--}}
                    <div class="form-group payment-show">
                        <label class="control-label col-md-2"><sup class="text-red">*</sup> 名目</label>
                        <div class="col-md-9 ">
                            <input type="text" class="form-control" name="finance-title" placeholder="请输入名目" value="" list="_fee_title">
                        </div>
                    </div>
                    {{--交易时间--}}
                    <div class="form-group payment-show">
                        <label class="control-label col-md-2"><sup class="text-red">*</sup> 金额</label>
                        <div class="col-md-9 ">
                            <input type="text" class="form-control" name="finance-amount" placeholder="请输入金额" value="">
                        </div>
                    </div>
                    {{--交易时间--}}
                    <div class="form-group payment-show">
                        <label class="control-label col-md-2"><sup class="text-red">*</sup> 交易时间</label>
                        <div class="col-md-9 ">
                            <input type="text" class="form-control form-filter time-picker" name="finance-transaction-datetime" readonly="readonly" />
                        </div>
                    </div>
                    {{--支付方式--}}
                    <div class="form-group payment-show">
                        <label class="control-label col-md-2">支付方式</label>
                        <div class="col-md-9 ">
                            <input type="text" class="form-control" name="finance-transaction-payment-method" placeholder="支付方式" value="" list="_payment_method">
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
                        <label class="control-label col-md-2">付款账号</label>
                        <div class="col-md-9 ">
                            <input type="text" class="form-control search-input" name="finance-transaction-account-from" placeholder="付款账号" value="" list="_fee_account_from" autocomplete="on">
                        </div>
                    </div>
                    <datalist id="_fee_account_from">
                    </datalist>
                    {{--收款账号--}}
                    <div class="form-group payment-show">
                        <label class="control-label col-md-2">收款账号</label>
                        <div class="col-md-9 ">
                            <input type="text" class="form-control search-input" name="finance-transaction-account-to" placeholder="收款账号" value="" list="_fee_account_to" autocomplete="on">
                        </div>
                    </div>
                    <datalist id="_fee_account_to">
                    </datalist>
                    {{--交易单号--}}
                    <div class="form-group payment-show">
                        <label class="control-label col-md-2">交易单号</label>
                        <div class="col-md-9 ">
                            <input type="text" class="form-control" name="finance-transaction-reference-no" placeholder="交易单号" value="">
                        </div>
                    </div>
                    {{--交易说明--}}
                    <div class="form-group payment-show">
                        <label class="control-label col-md-2">交易说明</label>
                        <div class="col-md-9 ">
                            <textarea class="form-control" name="finance-transaction-description" rows="3" cols="100%"></textarea>
                        </div>
                    </div>

                </div>
            </form>

            <div class="box-footer">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <button type="button" class="btn btn-success edit-submit" id="submit--for--finance-item-edit">
                            <i class="fa fa-check"></i> 提交
                        </button>
                        <button type="button" class="btn btn-default edit-cancel">取消</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>