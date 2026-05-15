
<div class="modal fade modal-main-body modal-wrapper" id="modal--for--staff-item-edit">
    <div class="modal-content col-md-8 col-md-offset-2 margin-top-24px margin-bottom-32px bg-white">
        <div class="box- box-info- form-container">

            <div class="box-header with-border margin-top-16px">
                <h3 class="box-title">添加员工</h3>
                <div class="box-tools pull-right">
                </div>
            </div>


            <form action="" method="post" class="form-horizontal form-bordered" id="form--for--staff-item-edit">
            <div class="box-body">

                <?php echo nl2br(e(csrf_field())); ?>

                <input readonly type="hidden" class="form-control" name="operate[type]" value="create" data-default="create">
                <input readonly type="hidden" class="form-control" name="operate[id]" value="0" data-default="0">
                <input readonly type="hidden" class="form-control" name="operate[item_category]" value="item" data-default="item">
                <input readonly type="hidden" class="form-control" name="operate[item_type]" value="staff" data-default="staff">


                
                <div class="form-group form-category">
                    <label class="control-label col-md-2"><sup class="text-red">*</sup> 类型</label>
                    <div class="col-md-8">
                        <div class="btn-group" id="department-category--for--staff-item-edit">

                            <?php if(in_array($me->user_type, [0,1])): ?>
                            <button type="button" class="btn radio-btn radio-staff-category">
                                <span class="radio">
                                    <label>
                                        <input type="radio" name="staff_category" value="1"> 总裁
                                    </label>
                                </span>
                            </button>
                            <?php endif; ?>

                            <?php if(in_array($me->user_type, [0,1])): ?>
                            <button type="button" class="btn radio-btn radio-staff-category">
                                <span class="radio">
                                    <label>
                                        <input type="radio" name="staff_category" value="11"> 人事
                                    </label>
                                </span>
                            </button>
                            <?php endif; ?>

                            <?php if(in_array($me->user_type, [0,1,11])): ?>
                            <button type="button" class="btn radio-btn radio-staff-category">
                                <span class="radio">
                                    <label>
                                        <input type="radio" name="staff_category" value="21"> 行政
                                    </label>
                                </span>
                                </button>
                            <?php endif; ?>

                            <?php if(in_array($me->user_type, [0,1,11])): ?>
                            <button type="button" class="btn radio-btn radio-staff-category">
                                <span class="radio">
                                    <label>
                                        <input type="radio" name="staff_category" value="31"> 财务
                                    </label>
                                </span>
                            </button>
                            <?php endif; ?>

                            <?php if(in_array($me->user_type, [0,1,11,41])): ?>
                            <button type="button" class="btn radio-btn radio-staff-category">
                                <span class="radio">
                                    <label>
                                        <input type="radio" name="staff_category" value="81" checked="checked"> 业务
                                    </label>
                                </span>
                            </button>
                            <?php endif; ?>











                        </div>
                    </div>
                </div>

                
                <div class="form-group form-category">
                    <label class="control-label col-md-2"><sup class="text-red">*</sup> 职位</label>
                    <div class="col-md-8">
                        <div class="btn-group">

                            <button type="button" class="btn radio-btn radio-staff-position">
                                <span class="radio">
                                    <label>
                                        <input type="radio" name="staff_position" value="11"> 公司老总
                                    </label>
                                </span>
                            </button>
                            <button type="button" class="btn radio-btn radio-staff-position">
                                <span class="radio">
                                    <label>
                                        <input type="radio" name="staff_position" value="31"> 部门总监
                                    </label>
                                </span>
                            </button>
                            <button type="button" class="btn radio-btn radio-staff-position">
                                <span class="radio">
                                    <label>
                                        <input type="radio" name="staff_position" value="41"> 团队经理
                                    </label>
                                </span>
                            </button>














                            <button type="button" class="btn radio-btn radio-staff-position">
                                <span class="radio">
                                    <label>
                                        <input type="radio" name="staff_position" value="99" checked="checked"> 业务员
                                    </label>
                                </span>
                            </button>

                        </div>
                    </div>
                </div>


                









                
                <?php if(in_array($me->user_type, [0,1,11])): ?>
                <div class="form-group company-box">
                    <label class="control-label col-md-2"><sup class="text-red">*</sup> 所属公司</label>
                    <div class="col-md-9 ">
                        <div class="col-sm-4 col-md-4 padding-0">
                            <select class="form-control select2-reset select2--company"
                                    name="company_id"
                                    id="select2--company--for--staff-item-edit"
                                    data-modal="#modal--for--staff-item-edit"
                                    data-item-category=""
                                    data-item-type=""
                                    data-department-category="#department-category--for--staff-item-edit"
                                    data-department-target="#select2--department--for--staff-item-edit"
                                    data-team-target="#select2--team--for--staff-item-edit"
                            >
                                <option data-id="" value="">选择公司</option>
                            </select>
                        </div>
                        <div class="col-sm-4 col-md-4 padding-0 department-box">
                            <select class="form-control select2-reset select2--department"
                                    name="department_id"
                                    id="select2--department--for--staff-item-edit"
                                    data-modal="#modal--for--staff-item-edit"
                                    data-item-category=""
                                    data-item-type=""
                                    data-department-category="#department-category--for--staff-item-edit"
                                    data-team-target="#select2--team--for--staff-item-edit"
                            >
                                <option data-id="" value="">选择部门</option>
                            </select>
                        </div>
                        <div class="col-sm-4 col-md-4 padding-0 team-box">
                            <select class="form-control select2-reset select2--team"
                                    name="team_id"
                                    id="select2--team--for--staff-item-edit"
                                    data-modal="#modal--for--staff-item-edit"
                                    data-item-category=""
                                    data-item-type=""
                            >
                                <option data-id="" value="">选择团队</option>
                            </select>
                        </div>
                    </div>
                </div>
                <?php endif; ?>






































                
                <div class="form-group">
                    <label class="control-label col-md-2"><sup class="text-red">*</sup> 登录工号</label>
                    <div class="col-md-9 ">
                        <input type="text" class="form-control" name="login_number" placeholder="登录工号" value="">
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-md-2"><sup class="text-red">*</sup> 姓名</label>
                    <div class="col-md-9 ">
                        <input type="text" class="form-control" name="name" placeholder="姓名" value="">
                    </div>
                </div>
                
                <div class="form-group _none">
                    <label class="control-label col-md-2">描述</label>
                    <div class="col-md-9 ">
                        
                        <textarea class="form-control" name="description" rows="3" cols="100%"></textarea>
                    </div>
                </div>


                
                <div class="form-group _none">
                    <label class="control-label col-md-2">头像</label>
                    <div class="col-md-9 fileinput-group">

                        <div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="fileinput-new thumbnail">
                                <?php if(!empty($data->portrait_img)): ?>
                                    <img src="<?php echo nl2br(e(url(env('DOMAIN_CDN').'/'.$data->portrait_img))); ?>" alt="" />
                                <?php endif; ?>
                            </div>
                            <div class="fileinput-preview fileinput-exists thumbnail">
                            </div>
                            <div class="btn-tool-group">
                            <span class="btn-file">
                                <button class="btn btn-sm btn-primary fileinput-new">选择图片</button>
                                <button class="btn btn-sm btn-warning fileinput-exists">更改</button>
                                <input type="file" name="portrait" />
                            </span>
                                <span class="">
                                <button class="btn btn-sm btn-danger fileinput-exists" data-dismiss="fileinput">移除</button>
                            </span>
                            </div>
                        </div>
                        <div id="titleImageError" style="color: #a94442"></div>

                    </div>
                </div>

                
                <div class="form-group form-type _none">
                    <label class="control-label col-md-2">启用</label>
                    <div class="col-md-9 ">
                        <div class="btn-group">

                            <button type="button" class="btn">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="active" value="0"> 暂不启用
                                    </label>
                                </div>
                            </button>
                            <button type="button" class="btn">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="active" value="1" checked="checked"> 启用
                                    </label>
                                </div>
                            </button>

                        </div>
                    </div>
                </div>

            </div>
            </form>


            <div class="box-footer">
                <div class="row">
                    <div class="col-md-9 col-md-offset-2">
                        <button type="button" class="btn btn-success edit-submit" id="submit--for--staff-item-edit">
                            <i class="fa fa-check"></i> 提交
                        </button>
                        <button type="button" class="btn btn-default edit-cancel">取消</button>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>