<script>
    function Datatable__for__Car_List($tableId)
    {
        let $that = $($tableId);
        let $datatable_wrapper = $that.parents('.datatable-wrapper');
        let $tableSearch = $datatable_wrapper.find('.datatable-search-box');

        $($tableId).DataTable({
            "aLengthMenu": [[10, 50, 100, 200, -1], ["10", "50", "100", "200", "全部"]],
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
                'url': "{{ url('/o1/car/car-list/datatable-query') }}",
                "type": 'POST',
                "dataType" : 'json',
                "data": function (d) {
                    d._token = $('meta[name="_token"]').attr('content');
                    d.id = $('input[name="car-id"]').val();
                    d.mobile = $('input[name="car-mobile"]').val();
                    d.username = $('input[name="car-username"]').val();
                    d.department_district = $tableSearch.find('select[name="car-department-district"]').val();
                    d.user_type = $tableSearch.find('select[name="car-user-type"]').val();
                    d.user_status = $tableSearch.find('select[name="car-user-status"]').val();
                },
            },
            "fixedColumns": {
                "leftColumns": "@if($is_mobile_equipment) 1 @else 2 @endif",
                "rightColumns": "1"
            },
            "columns": [
                {
                    "title": '<input type="checkbox" class="check-review-all">',
                    "data": "id",
                    "width": "60px",
                    "orderable": false,
                    render: function(data, type, row, meta) {
                        return '<label><input type="checkbox" name="bulk-id" class="minimal" value="'+data+'"></label>';
                    }
                },
                {
                    "title": "ID",
                    "data": "id",
                    "className": "",
                    "width": "50px",
                    "orderable": true,
                    "orderSequence": ["desc", "asc"],
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(row.is_completed != 1)
                        {
                            $(nTd).addClass('modal-show-for-attachment');
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
                    "width": "60px",
                    "title": "类型",
                    "data": 'car_type',
                    "orderable": false,
                    render: function(data, type, row, meta) {
                        if(data == 1) return '<i class="fa fa-circle text-blue"></i> 车';
                        else if(data == 21) return '<i class="fa fa-circle-o text-purple"></i> 挂';
                        else return "有误";
                    }
                },
                {
                    "className": "text-center",
                    "width": "120px",
                    "title": "车牌号",
                    "data": "name",
                    "orderable": false,
                    render: function(data, type, row, meta) {
                        return '<a class="car-control" data-id="'+row.id+'" data-title="'+data+'">'+data+'</a>';
                    }
                },
                {
                    "title": "所属车队",
                    "data": 'motorcade_id',
                    "width": "120px",
                    "orderable": false,
                    render: function(data, type, row, meta) {
                        if(row.motorcade_er == null) return '--';
                        else return '<a href="javascript:void(0);" class="text-black">' + row.motorcade_er.name + '</a>';
                    }
                },
                // {
                //     "className": "",
                //     "width": "80px",
                //     "title": "工作状态",
                //     "data": 'work_status',
                //     "orderable": false,
                //     render: function(data, type, row, meta) {
                //         if(data == 0) return '<small class="btn-xs bg-red">空闲</small>';
                //         else if(data == 1) return '<small class="btn-xs bg-olive">工作中</small>';
                //         else if(data == 9) return '<small class="btn-xs bg-blue">待发车</small>';
                //         else return "--";
                //     }
                // },
//                     {
//                         "className": "text-center",
//                         "width": "60px",
//                         "title": "当前位置",
//                         "data": "current_place",
//                         "orderable": false,
//                         render: function(data, type, row, meta) {
//                             return data;
//                         }
//                     },
//                     {
//                         "className": "text-center",
//                         "width": "60px",
//                         "title": "未来位置",
//                         "data": "future_place",
//                         "orderable": false,
//                         render: function(data, type, row, meta) {
//                             return data;
//                         }
//                     },
//                     {
//                         "className": "font-12px",
//                         "width": "120px",
//                         "title": "到达时间",
//                         "data": 'future_time',
//                         "orderable": false,
//                         render: function(data, type, row, meta) {
//                             if(!data) return "";
//
//                             var $date = new Date(data*1000);
//                             var $year = $date.getFullYear();
//                             var $month = ('00'+($date.getMonth()+1)).slice(-2);
//                             var $day = ('00'+($date.getDate())).slice(-2);
//                             var $hour = ('00'+$date.getHours()).slice(-2);
//                             var $minute = ('00'+$date.getMinutes()).slice(-2);
//                             var $second = ('00'+$date.getSeconds()).slice(-2);
//
// //                            return $year+'-'+$month+'-'+$day;
// //                            return $year+'-'+$month+'-'+$day+'&nbsp;&nbsp;'+$hour+':'+$minute;
// //                            return $year+'-'+$month+'-'+$day+'&nbsp;&nbsp;'+$hour+':'+$minute+':'+$second;
//
//                             var $currentYear = new Date().getFullYear();
//                             if($year == $currentYear) return $month+'-'+$day+'&nbsp;&nbsp;'+$hour+':'+$minute;
//                             else return $year+'-'+$month+'-'+$day+'&nbsp;&nbsp;'+$hour+':'+$minute;
//                         }
//                     },
                {
                    "title": "类型",
                    "data": "trailer_type",
                    "className": "text-center",
                    "width": "60px",
                    "orderable": false,
                    "sortable": true,
                    "sorting": ['asc','desc'],
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
//                            if(row.is_published != 0)
                        {
                            $(nTd).addClass('modal-show-for-info-select-set');
                            $(nTd).attr('data-id',row.id).attr('data-name',row.name);
                            $(nTd).attr('data-key','trailer_type').attr('data-value',data);
                            $(nTd).attr('data-column-name','类型');
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        return data;
                    }
                },
                {
                    "title": "尺寸",
                    "data": "trailer_length",
                    "className": "text-center",
                    "width": "60px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
//                            if(row.is_published != 0)
                        {
                            $(nTd).addClass('modal-show-for-info-select-set');
                            $(nTd).attr('data-id',row.id).attr('data-name',row.name);
                            $(nTd).attr('data-key','trailer_length').attr('data-value',data);
                            $(nTd).attr('data-column-name','尺寸');
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        return data;
                    }
                },
                {
                    "title": "容积",
                    "data": "trailer_volume",
                    "className": "text-center",
                    "width": "60px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
//                            if(row.is_published != 0)
                        {
                            $(nTd).addClass('modal-show-for-info-select-set');
                            $(nTd).attr('data-id',row.id).attr('data-name',row.name);
                            $(nTd).attr('data-key','trailer_volume').attr('data-value',data);
                            $(nTd).attr('data-column-name','容积');
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        return data;
                    }
                },
                {
                    "title": "载重",
                    "data": "trailer_weight",
                    "className": "text-center",
                    "width": "60px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
//                            if(row.is_published != 0)
                        {
                            $(nTd).addClass('modal-show-for-info-select-set');
                            $(nTd).attr('data-id',row.id).attr('data-name',row.name);
                            $(nTd).attr('data-key','trailer_weight').attr('data-value',data);
                            $(nTd).attr('data-column-name','载重');
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        return data;
                    }
                },
                {
                    "title": "轴数",
                    "data": "trailer_axis_count",
                    "className": "text-center",
                    "width": "60px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
//                            if(row.is_published != 0)
                        {
                            $(nTd).addClass('modal-show-for-info-select-set');
                            $(nTd).attr('data-id',row.id).attr('data-name',row.name);
                            $(nTd).attr('data-key','trailer_axis_count').attr('data-value',data);
                            $(nTd).attr('data-column-name','轴数');
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(data) return data;
                        else return "";
                    }
                },
                {
                    "title": "默认车挂",
                    "data": "trailer_id",
                    "className": "text-center",
                    "width": "120px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(row.is_completed != 1 && row.item_status != 97)
                        {
                            $(nTd).addClass('modal-show-for-info-select2-set');
                            $(nTd).attr('data-id',row.id).attr('data-name','默认驾驶员');
                            $(nTd).attr('data-key','driver_id').attr('data-value',data);
                            if(row.driver_er == null) $(nTd).attr('data-option-name','未指定');
                            else $(nTd).attr('data-option-name',row.driver_er.driver_name);
                            $(nTd).attr('data-column-name','驾驶员');
                            if(row.driver_id) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(row.trailer_er == null) return '--';
                        else return '<a href="javascript:void(0);">'+row.trailer_er.name+'</a>';
                    }
                },
                {
                    "title": "默认驾驶员",
                    "data": "driver_id",
                    "className": "text-center",
                    "width": "160px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(row.is_completed != 1 && row.item_status != 97)
                        {
                            $(nTd).addClass('modal-show-for-info-select2-set');
                            $(nTd).attr('data-id',row.id).attr('data-name','默认驾驶员');
                            $(nTd).attr('data-key','driver_id').attr('data-value',data);
                            if(row.driver_er == null) $(nTd).attr('data-option-name','未指定');
                            else $(nTd).attr('data-option-name',row.driver_er.driver_name);
                            $(nTd).attr('data-column-name','驾驶员');
                            if(row.driver_id) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(row.driver_er == null) return '--';
                        else
                        {
                            var $driver_html = '';
                            var $copilot_html = '';

                            $driver_html = '<a href="javascript:void(0);">'+row.driver_er.driver_name+' '+row.driver_er.driver_phone+'</a>';
                            if(row.copilot_er != null)
                            {
                                $copilot_html = '<a href="javascript:void(0);">'+row.copilot_er.driver_name+' '+row.copilot_er.driver_phone+'</a>';
                            }
                            return $driver_html+'<br>'+$copilot_html;
                        }
                    }
                },
                {
                    "className": "text-center",
                    "width": "100px",
                    "title": "联系人",
                    "data": "linkman_name",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
//                            if(row.is_published != 0)
                        {
                            $(nTd).addClass('modal-show-for-info-text-set');
                            $(nTd).attr('data-id',row.id).attr('data-name',row.name);
                            $(nTd).attr('data-key','linkman_name').attr('data-value',data);
                            $(nTd).attr('data-column-name','联系人');
                            $(nTd).attr('data-text-type','text');
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        return data;
                    }
                },
                {
                    "className": "text-center",
                    "width": "120px",
                    "title": "联系电话",
                    "data": "linkman_phone",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
//                            if(row.is_published != 0)
                        {
                            $(nTd).addClass('modal-show-for-info-text-set');
                            $(nTd).attr('data-id',row.id).attr('data-name',row.name);
                            $(nTd).attr('data-key','linkman_phone').attr('data-value',data);
                            $(nTd).attr('data-column-name','联系电话');
                            $(nTd).attr('data-text-type','text');
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        return data;
                    }
                },
                {
                    "className": "text-center",
                    "width": "180px",
                    "title": "ETC卡号",
                    "data": "ETC_account",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
//                            if(row.is_published != 0)
                        {
                            $(nTd).addClass('modal-show-for-info-text-set');
                            $(nTd).attr('data-id',row.id).attr('data-name',row.name);
                            $(nTd).attr('data-key','ETC_account').attr('data-value',data);
                            $(nTd).attr('data-column-name','ETC卡号');
                            $(nTd).attr('data-text-type','text');
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        return data;
                    }
                },
                {
                    "className": "text-center",
                    "width": "100px",
                    "title": "车辆类型",
                    "data": "car_info_model",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
//                            if(row.is_published != 0)
                        {
                            $(nTd).addClass('modal-show-for-info-text-set');
                            $(nTd).attr('data-id',row.id).attr('data-name',row.name);
                            $(nTd).attr('data-key','car_info_type').attr('data-value',data);
                            $(nTd).attr('data-column-name','车辆类型');
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        return data;
                    }
                },
                {
                    "className": "text-center",
                    "width": "100px",
                    "title": "所有人",
                    "data": "car_info_owner",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
//                            if(row.is_published != 0)
                        {
                            $(nTd).addClass('modal-show-for-info-text-set');
                            $(nTd).attr('data-id',row.id).attr('data-name',row.name);
                            $(nTd).attr('data-key','car_info_owner').attr('data-value',data);
                            $(nTd).attr('data-column-name','所有人');
                            $(nTd).attr('data-text-type','text');
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        return data;
                    }
                },
                {
                    "className": "text-center",
                    "width": "100px",
                    "title": "使用性质",
                    "data": "car_info_function",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
//                            if(row.is_published != 0)
                        {
                            $(nTd).addClass('modal-show-for-info-text-set');
                            $(nTd).attr('data-id',row.id).attr('data-name',row.name);
                            $(nTd).attr('data-key','car_info_function').attr('data-value',data);
                            $(nTd).attr('data-column-name','使用性质');
                            $(nTd).attr('data-text-type','text');
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        return data;
                    }
                },
                {
                    "className": "text-center",
                    "width": "100px",
                    "title": "品牌",
                    "data": "car_info_brand",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
//                            if(row.is_published != 0)
                        {
                            $(nTd).addClass('modal-show-for-info-text-set');
                            $(nTd).attr('data-id',row.id).attr('data-name',row.name);
                            $(nTd).attr('data-key','car_info_brand').attr('data-value',data);
                            $(nTd).attr('data-column-name','品牌');
                            $(nTd).attr('data-text-type','text');
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        return data;
                    }
                },
                {
                    "className": "text-center",
                    "width": "100px",
                    "title": "车辆识别代码",
                    "data": "car_info_identification_number",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
//                            if(row.is_published != 0)
                        {
                            $(nTd).addClass('modal-show-for-info-text-set');
                            $(nTd).attr('data-id',row.id).attr('data-name',row.name);
                            $(nTd).attr('data-key','car_info_identification_number').attr('data-value',data);
                            $(nTd).attr('data-column-name','车辆识别代码');
                            $(nTd).attr('data-text-type','text');
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        return data;
                    }
                },
                {
                    "className": "text-center",
                    "width": "60px",
                    "title": "发动机号",
                    "data": "car_info_engine_number",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
//                            if(row.is_published != 0)
                        {
                            $(nTd).addClass('modal-show-for-info-text-set');
                            $(nTd).attr('data-id',row.id).attr('data-name',row.name);
                            $(nTd).attr('data-key','car_info_engine_number').attr('data-value',data);
                            $(nTd).attr('data-column-name','发动机号');
                            $(nTd).attr('data-text-type','text');
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        return data;
                    }
                },
                {
                    "className": "text-center",
                    "width": "60px",
                    "title": "车头轴距",
                    "data": "car_info_locomotive_wheelbase",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
//                            if(row.is_published != 0)
                        {
                            $(nTd).addClass('modal-show-for-info-text-set');
                            $(nTd).attr('data-id',row.id).attr('data-name',row.name);
                            $(nTd).attr('data-key','car_info_locomotive_wheelbase').attr('data-value',data);
                            $(nTd).attr('data-column-name','车头轴距');
                            $(nTd).attr('data-text-type','text');
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        return data;
                    }
                },
                {
                    "className": "text-center",
                    "width": "60px",
                    "title": "主油箱",
                    "data": "car_info_main_fuel_tank",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
//                            if(row.is_published != 0)
                        {
                            $(nTd).addClass('modal-show-for-info-text-set');
                            $(nTd).attr('data-id',row.id).attr('data-name',row.name);
                            $(nTd).attr('data-key','car_info_main_fuel_tank').attr('data-value',data);
                            $(nTd).attr('data-column-name','主油箱');
                            $(nTd).attr('data-text-type','text');
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        return data;
                    }
                },
                {
                    "className": "text-center",
                    "width": "60px",
                    "title": "副油箱",
                    "data": "car_info_auxiliary_fuel_tank",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
//                            if(row.is_published != 0)
                        {
                            $(nTd).addClass('modal-show-for-info-text-set');
                            $(nTd).attr('data-id',row.id).attr('data-name',row.name);
                            $(nTd).attr('data-key','car_info_auxiliary_fuel_tank').attr('data-value',data);
                            $(nTd).attr('data-column-name','副油箱');
                            $(nTd).attr('data-text-type','text');
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        return data;
                    }
                },
                {
                    "className": "text-center",
                    "width": "60px",
                    "title": "总质量",
                    "data": "car_info_total_mass",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
//                            if(row.is_published != 0)
                        {
                            $(nTd).addClass('modal-show-for-info-text-set');
                            $(nTd).attr('data-id',row.id).attr('data-name',row.name);
                            $(nTd).attr('data-key','car_info_total_mass').attr('data-value',data);
                            $(nTd).attr('data-column-name','总质量');
                            $(nTd).attr('data-text-type','text');
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        return data;
                    }
                },
                {
                    "className": "text-center",
                    "width": "60px",
                    "title": "整备质量",
                    "data": "car_info_curb_weight",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
//                            if(row.is_published != 0)
                        {
                            $(nTd).addClass('modal-show-for-info-text-set');
                            $(nTd).attr('data-id',row.id).attr('data-name',row.name);
                            $(nTd).attr('data-key','car_info_curb_weight').attr('data-value',data);
                            $(nTd).attr('data-column-name','整备质量');
                            $(nTd).attr('data-text-type','text');
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        return data;
                    }
                },
                {
                    "className": "text-center",
                    "width": "60px",
                    "title": "核定载重",
                    "data": "car_info_load_weight",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
//                            if(row.is_published != 0)
                        {
                            $(nTd).addClass('modal-show-for-info-text-set');
                            $(nTd).attr('data-id',row.id).attr('data-name',row.name);
                            $(nTd).attr('data-key','car_info_load_weight').attr('data-value',data);
                            $(nTd).attr('data-column-name','核定载重');
                            $(nTd).attr('data-text-type','text');
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        return data;
                    }
                },
                {
                    "className": "text-center",
                    "width": "80px",
                    "title": "准牵引质量",
                    "data": "car_info_traction_mass",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
//                            if(row.is_published != 0)
                        {
                            $(nTd).addClass('modal-show-for-info-text-set');
                            $(nTd).attr('data-id',row.id).attr('data-name',row.name);
                            $(nTd).attr('data-key','car_info_traction_mass').attr('data-value',data);
                            $(nTd).attr('data-column-name','准牵引质量');
                            $(nTd).attr('data-text-type','text');
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        return data;
                    }
                },
                {
                    "className": "text-center",
                    "width": "60px",
                    "title": "外廓尺寸",
                    "data": "car_info_overall_size",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
//                            if(row.is_published != 0)
                        {
                            $(nTd).addClass('modal-show-for-info-text-set');
                            $(nTd).attr('data-id',row.id).attr('data-name',row.name);
                            $(nTd).attr('data-key','car_info_overall_size').attr('data-value',data);
                            $(nTd).attr('data-column-name','外廓尺寸');
                            $(nTd).attr('data-text-type','text');
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        return data;
                    }
                },
                {
                    "className": "text-center",
                    "width": "80px",
                    "title": "购买日期",
                    "data": "car_info_purchase_date",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
//                            if(row.is_published != 0)
                        {
                            $(nTd).addClass('modal-show-for-info-time-set');
                            $(nTd).attr('data-id',row.id).attr('data-name',row.name);
                            $(nTd).attr('data-key','car_info_purchase_date').attr('data-value',data);
                            $(nTd).attr('data-column-name','购买日期');
                            $(nTd).attr('data-time-type','date');
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        // return data;
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
                    "className": "text-center",
                    "width": "80px",
                    "title": "购买价格",
                    "data": "car_info_purchase_price",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
//                            if(row.is_published != 0)
                        {
                            $(nTd).addClass('modal-show-for-info-text-set');
                            $(nTd).attr('data-id',row.id).attr('data-name',row.name);
                            $(nTd).attr('data-key','car_info_purchase_price').attr('data-value',data);
                            $(nTd).attr('data-column-name','购买价格(元)');
                            $(nTd).attr('data-text-type','text');
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(data) return data;
                        return '--';
                    }
                },
                {
                    "className": "text-center",
                    "width": "80px",
                    "title": "出售日期",
                    "data": "car_info_sale_date",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
//                            if(row.is_published != 0)
                        {
                            $(nTd).addClass('modal-show-for-info-time-set');
                            $(nTd).attr('data-id',row.id).attr('data-name',row.name);
                            $(nTd).attr('data-key','car_info_sale_date').attr('data-value',data);
                            $(nTd).attr('data-column-name','出售日期');
                            $(nTd).attr('data-time-type','date');
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        // return data;
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
                    "className": "text-center",
                    "width": "80px",
                    "title": "出售价格",
                    "data": "car_info_sale_price",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
//                            if(row.is_published != 0)
                        {
                            $(nTd).addClass('modal-show-for-info-text-set');
                            $(nTd).attr('data-id',row.id).attr('data-name',row.name);
                            $(nTd).attr('data-key','car_info_sale_price').attr('data-value',data);
                            $(nTd).attr('data-column-name','出售价格(元)');
                            $(nTd).attr('data-text-type','text');
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(data) return data;
                        return '--';
                    }
                },
                {
                    "className": "text-center",
                    "width": "80px",
                    "title": "注册日期",
                    "data": "car_info_registration_date",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
//                            if(row.is_published != 0)
                        {
                            $(nTd).addClass('modal-show-for-info-time-set');
                            $(nTd).attr('data-id',row.id).attr('data-name',row.name);
                            $(nTd).attr('data-key','car_info_registration_date').attr('data-value',data);
                            $(nTd).attr('data-column-name','注册日期');
                            $(nTd).attr('data-time-type','date');
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        // return data;
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
                    "className": "text-center",
                    "width": "80px",
                    "title": "发证日期",
                    "data": "car_info_issue_date",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
//                            if(row.is_published != 0)
                        {
                            $(nTd).addClass('modal-show-for-info-time-set');
                            $(nTd).attr('data-id',row.id).attr('data-name',row.name);
                            $(nTd).attr('data-key','car_info_issue_date').attr('data-value',data);
                            $(nTd).attr('data-column-name','发证日期');
                            $(nTd).attr('data-time-type','date');
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        // return data;
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
                    "className": "text-center",
                    "width": "80px",
                    "title": "检验有效期",
                    "data": "car_info_inspection_validity",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
//                            if(row.is_published != 0)
                        {
                            $(nTd).addClass('modal-show-for-info-time-set');
                            $(nTd).attr('data-id',row.id).attr('data-name',row.name);
                            $(nTd).attr('data-key','car_info_inspection_validity').attr('data-value',data);
                            $(nTd).attr('data-column-name','检验有效期');
                            $(nTd).attr('data-time-type','date');
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        // return data;
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
                    "className": "text-center",
                    "width": "80px",
                    "title": "运输证-年检",
                    "data": "car_info_transportation_license_validity",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
//                            if(row.is_published != 0)
                        {
                            $(nTd).addClass('modal-show-for-info-time-set');
                            $(nTd).attr('data-id',row.id).attr('data-name',row.name);
                            $(nTd).attr('data-key','car_info_transportation_license_validity').attr('data-value',data);
                            $(nTd).attr('data-column-name','运输证-年检');
                            $(nTd).attr('data-time-type','date');
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        // return data;
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
                    "className": "text-center",
                    "width": "80px",
                    "title": "运输证-换证",
                    "data": "car_info_transportation_license_change_time",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
//                            if(row.is_published != 0)
                        {
                            $(nTd).addClass('modal-show-for-info-time-set');
                            $(nTd).attr('data-id',row.id).attr('data-name',row.name);
                            $(nTd).attr('data-key','car_info_transportation_license_change_time').attr('data-value',data);
                            $(nTd).attr('data-column-name','运输证-换证');
                            $(nTd).attr('data-time-type','date');
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        // return data;
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
                    "className": "text-center",
                    "width": "60px",
                    "title": "创建者",
                    "data": "creator_id",
                    "orderable": false,
                    render: function(data, type, row, meta) {
                        return row.creator == null ? '未知' : '<a target="_blank" href="/user/'+row.creator.id+'">'+row.creator.username+'</a>';
                    }
                },
                {
                    "className": "font-12px",
                    "width": "108px",
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
                        var $html_operation_record = '<a class="btn btn-xs bg-default modal-show--for--car--item-operation-record" data-id="'+data+'">记录</a>';

                        if(row.item_status == 1)
                        {
                            $html_able = '<a class="btn btn-xs car--item-disable-submit" data-id="'+data+'">禁用</a>';
                        }
                        else
                        {
                            $html_able = '<a class="btn btn-xs car--item-enable-submit" data-id="'+data+'">启用</a>';
                        }

                        if(row.deleted_at == null)
                        {
                            $html_delete = '<a class="btn btn-xs car--item-delete-submit" data-id="'+data+'">删除</a>';
                        }
                        else
                        {
                            $html_delete = '<a class="btn btn-xs car--item-restore-submit" data-id="'+data+'">恢复</a>';
                        }

                        var html =
                            '<a class="btn btn-xs modal-show--for--car-item-edit" data-id="'+data+'">编辑</a>'+
                            $html_able+
                            $html_delete+
                            $html_operation_record+
                            // '<a class="btn btn-xs car--item-statistic" data-id="'+data+'">统计</a>'+
                            // '<a class="btn btn-xs car--item-login-submit" data-id="'+data+'">登录</a>'+
                            '';
                        return html;

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