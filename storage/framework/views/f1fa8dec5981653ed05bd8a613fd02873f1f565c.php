<script>
    function Datatable_Statistic__Car__Task_Amount($tableId)
    {
        let $that = $($tableId);
        let $datatable_wrapper = $that.parents('.datatable-wrapper');
        let $tableSearch = $datatable_wrapper.find('.datatable-search-box');

        $($tableId).DataTable({
            "aLengthMenu": [[100, 100, 200, 500], ["100", "100", "200", "500"]],
            "processing": true,
            "serverSide": false,
            "searching": false,
            "pagingType": "simple_numbers",
            "sDom": '<"dataTables_length_box"l> <"dataTables_info_box"i> <"dataTables_paginate_box"p> <t>',
            "order": [],
            "orderCellsTop": true,
            "scrollX": true,
//                "scrollY": true,
            "scrollY": ($(document).height() - 298)+"px",
            "scrollCollapse": true,
            "ajax": {
                'url': "<?php echo nl2br(e(url('/o1/car/statistic/statistic-task-amount'))); ?>",
                "type": 'POST',
                "dataType" : 'json',
                "data": function (d) {
                    d._token = $('meta[name="_token"]').attr('content');
                    d.id = $tableSearch.find('input[name="car-id"]').val();
                    d.name = $tableSearch.find('input[name="car-name"]').val();
                    d.car_category = $tableSearch.find('select[name="car-category"]').val();
                    d.car_type = $tableSearch.find('select[name="car-type"]').val();
                    d.item_status = $tableSearch.find('select[name="car-item-status"]').val();
                    d.time_type = $tableSearch.find('input[name="car--statistic--task-amount-time-type"]').val();
                    d.time_month = $tableSearch.find('input[name="car--statistic--task-amount-month"]').val();
                    d.time_date = $tableSearch.find('input[name="car--statistic--task-amount-date"]').val();
                    d.date_start = $tableSearch.find('input[name="car--statistic--task-amount-start"]').val();
                    d.date_ended = $tableSearch.find('input[name="car--statistic--task-amount-ended"]').val();
                },
            },
            "fixedColumns": {
                "leftColumns": "<?php if($is_mobile_equipment): ?> 1 <?php else: ?> 2 <?php endif; ?>",
                "rightColumns": "0"
            },
            "columns": [
                // {
                //     "title": '<input type="checkbox" class="check-review-all">',
                //     "data": "id",
                //     "width": "60px",
                //     "orderable": false,
                //     render: function(data, type, row, meta) {
                //         return '<label><input type="checkbox" name="bulk-id" class="minimal" value="'+data+'"></label>';
                //     }
                // },
                {
                    "title": "ID",
                    "data": "id",
                    "className": "",
                    "width": "50px",
                    "orderable": true,
                    "orderSequence": ["desc", "asc"],
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(row.id == "统计")
                        {
                            $(nTd).addClass('_bold');
                        }
                    },
                    render: function(data, type, row, meta) {
                        return data;
                    }
                },
                {
                    "title": "类型",
                    "data": 'car_category',
                    "width": "60px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(row.id == "统计")
                        {
                            $(nTd).addClass('_bold');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(row.id == "统计") return '统计';
                        if(data == 1)
                        {
                            if(row.car_type == 1) return '<i class="fa fa-circle text-green"></i> 车';
                            else if(row.car_type == 11) return '<i class="fa fa-circle text-blue"></i> 车头';
                            else return '<i class="fa fa-circle-o text-blue"></i> 车';
                        }
                        else if(data == 21) return '<i class="fa fa-circle-o text-purple"></i> 挂';
                        else return "有误";
                    }
                },
                {
                    "title": "车编号",
                    "data": "car_number",
                    "className": "text-center",
                    "width": "60px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(row.id == "统计")
                        {
                            $(nTd).addClass('_bold');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(row.id == "统计") return '统计';
                        if(!data) return '--';
                        else return data;
                    }
                },
                {
                    "title": "车牌号",
                    "data": "name",
                    "className": "text-center",
                    "width": "200px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(row.id == "统计")
                        {
                            $(nTd).addClass('_bold');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(row.id == "统计") return '统计';
                        var $name = data;
                        if(row.pre_name) $name = data + ' (' + row.pre_name + ')';
                        return '<a class="car-control" data-id="'+row.id+'" data-title="'+data+'">'+$name+'</a>';
                    }
                },
                {
                    "title": "订单数",
                    "data": "order_count",
                    "className": "text-center",
                    "width": "80px",
                    "orderable": true,
                    "orderSequence": ["desc", "asc"],
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        $(nTd).addClass('color-red');
                        $(nTd).addClass('_bold');
                    },
                    render: function(data, type, row, meta) {
                        if (type === 'display')
                        {
                            // 显示时返回格式化字符串
                            if(!data) return '--';
                            return data;
                        }
                        else if (type === 'sort')
                        {
                            // 排序时返回数值
                            return data;
                        }
                        else
                        {
                            // 过滤等其他操作使用原始值
                            return data;
                        }
                    }
                },
                {
                    "title": "车型",
                    "data": "car_info_type",
                    "className": "text-center",
                    "width": "60px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(row.id == "统计")
                        {
                            $(nTd).addClass('_bold');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(row.id == "统计") return '统计';
                        if(!data) return '--';
                        else return data;
                    }
                },
                {
                    "title": "默认主驾",
                    "data": "driver_id",
                    "className": "text-center",
                    "width": "150px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(row.id == "统计")
                        {
                            $(nTd).addClass('_bold');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(row.id == "统计") return '统计';
                        if(row.driver_er == null) return '--';
                        else
                        {
                            var $driver_html = '';
                            var $copilot_html = '';

                            $driver_html = '<a href="javascript:void(0);">'+row.driver_er.driver_name+' '+row.driver_er.driver_phone+'</a>';
                            if(row.driver_er.copilot_name)
                            {
                                $copilot_html = '<a href="javascript:void(0);">'+row.driver_er.copilot_name+' '+row.driver_er.copilot_phone+'</a>';
                            }
                            // if(row.copilot_er != null)
                            // {
                            //     $copilot_html = '<a href="javascript:void(0);">'+row.copilot_er.driver_name+' '+row.copilot_er.driver_phone+'</a>';
                            // }
                            return $driver_html+'<br>'+$copilot_html;
                        }
                    }
                },
                {
                    "title": "备注",
                    "data": "description",
                    "className": "text-center",
                    "width": "",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                    },
                    render: function(data, type, row, meta) {
                        return data;
                        // if(data) return '<small class="btn-xs bg-yellow">查看</small>';
                        // else return '';
                    }
                },
            ],
            "drawCallback": function (settings) {

                console.log('car-list.datatable-query.execute');

//                    let startIndex = this.api().context[0]._iDisplayStart;//获取本页开始的条数
//                    this.api().column(1).nodes().each(function(cell, i) {
//                        cell.innerHTML =  startIndex + i + 1;
//                    });

            },
            "language": { url: '/common/dataTableI18n' },
        });

    }
</script>