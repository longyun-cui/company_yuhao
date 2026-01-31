{{--编辑-部门--}}
<div class="modal fade modal-main-body modal-wrapper" id="modal--for--car-item-edit">
    <div class="modal-content col-md-8 col-md-offset-2 margin-top-24px margin-bottom-64px bg-white">
        <div class="box- box-info- form-container">

            <div class="box-header with-border margin-top-16px">
                <h3 class="box-title">添加部门</h3>
                <div class="box-tools pull-right">
                </div>
            </div>


            <form action="" method="post" class="form-horizontal form-bordered" id="form--for--car-item-edit">
                <div class="box-body">

                    {{ csrf_field() }}
                    <input readonly type="hidden" class="form-control" name="operate[type]" value="create" data-default="create">
                    <input readonly type="hidden" class="form-control" name="operate[id]" value="0" data-default="0">
                    <input readonly type="hidden" class="form-control" name="operate[item_category]" value="item" data-default="item">
                    <input readonly type="hidden" class="form-control" name="operate[item_type]" value="car" data-default="car">


                    {{--车辆类型--}}
                    <div class="form-group form-category">
                        <label class="control-label col-md-2"><sup class="text-red">*</sup> 车辆类型</label>
                        <div class="col-md-9">
                            <div class="btn-group">

                                @if(in_array($me->user_type, [0,1,9,11]))
                                <button type="button" class="btn">
                                    <span class="radio">
                                        <label>
                                            <input type="radio" name="car_type" value="1" checked="checked"> 车辆
                                        </label>
                                    </span>
                                </button>
                                @endif

                                @if(in_array($me->user_type, [0,1,9,11]))
                                <button type="button" class="btn">
                                    <span class="radio">
                                        <label>
                                            <input type="radio" name="car_type" value="21"> 车挂
                                        </label>
                                    </span>
                                </button>
                                @endif

                            </div>
                        </div>
                    </div>


                    {{--车队--}}
                    <div class="form-group" style="height:70px;">
                        <label class="control-label col-md-2"><sup class="text-red">*</sup> 所属车队</label>
                        <div class="col-md-9 ">
                            <select class="form-control select2-reset select2--motorcade"
                                    name="motorcade_id"
                                    id="select2--motorcade--for--car-item-edit"
                                    data-modal="#modal--for--car-item-edit"
                                    data-item-category=""
                                    data-item-type=""
                            >
                                <option data-id="0" value="0">选择车队</option>
                            </select>
                        </div>
                    </div>

                    {{--车牌号--}}
                    <div class="form-group">
                        <label class="control-label col-md-2"><sup class="text-red">*</sup> 车牌号</label>
                        <div class="col-md-9 ">
                            <input type="text" class="form-control" name="name" placeholder="车牌号" value="">
                        </div>
                    </div>

                    {{--箱型--}}
                    <div class="form-group">
                        <label class="control-label col-md-2">箱型</label>
                        <div class="col-md-9 ">
                            <select class="form-control" name="trailer_type" id="">
                                <option value="">选择箱型</option>
                                <option value="直板">直板</option>
                                <option value="高栏">高栏</option>
                                <option value="平板">平板</option>
                                <option value="冷藏">冷藏</option>
                            </select>
                        </div>
                    </div>

                    {{--车挂尺寸--}}
                    <div class="form-group">
                        <label class="control-label col-md-2">车挂尺寸</label>
                        <div class="col-md-9 ">
                            <select class="form-control" name="trailer_length" id="">
                                <option value="">选择车挂尺寸</option>
                                <option value="9.6">9.6</option>
                                <option value="12.5">12.5</option>
                                <option value="15">15</option>
                                <option value="16.5">16.5</option>
                                <option value="17.5">17.5</option>
                            </select>
                        </div>
                    </div>
                    {{--承载方数--}}
                    <div class="form-group">
                        <label class="control-label col-md-2">承载方数</label>
                        <div class="col-md-9 ">
                            <select class="form-control" name="trailer_volume" id="">
                                <option value="">选择承载方数</option>
                                <option value="125">125</option>
                                <option value="130">130</option>
                                <option value="135">135</option>
                            </select>
                        </div>
                    </div>
                    {{--承载重量--}}
                    <div class="form-group">
                        <label class="control-label col-md-2">承载重量</label>
                        <div class="col-md-9 ">
                            <select class="form-control" name="trailer_weight" id="">
                                <option value="">选择承载重量</option>
                                <option value="13">13吨</option>
                                <option value="20">20吨</option>
                                <option value="25">25吨</option>
                            </select>
                        </div>
                    </div>
                    {{--轴数--}}
                    <div class="form-group">
                        <label class="control-label col-md-2">轴数</label>
                        <div class="col-md-9 ">
                            <select class="form-control" name="trailer_axis_count" id="">
                                <option value="">选择轴数</option>
                                <option value="1">1轴</option>
                                <option value="2">2轴</option>
                                <option value="3">3轴</option>
                            </select>
                        </div>
                    </div>


                    {{--车挂--}}
                    <div class="form-group" style="height:70px;">
                        <label class="control-label col-md-2"><sup class="text-red">*</sup> 默认车挂</label>
                        <div class="col-md-9 ">
                            <select class="form-control select2-reset select2--car"
                                    name="trailer_id"
                                    id="select2--trailer--for--car-item-edit"
                                    data-modal="#modal--for--car-item-edit"
                                    data-item-category=""
                                    data-item-type=""
                                    data-car-type="21"
                            >
                                <option data-id="0" value="0">选择车挂</option>
                            </select>
                        </div>
                    </div>
                    {{--驾驶员--}}
                    <div class="form-group" style="height:70px;">
                        <label class="control-label col-md-2"><sup class="text-red">*</sup> 默认驾驶员</label>
                        <div class="col-md-9 ">
                            <div class="col-sm-6 col-md-6 padding-0">
                                <select class="form-control select2-reset select2--driver"
                                        name="driver_id"
                                        id="select2--driver--for--car-item-edit"
                                        data-modal="#modal--for--car-item-edit"
                                        data-item-category=""
                                        data-item-type=""
                                >
                                    <option data-id="0" value="0">选择主驾</option>
                                </select>
                            </div>
                            <div class="col-sm-6 col-md-6 padding-0">
                                <select class="form-control select2-reset select2--driver"
                                        name="copilot_id"
                                        id="select2--copilot--for--car-item-edit"
                                        data-modal="#modal--for--car-item-edit"
                                        data-item-category=""
                                        data-item-type=""
                                >
                                    <option data-id="0" value="0">选择副驾</option>
                                </select>
                            </div>
                        </div>
                    </div>



                    {{--ETC号--}}
                    <div class="form-group">
                        <label class="control-label col-md-2">ETC号</label>
                        <div class="col-md-9 ">
                            <input type="text" class="form-control" name="ETC_account" placeholder="ETC号" value="">
                        </div>
                    </div>

                    {{--司机--}}
                    <div class="form-group">
                        <label class="control-label col-md-2">联系人</label>
                        <div class="col-md-9 ">
                            <input type="text" class="form-control" name="linkman_name" placeholder="联系人" value="">
                        </div>
                    </div>
                    {{--手机--}}
                    <div class="form-group">
                        <label class="control-label col-md-2">联系电话</label>
                        <div class="col-md-9 ">
                            <input type="text" class="form-control" name="linkman_phone" placeholder="联系电话" value="">
                        </div>
                    </div>
                    {{--住址--}}
                    <div class="form-group">
                        <label class="control-label col-md-2">联系地址</label>
                        <div class="col-md-9 ">
                            <input type="text" class="form-control" name="linkman_address" placeholder="联系地址" value="">
                        </div>
                    </div>
                    {{--车辆类型--}}
                    <div class="form-group">
                        <label class="control-label col-md-2">车辆类型</label>
                        <div class="col-md-9 ">
                            <input type="text" class="form-control" name="car_info_type" placeholder="车辆类型" value="">
                        </div>
                    </div>
                    {{--所有人--}}
                    <div class="form-group">
                        <label class="control-label col-md-2">所有人</label>
                        <div class="col-md-9 ">
                            <input type="text" class="form-control" name="car_info_owner" placeholder="所有人" value="">
                        </div>
                    </div>
                    {{--使用性质--}}
                    <div class="form-group">
                        <label class="control-label col-md-2">使用性质</label>
                        <div class="col-md-9 ">
                            <input type="text" class="form-control" name="car_info_function" placeholder="使用性质" value="">
                        </div>
                    </div>
                    {{--品牌--}}
                    <div class="form-group">
                        <label class="control-label col-md-2">品牌</label>
                        <div class="col-md-9 ">
                            <input type="text" class="form-control" name="car_info_brand" placeholder="品牌" value="">
                        </div>
                    </div>
                    {{--车辆识别代码--}}
                    <div class="form-group">
                        <label class="control-label col-md-2">车辆识别代码</label>
                        <div class="col-md-9 ">
                            <input type="text" class="form-control" name="car_info_identification_number" placeholder="车辆识别代码" value="">
                        </div>
                    </div>
                    {{--发动机号--}}
                    <div class="form-group">
                        <label class="control-label col-md-2">发动机号</label>
                        <div class="col-md-9 ">
                            <input type="text" class="form-control" name="car_info_engine_number" placeholder="发动机号" value="">
                        </div>
                    </div>
                    {{--车头轴距--}}
                    <div class="form-group">
                        <label class="control-label col-md-2">车头轴距</label>
                        <div class="col-md-9 ">
                            <input type="text" class="form-control" name="car_info_locomotive_wheelbase" placeholder="车头轴距" value="">
                        </div>
                    </div>
                    {{--主油厢--}}
                    <div class="form-group">
                        <label class="control-label col-md-2">主油厢</label>
                        <div class="col-md-9 ">
                            <input type="text" class="form-control" name="car_info_main_fuel_tank" placeholder="主油厢" value="">
                        </div>
                    </div>
                    {{--副油厢--}}
                    <div class="form-group">
                        <label class="control-label col-md-2">副油厢</label>
                        <div class="col-md-9 ">
                            <input type="text" class="form-control" name="car_info_auxiliary_fuel_tank" placeholder="副油厢" value="">
                        </div>
                    </div>
                    {{--总质量--}}
                    <div class="form-group">
                        <label class="control-label col-md-2">总质量</label>
                        <div class="col-md-9 ">
                            <input type="text" class="form-control" name="car_info_total_mass" placeholder="总质量" value="">
                        </div>
                    </div>
                    {{--整备质量--}}
                    <div class="form-group">
                        <label class="control-label col-md-2">整备质量</label>
                        <div class="col-md-9 ">
                            <input type="text" class="form-control" name="car_info_curb_weight" placeholder="整备质量" value="">
                        </div>
                    </div>
                    {{--核定载质量--}}
                    <div class="form-group">
                        <label class="control-label col-md-2">核定载重质量</label>
                        <div class="col-md-9 ">
                            <input type="text" class="form-control" name="car_info_load_weight" placeholder="核定载重质量" value="">
                        </div>
                    </div>
                    {{--准牵引总质量--}}
                    <div class="form-group">
                        <label class="control-label col-md-2">准牵引总质量</label>
                        <div class="col-md-9 ">
                            <input type="text" class="form-control" name="car_info_traction_mass" placeholder="准牵引总质量" value="">
                        </div>
                    </div>
                    {{--外廓尺寸--}}
                    <div class="form-group">
                        <label class="control-label col-md-2">外廓尺寸</label>
                        <div class="col-md-9 ">
                            <input type="text" class="form-control" name="car_info_overall_size" placeholder="外廓尺寸" value="">
                        </div>
                    </div>
                    {{--购买日期--}}
                    <div class="form-group">
                        <label class="control-label col-md-2">购买日期</label>
                        <div class="col-md-9 ">
                            <input type="text" class="form-control date_picker" name="car_info_purchase_date" placeholder="购买日期" value="" readonly="readonly">
                        </div>
                    </div>
                    {{--注册日期--}}
                    <div class="form-group">
                        <label class="control-label col-md-2">注册日期</label>
                        <div class="col-md-9 ">
                            <input type="text" class="form-control date_picker" name="car_info_registration_date" placeholder="注册日期" value="" readonly="readonly">
                        </div>
                    </div>
                    {{--发证日期--}}
                    <div class="form-group">
                        <label class="control-label col-md-2">发证日期</label>
                        <div class="col-md-9 ">
                            <input type="text" class="form-control date_picker" name="car_info_issue_date" placeholder="发证日期" value="" readonly="readonly">
                        </div>
                    </div>
                    {{--检验有效期--}}
                    <div class="form-group">
                        <label class="control-label col-md-2">检验有效期</label>
                        <div class="col-md-9 ">
                            <input type="text" class="form-control date_picker" name="car_info_inspection_validity" placeholder="检验有效期" value="" readonly="readonly">
                        </div>
                    </div>
                    {{--运输证-年检时间--}}
                    <div class="form-group">
                        <label class="control-label col-md-2">运输证-年检时间</label>
                        <div class="col-md-9 ">
                            <input type="text" class="form-control date_picker" name="car_info_transportation_license_validity" placeholder="运输证-年检时间" value="" readonly="readonly">
                        </div>
                    </div>
                    {{--运输证-换证期--}}
                    <div class="form-group">
                        <label class="control-label col-md-2">运输证-换证期</label>
                        <div class="col-md-9 ">
                            <input type="text" class="form-control date_picker" name="car_info_transportation_license_change_time" placeholder="运输证-换证期" value="" readonly="readonly">
                        </div>
                    </div>


                    {{--描述--}}
                    <div class="form-group">
                        <label class="control-label col-md-2">描述</label>
                        <div class="col-md-9 ">
                            {{--<input type="text" class="form-control" name="description" placeholder="描述" value="{{$data->description or ''}}">--}}
                            <textarea class="form-control" name="description" rows="3" cols="100%"></textarea>
                        </div>
                    </div>

                </div>
            </form>


            <div class="box-footer">
                <div class="row">
                    <div class="col-md-9 col-md-offset-2">
                        <button type="button" class="btn btn-success edit-submit" id="submit--for--car-item-edit">
                            <i class="fa fa-check"></i> 提交
                        </button>
                        <button type="button" class="btn btn-default edit-cancel">取消</button>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>