<div class="row datatable-body datatable-wrapper export-clone" data-datatable-item-category="export" data-item-name="导出">


    {{--录单--}}
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="box box-primary box-solid">
            <div class="box-header with-border">
                <h class="box-title comprehensive-month-title">订单•导出</h>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <ul class="nav nav-stacked">
                    <li class="">
                        <div class="col-md-12 datatable-search-row filter-box">
                            <div class="pull-right">


                                <input type="hidden" name="export-time-type" class="time-type" value="" readonly>


                                {{--选择车辆所有人--}}
                                <select class="search-filter form-filter filter-lg select2-box-c" name="order-export--car-owner-type">
                                    <option value="-1">选择车辆类型</option>
                                    <option value="1">自有</option>
                                    <option value="9">共建</option>
                                    <option value="11">外请</option>
                                </select>


                                <select class="search-filter form-filter filter-lg select2-reset select2-box-c select2-project-c-" name="order-export--project">
                                    <option value="-1">选择项目</option>
                                    @if(!empty($project_list) && count($project_list) > 0)
                                        @foreach($project_list as $v)
                                            <option value="{{ $v->id }}">{{ $v->name }}</option>
                                        @endforeach
                                    @endif
                                </select>


                                {{--<button type="button" class="btn btn-default btn-filter filter-submit filter-submit-for-order" data-type="latest">--}}
                                {{--<i class="fa fa-download"></i> 最新导出--}}
                                {{--</button>--}}


                                {{--按天导出--}}
                                <input type="text" class="search-filter form-filter filter-keyup date-picker-c" name="order-export--date" placeholder="选择日期" readonly="readonly" value="{{ date('Y-m-d') }}" data-default="{{ date('Y-m-d') }}" />
                                <button type="button" class="btn btn-primary btn-filter submit--for--order-export" data-type="date" data-time-type="date">
                                    <i class="fa fa-download"></i> 按日导出
                                </button>


                                {{--按月导出--}}
                                <input type="text" class="search-filter form-filter filter-keyup month-picker month-picker-c" name="order-export--month" placeholder="选择月份" readonly="readonly" value="{{ date('Y-m') }}" data-default="{{ date('Y-m') }}" />
                                <button type="button" class="btn btn-primary btn-filter submit--for--order-export" data-type="month" data-time-type="month">
                                    <i class="fa fa-download"></i> 按月导出
                                </button>


                                {{--按时间段导出--}}
                                <input type="text" class="search-filter form-filter filter-keyup date-picker-c" name="order-export--start" placeholder="起始时间" readonly="readonly" value="{{ date('Y-m-d') }}" data-default="{{ date('Y-m-d') }}" style="width:120px;text-align:center;" />
                                <input type="text" class="search-filter form-filter filter-keyup date-picker-c" name="order-export--ended" placeholder="结束时间" readonly="readonly" value="{{ date('Y-m-d') }}" data-default="{{ date('Y-m-d') }}" style="width:120px;text-align:center;" />

                                <button type="button" class="btn btn-primary btn-filter submit--for--order-export" data-type="period" data-time-type="period">
                                    <i class="fa fa-download"></i> 按时间段导出
                                </button>
                                <button type="button" class="btn btn-success btn-filter submit--for--order-export" data-type="all" data-time-type="all">
                                    <i class="fa fa-download"></i> 全部导出
                                </button>


                                <button type="button" class="btn btn-default btn-filter filter-empty--for--export">
                                    <i class="fa fa-remove"></i> 清空重选
                                </button>


                                <div class="month-picker-box clear-both">
                                </div>


                                <div class="month-picker-box clear-both">
                                </div>


                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>






    <div class="col-md-12 datatable-search-row datatable-search-box">
        <div class="pull-right">

            <input type="text" class="search-filter form-filter filter-md filter-keyup" name="record-id" placeholder="ID" />
            <select class="search-filter form-filter filter-md select2-box-c" name="export-record--operate-type">
                <option value="-1">导出方式</option>
                <option value="1">自定义时间导出</option>
                <option value="11">按月导出</option>
                <option value="31">按日导出</option>
                <option value="99">最新导出</option>
                <option value="100">ID导出</option>
            </select>

            <select class="search-filter form-filter filter-lg select2-box-c select2-staff-c-" name="export-record--staff">
                <option value="-1">选择员工</option>
                @if(!empty($staff_list) && count($staff_list) > 0)
                @foreach($staff_list as $v)
                    <option value="{{ $v->id }}">{{ $v->name }}</option>
                @endforeach
                @endif
            </select>

            <button type="button" class="btn btn-default btn-filter filter-submit" id="filter-submit--for--export-record">
                <i class="fa fa-search"></i> 搜索
            </button>
            <button type="button" class="btn btn-default btn-filter filter-cancel" id="filter-cancel--for--export-record">
                <i class="fa fa-circle-o-notch"></i> 重置
            </button>

        </div>
    </div>


    <div class="col-md-12 datatable-body">
        <div class="box box-primary box-solid-" style="box-shadow:0 0;">

            <div class="box-header with-border- margin-top-16px padding-top-8px _none">
                <h3 class="box-title datatable-title"></h3>
            </div>

            <div class="box-body no-padding">
                <div class="tableArea full margin-top-8px">
                    <table class='table table-striped table-bordered table-hover order-column'>
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


</div>