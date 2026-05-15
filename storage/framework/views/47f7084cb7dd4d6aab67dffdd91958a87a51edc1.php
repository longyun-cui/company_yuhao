
<div class="modal fade modal-main-body modal-wrapper" id="modal--for--driver-item-edit">
    <div class="modal-content col-md-8 col-md-offset-2 margin-top-24px margin-bottom-64px bg-white">
        <div class="box- box-info- form-container">

            <div class="box-header with-border margin-top-16px">
                <h3 class="box-title">添加部门</h3>
                <div class="box-tools pull-right">
                </div>
            </div>

            <form action="" method="post" class="form-horizontal form-bordered" id="form--for--driver-item-edit">
                <div class="box-body">

                    <?php echo nl2br(e(csrf_field())); ?>

                    <input readonly type="hidden" class="form-control" name="operate[type]" value="create" data-default="create">
                    <input readonly type="hidden" class="form-control" name="operate[id]" value="0" data-default="0">
                    <input readonly type="hidden" class="form-control" name="operate[item_category]" value="item" data-default="item">
                    <input readonly type="hidden" class="form-control" name="operate[item_type]" value="driver" data-default="driver">


                    
                    <div class="form-group form-category">
                        <label class="control-label col-md-2"><sup class="text-red">*</sup> 类型</label>
                        <div class="col-md-9">
                            <div class="btn-group">

                                <button type="button" class="btn radio-btn">
                                <span class="radio">
                                    <label>
                                        <input type="radio" name="driver_type" value="1" checked="checked"> 主驾
                                    </label>
                                </span>
                                </button>

                                <button type="button" class="btn radio-btn">
                                <span class="radio">
                                    <label>
                                        <input type="radio" name="driver_type" value="11"> 副驾
                                    </label>
                                </span>
                                </button>

                            </div>
                        </div>
                    </div>

                    
                    <div class="form-group">
                        <label class="control-label col-md-2"><sup class="text-red">*</sup> 驾驶员信息</label>
                        <div class="col-md-10 ">
                            <div class="col-sm-4 col-md-3 padding-0">
                                <input type="text" class="form-control" name="driver_name" placeholder="姓名" value="">
                            </div>
                            <div class="col-sm-4 col-md-3 padding-0">
                                <input type="text" class="form-control" name="driver_phone" placeholder="手机号" value="">
                            </div>
                            <div class="col-sm-4 col-md-6 padding-0">
                                <input type="text" class="form-control" name="driver_ID" placeholder="身份证号" value="">
                            </div>
                        </div>
                    </div>
                    






                    
                    <div class="form-group">
                        <label class="control-label col-md-2">入职 & 离职 & 职称</label>
                        <div class="col-md-10 ">
                            <div class="col-sm-3 col-md-3 padding-0">
                                <input type="text" class="form-control date-picker" name="driver_entry_date" placeholder="入职时间" value="" readonly="readonly">
                            </div>
                            <div class="col-sm-3 col-md-3 padding-0">
                                <input type="text" class="form-control date-picker" name="driver_leave_date" placeholder="离职时间" value="" readonly="readonly">
                            </div>
                            <div class="col-sm-6 col-md-6 padding-0">
                                <input type="text" class="form-control" name="driver_title" placeholder="职称" value="">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="control-label col-md-2">紧急联系人</label>
                        <div class="col-md-10 ">
                            <div class="col-sm-6 col-md-6 padding-0">
                                <input type="text" class="form-control" name="driver_emergency_contact_name" placeholder="紧急联系人" value="">
                            </div>
                            <div class="col-sm-6 col-md-6 padding-0">
                                <input type="text" class="form-control" name="driver_emergency_contact_phone" placeholder="紧急联系电话" value="">
                            </div>
                        </div>
                    </div>


                    
                    <div class="form-group"></div>


                    
                    <div class="form-group">
                        <label class="control-label col-md-2">副驾-基本信息</label>
                        <div class="col-md-10 ">
                            <div class="col-sm-3 col-md-3 padding-0">
                                <input type="text" class="form-control" name="copilot_name" placeholder="副驾姓名" value="">
                            </div>
                            <div class="col-sm-3 col-md-3 padding-0">
                                <input type="text" class="form-control" name="copilot_phone" placeholder="副驾电话" value="">
                            </div>
                            <div class="col-sm-6 col-md-6 padding-0">
                                <input type="text" class="form-control" name="copilot_ID" placeholder="副驾身份证号" value="">
                            </div>
                        </div>
                    </div>
                    






                    
                    <div class="form-group">
                        <label class="control-label col-md-2">副驾-职称&入职时间</label>
                        <div class="col-md-10 ">
                            <div class="col-sm-3 col-md-3 padding-0">
                                <input type="text" class="form-control date-picker" name="copilot_entry_date" placeholder="副驾入职时间" value="" readonly="readonly">
                            </div>
                            <div class="col-sm-3 col-md-3 padding-0">
                                <input type="text" class="form-control date-picker" name="copilot_leave_date" placeholder="副驾离职时间" value="" readonly="readonly">
                            </div>
                            <div class="col-sm-6 col-md-6 padding-0">
                                <input type="text" class="form-control" name="copilot_title" placeholder="副驾职称" value="">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="control-label col-md-2">副驾-紧急联系人&电话</label>
                        <div class="col-md-10 ">
                            <div class="col-sm-6 col-md-6 padding-0">
                                <input type="text" class="form-control" name="copilot_emergency_contact_name" placeholder="副驾紧急联系人" value="">
                            </div>
                            <div class="col-sm-6 col-md-6 padding-0">
                                <input type="text" class="form-control" name="copilot_emergency_contact_phone" placeholder="副驾紧急联系电话" value="">
                            </div>
                        </div>
                    </div>


                    
                    <div class="form-group _none">
                        <label class="control-label col-md-2">描述</label>
                        <div class="col-md-10 ">
                            
                            <textarea class="form-control" name="description" rows="3" cols="100%"></textarea>
                        </div>
                    </div>


                    
                    <div class="form-group _none">
                        <label class="control-label col-md-2">主驾-驾驶证</label>
                        <div class="col-md-10 fileinput-group">

                            <?php if(!empty($data->driver_licence)): ?>
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-new thumbnail">
                                        <a class="lightcase-image" data-rel="lightcase" href="<?php echo nl2br(e(url(env('DOMAIN_CDN').'/'.$data->driver_licence))); ?>">
                                            <img src="<?php echo nl2br(e(url(env('DOMAIN_CDN').'/'.$data->driver_licence))); ?>" alt="" />
                                        </a>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="fileinput-preview fileinput-exists thumbnail"></div>
                            <?php endif; ?>
                            <input type="file" class="file-multiple-images" name="driver_licence_file" multiple- >
                        </div>
                    </div>
                    
                    <div class="form-group _none">
                        <label class="control-label col-md-2">主驾-资格证</label>
                        <div class="col-md-10 fileinput-group">

                            <?php if(!empty($data->driver_certification)): ?>
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-new thumbnail">
                                        <a class="lightcase-image" data-rel="lightcase" href="<?php echo nl2br(e(url(env('DOMAIN_CDN').'/'.$data->driver_certification))); ?>">
                                            <img src="<?php echo nl2br(e(url(env('DOMAIN_CDN').'/'.$data->driver_certification))); ?>" alt="" />
                                        </a>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="fileinput-preview fileinput-exists thumbnail"></div>
                            <?php endif; ?>
                            <input type="file" class="file-multiple-images" name="driver_certification_file" multiple- >
                        </div>
                    </div>
                    
                    <div class="form-group _none">
                        <label class="control-label col-md-2">主驾-身份证-正页</label>
                        <div class="col-md-10 fileinput-group">

                            <?php if(!empty($data->driver_ID_front)): ?>
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-new thumbnail">
                                        <a class="lightcase-image" data-rel="lightcase" href="<?php echo nl2br(e(url(env('DOMAIN_CDN').'/'.$data->driver_ID_front))); ?>">
                                            <img src="<?php echo nl2br(e(url(env('DOMAIN_CDN').'/'.$data->driver_ID_front))); ?>" alt="" />
                                        </a>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="fileinput-preview fileinput-exists thumbnail"></div>
                            <?php endif; ?>
                            <input type="file" class="file-multiple-images" name="driver_ID_front_file" multiple- >
                        </div>
                    </div>
                    
                    <div class="form-group _none">
                        <label class="control-label col-md-2">主驾-身份证-副页</label>
                        <div class="col-md-10 fileinput-group">

                            <?php if(!empty($data->driver_ID_back)): ?>
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-new thumbnail">
                                        <a class="lightcase-image" data-rel="lightcase" href="<?php echo nl2br(e(url(env('DOMAIN_CDN').'/'.$data->driver_ID_back))); ?>">
                                            <img src="<?php echo nl2br(e(url(env('DOMAIN_CDN').'/'.$data->driver_ID_back))); ?>" alt="" />
                                        </a>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="fileinput-preview fileinput-exists thumbnail"></div>
                            <?php endif; ?>
                            <input type="file" class="file-multiple-images" name="driver_ID_back_file" multiple- >
                        </div>
                    </div>

                    
                    <div class="form-group _none">
                        <label class="control-label col-md-2">副驾-驾驶证</label>
                        <div class="col-md-10 fileinput-group">

                            <?php if(!empty($data->sub_driver_licence)): ?>
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-new thumbnail">
                                        <a class="lightcase-image" data-rel="lightcase" href="<?php echo nl2br(e(url(env('DOMAIN_CDN').'/'.$data->sub_driver_licence))); ?>">
                                            <img src="<?php echo nl2br(e(url(env('DOMAIN_CDN').'/'.$data->sub_driver_licence))); ?>" alt="" />
                                        </a>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="fileinput-preview fileinput-exists thumbnail"></div>
                            <?php endif; ?>
                            <input type="file" class="file-multiple-images" name="sub_driver_licence_file" multiple- >
                        </div>
                    </div>
                    
                    <div class="form-group _none">
                        <label class="control-label col-md-2">副驾-资格证</label>
                        <div class="col-md-10 fileinput-group">

                            <?php if(!empty($data->sub_driver_certification)): ?>
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-new thumbnail">
                                        <a class="lightcase-image" data-rel="lightcase" href="<?php echo nl2br(e(url(env('DOMAIN_CDN').'/'.$data->sub_driver_certification))); ?>">
                                            <img src="<?php echo nl2br(e(url(env('DOMAIN_CDN').'/'.$data->sub_driver_certification))); ?>" alt="" />
                                        </a>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="fileinput-preview fileinput-exists thumbnail"></div>
                            <?php endif; ?>
                            <input type="file" class="file-multiple-images" name="sub_driver_certification_file" multiple- >
                        </div>
                    </div>
                    
                    <div class="form-group _none">
                        <label class="control-label col-md-2">副驾-身份证-正页</label>
                        <div class="col-md-10 fileinput-group">

                            <?php if(!empty($data->sub_driver_ID_front)): ?>
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-new thumbnail">
                                        <a class="lightcase-image" data-rel="lightcase" href="<?php echo nl2br(e(url(env('DOMAIN_CDN').'/'.$data->sub_driver_ID_front))); ?>">
                                            <img src="<?php echo nl2br(e(url(env('DOMAIN_CDN').'/'.$data->sub_driver_ID_front))); ?>" alt="" />
                                        </a>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="fileinput-preview fileinput-exists thumbnail"></div>
                            <?php endif; ?>
                            <input type="file" class="file-multiple-images" name="sub_driver_ID_front_file" multiple- >
                        </div>
                    </div>
                    
                    <div class="form-group _none">
                        <label class="control-label col-md-2">副驾-身份证-副页</label>
                        <div class="col-md-10 fileinput-group">

                            <?php if(!empty($data->sub_driver_ID_back)): ?>
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-new thumbnail">
                                        <a class="lightcase-image" data-rel="lightcase" href="<?php echo nl2br(e(url(env('DOMAIN_CDN').'/'.$data->sub_driver_ID_back))); ?>">
                                            <img src="<?php echo nl2br(e(url(env('DOMAIN_CDN').'/'.$data->sub_driver_ID_back))); ?>" alt="" />
                                        </a>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="fileinput-preview fileinput-exists thumbnail"></div>
                            <?php endif; ?>
                            <input type="file" class="file-multiple-images" name="sub_driver_ID_back_file" multiple- >
                        </div>
                    </div>


                    
                    <div class="form-group _none">
                        <label class="control-label col-md-2">图片资料</label>
                        <div class="col-md-10 fileinput-group">
                            <?php if(!empty($data->attachment_list) && count($data->attachment_list) > 0): ?>
                                <?php $__currentLoopData = $data->attachment_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail">
                                            <a class="lightcase-image" data-rel="lightcase" href="<?php echo nl2br(e(url(env('DOMAIN_CDN').'/'.$img->attachment_src))); ?>">
                                                <img src="<?php echo nl2br(e(url(env('DOMAIN_CDN').'/'.$img->attachment_src))); ?>" alt="" />
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <div class="fileinput-preview fileinput-exists thumbnail"></div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-9 col-md-offset-2 ">
                            <input id="multiple-images" type="file" class="file-multiple-images" name="multiple_images[]" multiple >
                        </div>
                    </div>


                </div>
            </form>


            <div class="box-footer">
                <div class="row">
                    <div class="col-md-9 col-md-offset-2">
                        <button type="button" class="btn btn-success edit-submit" id="submit--for--driver-item-edit">
                            <i class="fa fa-check"></i> 提交
                        </button>
                        <button type="button" class="btn btn-default edit-cancel">取消</button>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>