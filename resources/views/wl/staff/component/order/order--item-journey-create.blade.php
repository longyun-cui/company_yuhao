{{--添加-行程-信息--}}
<div class="modal fade modal-wrapper" id="modal--for--order-item-journey-create">
    <div class="col-md-6 col-md-offset-3 margin-top-32px margin-bottom-64px bg-white">

        <div class="box- box-info- form-container">


            <div class="box-header with-border margin-top-16px margin-bottom-16px">
                <h3 class="box-title">操作记录</h3>
                <div class="box-tools pull-right caption _none">
                    <a href="javascript:void(0);">
                        <button type="button" class="btn btn-success pull-right"><i class="fa fa-plus"></i> 添加记录</button>
                    </a>
                </div>
            </div>

            <div class="box-body datatable-body" id="">

                <div class="row col-md-12 datatable-search-row _none">
                    <div class="input-group">

                        <input type="text" class="form-control form-filter filter-keyup" name="order-operation-keyword" placeholder="关键词" />

                        <select class="form-control form-filter" name="order-operation-attribute" style="width:96px;">
                            <option value="-1">选择属性</option>
                        </select>

                        <button type="button" class="form-control btn btn-flat btn-success filter-submit" id="filter-submit-for-order-operation-record">
                            <i class="fa fa-search"></i> 搜索
                        </button>
                        <button type="button" class="form-control btn btn-flat btn-default filter-cancel" id="filter-cancel-for-order-operation-record">
                            <i class="fa fa-circle-o-notch"></i> 重置
                        </button>

                    </div>
                </div>

                <table class='table table-striped table-bordered' id='datatable--for--order-item-operation-record-list'>
                    <thead>
                    <tr role='row' class='heading'>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <!-- datatable end -->
            </div>

            <div class="box-footer _none">
                <div class="row" style="margin:16px 0;">
                    <div class="col-md-offset-0 col-md-4 col-sm-8 col-xs-12">
                        {{--<button type="button" class="btn btn-primary"><i class="fa fa-check"></i> 提交</button>--}}
                        {{--<button type="button" onclick="history.go(-1);" class="btn btn-default">返回</button>--}}
                        <div class="input-group">
                            <span class="input-group-addon"><input type="checkbox" id="check-all"></span>
                            <input type="text" class="form-control" name="bulk-detect-rank" id="bulk-detect-rank" placeholder="指定排名">
                            <span class="input-group-addon btn btn-default" id="set-rank-bulk-submit"><i class="fa fa-check"></i>提交</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="box- box-info- form-container">

            <div class="box-header with-border margin-top-16px">
                <h3 class="box-title">
                    <span class="">添加【行程】信息</span>
                    <span class="id-title"></span>
                </h3>
                <div class="box-tools pull-right">
                </div>
            </div>

            <form action="" method="post" class="form-horizontal form-bordered " id="form--for--order-item-journey-create">
                <div class="box-body">

                    {{ csrf_field() }}
                    <input readonly type="hidden" name="operate[type]" value="edit" data-default="edit">
                    <input readonly type="hidden" name="operate[id]" value="0" data-default="0">
                    <input readonly type="hidden" name="operate[item_category]" value="order" data-default="order">
                    <input readonly type="hidden" name="operate[item_type]" value="fee" data-default="fee">

                    {{--行程类型--}}
                    <div class="form-group">
                        <label class="control-label col-md-2">类型</label>
                        <div class="col-md-8 control-label" style="text-align:left;">
                            <button type="button" class="btn radio">
                                <label>
                                    <input type="radio" name="journey-type" value="1" checked="checked"> 运输
                                </label>
                            </button>
                            <button type="button" class="btn radio">
                                <label>
                                    <input type="radio" name="journey-type" value="99"> 卸货
                                </label>
                            </button>
                            <button type="button" class="btn radio">
                                <label>
                                    <input type="radio" name="journey-type" value="101"> 空单
                                </label>
                            </button>
                        </div>
                    </div>
                    {{--应该时间--}}
                    <div class="form-group">
                        <label class="control-label col-md-2">计划时间</label>
                        <div class="col-md-9 ">
                            <div class="col-sm-6 col-md-6 padding-0">
                                <input type="text" class="form-control form-filter time-picker" name="journey-should-departure-datetime" placeholder="应出发时间" readonly="readonly" />
                            </div>
                            <div class="col-sm-6 col-md-6 padding-0">
                                <input type="text" class="form-control form-filter time-picker" name="journey-should-arrival-datetime" placeholder="应到达时间" readonly="readonly" />
                            </div>
                        </div>
                    </div>
                    {{--实际时间--}}
                    <div class="form-group">
                        <label class="control-label col-md-2"><sup class="text-red">*</sup> 实际时间</label>
                        <div class="col-md-9 ">
                            <div class="col-sm-6 col-md-6 padding-0">
                                <input type="text" class="form-control form-filter time-picker" name="journey-actual-departure-datetime" placeholder="实际出发时间" readonly="readonly" />
                            </div>
                            <div class="col-sm-6 col-md-6 padding-0">
                                <input type="text" class="form-control form-filter time-picker" name="journey-actual-arrival-datetime" placeholder="实际到达时间" readonly="readonly" />
                            </div>
                        </div>
                    </div>
                    {{--名目--}}
{{--                    <div class="form-group">--}}
{{--                        <label class="control-label col-md-2"><sup class="text-red">*</sup> 名目</label>--}}
{{--                        <div class="col-md-9 ">--}}
{{--                            <input type="text" class="form-control" name="journey-title" placeholder="请输入名目" value="" list="_journey_title">--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <datalist id="_journey_title">--}}
{{--                        <option value="油费" />--}}
{{--                        <option value="过路费" />--}}
{{--                        <option value="尿酸" />--}}
{{--                        <option value="迪奥" />--}}
{{--                        <option value="其他" />--}}
{{--                    </datalist>--}}
                    {{--里程--}}
                    <div class="form-group">
                        <label class="control-label col-md-2"><sup class="text-red">*</sup> 里程</label>
                        <div class="col-md-9 ">
                            <input type="text" class="form-control" name="journey-mileage" placeholder="请输如里程" value="">
                        </div>
                    </div>
                    {{--时效--}}
                    <div class="form-group">
                        <label class="control-label col-md-2">时效</label>
                        <div class="col-md-9 ">
                            <input type="text" class="form-control" name="journey-time-limitation" placeholder="请输如时效" value="">
                        </div>
                    </div>
                    {{--备注--}}
                    <div class="form-group">
                        <label class="control-label col-md-2">备注</label>
                        <div class="col-md-9 ">
                            <textarea class="form-control" name="journey-description" rows="3" cols="100%"></textarea>
                        </div>
                    </div>


                </div>
            </form>

            <div class="box-footer">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <button type="button" class="btn btn-success" id="item-submit--for--order-item-journey-create"><i class="fa fa-check"></i> 提交</button>
                        <button type="button" class="btn btn-default modal-cancel">取消</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>