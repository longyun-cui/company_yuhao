<script>
    function Datatable_for_Fee_List($tableId)
    {

        let $that = $($tableId);
        let $datatable_wrapper = $that.parents('.datatable-wrapper');
        let $tableSearch = $datatable_wrapper.find('.datatable-search-box');

        $($tableId).DataTable({
            "aLengthMenu": [[10, 50, 200, 500], ["10", "50", "200", "500"]],
            "processing": true,
            "serverSide": true,
            "searching": false,
            "pagingType": "simple_numbers",
            "sDom": '<"dataTables_length_box"l> <"dataTables_info_box"i> <"dataTables_paginate_box"p> <t>',
            "order": [],
            "orderCellsTop": true,
            "scrollX": true,
//                "scrollY": true,
            "scrollCollapse": true,
            "ajax": {
                'url': "{{ url('/v1/operate/fee/datatable-list-query') }}",
                "type": 'POST',
                "dataType" : 'json',
                "data": function (d) {
                    d._token = $('meta[name="_token"]').attr('content');
                    d.id = $('input[name="fee-id"]').val();
                    d.mobile = $('input[name="fee-mobile"]').val();
                    d.username = $('input[name="fee-username"]').val();
                    d.department_district = $tableSearch.find('select[name="fee-department-district"]').val();
                    d.user_type = $tableSearch.find('select[name="fee-user-type"]').val();
                    d.user_status = $tableSearch.find('select[name="fee-user-status"]').val();
                },
            },
            "fixedColumns": {
                "leftColumns": "@if($is_mobile_equipment) 1 @else 1 @endif",
                "rightColumns": "1"
            },
            "columns": [
                {
                    "title": "ID",
                    "data": "id",
                    "className": "",
                    "width": "60px",
                    "orderable": true,
                    "orderSequence": ["desc", "asc"],
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(row.is_completed != 1)
                        {
                            $(nTd).addClass('modal-show-for-attachment');
                            $(nTd).attr('data-id',row.id).attr('data-name',row.fee_name);
                            $(nTd).attr('data-key','attachment_list').attr('data-value','attachment');
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        return data;
                    }
                },
                {
                    "title": "状态",
                    "data": "is_completed",
                    "width": "80px",
                    "orderable": false,
                    render: function(data, type, row, meta) {
                        if(data == 0)
                        {
                            return '<i class="fa fa-square-o text-orange"></i> 待记账';
                        }
                        else if(data == 1)
                        {
                            return '<i class="fa fa-check-square-o text-black"></i> 已记账';
                        }
                        else if(data == 101)
                        {
                            return '<i class="fa fa-minus-square-o text-black"></i> 不记账';
                        }
                        else
                        {
                            return '<i class="fa fa-question-circle text-black"></i> 有误';
                        }
                    }
                },
                // {
                //     "title": "收支",
                //     "data": "fee_category",
                //     "width": "80px",
                //     "orderable": false,
                //     render: function(data, type, row, meta) {
                //         if(data == 1)
                //         {
                //             return '<i class="fa fa-sign-in text-green"></i> 收入';
                //         }
                //         else if(data == 99)
                //         {
                //             return '<i class="fa fa-sign-out text-red"></i> 支出';
                //         }
                //         else
                //         {
                //             return '<i class="fa fa-question-circle text-black"></i> 有误';
                //         }
                //     }
                // },
                {
                    "title": "类型",
                    "data": "fee_type",
                    "width": "80px",
                    "orderable": false,
                    render: function(data, type, row, meta) {
                        if(data == 1)
                        {
                            return '<i class="fa fa-sign-in text-green"></i> 收入';
                        }
                        if(data == 99)
                        {
                            return '<i class="fa fa-sign-out text-red"></i> 费用';
                        }
                        else if(data == 101)
                        {
                            return '<i class="fa fa-info-circle text-red"></i> 扣款';
                        }
                        else if(data == 111)
                        {
                            return '<i class="fa fa-times-circle text-orange"></i> 罚款';
                        }
                        else
                        {
                            return '<i class="fa fa-question-circle text-black"></i> 有误';
                        }
                    }
                },
                {
                    "title": "时间",
                    "data": "fee_datetime",
                    "className": "",
                    "width": "120px",
                    "orderable": false,
                    render: function(data, type, row, meta) {
                        if(data) return data.slice(0, -3);
                        else return '';
                    }
                },
                {
                    "className": "",
                    "width": "80px",
                    "title": "名目",
                    "data": "fee_title",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        $(nTd).attr('data-id',row.id).attr('data-name','名目');
                        $(nTd).attr('data-key','fee_title').attr('data-value',data);
                    },
                    render: function(data, type, row, meta) {
                        if(data) return data;
                        else return '--';
                    }
                },
                {
                    "title": "金额",
                    "data": "fee_amount",
                    "className": "",
                    "width": "80px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        $(nTd).attr('data-id',row.id).attr('data-name','金额');
                        $(nTd).attr('data-key','fee_amount').attr('data-value',data);
                    },
                    render: function(data, type, row, meta) {
                        if(data) return parseFloat(data);
                        else return '--';
                    }
                },
                {
                    "title": "客户",
                    "data": "client_id",
                    "className": "",
                    "width": "80px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                    },
                    render: function(data, type, row, meta) {
                        if(row.client_er)
                        {
                            return '<a href="javascript:void(0);" class="text-black">'+row.client_er.name+'</a>';
                        }
                        else return '--';
                    }
                },
                {
                    "title": "项目",
                    "data": "project_id",
                    "className": "",
                    "width": "100px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                    },
                    render: function(data, type, row, meta) {
                        if(row.project_er)
                        {
                            return '<a href="javascript:void(0);" class="text-black">'+row.project_er.name+'</a>';
                        }
                        else return '--';
                    }
                },
                {
                    "title": "工单",
                    "data": "order_id",
                    "className": "text-left",
                    "width": "300px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                    },
                    render: function(data, type, row, meta) {
                        if(row.order_er)
                        {
                            var $order_info = '';
                            $order_info += '(' + row.order_er.id + ')';
                            $order_info += ' ' + row.order_er.task_date;
                            $order_info += ' - ' + row.order_er.transport_departure_place;
                            $order_info += ' - ' + row.order_er.transport_destination_place;
                            return '<a href="javascript:void(0);" class="text-black">' + $order_info + '</a>';
                        }
                        else return '未指定';
                    }
                },
                {
                    "title": "车辆",
                    "data": "car_id",
                    "className": "",
                    "width": "100px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                    },
                    render: function(data, type, row, meta) {
                        if(row.car_er)
                        {
                            return '<a href="javascript:void(0);" class="text-black">'+row.car_er.name+'</a>';
                        }
                        else return '--';
                    }
                },
                {
                    "title": "驾驶员",
                    "data": "driver_id",
                    "className": "",
                    "width": "100px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                    },
                    render: function(data, type, row, meta) {
                        if(row.driver_er)
                        {
                            return '<a href="javascript:void(0);" class="text-black">'+row.driver_er.driver_name+'</a>';
                        }
                        else return '--';
                    }
                },
                {
                    "title": "备注",
                    "data": "remark",
                    "className": "",
                    "width": "",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(row.user_status != 97)
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
                    "title": "创建人",
                    "data": "creator_id",
                    "className": "",
                    "width": "80px",
                    "orderable": false,
                    render: function(data, type, row, meta) {
                        // return data;
                        if(row.creator) return row.creator.username;
                        else return '--';

                    }
                },
                {
                    "title": "创建时间",
                    "data": 'created_at',
                    "className": "",
                    "width": "120px",
                    "orderable": true,
                    render: function(data, type, row, meta) {
//                            return data;

//                            newDate = new Date();
//                            newDate.setTime(data * 1000);
//                            return newDate.toLocaleString('chinese',{hour12:false});
//                            return newDate.toLocaleDateString();

                        var $date = new Date(data*1000);
                        var $year = $date.getFullYear();
                        var $month = ('00'+($date.getMonth()+1)).slice(-2);
                        var $day = ('00'+($date.getDate())).slice(-2);
                        var $hour = ('00'+$date.getHours()).slice(-2);
                        var $minute = ('00'+$date.getMinutes()).slice(-2);
                        var $second = ('00'+$date.getSeconds()).slice(-2);

                        var $return = '';
                        // $return = $year+'-'+$month+'-'+$day;
                        // $return = $year+'-'+$month+'-'+$day+'&nbsp;&nbsp;'+$hour+':'+$minute;
                        // $return = $year+'-'+$month+'-'+$day+'&nbsp;&nbsp;'+$hour+':'+$minute+':'+$second;

                        var $currentYear = new Date().getFullYear();
                        if($year == $currentYear) $return = $month+'-'+$day+'&nbsp;&nbsp;'+$hour+':'+$minute;
                        else $return = $year+'-'+$month+'-'+$day+'&nbsp;&nbsp;'+$hour+':'+$minute;

                        return $return;
                    }
                },
                {
                    "title": "操作",
                    "data": "id",
                    "width": "160px",
                    "orderable": false,
                    render: function(data, type, row, meta) {
                        var html_edit = '';
                        var $html_delete = '';
                        var $html_pay = '<a class="btn btn-xs btn-default disabled" data-id="'+data+'">记账</a>';


                        if(row.is_completed == 0)
                        {
                            $html_pay = '<a class="btn btn-xs btn-success- item-modal-show-for-fee-financial-create" data-id="'+data+'">记账</a>';
                        }
                        else if(row.is_completed == 1)
                        {
                            $html_pay = '<a class="btn btn-xs btn-default disabled" data-id="'+data+'">记账</a>';
                        }
                        else if(row.is_completed == 101)
                        {
                            $html_pay = '<a class="btn btn-xs btn-default disabled" data-id="'+data+'">记账</a>';
                        }
                        else
                        {
                            $html_pay = '<a class="btn btn-xs btn-default disabled" data-id="'+data+'">记账</a>';
                        }


                        if(row.user_category == 1)
                        {
                            $html_edit = '<a class="btn btn-xs btn-default disabled" data-id="'+data+'">编辑</a>';
                        }
                        else
                        {
                            $html_edit = '<a class="btn btn-xs btn-primary- item-admin-edit-link" data-id="'+data+'">编辑</a>';
                        }

                        if(row.deleted_at == null)
                        {
                            $html_delete = '<a class="btn btn-xs bg-black- item-admin-delete-submit" data-id="'+data+'">删除</a>';
                        }
                        else
                        {
                            $html_delete = '<a class="btn btn-xs bg-grey- item-admin-restore-submit" data-id="'+data+'">恢复</a>';
                        }

                        $html_record = '<a class="btn btn-xs bg-purple- item-modal-show-for-modify" data-id="'+data+'">记录</a>';

                        var html =
                            // $html_edit+
                            $html_pay+
                            $html_delete+
                            $html_record+
                            '';
                        return html;
                    }
                }
            ],
            "drawCallback": function (settings) {

                console.log('fee-list-datatable-execute');

//                    let startIndex = this.api().context[0]._iDisplayStart;//获取本页开始的条数
//                    this.api().column(1).nodes().each(function(cell, i) {
//                        cell.innerHTML =  startIndex + i + 1;
//                    });

            },
            "language": { url: '/common/dataTableI18n' },
        });

    }
</script>