<script>
    function Datatable_Statistic__Car__Task_Recent($tableId)
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
                'url': "{{ url('/o1/car/statistic/statistic-task-recent') }}",
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
            {{--"leftColumns": "@if($is_mobile_equipment) 1 @else 3 @endif",--}}
            {{--"rightColumns": "@if($is_mobile_equipment) 0 @else 1 @endif"--}}
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
                    "title": "姓名",
                    "data": "name",
                    "className": "text-center",
                    "width": "160px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        {
                            // this.column(2)
                            $(nTd).addClass('modal-show-for-text');
                            $(nTd).attr('data-id',row.id).attr('data-name','姓名');
                            $(nTd).attr('data-key','username').attr('data-value',data);
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        return '<a class="caller-control" data-id="'+row.id+'" data-title="'+data+'">'+data+' ('+row.id+')'+'</a>';
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
                            $(nTd).addClass('_bold');
                            $(nTd).addClass('text-red');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(!data) return '';
                        return data;
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
                            $(nTd).addClass('_bold');
                            $(nTd).addClass('text-red');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(!data) return '';
                        return data;
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
                            $(nTd).addClass('text-red');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(!data) return '';
                        return data;
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
                            $(nTd).addClass('_bold');
                            $(nTd).addClass('text-red');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(!data) return '';
                        return data;
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
                            $(nTd).addClass('_bold');
                            $(nTd).addClass('text-red');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(!data) return '';
                        return data;
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
                            $(nTd).addClass('_bold');
                            $(nTd).addClass('text-red');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(!data) return '';
                        return data;
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
                            $(nTd).addClass('_bold');
                            $(nTd).addClass('text-red');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(!data) return '';
                        return data;
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
                            $(nTd).addClass('_bold');
                            $(nTd).addClass('text-red');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(!data) return '';
                        return data;
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
                            $(nTd).addClass('_bold');
                            $(nTd).addClass('text-red');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(!data) return '';
                        return data;
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
                            $(nTd).addClass('_bold');
                            $(nTd).addClass('text-red');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(!data) return '';
                        return data;
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
                            $(nTd).addClass('_bold');
                            $(nTd).addClass('text-red');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(!data) return '';
                        return data;
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
                            $(nTd).addClass('_bold');
                            $(nTd).addClass('text-red');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(!data) return '';
                        return data;
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
                            $(nTd).addClass('_bold');
                            $(nTd).addClass('text-red');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(!data) return '';
                        return data;
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
                            $(nTd).addClass('_bold');
                            $(nTd).addClass('text-red');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(!data) return '';
                        return data;
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
                            $(nTd).addClass('_bold');
                            $(nTd).addClass('text-red');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(!data) return '';
                        return data;
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
                            $(nTd).addClass('_bold');
                            $(nTd).addClass('text-red');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(!data) return '';
                        return data;
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
                            $(nTd).addClass('_bold');
                            $(nTd).addClass('text-red');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(!data) return '';
                        return data;
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