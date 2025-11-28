{{--编辑-部门--}}
<div class="modal fade modal-main-body modal-wrapper" id="modal-for-staff-edit">
    <div class="modal-content col-md-8 col-md-offset-2 margin-top-24px margin-bottom-32px bg-white">
        <div class="box- box-info- form-container modal-body">

            <div class="box-header with-border margin-top-16px">
                <h3 class="box-title">添加员工</h3>
                <div class="box-tools pull-right">
                </div>
            </div>


            <form action="" method="post" class="form-horizontal form-bordered" id="form-for-staff-edit">
            <div class="box-body">

                {{ csrf_field() }}
                <input readonly type="hidden" class="form-control" name="operate[type]" value="create" data-default="create">
                <input readonly type="hidden" class="form-control" name="operate[id]" value="0" data-default="0">
                <input readonly type="hidden" class="form-control" name="operate[item_category]" value="item" data-default="item">
                <input readonly type="hidden" class="form-control" name="operate[item_type]" value="staff" data-default="staff">


                {{--类别--}}
                <div class="form-group form-category">
                    <label class="control-label col-md-2"><sup class="text-red">*</sup> 类型</label>
                    <div class="col-md-8">
                        <div class="btn-group">

                            @if(in_array($me->user_type, [0,1]))
                            <button type="button" class="btn radio-btn radio-staff-category">
                                <span class="radio">
                                    <label>
                                        <input type="radio" name="staff_category" value="11" checked="checked"> 人事
                                    </label>
                                </span>
                            </button>
                            @endif

                            @if(in_array($me->user_type, [0,1,11]))
                            <button type="button" class="btn radio-btn radio-staff-category">
                                <span class="radio">
                                    <label>
                                        <input type="radio" name="staff_category" value="21"> 行政
                                    </label>
                                </span>
                                </button>
                            @endif

                            @if(in_array($me->user_type, [0,1,11,41]))
                            <button type="button" class="btn radio-btn radio-staff-category">
                                <span class="radio">
                                    <label>
                                        <input type="radio" name="staff_category" value="31"> 财务
                                    </label>
                                </span>
                            </button>
                            @endif

                            @if(in_array($me->user_type, [0,1,11,41]))
                            <button type="button" class="btn radio-btn radio-staff-category">
                                <span class="radio">
                                    <label>
                                        <input type="radio" name="staff_category" value="81"> 业务
                                    </label>
                                </span>
                            </button>
                            @endif

{{--                            @if(in_array($me->user_type, [0,1,11]))--}}
{{--                            <button type="button" class="btn radio-btn radio-staff-category">--}}
{{--                                <span class="radio">--}}
{{--                                    <label>--}}
{{--                                        <input type="radio" name="staff_category" value="91"> 客服--}}
{{--                                    </label>--}}
{{--                                </span>--}}
{{--                            </button>--}}
{{--                            @endif--}}

                        </div>
                    </div>
                </div>

                {{--职级--}}
                <div class="form-group form-category">
                    <label class="control-label col-md-2"><sup class="text-red">*</sup> 职级</label>
                    <div class="col-md-8">
                        <div class="btn-group">

                            <button type="button" class="btn radio-btn radio-staff-position">
                                <span class="radio">
                                    <label>
                                        <input type="radio" name="staff_position" value="11"> 总经理
                                    </label>
                                </span>
                            </button>
                            <button type="button" class="btn radio-btn radio-staff-position">
                                <span class="radio">
                                    <label>
                                        <input type="radio" name="staff_position" value="21"> 部门经理
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
                                        <input type="radio" name="staff_position" value="51"> 分部主管
                                    </label>
                                </span>
                            </button>
                            <button type="button" class="btn radio-btn radio-staff-position">
                                <span class="radio">
                                    <label>
                                        <input type="radio" name="staff_position" value="61"> 小组组长
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


                {{--上级--}}
{{--                <div class="form-group superior-box" style="display:none;">--}}
{{--                    <label class="control-label col-md-2"><sup class="text-red">*</sup> 上级</label>--}}
{{--                    <div class="col-md-8 ">--}}
{{--                        <select class="form-control" name="superior_id" id="staff-edit-select2-superior" data-type="">--}}
{{--                            <option data-id="" value="">未指定</option>--}}
{{--                        </select>--}}
{{--                    </div>--}}
{{--                </div>--}}

                {{--部门--}}
                @if(in_array($me->user_type, [0,1,11]))
                <div class="form-group department-box">
                    <label class="control-label col-md-2"><sup class="text-red">*</sup> 部门</label>
                    <div class="col-md-8 ">
                        <select class="form-control" name="department_id" id="staff-edit-select2-department" data-type="">
                            <option data-id="" value="">选择部门</option>
                        </select>
                    </div>
                </div>
                @endif

                {{--团队-小组--}}
                <div class="form-group team-box">
                    <label class="control-label col-md-2"><sup class="text-red">*</sup> 团队</label>
                    <div class="col-md-8 ">
                        <select class="form-control" name="team_id" id="staff-edit-select2-team" data-type="">
                            <option data-id="" value="">选择团队</option>
                        </select>
                    </div>
                </div>


                {{--手机--}}
                <div class="form-group">
                    <label class="control-label col-md-2"><sup class="text-red">*</sup> 登录工号</label>
                    <div class="col-md-8 ">
                        <input type="text" class="form-control" name="login_number" placeholder="登录工号" value="">
                    </div>
                </div>
                {{--真实姓名--}}
                <div class="form-group">
                    <label class="control-label col-md-2"><sup class="text-red">*</sup> 真实姓名</label>
                    <div class="col-md-8 ">
                        <input type="text" class="form-control" name="true_name" placeholder="真实姓名" value="">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-2">用户名</label>
                    <div class="col-md-8 ">
                        <input type="text" class="form-control" name="username" placeholder="用户名" value="">
                    </div>
                </div>
                {{--描述--}}
                <div class="form-group _none">
                    <label class="control-label col-md-2">描述</label>
                    <div class="col-md-8 ">
                        {{--<input type="text" class="form-control" name="description" placeholder="描述" value="{{$data->description or ''}}">--}}
                        <textarea class="form-control" name="description" rows="3" cols="100%"></textarea>
                    </div>
                </div>


                {{--头像--}}
                <div class="form-group _none">
                    <label class="control-label col-md-2">头像</label>
                    <div class="col-md-8 fileinput-group">

                        <div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="fileinput-new thumbnail">
                                @if(!empty($data->portrait_img))
                                    <img src="{{ url(env('DOMAIN_CDN').'/'.$data->portrait_img) }}" alt="" />
                                @endif
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

                {{--启用--}}
                <div class="form-group form-type _none">
                    <label class="control-label col-md-2">启用</label>
                    <div class="col-md-8">
                        <div class="btn-group">

                            <button type="button" class="btn">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="active" value="0" checked="checked"> 暂不启用
                                    </label>
                                </div>
                            </button>
                            <button type="button" class="btn">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="active" value="1"> 启用
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
                        <button type="button" class="btn btn-success edit-submit" id="edit-submit-for-staff">
                            <i class="fa fa-check"></i> 提交
                        </button>
                        <button type="button" class="btn btn-default edit-cancel">取消</button>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>