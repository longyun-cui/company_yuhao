{{--编辑-工单--}}
<div class="modal fade modal-main-body modal-wrapper" id="modal--for--fee--import--by-excel"
     tabindex="-1"
     role="dialog"
     data-backdrop="static"
     data-keyboard="false"
     data-bs-backdrop="static"
     data-bs-keyboard="false"
>
    <div class="modal-content col-md-8 col-md-offset-2 margin-top-64px margin-bottom-64px bg-white">
        <div class="box- box-info- form-container">

            <div class="box-header with-border margin-top-16px">
                <h3 class="box-title">导入车辆</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool edit-cancel" data-widget="remove">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
            </div>


            <form action="" method="post" class="form-horizontal form-bordered" id="form--for--fee--import--by-excel">
            <div class="box-body">

                {{ csrf_field() }}
                <input readonly type="hidden" class="form-control" name="operate[type]" value="import" data-default="import">
                <input readonly type="hidden" class="form-control" name="operate[id]" value="0" data-default="0">
                <input readonly type="hidden" class="form-control" name="operate[item_category]" value="item" data-default="item">
                <input readonly type="hidden" class="form-control" name="operate[item_type]" value="order" data-default="order">


                {{--费用类型--}}
                <div class="form-group">
                    <label class="control-label col-md-2"><sup class="text-red">*</sup> 费用类型</label>
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


                {{--记录类型--}}
                <div class="form-group fee-record-type-box">
                    <label class="control-label col-md-2"><sup class="text-red">*</sup> 记录类型</label>
                    <div class="col-md-8 control-label" style="text-align:left;">
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

                {{--attachment 附件--}}
                <div class="form-group">
                    <label class="control-label col-md-2">Excel文件</label>
                    <div class="col-md-9">
                        <input type="file"
                               class="upload-file upload-file--by--excel"
                               name="upload-file"
                               id="upload-file--for--fee--import--by-excel"
                        >
                        {{--<input id="multiple-files" type="file" class="file-upload" name="multiple-excel-file" multiple >--}}
                    </div>
                </div>

            </div>
            </form>


            <div class="box-footer">
                <div class="row">
                    <div class="col-md-9 col-md-offset-2">
                        <button type="button" class="btn btn-success edit-submit"
                                id="submit--for--fee--import--by-excel"
                        >
                            <i class="fa fa-check"></i> 提交
                        </button>
                        <button type="button" class="btn btn-default edit-cancel">
                            <i class="fa fa-times"></i> 取消
                        </button>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>