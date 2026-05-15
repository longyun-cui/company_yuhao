<script>
    function Datatable__for__Department_List($tableId)
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
            "sDom": '<"dataTables_length_box"l> <"dataTables_info_box"i> <"dataTables_paginate_box"p> <t>',
            "order": [],
            "orderCellsTop": true,
            "scrollX": true,
//                "scrollY": true,
            "scrollCollapse": true,
            "ajax": {
                'url': "<?php echo nl2br(e(url('/o1/department/department-list/datatable-query'))); ?>",
                "type": 'POST',
                "dataType" : 'json',
                "data": function (d) {
                    d._token = $('meta[name="_token"]').attr('content');
                    d.id = $tableSearch.find('input[name="department-id"]').val();
                    d.name = $tableSearch.find('input[name="department-name"]').val();
                    d.title = $tableSearch.find('input[name="department-title"]').val();
                    d.keyword = $tableSearch.find('input[name="department-keyword"]').val();
                    d.item_status = $tableSearch.find('select[name="department-status"]').val();
                    d.department_category = $tableSearch.find('select[name="department-category"]').val();
                    d.department_type = $tableSearch.find('select[name="department-type"]').val();
                    d.department_work_status = $tableSearch.find('select[name="department-work-status"]').val();
                },
            },
            "fixedColumns": {
                "leftColumns": "<?php if($is_mobile_equipment): ?> 1 <?php else: ?> 1 <?php endif; ?>",
                "rightColumns": "0"
            },
            "columns": [
                {
                    "title": '<input type="checkbox" class="check-review-all">',
                    "width": "60px",
                    "data": "id",
                    "orderable": false,
                    render: function(data, type, row, meta) {
                        return '<label><input type="checkbox" name="bulk-id" class="minimal" value="'+data+'"></label>';
                    }
                },
//                    {
//                        "width": "40px",
//                        "title": "序号",
//                        "data": null,
//                        "targets": 0,
//                        'orderable': false
//                    },
                {
                    "title": "ID",
                    "data": "id",
                    "className": "",
                    "width": "60px",
                    "orderable": true,
                    "orderSequence": ["desc", "asc"],
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(row.is_completed != 1 && row.item_status != 97)
                        {
                            $(nTd).addClass('modal-show-for-attachment-');
                            $(nTd).attr('data-id',row.id).attr('data-name','附件');
                            $(nTd).attr('data-key','attachment_list').attr('data-value','');
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
                    "data": "item_status",
                    "width": "80px",
                    "orderable": false,
                    render: function(data, type, row, meta) {
                        if(row.deleted_at != null)
                        {
                            return '<i class="fa fa-times-circle text-black"></i> 已删除';
                        }

                        if(data == 1)
                        {
                            return '<i class="fa fa-circle-o text-green"></i> 正常';
                        }
                        else
                        {
                            return '<i class="fa fa-ban text-red"></i> 禁用';
                        }
                    }
                },
                {
                    "title": "类型",
                    "data": 'department_category',
                    "width": "80px",
                    "orderable": false,
                    render: function(data, type, row, meta) {
                        if(data == 0) return "未选择";
                        else if(data == 1) return '<small class="btn-xs bg-black">BOSS</small>';
                        else if(data == 11) return '<i class="fa fa-star text-red"></i> 人事部';
                        else if(data == 21) return '<i class="fa fa-star text-orange"></i> 行政部';
                        else if(data == 31) return '<i class="fa fa-star text-green"></i> 财务部';
                        else if(data == 81) return '<i class="fa fa-star text-blue"></i> 业务部';
                        else return "有误";
                    }
                },
                {
                    "title": "名称",
                    "data": "name",
                    "className": "",
                    "width":"120px",
                    "orderable": false,
                    render: function(data, type, row, meta) {
                        return row.name;
                    }
                },
                // {
                //     "title": "负责人",
                //     "data": "leader_id",
                //     "className": "text-center",
                //     "width": "160px",
                //     "orderable": false,
                //     "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                //         if(row.is_completed != 1 && row.item_status != 97)
                //         {
                //             $(nTd).addClass('modal-show-for-info-select2-set');
                //             $(nTd).attr('data-id',row.id).attr('data-name','负责人');
                //             $(nTd).attr('data-key','leader_id').attr('data-value',data);
                //             if(row.leader == null) $(nTd).attr('data-option-name','未指定');
                //             else {
                //                 $(nTd).attr('data-option-name',row.leader.name);
                //             }
                //             $(nTd).attr('data-column-name','负责人');
                //             if(row.leader_id) $(nTd).attr('data-operate-type','edit');
                //             else $(nTd).attr('data-operate-type','add');
                //
                //             if(row.department_type == 11)
                //             {
                //                 $(nTd).attr('data-department-type','manager');
                //             }
                //             else if(row.department_type == 21)
                //             {
                //                 $(nTd).attr('data-department-type','supervisor');
                //             }
                //
                //         }
                //     },
                //     render: function(data, type, row, meta) {
                //         if(row.leader == null) return '--';
                //         else return '<a href="javascript:void(0);">'+row.leader.name+' ('+row.leader.id+')'+'</a>';
                //     }
                // },
                {
                    "title": "所属公司",
                    "data": 'company_id',
                    "width": "160px",
                    "orderable": false,
                    render: function(data, type, row, meta) {
                        if(row.company_er == null) return '--';
                        else return '<a href="javascript:void(0);" class="text-black">' + row.company_er.name + '</a>';
                    }
                },
                {
                    "title": "备注",
                    "data": "remark",
                    "className": "text-center",
                    "width": "",
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
                    "className": "text-center",
                    "width": "80px",
                    "title": "创建者",
                    "data": "creator_id",
                    "orderable": false,
                    render: function(data, type, row, meta) {
                        return row.creator == null ? '未知' : row.creator.name;
                    }
                },
                {
                    "className": "font-12px",
                    "width": "120px",
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
                        if($year == $currentYear) return $month+'-'+$day+'&nbsp;&nbsp;'+$hour+':'+$minute;
                        else return $year+'-'+$month+'-'+$day+'&nbsp;&nbsp;'+$hour+':'+$minute;
                    }
                },
                {
                    "className": "font-12px",
                    "width": "120px",
                    "title": "更新时间",
                    "data": 'updated_at',
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
                        if($year == $currentYear) return $month+'-'+$day+'&nbsp;&nbsp;'+$hour+':'+$minute;
                        else return $year+'-'+$month+'-'+$day+'&nbsp;&nbsp;'+$hour+':'+$minute;
                    }
                },
                {
                    "title": "操作",
                    "data": 'id',
                    "width": "160px",
                    "orderable": false,
                    render: function(data, type, row, meta) {

                        var $html_edit = '';
                        var $html_detail = '';
                        var $html_able = '';
                        var $html_delete = '';
                        var $html_operation_record = '<a class="btn btn-xs bg-default modal-show--for--department--item-operation-record" data-id="'+data+'">记录</a>';

                        if(row.item_status == 1)
                        {
                            $html_able = '<a class="btn btn-xs department--item-disable-submit" data-id="'+data+'">禁用</a>';
                        }
                        else
                        {
                            $html_able = '<a class="btn btn-xs department--item-enable-submit" data-id="'+data+'">启用</a>';
                        }

                        if(row.deleted_at == null)
                        {
                            $html_delete = '<a class="btn btn-xs department--item-delete-submit" data-id="'+data+'">删除</a>';
                        }
                        else
                        {
                            $html_delete = '<a class="btn btn-xs department--item-restore-submit" data-id="'+data+'">恢复</a>';
                        }

                        var html =
                            '<a class="btn btn-xs modal-show--for--department-item-edit" data-id="'+data+'">编辑</a>'+
                            $html_able+
                            $html_delete+
                            $html_operation_record+
                            // '<a class="btn btn-xs department--item-statistic" data-id="'+data+'">统计</a>'+
                            // '<a class="btn btn-xs department--item-login-submit" data-id="'+data+'">登录</a>'+
                            '';
                        return html;

                    }
                },
            ],
            "drawCallback": function (settings) {

                console.log('department-list.datatable-query.execute');

//                    let startIndex = this.api().context[0]._iDisplayStart;//获取本页开始的条数
//                    this.api().column(1).nodes().each(function(cell, i) {
//                        cell.innerHTML =  startIndex + i + 1;
//                    });

            },
            "language": { url: '/common/dataTableI18n' },
        });

    }
</script>