<script>

    function Datatable_Statistic_of_Project_by_Daily_for_Fee($tableId)
    {
        let $that = $($tableId);
        let $datatable_wrapper = $that.parents('.datatable-wrapper');
        let $tableSearch = $datatable_wrapper.find('.datatable-search-box');

        $($tableId).DataTable({

            // "aLengthMenu": [[20, 50, 200, 500, -1], ["20", "50", "200", "500", "全部"]],
            "aLengthMenu": [[-1], ["全部"]],
            "processing": true,
            "serverSide": true,
            "searching": false,
            "pagingType": "simple_numbers",
            "sDom": '<t>',
            "order": [],
            "orderCellsTop": true,
            "ajax": {
                'url': "{{ url('/v1/operate/statistic/statistic-project-by-daily-for-fee') }}",
                "type": 'POST',
                "dataType" : 'json',
                "data": function (d) {
                    d._token = $('meta[name="_token"]').attr('content');
                    d.project_id = $tableSearch.find('input[name="statistic-project-by-daily-project-id"]').val();
                    d.id = $tableSearch.find('input[name="statistic-project-by-daily-id"]').val();
                    d.name = $tableSearch.find('input[name="statistic-project-by-daily-name"]').val();
                    d.title = $tableSearch.find('input[name="statistic-project-by-daily-title"]').val();
                    d.keyword = $tableSearch.find('input[name="statistic-project-by-daily-keyword"]').val();
                    d.status = $tableSearch.find('select[name="statistic-project-by-daily-status"]').val();
                    d.time_type = $tableSearch.find('input[name="statistic-project-by-daily-time-type"]').val();
                    d.time_month = $tableSearch.find('input[name="statistic-project-by-daily-month"]').val();
                    d.time_date = $tableSearch.find('input[name="statistic-project-by-daily-date"]').val();
                    d.date_start = $tableSearch.find('input[name="statistic-project-by-daily-start"]').val();
                    d.date_ended = $tableSearch.find('input[name="statistic-project-by-daily-ended"]').val();
                },
            },
            // "fixedColumns": {
            {{--"leftColumns": "@if($is_mobile_equipment) 1 @else 3 @endif",--}}
            {{--"rightColumns": "@if($is_mobile_equipment) 0 @else 1 @endif"--}}
            // },
            "columns": [
                {
                    "title": "ID",
                    "data": "id",
                    "className": "",
                    "width": "60px",
                    "orderable": true,
                    "orderSequence": ["desc", "asc"],
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(row.id == '总计' || row.id == '统计')
                        {
                            $(nTd).addClass('_total');
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
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(row.id == '总计' || row.id == '统计')
                        {
                            $(nTd).addClass('_total');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(row.id == '总计' || row.id == '统计')
                        {
                            return '--';
                        }

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
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(row.id == '总计' || row.id == '统计')
                        {
                            $(nTd).addClass('_total');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(row.id == '总计' || row.id == '统计')
                        {
                            return '--';
                        }

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
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(row.id == '总计' || row.id == '统计')
                        {
                            $(nTd).addClass('_total');
                        }
                    },
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
                        if(row.id == '总计' || row.id == '统计')
                        {
                            $(nTd).addClass('_total');
                        }
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
                        if(row.id == '总计' || row.id == '统计')
                        {
                            $(nTd).addClass('_total');
                        }
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
                        if(row.id == '总计' || row.id == '统计')
                        {
                            $(nTd).addClass('_total');
                        }
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
                        if(row.id == '总计' || row.id == '统计')
                        {
                            $(nTd).addClass('_total');
                        }
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
                        if(row.id == '总计' || row.id == '统计')
                        {
                            $(nTd).addClass('_total');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(row.id == '总计' || row.id == '统计')
                        {
                            return '--';
                        }
                        else
                        {
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
                    }
                },
                {
                    "title": "车辆",
                    "data": "car_id",
                    "className": "",
                    "width": "100px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(row.id == '总计' || row.id == '统计')
                        {
                            $(nTd).addClass('_total');
                        }
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
                        if(row.id == '总计' || row.id == '统计')
                        {
                            $(nTd).addClass('_total');
                        }
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
                        if(row.id == '总计' || row.id == '统计')
                        {
                            $(nTd).addClass('_total');
                        }
                    },
                    render: function(data, type, row, meta) {
                        return data;
                        // if(data) return '<small class="btn-xs bg-yellow">查看</small>';
                        // else return '';
                    }
                },
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