<script>
    function Datatable__for__Driver_List($tableId)
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
                'url': "{{ url('/o1/driver/driver-list/datatable-query') }}",
                "type": 'POST',
                "dataType" : 'json',
                "data": function (d) {
                    d._token = $('meta[name="_token"]').attr('content');
                    d.id = $('input[name="driver-id"]').val();
                    d.mobile = $('input[name="driver-mobile"]').val();
                    d.username = $('input[name="driver-username"]').val();
                    d.department_district = $tableSearch.find('select[name="driver-department-district"]').val();
                    d.user_type = $tableSearch.find('select[name="driver-user-type"]').val();
                    d.user_status = $tableSearch.find('select[name="driver-user-status"]').val();
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
                        if(row.is_completed != 1 && row.item_status != 97)
                        {
                            $(nTd).addClass('modal-show-for-attachment');
                            $(nTd).attr('data-id',row.id).attr('data-name',row.driver_name);
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
                    "width": "80px",
                    "data": "item_status",
                    "orderable": false,
                    render: function(data, type, row, meta) {
//                            return data;
                        if(row.deleted_at != null)
                        {
                            return '<i class="fa fa-times-circle text-black"></i> 已删除';
                        }

                        if(row.item_status == 1)
                        {
                            return '<i class="fa fa-circle-o text-green"></i> 正常';
                        }
                        else if(row.item_status == 99)
                        {
                            return '<i class="fa fa-lock text-orange"></i> 锁定';
                        }
                        else
                        {
                            return '<i class="fa fa-ban text-red"></i> 禁用';
                        }
                    }
                },
                {
                    "title": "主驾姓名",
                    "data": "driver_name",
                    "className": "_bold",
                    "width": "80px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(row.user_status != 97)
                        {
                            $(nTd).addClass('modal-show-for-info-text-set');
                            $(nTd).attr('data-id',row.id).attr('data-name','主驾姓名');
                            $(nTd).attr('data-key','driver_name').attr('data-value',data);
                            $(nTd).attr('data-column-name','主驾姓名');
                            $(nTd).attr('data-text-type','text');
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(data)
                        {
                            return '<a class="driver-control" data-id="'+row.id+'" data-title="'+data+'">'+data+'</a>';
                        }
                        else return '--';
                    }
                },
                {
                    "className": "_bold",
                    "width": "100px",
                    "title": "主驾电话",
                    "data": "driver_phone",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(row.user_status != 97)
                        {
                            $(nTd).addClass('modal-show-for-info-text-set');
                            $(nTd).attr('data-id',row.id).attr('data-name','主驾电话');
                            $(nTd).attr('data-key','driver_phone').attr('data-value',data);
                            $(nTd).attr('data-column-name','主驾电话');
                            $(nTd).attr('data-text-type','text');
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(data) return data;
                        else return '--';
                    }
                },
                {
                    "className": "",
                    "width": "120px",
                    "title": "身份证号",
                    "data": "driver_ID",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(row.user_status != 97)
                        {
                            $(nTd).addClass('modal-show-for-info-text-set');
                            $(nTd).attr('data-id',row.id).attr('data-name',row.driver_name);
                            $(nTd).attr('data-key','driver_ID').attr('data-value',data);
                            $(nTd).attr('data-column-name','主驾身份证号');
                            $(nTd).attr('data-text-type','text');
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(data) return data;
                        else return '--';
                    }
                },
                {
                    "className": "",
                    "width": "80px",
                    "title": "主驾职称",
                    "data": "driver_title",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(row.user_status != 97)
                        {
                            $(nTd).addClass('modal-show-for-info-text-set');
                            $(nTd).attr('data-id',row.id).attr('data-name','主驾职称');
                            $(nTd).attr('data-key','driver_title').attr('data-value',data);
                            $(nTd).attr('data-column-name','主驾职称');
                            $(nTd).attr('data-text-type','text');
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(data) return data;
                        else return '--';
                    }
                },
                {
                    "className": "",
                    "width": "80px",
                    "title": "入职时间",
                    "data": "driver_entry_date",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
//                            if(row.is_published != 0)
                        {
                            $(nTd).addClass('modal-show-for-info-time-set');
                            $(nTd).attr('data-id',row.id).attr('data-name',row.driver_name);
                            $(nTd).attr('data-key','driver_entry_time').attr('data-value',data);
                            $(nTd).attr('data-column-name','主驾入职时间');
                            $(nTd).attr('data-time-type','date');
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(data)
                        {
                            var $date = new Date(data);
                            var $year = $date.getFullYear();
                            var $month = ('00'+($date.getMonth()+1)).slice(-2);
                            var $day = ('00'+($date.getDate())).slice(-2);

                            var $currentYear = new Date().getFullYear();
                            if($year == $currentYear) return $month+'-'+$day;
                            else return $year+'-'+$month+'-'+$day;
                            return $year;
                        }
                        return '--';
                    }
                },
                {
                    "className": "",
                    "width": "80px",
                    "title": "离职时间",
                    "data": "driver_leave_date",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
//                            if(row.is_published != 0)
                        {
                            $(nTd).addClass('modal-show-for-info-time-set');
                            $(nTd).attr('data-id',row.id).attr('data-name',row.driver_name);
                            $(nTd).attr('data-key','driver_leave_time').attr('data-value',data);
                            $(nTd).attr('data-column-name','主驾离职时间');
                            $(nTd).attr('data-time-type','date');
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(data)
                        {
                            var $date = new Date(data);
                            var $year = $date.getFullYear();
                            var $month = ('00'+($date.getMonth()+1)).slice(-2);
                            var $day = ('00'+($date.getDate())).slice(-2);

                            var $currentYear = new Date().getFullYear();
                            if($year == $currentYear) return $month+'-'+$day;
                            else return $year+'-'+$month+'-'+$day;
                            return $year;
                        }
                        return '--';
                    }
                },
                // {
                //     "className": "",
                //     "width": "80px",
                //     "title": "驾驶证",
                //     "data": "driver_licence",
                //     "orderable": false,
                //     "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                //         if(row.user_status != 97)
                //         {
                //             $(nTd).addClass('modal-show-for-attachment');
                //             $(nTd).attr('data-id',row.id).attr('data-name','主驾驾驶证');
                //             $(nTd).attr('data-key','driver_licence').attr('data-value',data);
                //             $(nTd).attr('data-column-name','主驾驾驶证');
                //             $(nTd).attr('data-operate-category','attachment');
                //             if(data) $(nTd).attr('data-operate-type','edit');
                //             else $(nTd).attr('data-operate-type','add');
                //         }
                //     },
                //     render: function(data, type, row, meta) {
                //         if(data) return '<small class="btn-xs bg-purple">已上传</small>';
                //         else return '--';
                //     }
                // },
                // {
                //     "className": "",
                //     "width": "80px",
                //     "title": "资格证",
                //     "data": "driver_certification",
                //     "orderable": false,
                //     "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                //         if(row.user_status != 97)
                //         {
                //             $(nTd).addClass('modal-show-for-attachment');
                //             $(nTd).attr('data-id',row.id).attr('data-name','主驾资格证');
                //             $(nTd).attr('data-key','driver_certification').attr('data-value',data);
                //             $(nTd).attr('data-column-name','主驾资格证');
                //             $(nTd).attr('data-operate-category','attachment');
                //             if(data) $(nTd).attr('data-operate-type','edit');
                //             else $(nTd).attr('data-operate-type','add');
                //         }
                //     },
                //     render: function(data, type, row, meta) {
                //         if(data) return '<small class="btn-xs bg-purple">已上传</small>';
                //         else return '--';
                //     }
                // },
                // {
                //     "className": "",
                //     "width": "80px",
                //     "title": "主驾-正页",
                //     "data": "driver_ID_front",
                //     "orderable": false,
                //     "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                //         if(row.user_status != 97)
                //         {
                //             $(nTd).addClass('modal-show-for-attachment');
                //             $(nTd).attr('data-id',row.id).attr('data-name','主驾身份证正页');
                //             $(nTd).attr('data-key','driver_ID_front').attr('data-value',data);
                //             $(nTd).attr('data-column-name','主驾身份证正页');
                //             $(nTd).attr('data-operate-category','attachment');
                //             if(data) $(nTd).attr('data-operate-type','edit');
                //             else $(nTd).attr('data-operate-type','add');
                //         }
                //     },
                //     render: function(data, type, row, meta) {
                //         if(data) return '<small class="btn-xs bg-purple">已上传</small>';
                //         else return '--';
                //     }
                // },
                // {
                //     "className": "",
                //     "width": "80px",
                //     "title": "主驾-副页",
                //     "data": "driver_ID_back",
                //     "orderable": false,
                //     "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                //         if(row.user_status != 97)
                //         {
                //             $(nTd).addClass('modal-show-for-attachment');
                //             $(nTd).attr('data-id',row.id).attr('data-name','主驾身份证副页');
                //             $(nTd).attr('data-key','driver_ID_back').attr('data-value',data);
                //             $(nTd).attr('data-column-name','主驾身份证副页');
                //             $(nTd).attr('data-operate-category','attachment');
                //             if(data) $(nTd).attr('data-operate-type','edit');
                //             else $(nTd).attr('data-operate-type','add');
                //         }
                //     },
                //     render: function(data, type, row, meta) {
                //         if(data) return '<small class="btn-xs bg-purple">已上传</small>';
                //         else return '--';
                //     }
                // },
                {
                    "className": "",
                    "width": "80px",
                    "title": "紧急联系人",
                    "data": "driver_emergency_contact_name",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(row.user_status != 97)
                        {
                            $(nTd).addClass('modal-show-for-info-text-set');
                            $(nTd).attr('data-id',row.id).attr('data-name','紧急联系人');
                            $(nTd).attr('data-key','emergency_contact_name').attr('data-value',data);
                            $(nTd).attr('data-column-name','紧急联系人');
                            $(nTd).attr('data-text-type','text');
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(data) return data;
                        else return '--';
                    }
                },
                {
                    "className": "",
                    "width": "100px",
                    "title": "紧急联系电话",
                    "data": "driver_emergency_contact_phone",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(row.user_status != 97)
                        {
                            $(nTd).addClass('modal-show-for-info-text-set');
                            $(nTd).attr('data-id',row.id).attr('data-name','紧急联系电话');
                            $(nTd).attr('data-key','emergency_contact_phone').attr('data-value',data);
                            $(nTd).attr('data-column-name','紧急联系电话');
                            $(nTd).attr('data-text-type','text');
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(data) return data;
                        else return '--';
                    }
                },
                {
                    "className": "_bold",
                    "width": "80px",
                    "title": "副驾姓名",
                    "data": "copilot_name",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(row.user_status != 97)
                        {
                            $(nTd).addClass('modal-show-for-info-text-set');
                            $(nTd).attr('data-id',row.id).attr('data-name','副驾姓名');
                            $(nTd).attr('data-key','sub_driver_name').attr('data-value',data);
                            $(nTd).attr('data-column-name','副驾姓名');
                            $(nTd).attr('data-text-type','text');
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(data) return data;
                        else return '--';
                    }
                },
                {
                    "className": "_bold",
                    "width": "100px",
                    "title": "副驾电话",
                    "data": "copilot_phone",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(row.user_status != 97)
                        {
                            $(nTd).addClass('modal-show-for-info-text-set');
                            $(nTd).attr('data-id',row.id).attr('data-name','副驾电话');
                            $(nTd).attr('data-key','sub_driver_phone').attr('data-value',data);
                            $(nTd).attr('data-column-name','副驾电话');
                            $(nTd).attr('data-text-type','text');
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(data) return data;
                        else return '--';
                    }
                },
                {
                    "title": "副驾身份证",
                    "data": "copilot_ID",
                    "className": "",
                    "width": "120px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(row.user_status != 97)
                        {
                            $(nTd).addClass('modal-show-for-info-text-set');
                            $(nTd).attr('data-id',row.id).attr('data-name','副驾身份证号');
                            $(nTd).attr('data-key','sub_driver_ID').attr('data-value',data);
                            $(nTd).attr('data-column-name','副驾身份证号');
                            $(nTd).attr('data-text-type','text');
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(data) return data;
                        else return '--';
                    }
                },
                {
                    "className": "",
                    "width": "80px",
                    "title": "副驾职称",
                    "data": "copilot_title",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(row.user_status != 97)
                        {
                            $(nTd).addClass('modal-show-for-info-text-set');
                            $(nTd).attr('data-id',row.id).attr('data-name','副驾职称');
                            $(nTd).attr('data-key','sub_driver_title').attr('data-value',data);
                            $(nTd).attr('data-column-name','副驾职称');
                            $(nTd).attr('data-text-type','text');
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(data) return data;
                        else return '--';
                    }
                },
                {
                    "className": "",
                    "width": "80px",
                    "title": "入职时间",
                    "data": "copilot_entry_date",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
//                            if(row.is_published != 0)
                        {
                            $(nTd).addClass('modal-show-for-info-time-set');
                            $(nTd).attr('data-id',row.id).attr('data-name',row.sub_driver_name);
                            $(nTd).attr('data-key','sub_driver_entry_time').attr('data-value',data);
                            $(nTd).attr('data-column-name','副驾入职时间');
                            $(nTd).attr('data-time-type','date');
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(data)
                        {
                            var $date = new Date(data);
                            var $year = $date.getFullYear();
                            var $month = ('00'+($date.getMonth()+1)).slice(-2);
                            var $day = ('00'+($date.getDate())).slice(-2);

                            var $currentYear = new Date().getFullYear();
                            if($year == $currentYear) return $month+'-'+$day;
                            else return $year+'-'+$month+'-'+$day;
                            return $year;
                        }
                        return '--';
                    }
                },
                {
                    "className": "",
                    "width": "80px",
                    "title": "离职时间",
                    "data": "copilot_leave_date",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
//                            if(row.is_published != 0)
                        {
                            $(nTd).addClass('modal-show-for-info-time-set');
                            $(nTd).attr('data-id',row.id).attr('data-name',row.sub_driver_name);
                            $(nTd).attr('data-key','sub_driver_leave_time').attr('data-value',data);
                            $(nTd).attr('data-column-name','副驾离职时间');
                            $(nTd).attr('data-time-type','date');
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(data)
                        {
                            var $date = new Date(data);
                            var $year = $date.getFullYear();
                            var $month = ('00'+($date.getMonth()+1)).slice(-2);
                            var $day = ('00'+($date.getDate())).slice(-2);

                            var $currentYear = new Date().getFullYear();
                            if($year == $currentYear) return $month+'-'+$day;
                            else return $year+'-'+$month+'-'+$day;
                            return $year;
                        }
                        return '--';
                    }
                },
                // {
                //     "className": "",
                //     "width": "80px",
                //     "title": "驾驶证",
                //     "data": "sub_driver_licence",
                //     "orderable": false,
                //     "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                //         if(row.user_status != 97)
                //         {
                //             $(nTd).addClass('modal-show-for-attachment');
                //             $(nTd).attr('data-id',row.id).attr('data-name','副驾驾驶证');
                //             $(nTd).attr('data-key','sub_driver_licence').attr('data-value',data);
                //             $(nTd).attr('data-column-name','副驾驾驶证');
                //             $(nTd).attr('data-operate-category','attachment');
                //             if(data) $(nTd).attr('data-operate-type','edit');
                //             else $(nTd).attr('data-operate-type','add');
                //         }
                //     },
                //     render: function(data, type, row, meta) {
                //         if(data) return '<small class="btn-xs bg-purple">已上传</small>';
                //         else return '--';
                //     }
                // },
                // {
                //     "className": "",
                //     "width": "80px",
                //     "title": "资格证",
                //     "data": "sub_driver_certification",
                //     "orderable": false,
                //     "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                //         if(row.user_status != 97)
                //         {
                //             $(nTd).addClass('modal-show-for-attachment');
                //             $(nTd).attr('data-id',row.id).attr('data-name','副驾资格证');
                //             $(nTd).attr('data-key','sub_driver_certification').attr('data-value',data);
                //             $(nTd).attr('data-column-name','副驾资格证');
                //             $(nTd).attr('data-operate-category','attachment');
                //             if(data) $(nTd).attr('data-operate-type','edit');
                //             else $(nTd).attr('data-operate-type','add');
                //         }
                //     },
                //     render: function(data, type, row, meta) {
                //         if(data) return '<small class="btn-xs bg-purple">已上传</small>';
                //         else return '--';
                //     }
                // },
                // {
                //     "className": "",
                //     "width": "80px",
                //     "title": "副驾-正页",
                //     "data": "sub_driver_ID_front",
                //     "orderable": false,
                //     "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                //         if(row.user_status != 97)
                //         {
                //             $(nTd).addClass('modal-show-for-attachment');
                //             $(nTd).attr('data-id',row.id).attr('data-name','副驾身份证正页');
                //             $(nTd).attr('data-key','sub_driver_ID_front').attr('data-value',data);
                //             $(nTd).attr('data-column-name','副驾身份证正页');
                //             $(nTd).attr('data-operate-category','attachment');
                //             if(data) $(nTd).attr('data-operate-type','edit');
                //             else $(nTd).attr('data-operate-type','add');
                //         }
                //     },
                //     render: function(data, type, row, meta) {
                //         if(data) return '<small class="btn-xs bg-purple">已上传</small>';
                //         else return '--';
                //     }
                // },
                // {
                //     "className": "",
                //     "width": "80px",
                //     "title": "副驾-副页",
                //     "data": "sub_driver_ID_back",
                //     "orderable": false,
                //     "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                //         if(row.user_status != 97)
                //         {
                //             $(nTd).addClass('modal-show-for-attachment');
                //             $(nTd).attr('data-id',row.id).attr('data-name','副驾身份证副页');
                //             $(nTd).attr('data-key','sub_driver_ID_back').attr('data-value',data);
                //             $(nTd).attr('data-column-name','副驾身份证副页');
                //             $(nTd).attr('data-operate-category','attachment');
                //             if(data) $(nTd).attr('data-operate-type','edit');
                //             else $(nTd).attr('data-operate-type','add');
                //         }
                //     },
                //     render: function(data, type, row, meta) {
                //         if(data) return '<small class="btn-xs bg-purple">已上传</small>';
                //         else return '--';
                //     }
                // },
                {
                    "title": "紧急联系人",
                    "data": "copilot_emergency_contact_name",
                    "className": "",
                    "width": "100px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(row.user_status != 97)
                        {
                            $(nTd).addClass('modal-show-for-info-text-set');
                            $(nTd).attr('data-id',row.id).attr('data-name','副驾紧急联系人');
                            $(nTd).attr('data-key','sub_emergency_contact_name').attr('data-value',data);
                            $(nTd).attr('data-column-name','副驾紧急联系人');
                            $(nTd).attr('data-text-type','text');
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(data) return data;
                        else return '--';
                    }
                },
                {
                    "title": "紧急联系电话",
                    "data": "copilot_emergency_contact_phone",
                    "className": "",
                    "width": "100px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(row.user_status != 97)
                        {
                            $(nTd).addClass('modal-show-for-info-text-set');
                            $(nTd).attr('data-id',row.id).attr('data-name','副驾紧急联系电话');
                            $(nTd).attr('data-key','sub_contact_phone').attr('data-value',data);
                            $(nTd).attr('data-column-name','副驾紧急联系电话');
                            $(nTd).attr('data-text-type','text');
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(data) return data;
                        else return '--';
                    }
                },
                {
                    "title": "备注",
                    "data": "remark",
                    "className": "text-center",
                    "width": "300px",
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
                        return $year+'-'+$month+'-'+$day;
//                            return $year+'-'+$month+'-'+$day+'&nbsp;&nbsp;'+$hour+':'+$minute;
//                            return $year+'-'+$month+'-'+$day+'&nbsp;&nbsp;'+$hour+':'+$minute+':'+$second;
                    }
                },
                {
                    "title": "操作",
                    "data": "id",
                    "width": "160px",
                    "orderable": false,
                    render: function(data, type, row, meta) {

                        var html_edit = '';
                        var $html_detail = '';
                        var $html_able = '';
                        var $html_delete = '';
                        var $html_operation_record = '<a class="btn btn-xs modal-show--for--driver--item-operation-record" data-id="'+data+'">记录</a>';

                        if(row.item_status == 1)
                        {
                            $html_able = '<a class="btn btn-xs driver--item-disable-submit" data-id="'+data+'">禁用</a>';
                        }
                        else
                        {
                            $html_able = '<a class="btn btn-xs driver--item-enable-submit" data-id="'+data+'">启用</a>';
                        }

                        if(row.deleted_at == null)
                        {
                            $html_delete = '<a class="btn btn-xs driver--item-delete-submit" data-id="'+data+'">删除</a>';
                        }
                        else
                        {
                            $html_delete = '<a class="btn btn-xs driver--item-restore-submit" data-id="'+data+'">恢复</a>';
                        }

                        var html =
                            '<a class="btn btn-xs modal-show--for--driver-item-edit" data-id="'+data+'">编辑</a>'+
                            $html_able+
                            $html_delete+
                            $html_operation_record+
                            // '<a class="btn btn-xs driver--item-statistic" data-id="'+data+'">统计</a>'+
                            // '<a class="btn btn-xs driver--item-login-submit" data-id="'+data+'">登录</a>'+
                            '';
                        return html;
                    }
                },
            ],
            "drawCallback": function (settings) {

                console.log('driver-list.datatable-query.execute');

//                    let startIndex = this.api().context[0]._iDisplayStart;//获取本页开始的条数
//                    this.api().column(1).nodes().each(function(cell, i) {
//                        cell.innerHTML =  startIndex + i + 1;
//                    });

            },
            "language": { url: '/common/dataTableI18n' },
        });

    }
</script>