
<div class="modal fade modal-main-body modal-wrapper" id="modal--for--client-item-edit">
    <div class="modal-content col-md-8 col-md-offset-2 margin-top-64px margin-bottom-64px bg-white">
        <div class="box- box-info- form-container">

            <div class="box-header with-border margin-top-16px">
                <h3 class="box-title">添加渠道</h3>
                <div class="box-tools pull-right">
                </div>
            </div>


            <form action="" method="post" class="form-horizontal form-bordered" id="form--for--client-item-edit">
            <div class="box-body">

                <?php echo nl2br(e(csrf_field())); ?>

                <input readonly type="hidden" class="form-control" name="operate[type]" value="create" data-default="create">
                <input readonly type="hidden" class="form-control" name="operate[id]" value="0" data-default="0">
                <input readonly type="hidden" class="form-control" name="operate[item_category]" value="item" data-default="item">
                <input readonly type="hidden" class="form-control" name="operate[item_type]" value="client" data-default="client">




                
                <div class="form-group form-category">
                    <label class="control-label col-md-2"><sup class="text-red">*</sup> 客户类型</label>
                    <div class="col-md-8">
                        <div class="btn-group">

                            <button type="button" class="btn radio-btn radio-client-category">
                            <span class="radio">
                                <label>
                                    <input type="radio" name="client_category" value="1" checked="checked"> 长期
                                </label>
                            </span>
                            </button>

                            <button type="button" class="btn radio-btn radio-client-category">
                            <span class="radio">
                                <label>
                                    <input type="radio" name="client_category" value="11"> 短期
                                </label>
                            </span>
                            </button>

                            <button type="button" class="btn radio-btn radio-client-category">
                            <span class="radio">
                                <label>
                                    <input type="radio" name="client_category" value="91"> 临时
                                </label>
                            </span>
                            </button>

                        </div>
                    </div>
                </div>



                
                <div class="form-group">
                    <label class="control-label col-md-2"><sup class="text-red">*</sup> 客户名称</label>
                    <div class="col-md-8 ">
                        <input type="text" class="form-control" name="name" placeholder="客户名称" value="">
                    </div>
                </div>


                








                









                






                








                
                <div class="form-group">
                    <label class="control-label col-md-2">描述</label>
                    <div class="col-md-8 ">
                        
                        <textarea class="form-control" name="description" rows="3" cols="100%"></textarea>
                    </div>
                </div>


                
                <div class="form-group _none">
                    <label class="control-label col-md-2">头像</label>
                    <div class="col-md-8 fileinput-group">

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
                    <div class="col-md-8">
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
                    <div class="col-md-8 col-md-offset-2">
                        <button type="button" class="btn btn-success edit-submit" id="submit--for--client-item-edit">
                            <i class="fa fa-check"></i> 提交
                        </button>
                        <button type="button" class="btn btn-default edit-cancel">取消</button>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>