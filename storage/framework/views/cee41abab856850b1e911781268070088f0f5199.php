<script>
    function Datatable_Statistic__Car__External_Task_Recent($tableId)
    {
        let $that = $($tableId);
        let $datatable_wrapper = $that.parents('.datatable-wrapper');
        let $tableSearch = $datatable_wrapper.find('.datatable-search-box');

        $($tableId).DataTable({

            // "aLengthMenu": [[20, 50, 200, 500, -1], ["20", "50", "200", "500", "全部"]],
            "aLengthMenu": [[-1], ["全部"]],
            "processing": true,
            "serverSide": false,
            "searching": false,
            "pagingType": "simple_numbers",
            "sDom": '<t>',
            "order": [],
            "orderCellsTop": true,
            "scrollX": true,
//                "scrollY": true,
            "scrollY": ($(document).height() - 298)+"px",
            "scrollCollapse": true,
            "showRefresh": true,
            "ajax": {
                'url': "<?php echo nl2br(e(url('/o1/car/statistic/statistic-external-task-recent'))); ?>",
                "type": 'POST',
                "dataType" : 'json',
                "data": function (d) {
                    d._token = $('meta[name="_token"]').attr('content');
                    d.id = $tableSearch.find('input[name="caller-recent-id"]').val();
                    d.name = $tableSearch.find('input[name="caller-recent-name"]').val();
                    d.title = $tableSearch.find('input[name="caller-recent-title"]').val();
                    d.keyword = $tableSearch.find('input[name="caller-recent-keyword"]').val();
                    d.status = $tableSearch.find('select[name="caller-recent-status"]').val();
                    d.time_type = $tableSearch.find('input[name="car--statistic--task-recent-time-type"]').val();
                    d.time_month = $tableSearch.find('input[name="car--statistic--task-recent-month"]').val();
                    d.time_date = $tableSearch.find('input[name="car--statistic--task-recent-date"]').val();
                    d.date_start = $tableSearch.find('input[name="car--statistic--task-recent-start"]').val();
                    d.date_ended = $tableSearch.find('input[name="car--statistic--task-recent-ended"]').val();
                    d.project = $tableSearch.find('input[name="statistic-caller--project"]').val();
                    d.team = $tableSearch.find('select[name="car--statistic--task-recent--team"]').val();
                    d.group = $tableSearch.find('select[name="car--statistic--task-recent--group"]').val();

                },
            },
            // "fixedColumns": {
            
            
            // },
            "columns": [
//                    {
//                        "title": "选择",
//                        "data": "id",
//                        "width": "32px",
//                        "orderable": false,
//                        render: function(data, type, row, meta) {
//                            return '<label><input type="checkbox" name="bulk-id" class="minimal" value="'+data+'"></label>';
//                        }
//                    },
//                    {
//                        "title": "序号",
//                        "data": null,
//                        "width": "32px",
//                        "targets": 0,
//                        "orderable": false
//                    },
                {
                    "title": "外请车",
                    "data": "external_car",
                    "className": "text-center",
                    "width": "160px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        {
                            // this.column(2)
                            $(nTd).addClass('modal-show-for-text');
                            $(nTd).attr('data-id',row.id).attr('data-name','外请车');
                            $(nTd).attr('data-key','username').attr('data-value',data);
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        return data;
                    }
                },
                // {
                //     "title": "部门",
                //     "data": "id",
                //     "className": "text-center",
                //     "width": "120px",
                //     "orderable": false,
                //     "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                //     },
                //     render: function(data, type, row, meta) {
                //         var $team_name = row.team_er == null ? '' : row.team_er.name;
                //         var $group_name = row.team_group_er == null ? '' : (' - ' + row.team_group_er.name);
                //         return $team_name + $group_name;
                //
                //     }
                // },
                {
                    "title": getDateOffset(2)+"<br>(后天)",
                    "data": "order_b",
                    "className": "bg-delivered",
                    "width": "80px",
                    "orderable": true,
                    "orderSequence": ["desc", "asc"],
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(data)
                        {
                            $(nTd).addClass('_bold-');
                            $(nTd).addClass('text-red-');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(!data) return '';
                        // return data;
                        return row.departure_b + '<br> ↓ <br>' + row.destination_b;
                    }
                },
                {
                    "title": getDateOffset(1)+"<br>(明天)",
                    "data": "order_a",
                    "className": "bg-delivered",
                    "width": "80px",
                    "orderable": true,
                    "orderSequence": ["desc", "asc"],
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(data)
                        {
                            $(nTd).addClass('_bold-');
                            $(nTd).addClass('text-red-');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(!data) return '';
                        // return data;
                        return row.departure_a + '<br> ↓ <br>' + row.destination_a;
                    }
                },
                {
                    "title": getDateOffset(0)+"<br>(今天)",
                    "data": "order_0",
                    "className": "bg-delivered _bold text-green",
                    "width": "80px",
                    "orderable": true,
                    "orderSequence": ["desc", "asc"],
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(data)
                        {
                            $(nTd).addClass('_bold');
                            $(nTd).addClass('text-red-');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(!data) return '';
                        // return data;
                        return row.departure_0 + '<br> ↓ <br>' + row.destination_0;
                    }
                },
                {
                    "title": getDateOffset(-1)+"<br>(昨天)",
                    "data": "order_1",
                    "className": "bg-delivered",
                    "width": "80px",
                    "orderable": true,
                    "orderSequence": ["desc", "asc"],
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(data)
                        {
                            $(nTd).addClass('_bold-');
                            $(nTd).addClass('text-red-');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(!data) return '';
                        // return data;
                        return row.departure_1 + '<br> ↓ <br>' + row.destination_1;
                    }
                },
                {
                    "title": getDateOffset(-2)+"<br>(前天)",
                    "data": "order_2",
                    "className": "bg-delivered",
                    "width": "80px",
                    "orderable": true,
                    "orderSequence": ["desc", "asc"],
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(data)
                        {
                            $(nTd).addClass('_bold-');
                            $(nTd).addClass('text-red-');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(!data) return '';
                        // return data;
                        return row.departure_2 + '<br> ↓ <br>' + row.destination_2;
                    }
                },
                {
                    "title": getDateOffset(-3),
                    "data": "order_3",
                    "className": "bg-delivered",
                    "width": "80px",
                    "orderable": true,
                    "orderSequence": ["desc", "asc"],
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(data)
                        {
                            $(nTd).addClass('_bold-');
                            $(nTd).addClass('text-red-');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(!data) return '';
                        // return data;
                        return row.departure_3 + '<br> ↓ <br>' + row.destination_3;
                    }
                },
                {
                    "title": getDateOffset(-4),
                    "data": "order_4",
                    "className": "bg-delivered",
                    "width": "80px",
                    "orderable": true,
                    "orderSequence": ["desc", "asc"],
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(data)
                        {
                            $(nTd).addClass('_bold-');
                            $(nTd).addClass('text-red-');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(!data) return '';
                        // return data;
                        return row.departure_4 + '<br> ↓ <br>' + row.destination_4;
                    }
                },
                {
                    "title": getDateOffset(-5),
                    "data": "order_5",
                    "className": "bg-delivered",
                    "width": "80px",
                    "orderable": true,
                    "orderSequence": ["desc", "asc"],
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(data)
                        {
                            $(nTd).addClass('_bold-');
                            $(nTd).addClass('text-red-');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(!data) return '';
                        // return data;
                        return row.departure_5 + '<br> ↓ <br>' + row.destination_5;
                    }
                },
                {
                    "title": getDateOffset(-6),
                    "data": "order_6",
                    "className": "bg-delivered",
                    "width": "80px",
                    "orderable": true,
                    "orderSequence": ["desc", "asc"],
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(data)
                        {
                            $(nTd).addClass('_bold-');
                            $(nTd).addClass('text-red-');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(!data) return '';
                        // return data;
                        return row.departure_6 + '<br> ↓ <br>' + row.destination_6;
                    }
                },
                {
                    "title": getDateOffset(-7),
                    "data": "order_7",
                    "className": "bg-delivered",
                    "width": "80px",
                    "orderable": true,
                    "orderSequence": ["desc", "asc"],
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(data)
                        {
                            $(nTd).addClass('_bold-');
                            $(nTd).addClass('text-red-');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(!data) return '';
                        // return data;
                        return row.departure_7 + '<br> ↓ <br>' + row.destination_7;
                    }
                },
                {
                    "title": getDateOffset(-8),
                    "data": "order_8",
                    "className": "bg-delivered",
                    "width": "80px",
                    "orderable": true,
                    "orderSequence": ["desc", "asc"],
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(data)
                        {
                            $(nTd).addClass('_bold-');
                            $(nTd).addClass('text-red-');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(!data) return '';
                        // return data;
                        return row.departure_8 + '<br> ↓ <br>' + row.destination_8;
                    }
                },
                {
                    "title": getDateOffset(-9),
                    "data": "order_9",
                    "className": "bg-delivered",
                    "width": "80px",
                    "orderable": true,
                    "orderSequence": ["desc", "asc"],
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(data)
                        {
                            $(nTd).addClass('_bold-');
                            $(nTd).addClass('text-red-');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(!data) return '';
                        // return data;
                        return row.departure_9 + '<br> ↓ <br>' + row.destination_9;
                    }
                },
                {
                    "title": getDateOffset(-10),
                    "data": "order_10",
                    "className": "bg-delivered",
                    "width": "80px",
                    "orderable": true,
                    "orderSequence": ["desc", "asc"],
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(data)
                        {
                            $(nTd).addClass('_bold-');
                            $(nTd).addClass('text-red-');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(!data) return '';
                        // return data;
                        return row.departure_10 + '<br> ↓ <br>' + row.destination_10;
                    }
                },
                {
                    "title": getDateOffset(-11),
                    "data": "order_11",
                    "className": "bg-delivered",
                    "width": "80px",
                    "orderable": true,
                    "orderSequence": ["desc", "asc"],
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(data)
                        {
                            $(nTd).addClass('_bold-');
                            $(nTd).addClass('text-red-');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(!data) return '';
                        // return data;
                        return row.departure_11 + '<br> ↓ <br>' + row.destination_11;
                    }
                },
                {
                    "title": getDateOffset(-12),
                    "data": "order_12",
                    "className": "bg-delivered",
                    "width": "80px",
                    "orderable": true,
                    "orderSequence": ["desc", "asc"],
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(data)
                        {
                            $(nTd).addClass('_bold-');
                            $(nTd).addClass('text-red-');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(!data) return '';
                        // return data;
                        return row.departure_12 + '<br> ↓ <br>' + row.destination_12;
                    }
                },
                {
                    "title": getDateOffset(-13),
                    "data": "order_13",
                    "className": "bg-delivered",
                    "width": "80px",
                    "orderable": true,
                    "orderSequence": ["desc", "asc"],
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(data)
                        {
                            $(nTd).addClass('_bold-');
                            $(nTd).addClass('text-red-');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(!data) return '';
                        // return data;
                        return row.departure_13 + '<br> ↓ <br>' + row.destination_13;
                    }
                },
                {
                    "title": getDateOffset(-14),
                    "data": "order_14",
                    "className": "bg-delivered",
                    "width": "80px",
                    "orderable": true,
                    "orderSequence": ["desc", "asc"],
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(data)
                        {
                            $(nTd).addClass('_bold-');
                            $(nTd).addClass('text-red-');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(!data) return '';
                        // return data;
                        return row.departure_14 + '<br> ↓ <br>' + row.destination_14;
                    }
                }

            ],
            "columnDefs": [
            ],
            "drawCallback": function (settings) {

//                    let startIndex = this.api().context[0]._iDisplayStart;//获取本页开始的条数
//                    this.api().column(1).nodes().each(function(cell, i) {
//                        cell.innerHTML =  startIndex + i + 1;
//                    });

            },
            "language": { url: '/common/dataTableI18n' },
        });

    }
</script>