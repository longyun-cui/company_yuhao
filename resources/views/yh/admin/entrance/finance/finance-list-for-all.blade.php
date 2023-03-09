@extends(env('TEMPLATE_YH_ADMIN').'layout.layout')


@section('head_title')
    @if(in_array(env('APP_ENV'),['local'])){{ $local or 'L.' }}@endif
    {{ $title_text or '财务记录' }} - 管理员系统 - {{ config('info.info.short_name') }}
@endsection




@section('header','')
@section('description')财务记录 - 管理员系统 - {{ config('info.info.short_name') }}@endsection
@section('breadcrumb')
    <li><a href="{{ url('/') }}"><i class="fa fa-home"></i>首页</a></li>
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="box box-info main-list-body">

            <div class="box-header with-border" style="margin:16px 0;">

                <h3 class="box-title">财务记录</h3>

                <div class="caption pull-right _none">
                    <i class="icon-pin font-blue"></i>
                    <span class="caption-subject font-blue sbold uppercase"></span>
                    <a href="{{ url('/item/finance-create') }}">
                        <button type="button" onclick="" class="btn btn-success pull-right"><i class="fa fa-plus"></i> 添加财务记录</button>
                    </a>
                </div>

            </div>


            <div class="box-body datatable-body item-main-body" id="datatable-for-finance-list">

                <div class="row col-md-12 datatable-search-row">
                    <div class="input-group">

                        <input type="text" class="form-control form-filter item-search-keyup" name="order_id" placeholder="订单ID" style="width:88px;" />
                        <input type="text" class="form-control form-filter item-search-keyup" name="title" placeholder="名目" style="width:88px;" />
                        <input type="text" class="form-control form-filter item-search-keyup" name="transaction_type" placeholder="支付方式" style="width:88px;" />
                        <input type="text" class="form-control form-filter item-search-keyup" name="transaction_receipt_account" placeholder="收款账户" style="width:88px;" />
                        <input type="text" class="form-control form-filter item-search-keyup" name="transaction_payment_account" placeholder="支出账户" style="width:88px;" />
                        <input type="text" class="form-control form-filter item-search-keyup" name="transaction_order" placeholder="交易单号" style="width:88px;" />

                        <select class="form-control form-filter" name="finance-type" style="width:96px;">
                            <option value ="-1">全部收支</option>
                            <option value ="1">收入</option>
                            <option value ="21">支出</option>
                        </select>

                        <select class="form-control form-filter select2-container finance-select2-car" name="finance-car" style="width:100px;">
                            @if(isset($car_id) && $car_id > 0)
                                <option value="-1">选择车辆</option>
                                <option value="{{ $car_id or 0 }}" selected="selected">{{ $car_name or '' }}</option>
                            @else
                                <option value="-1">选择车辆</option>
                            @endif
                        </select>

                        <div1 class="clear-both-">
{{--                            <input type="text" class="form-control form-filter date_picker" name="transaction_time" placeholder="交易日期" readonly="readonly" style="width:80px;" />--}}
                            <input type="text" class="form-control form-filter date_picker" name="transaction_start" placeholder="起始日期" readonly="readonly" style="width:80px;" />
                            <input type="text" class="form-control form-filter date_picker" name="transaction_ended" placeholder="终止日期" readonly="readonly" style="width:80px;" />

                            <button type="button" class="form-control btn btn-flat btn-success filter-submit" id="filter-submit-for-finance">
                                <i class="fa fa-search"></i> 搜索
                            </button>
                            <button type="button" class="form-control btn btn-flat btn-primary filter-refresh" id="filter-refresh-for-finance">
                                <i class="fa fa-circle-o-notch"></i> 刷新
                            </button>
                            <button type="button" class="form-control btn btn-flat btn-warning filter-cancel" id="filter-cancel-for-finance">
                                <i class="fa fa-undo"></i> 重置
                            </button>
                            <button type="button" class="form-control btn btn-flat btn-default filter-empty" id="filter-empty-for-finance">
                                <i class="fa fa-remove"></i> 清空重选
                            </button>
                        </div1>

                    </div>
                </div>

                <div class="tableArea">
                <table class='table table-striped table-bordered- table-hover main-table' id='datatable_ajax'>
                    <thead>
                        <tr role='row' class='heading'>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                </div>

            </div>


            <div class="box-footer _none">
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
@endsection




@section('custom-style')
    <style>
        .tableArea .main-table {
            min-width:1800px;
        }
    </style>
@endsection




@section('custom-script')
<script>
    var TableDatatablesAjax = function () {
        var datatableAjax = function () {

            var dt = $('#datatable_ajax');
            var ajax_datatable = dt.DataTable({
//                "aLengthMenu": [[20, 50, 200, 500, -1], ["20", "50", "200", "500", "全部"]],
                "aLengthMenu": [[50, 100, 200], ["50", "100", "200"]],
                "processing": true,
                "serverSide": true,
                "searching": false,
                "ajax": {
                    'url': "{{ url('/finance/finance-list-for-all') }}",
                    "type": 'POST',
                    "dataType" : 'json',
                    "data": function (d) {
                        d._token = $('meta[name="_token"]').attr('content');
                        d.order_id = $('input[name="order_id"]').val();
                        d.transaction_time = $('input[name="transaction_time"]').val();
                        d.title = $('input[name="title"]').val();
                        d.transaction_type = $('input[name="transaction_type"]').val();
                        d.transaction_receipt_account = $('input[name="transaction_receipt_account"]').val();
                        d.transaction_payment_account = $('input[name="transaction_payment_account"]').val();
                        d.transaction_order = $('input[name="transaction_order"]').val();
                        d.transaction_start = $('input[name="transaction_start"]').val();
                        d.transaction_ended= $('input[name="transaction_ended"]').val();
                        d.car = $('select[name="finance-car"]').val();
                        d.finance_type = $('select[name="finance-type"]').val();
//
//                        d.created_at_from = $('input[name="created_at_from"]').val();
//                        d.created_at_to = $('input[name="created_at_to"]').val();
//                        d.updated_at_from = $('input[name="updated_at_from"]').val();
//                        d.updated_at_to = $('input[name="updated_at_to"]').val();

                    },
                },
                "pagingType": "simple_numbers",
                "order": [],
                "orderCellsTop": true,
                "scrollX": true,
//                "scrollY": true,
                "scrollCollapse": true,
                "fixedColumns": {
                    "leftColumns": "@if($is_mobile_equipment) 1 @else 6 @endif",
                    "rightColumns": "@if($is_mobile_equipment) 0 @else 1 @endif"
                },
                "columns": [
//                    {
//                        "width": "40px",
//                        "title": "选择",
//                        "data": "id",
//                        'orderable': false,
//                        render: function(data, type, row, meta) {
//                            return '<label><input type="checkbox" name="bulk-id" class="minimal" value="'+data+'"></label>';
//                        }
//                    },
//                    {
//                        "width": "40px",
//                        "title": "序号",
//                        "data": null,
//                        "targets": 0,
//                        'orderable': false
//                    },
                    {
                        "className": "font-12px",
                        "width": "50px",
                        "title": "ID",
                        "data": "id",
                        "orderable": true,
                        render: function(data, type, row, meta) {
                            return data;
                        }
                    },
                    {
                        "width": "80px",
                        "title": "操作",
                        "data": 'id',
                        "orderable": false,
                        render: function(data, type, row, meta) {
                            var $html_confirm = '';
                            var $html_delete = '';

                            if(row.deleted_at == null)
                            {
                                $html_delete = '<a class="btn btn-xs bg-navy item-delete-submit" data-id="'+data+'">删除</a>';

                                if(row.is_confirmed == 1)
                                {
                                    $html_confirm = '<a class="btn btn-xs btn-default disabled">确认</a>';

                                    if(row.confirmer_id == 0 || row.confirmer_id == row.creator_id)
                                    {
                                        $html_delete = '<a class="btn btn-xs bg-navy item-delete-submit" data-id="'+data+'">删除</a>';
                                    }
                                    else $html_delete = '<a class="btn btn-xs btn-default disabled">删除</a>';
                                }
                                else
                                {
                                    $html_confirm = '<a class="btn btn-xs bg-green item-confirm-submit" data-id="'+data+'">确认</a>';
                                }
                            }
                            else
                            {
                                $html_confirm = '<a class="btn btn-xs btn-default disabled">确认</a>';
                                $html_delete = '<a class="btn btn-xs btn-default disabled">删除</a>';
//                                $html_delete = '<a class="btn btn-xs bg-grey item-restore-submit" data-id="'+data+'">恢复</a>';
                            }


                            var html =
                                    $html_confirm+
                                    $html_delete+
//                                    '<a class="btn btn-xs bg-navy item-admin-delete-permanently-submit" data-id="'+data+'">彻底删除</a>'+
//                                '<a class="btn btn-xs bg-primary item-detail-show" data-id="'+data+'">查看详情</a>'+
                                '';
                            return html;

                        }
                    },
                    {
                        "width": "60px",
                        "title": "状态",
                        "data": "is_confirmed",
                        "orderable": false,
                        render: function(data, type, row, meta) {
                            if(row.deleted_at == null)
                            {
                                if(data == 1) return '<small class="btn-xs btn-success">已确认</small>';
                                else return '<small class="btn-xs btn-danger">待确认</small>';
                            }
                            else return '<small class="btn-xs bg-black">已删除</small>';
                        }
                    },
                    {
                        "width": "60px",
                        "title": "收支类型",
                        "data": 'finance_type',
                        "orderable": false,
                        render: function(data, type, row, meta) {
                            if(data == 1) return '<small class="btn-xs bg-olive">收入</small>';
                            else if(data == 21) return '<small class="btn-xs bg-orange">支出</small>';
                            else return "有误";
                        }
                    },
                    {
                        "width": "240px",
                        "title": "订单",
                        "data": "order_id",
                        "orderable": false,
                        render: function(data, type, row, meta) {

                            var $id = (row.order_er) ? row.order_er.id : data;
                            var $title = (row.order_er && row.order_er.title) ? row.order_er.title : '';
                            var $departure  = (row.order_er && row.order_er.departure_place) ? row.order_er.departure_place : '';
                            var $stopover  = (row.order_er && row.order_er.stopover_place) ? row.order_er.stopover_place : '';
                            var $destination  = (row.order_er && row.order_er.destination_place) ? row.order_er.destination_place : '';
                            var $car  = (row.order_er.car_er) ? row.order_er.car_er.name : row.order_er.outside_car;

                            var $date = new Date(row.order_er.assign_time*1000);
                            var $year = $date.getFullYear();
                            var $month = ('00'+($date.getMonth()+1)).slice(-2);
                            var $day = ('00'+($date.getDate())).slice(-2);
                            var $assign = $year+'-'+$month+'-'+$day;

                            var $text = $id + "&nbsp;&nbsp;(" + $car + ")&nbsp;&nbsp;[" + $assign + "]&nbsp;&nbsp;" + "(" + $departure + "-" + $stopover + "-" + $destination + ")&nbsp;&nbsp;" + $title;

                            $html = '<a target="_blank" href="/item/order-list-for-all?order_id='+$id+'" data-id="'+$id+'">'+$text+'</a><br>';

                            return $html;

                        }
                    },
                    {
                        "className": "text-center",
                        "width": "88px",
                        "title": "交易日期",
                        "data": "transaction_time",
                        "orderable": true,
                        render: function(data, type, row, meta) {
//                            return data;
                            var $date = new Date(data*1000);
                            var $year = $date.getFullYear();
                            var $month = ('00'+($date.getMonth()+1)).slice(-2);
                            var $day = ('00'+($date.getDate())).slice(-2);
                            var $hour = ('00'+$date.getHours()).slice(-2);
                            var $minute = ('00'+$date.getMinutes()).slice(-2);
                            var $second = ('00'+$date.getSeconds()).slice(-2);

//                            return $year+'-'+$month+'-'+$day;
//                            return $year+'-'+$month+'-'+$day+'&nbsp;&nbsp;'+$hour+':'+$minute;
//                            return $year+'-'+$month+'-'+$day+'&nbsp;&nbsp;'+$hour+':'+$minute+':'+$second;


                            var $currentYear = new Date().getFullYear();
                            if($year == $currentYear) return $month+'-'+$day;
                            else return $year+'-'+$month+'-'+$day;
                        }
                    },
                    {
                        "className": "text-center",
                        "width": "60px",
                        "title": "创建者",
                        "data": "creator_id",
                        "orderable": false,
                        render: function(data, type, row, meta) {
                            return row.creator == null ? '未知' : '<a href="javascript:void(0);">'+row.creator.true_name+'</a>';
                        }
                    },
                    {
                        "className": "text-center",
                        "width": "60px",
                        "title": "确认者",
                        "data": "confirmer_id",
                        "orderable": false,
                        render: function(data, type, row, meta) {
                            return row.confirmer == null ? '' : '<a href="javascript:void(0);">'+row.confirmer.true_name+'</a>';
                        }
                    },
                    {
                        "className": "text-center",
                        "width": "108px",
                        "title": "确认时间",
                        "data": "confirmed_at",
                        "orderable": false,
                        render: function(data, type, row, meta) {
                            if(!data) return '';

                            var $date = new Date(data*1000);
                            var $year = $date.getFullYear();
                            var $month = ('00'+($date.getMonth()+1)).slice(-2);
                            var $day = ('00'+($date.getDate())).slice(-2);
                            var $hour = ('00'+$date.getHours()).slice(-2);
                            var $minute = ('00'+$date.getMinutes()).slice(-2);
                            var $second = ('00'+$date.getSeconds()).slice(-2);

//                            return $year+'-'+$month+'-'+$day;
//                            return $year+'-'+$month+'-'+$day+'&nbsp;&nbsp;'+$hour+':'+$minute;
//                            return $year+'-'+$month+'-'+$day+'&nbsp;&nbsp;'+$hour+':'+$minute+':'+$second;

                            var $currentYear = new Date().getFullYear();
                            if($year == $currentYear) return $month+'-'+$day+'&nbsp;&nbsp;'+$hour+':'+$minute;
                            else return $year+'-'+$month+'-'+$day+'&nbsp;&nbsp;'+$hour+':'+$minute;

                        }
                    },
                    {
                        "className": "text-center",
                        "width": "60px",
                        "title": "金额",
                        "data": "transaction_amount",
                        "orderable": false,
                        render: function(data, type, row, meta) {
                            return data;
                        }
                    },
                    {
                        "width": "80px",
                        "title": "费用名目",
                        "data": "title",
                        "orderable": false,
                        render: function(data, type, row, meta) {
                            return data ? data : '--';
                        }
                    },
                    {
                        "width": "80px",
                        "title": "支付方式",
                        "data": "transaction_type",
                        "orderable": false,
                        render: function(data, type, row, meta) {
                            return data ? data : '--';
                        }
                    },
                    {
                        "className": "",
                        "width": "120px",
                        "title": "收款账户",
                        "data": "transaction_receipt_account",
                        "orderable": false,
                        render: function(data, type, row, meta) {
                            return data;
                        }
                    },
                    {
                        "className": "",
                        "width": "120px",
                        "title": "支出账户",
                        "data": "transaction_payment_account",
                        "orderable": false,
                        render: function(data, type, row, meta) {
                            return data;
                        }
                    },
                    {
                        "className": "",
                        "width": "120px",
                        "title": "交易单号",
                        "data": "transaction_order",
                        "orderable": false,
                        render: function(data, type, row, meta) {
                            return data;
                        }
                    },
                    {
                        "className": "text-center",
                        "width": "",
                        "title": "备注",
                        "data": "remark",
                        "orderable": false,
                        "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                            if(row.is_completed != 1 && row.item_status != 97)
                            {
                                $(nTd).addClass('modal-show-for-info-text-set');
                                $(nTd).attr('data-id',row.id).attr('data-name','备注');
                                $(nTd).attr('data-key','remark').attr('data-value',data);
                                $(nTd).attr('data-column-name','备注');
                                $(nTd).attr('data-text-type','textarea');
                                if(data) $(nTd).attr('data-operate-type','edit');
                                else $(nTd).attr('data-operate-type','add');
                            }
                        },
                        render: function(data, type, row, meta) {
                            return data;
                            // if(data) return '<small class="btn-xs bg-yellow">查看</small>';
                            // else return '';
                        }
                    },
                    {
                        "className": "font-12px",
                        "width": "108px",
                        "title": "创建时间",
                        "data": 'created_at',
                        "orderable": false,
                        render: function(data, type, row, meta) {
//                            return data;
                            var $date = new Date(data*1000);
                            var $year = $date.getFullYear();
                            var $month = ('00'+($date.getMonth()+1)).slice(-2);
                            var $day = ('00'+($date.getDate())).slice(-2);
                            var $hour = ('00'+$date.getHours()).slice(-2);
                            var $minute = ('00'+$date.getMinutes()).slice(-2);
                            var $second = ('00'+$date.getSeconds()).slice(-2);

//                            return $year+'-'+$month+'-'+$day;
//                            return $year+'-'+$month+'-'+$day+'&nbsp;&nbsp;'+$hour+':'+$minute;
//                            return $year+'-'+$month+'-'+$day+'&nbsp;&nbsp;'+$hour+':'+$minute+':'+$second;

                            var $currentYear = new Date().getFullYear();
                            if($year == $currentYear) return $month+'-'+$day+'&nbsp;'+$hour+':'+$minute;
                            else return $year+'-'+$month+'-'+$day+'&nbsp;'+$hour+':'+$minute;
                        }
                    }
                ],
                "drawCallback": function (settings) {

//                    let startIndex = this.api().context[0]._iDisplayStart;//获取本页开始的条数
//                    this.api().column(1).nodes().each(function(cell, i) {
//                        cell.innerHTML =  startIndex + i + 1;
//                    });

                    ajax_datatable.$('.tooltips').tooltip({placement: 'top', html: true});
                    $("a.verify").click(function(event){
                        event.preventDefault();
                        var node = $(this);
                        var tr = node.closest('tr');
                        var nickname = tr.find('span.nickname').text();
                        var cert_name = tr.find('span.certificate_type_name').text();
                        var action = node.attr('data-action');
                        var certificate_id = node.attr('data-id');
                        var action_name = node.text();

                        var tpl = "{{trans('labels.crc.verify_user_certificate_tpl')}}";
                        layer.open({
                            'title': '警告',
                            content: tpl
                                .replace('@action_name', action_name)
                                .replace('@nickname', nickname)
                                .replace('@certificate_type_name', cert_name),
                            btn: ['Yes', 'No'],
                            yes: function(index) {
                                layer.close(index);
                                $.post(
                                    '/admin/medsci/certificate/user/verify',
                                    {
                                        action: action,
                                        id: certificate_id,
                                        _token: '{{csrf_token()}}'
                                    },
                                    function(json){
                                        if(json['response_code'] == 'success') {
                                            layer.msg('操作成功!', {time: 3500});
                                            ajax_datatable.ajax.reload();
                                        } else {
                                            layer.alert(json['response_data'], {time: 10000});
                                        }
                                    }, 'json');
                            }
                        });
                    });
                },
                "language": { url: '/common/dataTableI18n' },
            });


            dt.on('click', '.filter-submit', function () {
                ajax_datatable.ajax.reload();
            });

            dt.on('click', '.filter-cancel', function () {
                $('textarea.form-filter, select.form-filter, input.form-filter', dt).each(function () {
                    $(this).val("");
                });

                $('select.form-filter').selectpicker('refresh');

                ajax_datatable.ajax.reload();
            });

        };
        return {
            init: datatableAjax
        }
    }();
    $(function () {
        TableDatatablesAjax.init();
    });
</script>
@include(env('TEMPLATE_YH_ADMIN').'entrance.finance.finance-script')
@endsection
