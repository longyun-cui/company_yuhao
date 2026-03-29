{{--编辑-工单--}}
<div class="modal fade modal-main-body modal-wrapper" id="modal--for--car--import--by-excel"
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


            <form action="" method="post" class="form-horizontal form-bordered" id="form--for--car--import--by-excel">
            <div class="box-body">

                {{ csrf_field() }}
                <input readonly type="hidden" class="form-control" name="operate[type]" value="import" data-default="import">
                <input readonly type="hidden" class="form-control" name="operate[id]" value="0" data-default="0">
                <input readonly type="hidden" class="form-control" name="operate[item_category]" value="item" data-default="item">
                <input readonly type="hidden" class="form-control" name="operate[item_type]" value="order" data-default="order">


                {{--车辆类型--}}
                <div class="form-group form-category">
                    <label class="control-label col-md-2"><sup class="text-red">*</sup> 车辆种类</label>
                    <div class="col-md-9">
                        <div class="btn-group">

                            <button type="button" class="btn">
                                <span class="radio">
                                    <label>
                                        <input type="radio" name="car_category" value="1" checked="checked"> 车
                                    </label>
                                </span>
                            </button>

                            <button type="button" class="btn">
                                <span class="radio">
                                    <label>
                                        <input type="radio" name="car_category" value="21"> 挂
                                    </label>
                                </span>
                            </button>

                        </div>
                    </div>
                </div>


                {{--车辆类型--}}
                <div class="form-group form-category">
                    <label class="control-label col-md-2"><sup class="text-red">*</sup> 车辆类型</label>
                    <div class="col-md-9">
                        <div class="btn-group">

                            <button type="button" class="btn">
                                <span class="radio">
                                    <label>
                                        <input type="radio" name="car_type" value="1" checked="checked"> 车(9.6)
                                    </label>
                                </span>
                            </button>

                            <button type="button" class="btn">
                                <span class="radio">
                                    <label>
                                        <input type="radio" name="car_type" value="11"> 车头
                                    </label>
                                </span>
                            </button>

                            <button type="button" class="btn">
                                <span class="radio">
                                    <label>
                                        <input type="radio" name="car_type" value="21"> 挂
                                    </label>
                                </span>
                            </button>

                        </div>
                    </div>
                </div>

                {{--attachment 附件--}}
                <div class="form-group">
                    <label class="control-label col-md-2">Excel文件</label>
                    <div class="col-md-9">
                        <input type="file"
                               class="upload-file upload-file--by--excel"
                               name="upload-file"
                               id="upload-file--for--car--import--by-excel"
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
                                id="submit--for--car--import--by-excel"
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