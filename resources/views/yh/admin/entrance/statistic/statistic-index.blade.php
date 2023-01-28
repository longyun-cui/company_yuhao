@extends(env('TEMPLATE_YH_ADMIN').'layout.layout')


@section('head_title')
    @if(in_array(env('APP_ENV'),['local'])){{ $local or '【l】' }}@endif{{ $head_title or '统计' }} - 管理员后台系统 - 兆益信息
@endsection




@section('header','')
@section('description','统计 - 管理员后台系统 - 兆益信息')
@section('breadcrumb')
    <li><a href="{{url('/')}}"><i class="fa fa-home"></i>首页</a></li>
    <li><a href="#"><i class="fa "></i>Here</a></li>
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="box box-info main-list-body">

            <div class="box-header with-border" style="margin:16px 0;">

                <h3 class="box-title">{{ $title_text or '' }}</h3>

            </div>


            <div class="box-body datatable-body item-main-body" id="datatable-for-circle-list">

                <div class="row col-md-12 datatable-search-row">
                    <div class="input-group">

                        <input type="text" class="form-control form-filter filter-keyup month_picker" name="statistic-month" placeholder="选择月份" readonly="readonly" />

                        <select class="form-control form-filter" name="statistic-staff" style="width:96px;">
                            <option value ="-1">选择员工</option>
                            @foreach($staff_list as $v)
                                <option value ="{{ $v->id }}">{{ $v->true_name }}</option>
                            @endforeach
                        </select>

                        <select class="form-control form-filter" name="statistic-client" style="width:96px;">
                            <option value ="-1">选择客户</option>
                            @foreach($client_list as $v)
                                <option value ="{{ $v->id }}">{{ $v->username }}</option>
                            @endforeach
                        </select>

                        <select class="form-control form-filter" name="statistic-route" style="width:96px;">
                            <option value="-1">选择线路</option>
                            @foreach($route_list as $v)
                                <option value ="{{ $v->id }}">{{ $v->title }}</option>
                            @endforeach
                        </select>

                        {{--<select class="form-control form-filter" name="order-car" style="width:96px;">--}}
                        {{--<option value ="-1">选择车辆</option>--}}
                        {{--@foreach($car_list as $v)--}}
                        {{--<option value ="{{ $v->id }}">{{ $v->name }}</option>--}}
                        {{--@endforeach--}}
                        {{--</select>--}}
                        <select class="form-control form-filter order-list-select2-car" name="statistic-car" style="width:96px;">
                            <option value="-1">选择车辆</option>
                        </select>

                        <select class="form-control form-filter" name="statistic-pricing" style="width:96px;">
                            <option value="-1">选择定价</option>
                            @foreach($pricing_list as $v)
                                <option value ="{{ $v->id }}">{{ $v->title }}</option>
                            @endforeach
                        </select>

                        <select class="form-control form-filter" name="statistic-type" style="width:96px;">
                            <option value ="-1">订单类型</option>
                            <option value ="1">自有</option>
                            <option value ="11">空单</option>
                            <option value ="41">外配·配货</option>
                            <option value ="61">外请·调车</option>
                        </select>


                        <button type="button" class="form-control btn btn-flat btn-success filter-submit" id="filter-submit-for-statistic">
                            <i class="fa fa-search"></i> 搜索
                        </button>
                        <button type="button" class="form-control btn btn-flat btn-default filter-cancel" id="filter-cancel-for-statistic">
                            <i class="fa fa-circle-o-notch"></i> 重置
                        </button>

                    </div>
                </div>

            </div>


            <div class="box-footer">
                <div class="row" style="margin:16px 0;">
                    <div class="col-md-offset-0 col-md-6 col-sm-9 col-xs-12">
                        {{--<button type="button" class="btn btn-primary"><i class="fa fa-check"></i> 提交</button>--}}
                        {{--<button type="button" onclick="history.go(-1);" class="btn btn-default">返回</button>--}}
                        <div class="input-group">
                            <span class="input-group-addon"><input type="checkbox" id="check-review-all"></span>
                            <select name="bulk-operate-status" class="form-control form-filter">
                                <option value ="-1">请选择操作类型</option>
                                <option value ="启用">启用</option>
                                <option value ="禁用">禁用</option>
                                <option value ="删除">删除</option>
                                <option value ="彻底删除">彻底删除</option>
                            </select>
                            <span class="input-group-addon btn btn-default" id="operate-bulk-submit"><i class="fa fa-check"></i> 批量操作</span>
                            <span class="input-group-addon btn btn-default" id="delete-bulk-submit"><i class="fa fa-trash-o"></i> 批量删除</span>
                        </div>
                    </div>
                </div>
            </div>


            <div class="box-footer _none">
                <div class="row" style="margin:16px 0;">
                    <div class="col-md-offset-0 col-md-9">
                        <button type="button" onclick="" class="btn btn-primary _none"><i class="fa fa-check"></i> 提交</button>
                        <button type="button" onclick="history.go(-1);" class="btn btn-default">返回</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


{{--网站总流量统计--}}
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PORTLET-->
        <div class="box box-info">

            <div class="box-header with-border" style="margin:16px 0;">
                <h3 class="box-title">总量统计</h3>
            </div>
            {{--总电话量--}}
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="echart-all" style="width:100%;height:320px;"></div>
                    </div>
                </div>
            </div>
            <div class="box-footer">
            </div>

        </div>
        <!-- END PORTLET-->
    </div>
</div>


{{--网站总流量统计--}}
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PORTLET-->
        <div class="box box-info">

            <div class="box-header with-border" style="margin:16px 0;">
                <h3 class="box-title">员工对比</h3>
            </div>

            {{--总电话量-对比--}}
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="echart-all-comparison" style="width:100%;height:240px;"></div>
                    </div>
                </div>
            </div>
            {{--通话量-对比--}}
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="echart-dialog-comparison" style="width:100%;height:240px;"></div>
                    </div>
                </div>
            </div>
            {{--加微信-对比--}}
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="echart-wx-comparison" style="width:100%;height:240px;"></div>
                    </div>
                </div>
            </div>

            <div class="box-footer">
            </div>

        </div>
        <!-- END PORTLET-->
    </div>
</div>


{{--转化率--}}
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PORTLET-->
        <div class="box box-warning">

            <div class="box-header with-border" style="margin:16px 0;">
                <h3 class="box-title">转化率</h3>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                        <div id="echart-all-rate" style="width:100%;height:320px;"></div>
                    </div>
                    <div class="col-md-6">
                        <div id="echart-today-rate" style="width:100%;height:320px;"></div>
                    </div>
                </div>
            </div>

            <div class="box-footer">
            </div>

        </div>
        <!-- END PORTLET-->
    </div>
</div>
@endsection




@section('custom-js')
    <script src="{{ asset('/lib/js/echarts-3.7.2.min.js') }}"></script>
@endsection
@section('custom-script')
<script>
$(function() {

    $('input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%' // optional
    });

});
</script>

<script>
    $(function(){

        // 电话总量
        var $all_res = new Array();
        $.each({!! $all !!},function(key,v){
            $all_res[(v.day - 1)] = { value:v.count, name:v.day };
//            $all_res.push({ value:v.sum, name:v.date });
        });
        // 通话量
        var $dialog_res = new Array();
        $.each({!! $dialog !!},function(key,v){
            $dialog_res[(v.day - 1)] = { value:v.count, name:v.day };
        });
        // 加微信量
        var $plus_wx_res = new Array();
        $.each({!! $plus_wx !!},function(key,v){
            $plus_wx_res[(v.day - 1)] = { value:v.count, name:v.day };
        });

        var option_all = {
            title: {
                text: '电话量'
            },
            tooltip : {
                trigger: 'axis',
                axisPointer: {
                    type: 'line',
                    label: {
                        backgroundColor: '#6a7985'
                    }
                }
            },
            legend: {
                data:['电话总量','通话量','加微信量']
            },
            toolbox: {
                feature: {
                    saveAsImage: {}
                }
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            xAxis : [
                {
                    type : 'category',
                    boundaryGap : false,
                    axisLabel : { interval:0 },
                    data : [
                        1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31
                        {{--@foreach($all as $v)--}}
                            {{--@if (!$loop->last) '{{$v->date}}', @else '{{$v->date}}' @endif--}}
                        {{--@endforeach--}}
                    ]
                }
            ],
            yAxis : [
                {
                    type : 'value'
                }
            ],
            series : [
                {
                    name:'电话总量',
                    type:'line',
                    label: {
                        normal: {
                            show: true,
                            position: 'top'
                        }
                    },
                    itemStyle : { normal: { label : { show: true } } },
                    data: $all_res
                    {{--data:[--}}
                        {{--@foreach($all as $v)--}}
                        {{--@if (!$loop->last)--}}
                            {{--{ value:'{{ $v->count }}', name:'{{ $v->day }}' },--}}
                        {{--@else--}}
                            {{--{ value:'{{ $v->count }}', name:'{{ $v->day }}' }--}}
                        {{--@endif--}}
                        {{--@endforeach--}}
                    {{--]--}}
                },
                {
                    name:'通话量',
                    type:'line',
                    label: {
                        normal: {
                            show: true,
                            position: 'top'
                        }
                    },
                    itemStyle : { normal: { label : { show: true } } },
                    data: $dialog_res
                },
                {
                    name:'加微信量',
                    type:'line',
                    label: {
                        normal: {
                            show: true,
                            position: 'top'
                        }
                    },
                    itemStyle : { normal: { label : { show: true } } },
                    data: $plus_wx_res
                }
            ]
        };
        var myChart_all = echarts.init(document.getElementById('echart-all'));
        myChart_all.setOption(option_all);



        // 总转化率
        var option_all_rate = {
            title : {
                text: '转化率',
                subtext: '转化率',
                x:'center'
            },
            tooltip : {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} ({d}%)"
            },
            legend: {
                orient : 'vertical',
                x : 'left',
                data: [
                    @foreach($all_rate as $v)
                        @if (!$loop->last) '{{ $v->name }}', @else '{{ $v->name }}' @endif
                    @endforeach
                ]
            },
            toolbox: {
                show : true,
                feature : {
                    mark : {show: true},
                    dataView : {show: true, readOnly: false},
                    magicType : {
                        show: true,
                        type: ['pie', 'funnel'],
                        option: {
                            funnel: {
                                x: '25%',
                                width: '50%',
                                funnelAlign: 'left',
                                max: 1548
                            }
                        }
                    },
                    restore : {show: true},
                    saveAsImage : {show: true}
                }
            },
            calculable : true,
            series : [
                {
                    name:'访问来源',
                    type:'pie',
                    radius : '55%',
                    center: ['50%', '60%'],
                    data: [
                        @foreach($all_rate as $v)
                            @if (!$loop->last)
                                { value:'{{ $v->count }}', name:'{{ $v->name }}' },
                            @else
                                { value:'{{ $v->count }}', name:'{{ $v->name }}' }
                            @endif
                        @endforeach
                    ]
                }
            ]
        };
        var myChart_all_rate = echarts.init(document.getElementById('echart-all-rate'));
        myChart_all_rate.setOption(option_all_rate);

        // 今日转化率
        var option_today_rate = {
            title : {
                text: '今日转化率',
                subtext: '今日转化率',
                x:'center'
            },
            tooltip : {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} ({d}%)"
            },
            legend: {
                orient : 'vertical',
                x : 'left',
                data: [
                    @foreach($today_rate as $v)
                        @if (!$loop->last) '{{ $v->name }}', @else '{{ $v->name }}' @endif
                    @endforeach
                ]
            },
            toolbox: {
                show : true,
                feature : {
                    mark : {show: true},
                    dataView : {show: true, readOnly: false},
                    magicType : {
                        show: true,
                        type: ['pie', 'funnel'],
                        option: {
                            funnel: {
                                x: '25%',
                                width: '50%',
                                funnelAlign: 'left',
                                max: 1548
                            }
                        }
                    },
                    restore : {show: true},
                    saveAsImage : {show: true}
                }
            },
            calculable : true,
            series : [
                {
                    name:'访问来源',
                    type:'pie',
                    radius : '55%',
                    center: ['50%', '60%'],
                    data: [
                        @foreach($today_rate as $v)
                            @if(!$loop->last)
                                { value:'{{ $v->count }}', name:'{{ $v->name }}' },
                            @else
                                { value:'{{ $v->count }}', name:'{{ $v->name }}' }
                            @endif
                        @endforeach
                    ]
                }
            ]
        };
        var myChart_today_rate = echarts.init(document.getElementById('echart-today-rate'));
        myChart_today_rate.setOption(option_today_rate);



        // 对比
        var $staff_res = new Array();
        $staff_res['all'] = new Array();
        $staff_res['dialog'] = new Array();
        $staff_res['wx'] = new Array();
        @foreach($staff as $key => $val)
            $staff_res['all']["{{ $key }}"] = new Array();
            $.each({!! $val['all'] !!},function(key,v){
                $staff_res['all']["{{ $key }}"][(v.day - 1)] = { value:v.count, name:v.day };
            });
            $staff_res['dialog']["{{ $key }}"] = new Array();
            $.each({!! $val['dialog'] !!},function(key,v){
                $staff_res['dialog']["{{ $key }}"][(v.day - 1)] = { value:v.count, name:v.day };
            });
            $staff_res['wx']["{{ $key }}"] = new Array();
            $.each({!! $val['wx'] !!},function(key,v){
                $staff_res['wx']["{{ $key }}"][(v.day - 1)] = { value:v.count, name:v.day };
            });
        @endforeach

        var option_all_comparison = {
                title: {
                    text: '总电话量-对比'
                },
                tooltip : {
                    trigger: 'axis',
                    axisPointer: {
                        type: 'line',
                        label: {
                            backgroundColor: '#6a7985'
                        }
                    }
                },
                legend: {
                    data:[
                        @foreach($staff as $k => $v)
                        @if (!$loop->last)
                            "{{ $k }}",
                        @else
                            "{{ $k }}"
                        @endif
                        @endforeach
                    ]
                },
                toolbox: {
                    feature: {
                        saveAsImage: {}
                    }
                },
                grid: {
                    left: '3%',
                    right: '4%',
                    bottom: '3%',
                    containLabel: true
                },
                xAxis : [
                    {
                        type : 'category',
                        boundaryGap : false,
                        axisLabel : { interval:0 },
                        data : [
                            1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31
                        ]
                    }
                ],
                yAxis : [
                    {
                        type : 'value'
                    }
                ],
                series : [
                    @foreach($staff as $k => $v)
                    {
                        name:'{{ $k }}',
                        type:'line',
                        label: {
                            normal: {
                                show: true,
                                position: 'top'
                            }
                        },
                        itemStyle : { normal: { label : { show: true } } },
                        data: $staff_res["all"]["{{ $k }}"]
                    },
                    @endforeach
                ]
            };
        var myChart_all_comparison = echarts.init(document.getElementById('echart-all-comparison'));
        myChart_all_comparison.setOption(option_all_comparison);

        var option_dialog_comparison = {
            title: {
                text: '通话量-对比'
            },
            tooltip : {
                trigger: 'axis',
                axisPointer: {
                    type: 'line',
                    label: {
                        backgroundColor: '#6a7985'
                    }
                }
            },
            legend: {
                data:[
                    @foreach($staff as $k => $v)
                    @if (!$loop->last)
                        "{{ $k }}",
                    @else
                        "{{ $k }}"
                    @endif
                    @endforeach
                ]
            },
            toolbox: {
                feature: {
                    saveAsImage: {}
                }
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            xAxis : [
                {
                    type : 'category',
                    boundaryGap : false,
                    axisLabel : { interval:0 },
                    data : [
                        1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31
                    ]
                }
            ],
            yAxis : [
                {
                    type : 'value'
                }
            ],
            series : [
                @foreach($staff as $k => $v)
                {
                    name:'{{ $k }}',
                    type:'line',
                    label: {
                        normal: {
                            show: true,
                            position: 'top'
                        }
                    },
                    itemStyle : { normal: { label : { show: true } } },
                    data: $staff_res["dialog"]["{{ $k }}"]
                },
                @endforeach
            ]
        };
        var myChart_dialog_comparison = echarts.init(document.getElementById('echart-dialog-comparison'));
        myChart_dialog_comparison.setOption(option_dialog_comparison);

        var option_wx_comparison = {
            title: {
                text: '加微信量-对比'
            },
            tooltip : {
                trigger: 'axis',
                axisPointer: {
                    type: 'line',
                    label: {
                        backgroundColor: '#6a7985'
                    }
                }
            },
            legend: {
                data:[
                    @foreach($staff as $k => $v)
                    @if (!$loop->last)
                        "{{ $k }}",
                    @else
                        "{{ $k }}"
                    @endif
                    @endforeach
                ]
            },
            toolbox: {
                feature: {
                    saveAsImage: {}
                }
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            xAxis : [
                {
                    type : 'category',
                    boundaryGap : false,
                    axisLabel : { interval:0 },
                    data : [
                        1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31
                    ]
                }
            ],
            yAxis : [
                {
                    type : 'value'
                }
            ],
            series : [
                @foreach($staff as $k => $v)
                {
                    name:'{{ $k }}',
                    type:'line',
                    label: {
                        normal: {
                            show: true,
                            position: 'top'
                        }
                    },
                    itemStyle : { normal: { label : { show: true } } },
                    data: $staff_res["wx"]["{{ $k }}"]
                },
                @endforeach
            ]
        };
        var myChart_wx_comparison = echarts.init(document.getElementById('echart-wx-comparison'));
        myChart_wx_comparison.setOption(option_wx_comparison);


    });
</script>

@endsection


