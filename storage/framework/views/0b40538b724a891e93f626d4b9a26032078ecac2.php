<div class="row datatable-body datatable-wrapper order-list--clone" data-datatable-item-category="order" data-item-name="工单">


    <div class="col-md-12 datatable-search-row datatable-search-box">


        <div class="col-md-2- pull-left">
            <button type="button" onclick="" class="btn btn-filter modal-show--for--order--item-create"
                    data-form-id="form--for--order--item-edit"
                    data-modal-id="modal--for--order--item-edit"
                    data-title="添加工单"
            >
                <i class="fa fa-plus"></i> 添加
            </button>
        </div>


        <div class="pull-right _none">


            <div class="nav navbar-nav">

                <div class="dropdown filter-menu _none" data-bs-auto-close="outside">
                    <button type="button" class="btn btn-default btn-filter dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-th-list"></i> 显示列
                    </button>

                    <div class="dropdown-menu non-auto-hide box box-success" style="top:32px;right:4px;">

                        <div class="box-header with-border- _none">
                            筛选
                        </div>



                        
                        <div class="box-body">
                            <div class="form-group">
                                <label class="col-md-3">
                                    <input type="checkbox" class="minimal" checked disabled>
                                </label>
                                <label class="col-md-9 text-align-left">
                                    Minimal skin checkbox
                                </label>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label class="col-md-3">
                                    <input type="checkbox" class="minimal" checked>
                                </label>
                                <label class="col-md-9 text-align-left">
                                    Minimal skin checkbox
                                </label>
                            </div>
                        </div>
                        
                        <div class="box-body">
                            <label class="col-md-3">ID</label>
                            <div class="col-md-9 filter-body">
                                <input type="text" class="form-control form-filter filter-keyup" name="order-id-" placeholder="ID" value="" />
                            </div>
                        </div>

                        
                        <div class="box-body">
                            <label class="col-md-3">派车日期</label>
                            <div class="col-md-9 filter-body">
                                <input type="text" class="form-control form-filter filter-keyup date-picker-c" name="order-assign-date-" placeholder="指派日期" value="" readonly="readonly" />
                            </div>
                        </div>

                        
                        <div class="box-body">
                            <label class="col-md-3">任务日期</label>
                            <div class="col-md-9 filter-body">
                                <input type="text" class="form-control form-filter filter-keyup date-picker-c" name="order-task-date-" placeholder="任务日期" value="" readonly="readonly" />
                            </div>
                        </div>




                        <div class="box-footer" style="text-align: center;">

                            <label class="col-md-3"> </label>
                            <div class="col-md-9 filter-body">
                                <button type="button" class="btn btn-default btn-filter filter-submit" id="filter-submit-for-order-">
                                    <i class="fa fa-search"></i> 搜 索
                                </button>
                                <button type="button" class="btn bg-default btn-filter filter-empty" id="filter-empty-for-order-">
                                    <i class="fa fa-remove"></i> 重 置
                                </button>
                            </div>

                        </div>
                    </div>
                </div>

            </div>


        </div>


        <div class="pull-right _none-">

            
            <input type="text" class="search-filter form-filter filter-sm filter-keyup" name="order-id" placeholder="ID" value="" />

            <select class="search-filter form-filter filter-md select2-box-c" name="order-month">
                <option value="-1">全部时间</option>
                <?php $__currentLoopData = $months; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo nl2br(e($value)); ?>" <?php echo nl2br(e($value == $currentMonth ? 'selected' : '')); ?>>
                        <?php echo nl2br(e($label)); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>

            
            <input type="text" class="search-filter form-filter filter-md filter-keyup date-picker-c" name="order-assign-date" placeholder="派车日期" value="" readonly="readonly" />

            
            <input type="text" class="search-filter form-filter filter-md filter-keyup date-picker-c" name="order-task-date" placeholder="任务日期" value="" readonly="readonly" />


            






            






            











            
            <select class="search-filter form-filter filter-md select2-box-c" name="order-car-owner-type">
                <option value="-1">选择类型</option>
                <option value="1">自有</option>
                <option value="9">共建</option>
                <option value="11">外请</option>
            </select>

            
            <select class="search-filter form-filter filter-md select2-box-c select2--project-c-" name="order-project">
                <option value="-1">选择项目</option>
                <?php if(!empty($project_list) && count($project_list) > 0): ?>
                    <?php $__currentLoopData = $project_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo nl2br(e($v->id)); ?>"><?php echo nl2br(e($v->name)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </select>


            
            <select class="search-filter form-filter filter-md select2-box-c select2--car-c-" name="order-car" data-car-type="1">
                <option value="-1">选择车辆</option>
                <?php if(!empty($car_list) && count($car_list) > 0): ?>
                    <?php $__currentLoopData = $car_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo nl2br(e($v->id)); ?>"><?php echo nl2br(e($v->name)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </select>

            
            <select class="search-filter form-filter filter-md select2-box-c select2--car-c-" name="order-trailer" data-car-type="21">
                <option value="-1">选择车挂</option>
                <?php if(!empty($trailer_list) && count($trailer_list) > 0): ?>
                    <?php $__currentLoopData = $trailer_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo nl2br(e($v->id)); ?>"><?php echo nl2br(e($v->name)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </select>

            
            <input type="text" class="search-filter form-filter filter-md filter-keyup" name="order-external-car" placeholder="外请车" value="" />

            
            <select class="search-filter form-filter filter-md select2-box-c" name="order-car-type">
                <option value="">选择车型</option>
                <?php if(!empty(config('wl.common-config.car_type'))): ?>
                    <?php $__currentLoopData = config('wl.common-config.car_type'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo nl2br(e($v)); ?>"><?php echo nl2br(e($v)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </select>




            <button type="button" class="btn btn-default btn-filter filter-submit pull-right-">
                <i class="fa fa-search"></i> 搜索
            </button>

            <button type="button" class="btn btn-default btn-filter filter-empty pull-right-">
                <i class="fa fa-remove"></i> 清空
            </button>

            <button type="button" class="btn btn-default btn-filter filter-refresh pull-right-">
                <i class="fa fa-circle-o-notch"></i> 刷新
            </button>

            <button type="button" class="btn btn-default btn-filter filter-cancel pull-right-">
                <i class="fa fa-undo"></i> 重置
            </button>

        </div>


    </div>


    <div class="col-md-12 datatable-body">
        <div class="box box-success box-solid-" style="box-shadow:0 0;">

            <div class="box-header with-border- _none" style="margin-top:16px;padding-top:8px;">
                <h3 class="box-title comprehensive-month-title">订单统计</h3>
            </div>
            <div class="box-body no-padding">
                <div class="tableArea full table-order" style="margin-top:8px;">
                    <table class='table table-striped table-bordered table-hover order-column' id="datatable-order-list">
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


    <div class="col-md-12 datatable-search-row _none">

        <div class=" pull-left">

            
            
            


            <button class="btn btn-default btn-filter">
                <input type="checkbox" class="check-review-all">
            </button>


            <button type="button" onclick="" class="btn btn-default btn-filter bulk-submit-for-order-export" id="" data-item-category="1">
                <i class="fa fa-download"></i> 批量导出
            </button>
            


            <?php if(in_array($me->user_type,[0,1,9,11,61,66,71,77])): ?>

                
                <select class="search-filter form-filter filter-lg select2-box-c- select2-project-c" data-item-category="1" name="bulk-operate-delivered-project">
                    <option value="-1">选择交付项目</option>
                    
                    
                    
                </select>


                
                <input type="text" class="search-filter filter-lg form-filter" name="bulk-operate-delivered-description" placeholder="交付说明">


                <button type="button" class="btn btn-default btn-filter" id="bulk-submit-for-delivered">
                    <i class="fa fa-share"></i> 批量交付
                </button>

            <?php endif; ?>

        </div>

    </div>

</div>