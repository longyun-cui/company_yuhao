
<div class="modal fade modal-main-body modal-wrapper" id="modal--for--order--item-edit">
    <div class="modal-content col-md-6 col-md-offset-4- margin-top-16px margin-bottom-64px margin-right-32px bg-white"
    style="float:right;">
        <div class="box- box-info- form-container">

            <div class="box-header with-border margin-top-16px">
                <h3 class="box-title">添加工单</h3>
                <div class="box-tools pull-right">
                </div>
            </div>


            <form action="" method="post" class="form-horizontal form-bordered" id="form--for--order--item-edit">
            <div class="box-body">

                <?php echo nl2br(e(csrf_field())); ?>

                <input readonly type="hidden" class="form-control" name="operate[type]" value="create" data-default="create">
                <input readonly type="hidden" class="form-control" name="operate[id]" value="0" data-default="0">
                <input readonly type="hidden" class="form-control" name="operate[item_category]" value="item" data-default="item">
                <input readonly type="hidden" class="form-control" name="operate[item_type]" value="order" data-default="order">



                
                <div class="form-group _none">
                    <label class="control-label col-md-3"><sup class="text-red">*</sup> 自定义标题</label>
                    <div class="col-md-9 ">
                        <input type="text" class="form-control" name="title" placeholder="自定义订单标题" value="">
                    </div>
                </div>

                
                <div class="form-group" >
                    <label class="control-label col-md-3"><sup class="text-red">*</sup> 派车日期 & 任务日期</label>
                    <div class="col-md-9 ">
                        <div class="col-sm-6 col-md-6 padding-0">
                            <input type="text" class="form-control date-picker" name="assign_date" placeholder="派车日期" value="<?php echo nl2br(e(date('Y-m-d'))); ?>" data-default="<?php echo nl2br(e(date('Y-m-d'))); ?>" readonly="readonly">
                        </div>
                        <div class="col-sm-6 col-md-6 padding-0">
                            <input type="text" class="form-control date-picker" name="task_date" placeholder="任务日期" value="<?php echo nl2br(e(date('Y-m-d'))); ?>" data-default="<?php echo nl2br(e(date('Y-m-d'))); ?>" readonly="readonly">
                        </div>
                    </div>
                </div>

                
                <div class="form-group form-category">
                    <label class="control-label col-md-3"><sup class="text-red">*</sup> 订单类型</label>
                    <div class="col-md-9">
                        <div class="btn-group">

                            <button type="button" class="btn radio-btn radio-department-type">
                                <span class="radio">
                                    <label>
                                        <input type="radio" name="order_type" value="1" checked="checked"> 固定线路
                                    </label>
                                </span>
                            </button>

                            <button type="button" class="btn radio-btn radio-department-type">
                                <span class="radio">
                                    <label>
                                        <input type="radio" name="order_type" value="11"> 配货
                                    </label>
                                </span>
                            </button>

                        </div>
                    </div>
                </div>

                








                
                <div class="form-group">
                    <label class="control-label col-md-3"><sup class="text-red">*</sup> 项目</label>
                    <div class="col-md-9 ">
                        <select class="form-control select2--project"
                                name="project_id"
                                id="select2--project--for--order--item-edit"
                                data-modal="#modal--for--order--item-edit"
                        >
                            <option data-id="" value="">选择项目</option>
                        </select>
                    </div>
                </div>
                














                <div class="form-group" >
                    <label class="control-label col-md-3"><sup class="text-red">*</sup> 出发地</label>
                    <div class="col-md-9 ">
                        <div class="col-sm-12 col-md-12 padding-0">
                            <input type="text" class="form-control" name="transport_departure_place" placeholder="出发地" value="" data-default="" list="_transport_departure_place_title">
                        </div>
                    </div>
                </div>
                
                <div class="form-group" >
                    <label class="control-label col-md-3"><sup class="text-red">*</sup> 目的地</label>
                    <div class="col-md-9 ">
                        <div class="col-sm-12 col-md-12 padding-0">
                            <input type="text" class="form-control" name="transport_destination_place" placeholder="目的地" value="" data-default="" list="_transport_destination_place_title">
                        </div>
                    </div>
                </div>
                <div class="form-group" >
                    <label class="control-label col-md-3"><sup class="text-red">*</sup> 线路</label>
                    <div class="col-md-9 ">
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
                
                <div class="form-group" >
                    <label class="control-label col-md-3"><sup class="text-red">*</sup> 里程 & 时效</label>
                    <div class="col-md-9 ">
                        <div class="col-sm-6 col-md-6 padding-0">
                            <input type="text" class="form-control" name="transport_distance" placeholder="里程" value="0" data-default="">
                        </div>
                        <div class="col-sm-6 col-md-6 padding-0">
                            <input type="text" class="form-control" name="transport_time_limitation" placeholder="时效" value="0" data-default="">
                        </div>
                    </div>
                </div>

                
                <div class="form-group">
                    <label class="control-label col-md-3"><sup class="text-red">*</sup> 运费 & 油卡</label>
                    <div class="col-md-9 ">
                        <div class="col-sm-6 col-md-6 padding-0">
                            <input type="text" class="form-control" name="freight_amount" placeholder="运费" value="0" data-default="0">
                        </div>
                        <div class="col-sm-6 col-md-6 padding-0">
                            <input type="text" class="form-control" name="freight_oil_card_amount" placeholder="油卡" value="0" data-default="0">
                        </div>
                    </div>
                </div>

                
                <div class="form-group">
                    <label class="control-label col-md-3"><sup class="text-red">*</sup> 串点运费(收)</label>
                    <div class="col-md-9 ">
                        <div class="col-sm-12 col-md-12 padding-0">
                            <input type="text" class="form-control" name="freight_extra_amount" placeholder="串点运费" value="0" data-default="0">
                        </div>
                    </div>
                </div>

                
                <div class="form-group">
                    <label class="control-label col-md-3"><sup class="text-red">*</sup> 开票金额 & 票点 (收)</label>
                    <div class="col-md-9 ">
                        <div class="col-sm-6 col-md-6 padding-0">
                            <input type="text" class="form-control" name="financial_receipt_for_invoice_amount" placeholder="开票金额" value="0" data-default="0">
                        </div>
                        <div class="col-sm-6 col-md-6 padding-0">
                            <input type="text" class="form-control" name="financial_receipt_for_invoice_point" placeholder="开票点" value="0.00" data-default="0.00">
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-md-3"><sup class="text-red">*</sup> 共建车费(支)</label>
                    <div class="col-md-9 ">
                        <div class="col-sm-12 col-md-12 padding-0">
                            <input type="text" class="form-control" name="cooperative_vehicle_amount" placeholder="共建车费" value="0" data-default="0">
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-md-3"><sup class="text-red">*</sup> 信息费 (支)</label>
                    <div class="col-md-9 ">
                        <div class="col-sm-12 col-md-12 padding-0">
                            <input type="text" class="form-control" name="financial_fee_for_information" placeholder="信息费" value="0" data-default="0">
                        </div>
                    </div>
                </div>

                
                <div class="form-group">
                    <label class="control-label col-md-3">任务编号</label>
                    <div class="col-md-9 ">
                        <input type="text" class="form-control" name="task_number" placeholder="任务编号" value="" data-default="">
                    </div>
                </div>

                
                <div class="form-group form-category">
                    <label class="control-label col-md-3"><sup class="text-red">*</sup> 车辆所属</label>
                    <div class="col-md-9">
                        <div class="btn-group">

                            <button type="button" class="btn radio-btn radio-car-type">
                                <span class="radio">
                                    <label>
                                        <input type="radio" name="car_owner_type" value="1" checked="checked"> 自有车
                                    </label>
                                </span>
                            </button>

                            <button type="button" class="btn radio-btn radio-car-type">
                                <span class="radio">
                                    <label>
                                        <input type="radio" name="car_owner_type" value="9"> 共建车
                                    </label>
                                </span>
                            </button>

                            <button type="button" class="btn radio-btn radio-department-type">
                                <span class="radio">
                                    <label>
                                        <input type="radio" name="car_owner_type" value="11"> 外请车
                                    </label>
                                </span>
                            </button>

                        </div>
                    </div>
                </div>

                
                <div class="form-group">
                    <label class="control-label col-md-3"><sup class="text-red">*</sup> 车型</label>
                    <div class="col-md-9 ">
                        <select class="form-control modal--select2 select2-reset"
                                name="car_type"
                                data-modal="#modal--for--order--item-edit"
                        >
                            <option value="">选择车型</option>
                            <?php if(!empty(config('wl.common-config.car_type'))): ?>
                                <?php $__currentLoopData = config('wl.common-config.car_type'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value ="<?php echo nl2br(e($v)); ?>"><?php echo nl2br(e($v)); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                
                <div class="form-group internal-car">
                    <label class="control-label col-md-3"><sup class="text-red">*</sup> 自有车辆</label>
                    <div class="col-md-9 ">
                        <div class="col-sm-6 col-md-6 padding-0">
                            <select class="form-control select2-reset select2--car"
                                    name="car_id"
                                    id="select2--car--for--order--item-edit"
                                    data-modal="#modal--for--order--item-edit"
                                    data-car-category="1"

                                    data-with="order"
                            >
                                <option value="0">选择车辆</option>
                            </select>
                        </div>
                        <div class="col-sm-6 col-md-6 padding-0">
                            <select class="form-control select2-reset select2--car"
                                    name="trailer_id"
                                    id="select2--trailer--for--order--item-edit"
                                    data-modal="#modal--for--order--item-edit"
                                    data-car-category="21"
                                    data-car-type="21"
                            >
                                <option value="0">选择车挂</option>
                            </select>
                        </div>
                    </div>
                </div>

                
                <div class="form-group external-car" style="display:none;">
                    <label class="control-label col-md-3"><sup class="text-red">*</sup> 外请车</label>
                    <div class="col-md-9 ">
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

                
                <div class="form-group internal-car">
                    <label class="control-label col-md-3"><sup class="text-red">*</sup> 自家司机</label>
                    <div class="col-md-9 ">
                        <div class="col-sm-6 col-md-6 padding-0">
                            <select class="form-control select2-reset select2--driver"
                                    name="driver_id"
                                    id="select2--driver--for--order--item-edit"
                                    data-modal="#modal--for--order--item-edit"
                                    data-item-category=""
                                    data-item-type=""
                                    data-driver-type="1"
                            >
                                <option value="0">选择主驾</option>
                            </select>
                        </div>
                        <div class="col-sm-6 col-md-6 padding-0">
                            <select class="form-control select2-reset select2--driver"
                                    name="copilot_id"
                                    id="select2--copilot--for--order--item-edit"
                                    data-modal="#modal--for--order--item-edit"
                                    data-item-category=""
                                    data-item-type=""
                                    data-driver-type="11"
                            >
                                <option value="0">选择副驾</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-md-3"><sup class="text-red">*</sup> 驾驶员信息</label>
                    <div class="col-md-9 ">
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

                
                <div class="form-group">
                    <label class="control-label col-md-3">安排人 & 收款人 & 车货源</label>
                    <div class="col-md-9 ">
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


                
                <div class="form-group">
                    <label class="control-label col-md-3">备注</label>
                    <div class="col-md-9 ">
                        
                        <textarea class="form-control" name="description" rows="3" cols="100%"></textarea>
                    </div>
                </div>



            </div>
            </form>


            <div class="box-footer">
                <div class="row">
                    <div class="col-md-9 col-md-offset-3">
                        <button type="button" class="btn btn-success edit-submit" id="submit--for--order--item-edit">
                            <i class="fa fa-check"></i> 提交
                        </button>
                        <button type="button" class="btn btn-default edit-cancel">取消</button>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>