<script>

    function Datatable_Statistic_of_Car_by_Daily_for_Order($tableId, $eChartId)
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
                'url': "{{ url('/v1/operate/statistic/statistic-car-by-daily-for-order') }}",
                "type": 'POST',
                "dataType" : 'json',
                "data": function (d) {
                    d._token = $('meta[name="_token"]').attr('content');
                    d.car_id = $tableSearch.find('input[name="statistic-car-by-daily-car-id"]').val();
                    d.id = $tableSearch.find('input[name="statistic-car-by-daily-id"]').val();
                    d.name = $tableSearch.find('input[name="statistic-car-by-daily-name"]').val();
                    d.title = $tableSearch.find('input[name="statistic-car-by-daily-title"]').val();
                    d.keyword = $tableSearch.find('input[name="statistic-car-by-daily-keyword"]').val();
                    d.status = $tableSearch.find('select[name="statistic-car-by-daily-status"]').val();
                    d.time_type = $tableSearch.find('input[name="statistic-car-by-daily-time-type"]').val();
                    d.time_month = $tableSearch.find('input[name="statistic-car-by-daily-month"]').val();
                    d.time_date = $tableSearch.find('input[name="statistic-car-by-daily-date"]').val();
                    d.date_start = $tableSearch.find('input[name="statistic-car-by-daily-start"]').val();
                    d.date_ended = $tableSearch.find('input[name="statistic-car-by-daily-ended"]').val();
                },
            },
            // "fixedColumns": {
            {{--"leftColumns": "@if($is_mobile_equipment) 1 @else 3 @endif",--}}
            {{--"rightColumns": "@if($is_mobile_equipment) 0 @else 1 @endif"--}}
            // },
            "columns": [
                {
                    "title": "工单ID",
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
                // {
                //     "title": "工单",
                //     "data": "id",
                //     "className": "",
                //     "width": "80px",
                //     "orderable": false,
                //     render: function(data, type, row, meta) {
                //         if(row.id)
                //         {
                //             var $order_info = '';
                //             $order_info += '(' + row.id + ')';
                //             $order_info += ' ' + row.task_date;
                //             $order_info += ' - ' + row.transport_departure_place;
                //             $order_info += ' - ' + row.transport_destination_place;
                //             return '<a href="javascript:void(0);" class="text-black">' + $order_info + '</a>';
                //         }
                //         else return '未指定';
                //     }
                // },
                {
                    "title": "任务日期",
                    "data": 'task_date',
                    "className": "",
                    "width": "100px",
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
                        // if(!data) return '';
                        //
                        // var $date = new Date(data*1000);
                        // var $year = $date.getFullYear();
                        // var $month = ('00'+($date.getMonth()+1)).slice(-2);
                        // var $day = ('00'+($date.getDate())).slice(-2);
                        // var $hour = ('00'+$date.getHours()).slice(-2);
                        // var $minute = ('00'+$date.getMinutes()).slice(-2);
                        // var $second = ('00'+$date.getSeconds()).slice(-2);
                        //
                        // var $currentYear = new Date().getFullYear();
                        // if($year == $currentYear) return $month+'-'+$day;
                        // else return $year+'-'+$month+'-'+$day;
                    }
                },
                {
                    "title": "客户",
                    "data": "client_id",
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
                    "width": "120px",
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
                    "title": "驾驶员",
                    "data": "driver_id",
                    "className": "",
                    "width": "160px",
                    "orderable": false,
                    "visible" : true,
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
                            var $driver_text = '';
                            var $copilot_text = '';

                            if(row.driver_name)
                            {
                                $driver_text = row.driver_name + ' (' +  row.driver_phone + ') <br>';
                            }
                            if(row.copilot_name)
                            {
                                $copilot_text = row.copilot_name + ' (' +  row.copilot_phone + ')';
                            }
                            return $driver_text + $copilot_text;


                            // if(data) return data;
                            // if(row.car_owner_type == 1 || row.car_owner_type == 11 || row.car_owner_type == 41)
                            // {
                            //     if(row.car_er != null) return row.car_er.linkman_name;
                            //     else return data;
                            // }
                            // else return data;
                        }
                    }
                },
                {
                    "title": "出发地",
                    "data": "transport_departure_place",
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
                        return data == null ? '--' : data;
                    }
                },
                {
                    "title": "目的地",
                    "data": "transport_destination_place",
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
                        return data == null ? '--' : data;
                    }
                },
                {
                    "title": "里程",
                    "data": "transport_mileage",
                    "className": "bg-route",
                    "width": "60px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(row.id == '总计' || row.id == '统计')
                        {
                            $(nTd).addClass('_total');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(!data) return '';
                        else return parseFloat(data);
                    }
                },
                {
                    "title": "时效",
                    "data": "transport_time_limitation",
                    "className": "",
                    "width": "60px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(row.id == '总计' || row.id == '统计')
                        {
                            $(nTd).addClass('_total');
                        }
                    },
                    render: function(data, type, row, meta) {
                        return parseFloat(data);
                        // if(!data) return '';
                        // else return parseFloat(data);
                    }
                },
                {
                    "title": "运费",
                    "data": "freight_amount",
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
                        return parseFloat(data);
                        // if(data) return parseFloat(data);
                        // else return '';
                    }
                },
                {
                    "title": "费用",
                    "data": "financial_expense_total",
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
                        return parseFloat(data);
                        // if(data) return parseFloat(data);
                        // else return '';
                    }
                },
                {
                    "title": "订单扣款",
                    "data": "financial_deduction_total",
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
                        return parseFloat(data);
                        // if(data) return parseFloat(data);
                        // else return '';
                    }
                },
                // {
                //     "title": "罚款",
                //     "data": "fee_sum_for_fine",
                //     "className": "",
                //     "width": "80px",
                //     "orderable": false,
                //     "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                //         if(row.id == '总计' || row.id == '统计')
                //         {
                //             $(nTd).addClass('_total');
                //         }
                //     },
                //     render: function(data, type, row, meta) {
                //         if(data) return parseFloat(data);
                //         else return '';
                //     }
                // },
                {
                    "title": "利润",
                    "data": "id",
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
                        if(row.minutes_per) return row.minutes_per;
                        else return '';
                    }
                },
            ],
            "drawCallback": function (settings) {

//                    let startIndex = this.api().context[0]._iDisplayStart;//获取本页开始的条数
//                    this.api().column(1).nodes().each(function(cell, i) {
//                        cell.innerHTML =  startIndex + i + 1;
//                    });
                // 每日交付量
                var $res_total = new Array();
                var $res_accepted = new Array();
                var $res_effective = new Array();
                var $res_delivery_all = new Array();
                var $res_delivery_distributed = new Array();

                this.api().rows().every(function() {
                    var $rowData = this.data();
                    $res_total[($rowData.day - 1)] = { value:$rowData.order_count_for_all, name:$rowData.day };
                    $res_accepted[($rowData.day - 1)] = { value:$rowData.order_count_for_accepted, name:$rowData.day };
                    $res_effective[($rowData.day - 1)] = { value:$rowData.order_count_for_effective, name:$rowData.day };
                    $res_delivery_all[($rowData.day - 1)] = { value:$rowData.delivery_count_for_all, name:$rowData.day };
                    $res_delivery_distributed[($rowData.day - 1)] = { value:$rowData.delivery_count_for_distributed, name:$rowData.day };
                });

                var $option_statistics = {
                    title: {
                        text: '每日交付量'
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
                        data:['提单量','通过量','有效量','交付量','分发量']
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
                        {
                            name:'提单量',
                            type:'line',
                            label: {
                                normal: {
                                    show: true,
                                    position: 'top'
                                }
                            },
                            itemStyle : { normal: { label : { show: true } } },
                            data: $res_total
                        },
                        {
                            name:'通过量',
                            type:'line',
                            label: {
                                normal: {
                                    show: true,
                                    position: 'top'
                                }
                            },
                            itemStyle : { normal: { label : { show: true } } },
                            data: $res_accepted
                        },
                        {
                            name:'有效量',
                            type:'line',
                            label: {
                                normal: {
                                    show: true,
                                    position: 'top'
                                }
                            },
                            itemStyle : { normal: { label : { show: true } } },
                            data: $res_effective
                        },
                        {
                            name:'交付量',
                            type:'line',
                            label: {
                                normal: {
                                    show: true,
                                    position: 'top'
                                }
                            },
                            itemStyle : { normal: { label : { show: true } } },
                            data: $res_delivery_all
                        },
                        {
                            name:'分发量',
                            type:'line',
                            label: {
                                normal: {
                                    show: true,
                                    position: 'top'
                                }
                            },
                            itemStyle : { normal: { label : { show: true } } },
                            data: $res_delivery_distributed
                        }
                    ]
                };
                console.log($('#tab-pane-width').width());
                // var $myChart_statistics = echarts.init(document.getElementById($eChartId));
                var $myChart_statistics = echarts.init(document.getElementById($eChartId), null, {
                    width: $('#tab-pane-width').width(),   // 最高优先级
                    height: 320
                });
                // $myChart_statistics.setOption($option_statistics);

            },
            "columnDefs": [
            ],
            "language": { url: '/common/dataTableI18n' },
        });
    }

</script>