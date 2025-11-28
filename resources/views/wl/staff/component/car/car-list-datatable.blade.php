<script>
    function Datatable_for_Car_List($tableId)
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
                'url': "{{ url('/v1/operate/car/datatable-list-query') }}",
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
                            return '<i class="fa fa-circle text-black"></i> 已删除';
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
                        if(data == 1) return '<i class="fa fa-circle text-green"></i> 车辆';
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
                    "className": "text-center",
                    "width": "60px",
                    "title": "类型",
                    "data": "trailer_type",
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
                    "className": "text-center",
                    "width": "60px",
                    "title": "尺寸",
                    "data": "trailer_length",
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
                    "className": "text-center",
                    "width": "60px",
                    "title": "容积",
                    "data": "trailer_volume",
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
                    "className": "text-center",
                    "width": "60px",
                    "title": "载重",
                    "data": "trailer_weight",
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
                    "className": "text-center",
                    "width": "60px",
                    "title": "轴数",
                    "data": "trailer_axis_count",
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
                    "className": "text-center",
                    "width": "160px",
                    "title": "驾驶员",
                    "data": "driver_id",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(row.is_completed != 1 && row.item_status != 97)
                        {
                            $(nTd).addClass('modal-show-for-info-select2-set');
                            $(nTd).attr('data-id',row.id).attr('data-name','驾驶员');
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
                        else return '<a href="javascript:void(0);">'+row.driver_er.driver_name+' '+row.driver_er.driver_phone+'</a>';
                    }
                },
                {
                    "className": "text-center",
                    "width": "80px",
                    "title": "司机",
                    "data": "linkman_name",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
//                            if(row.is_published != 0)
                        {
                            $(nTd).addClass('modal-show-for-info-text-set');
                            $(nTd).attr('data-id',row.id).attr('data-name',row.name);
                            $(nTd).attr('data-key','linkman_name').attr('data-value',data);
                            $(nTd).attr('data-column-name','司机');
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
                    "title": "电话",
                    "data": "linkman_phone",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
//                            if(row.is_published != 0)
                        {
                            $(nTd).addClass('modal-show-for-info-text-set');
                            $(nTd).attr('data-id',row.id).attr('data-name',row.name);
                            $(nTd).attr('data-key','linkman_phone').attr('data-value',data);
                            $(nTd).attr('data-column-name','电话');
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
                    "width": "160px",
                    "title": "车辆类型",
                    "data": "car_model",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
//                            if(row.is_published != 0)
                        {
                            $(nTd).addClass('modal-show-for-info-text-set');
                            $(nTd).attr('data-id',row.id).attr('data-name',row.name);
                            $(nTd).attr('data-key','car_type').attr('data-value',data);
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
                    "data": "car_owner",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
//                            if(row.is_published != 0)
                        {
                            $(nTd).addClass('modal-show-for-info-text-set');
                            $(nTd).attr('data-id',row.id).attr('data-name',row.name);
                            $(nTd).attr('data-key','car_owner').attr('data-value',data);
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
                    "width": "60px",
                    "title": "使用性质",
                    "data": "car_function",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
//                            if(row.is_published != 0)
                        {
                            $(nTd).addClass('modal-show-for-info-text-set');
                            $(nTd).attr('data-id',row.id).attr('data-name',row.name);
                            $(nTd).attr('data-key','car_function').attr('data-value',data);
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
                    "width": "160px",
                    "title": "品牌",
                    "data": "car_brand",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
//                            if(row.is_published != 0)
                        {
                            $(nTd).addClass('modal-show-for-info-text-set');
                            $(nTd).attr('data-id',row.id).attr('data-name',row.name);
                            $(nTd).attr('data-key','car_brand').attr('data-value',data);
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
                    "data": "car_identification_number",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
//                            if(row.is_published != 0)
                        {
                            $(nTd).addClass('modal-show-for-info-text-set');
                            $(nTd).attr('data-id',row.id).attr('data-name',row.name);
                            $(nTd).attr('data-key','car_identification_number').attr('data-value',data);
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
                    "data": "engine_number",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
//                            if(row.is_published != 0)
                        {
                            $(nTd).addClass('modal-show-for-info-text-set');
                            $(nTd).attr('data-id',row.id).attr('data-name',row.name);
                            $(nTd).attr('data-key','engine_number').attr('data-value',data);
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
                    "data": "locomotive_wheelbase",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
//                            if(row.is_published != 0)
                        {
                            $(nTd).addClass('modal-show-for-info-text-set');
                            $(nTd).attr('data-id',row.id).attr('data-name',row.name);
                            $(nTd).attr('data-key','locomotive_wheelbase').attr('data-value',data);
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
                    "data": "main_fuel_tank",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
//                            if(row.is_published != 0)
                        {
                            $(nTd).addClass('modal-show-for-info-text-set');
                            $(nTd).attr('data-id',row.id).attr('data-name',row.name);
                            $(nTd).attr('data-key','main_fuel_tank').attr('data-value',data);
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
                    "data": "auxiliary_fuel_tank",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
//                            if(row.is_published != 0)
                        {
                            $(nTd).addClass('modal-show-for-info-text-set');
                            $(nTd).attr('data-id',row.id).attr('data-name',row.name);
                            $(nTd).attr('data-key','auxiliary_fuel_tank').attr('data-value',data);
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
                    "data": "total_mass",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
//                            if(row.is_published != 0)
                        {
                            $(nTd).addClass('modal-show-for-info-text-set');
                            $(nTd).attr('data-id',row.id).attr('data-name',row.name);
                            $(nTd).attr('data-key','total_mass').attr('data-value',data);
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
                    "data": "curb_weight",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
//                            if(row.is_published != 0)
                        {
                            $(nTd).addClass('modal-show-for-info-text-set');
                            $(nTd).attr('data-id',row.id).attr('data-name',row.name);
                            $(nTd).attr('data-key','curb_weight').attr('data-value',data);
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
                    "data": "load_weight",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
//                            if(row.is_published != 0)
                        {
                            $(nTd).addClass('modal-show-for-info-text-set');
                            $(nTd).attr('data-id',row.id).attr('data-name',row.name);
                            $(nTd).attr('data-key','load_weight').attr('data-value',data);
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
                    "data": "traction_mass",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
//                            if(row.is_published != 0)
                        {
                            $(nTd).addClass('modal-show-for-info-text-set');
                            $(nTd).attr('data-id',row.id).attr('data-name',row.name);
                            $(nTd).attr('data-key','traction_mass').attr('data-value',data);
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
                    "data": "overall_size",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
//                            if(row.is_published != 0)
                        {
                            $(nTd).addClass('modal-show-for-info-text-set');
                            $(nTd).attr('data-id',row.id).attr('data-name',row.name);
                            $(nTd).attr('data-key','overall_size').attr('data-value',data);
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
                    "data": "purchase_date",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
//                            if(row.is_published != 0)
                        {
                            $(nTd).addClass('modal-show-for-info-time-set');
                            $(nTd).attr('data-id',row.id).attr('data-name',row.name);
                            $(nTd).attr('data-key','purchase_date').attr('data-value',data);
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
                    "data": "purchase_price",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
//                            if(row.is_published != 0)
                        {
                            $(nTd).addClass('modal-show-for-info-text-set');
                            $(nTd).attr('data-id',row.id).attr('data-name',row.name);
                            $(nTd).attr('data-key','purchase_price').attr('data-value',data);
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
                    "data": "sale_date",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
//                            if(row.is_published != 0)
                        {
                            $(nTd).addClass('modal-show-for-info-time-set');
                            $(nTd).attr('data-id',row.id).attr('data-name',row.name);
                            $(nTd).attr('data-key','sale_date').attr('data-value',data);
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
                    "data": "sale_price",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
//                            if(row.is_published != 0)
                        {
                            $(nTd).addClass('modal-show-for-info-text-set');
                            $(nTd).attr('data-id',row.id).attr('data-name',row.name);
                            $(nTd).attr('data-key','sale_price').attr('data-value',data);
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
                    "data": "registration_date",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
//                            if(row.is_published != 0)
                        {
                            $(nTd).addClass('modal-show-for-info-time-set');
                            $(nTd).attr('data-id',row.id).attr('data-name',row.name);
                            $(nTd).attr('data-key','registration_date').attr('data-value',data);
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
                    "data": "issue_date",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
//                            if(row.is_published != 0)
                        {
                            $(nTd).addClass('modal-show-for-info-time-set');
                            $(nTd).attr('data-id',row.id).attr('data-name',row.name);
                            $(nTd).attr('data-key','issue_date').attr('data-value',data);
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
                    "data": "inspection_validity",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
//                            if(row.is_published != 0)
                        {
                            $(nTd).addClass('modal-show-for-info-time-set');
                            $(nTd).attr('data-id',row.id).attr('data-name',row.name);
                            $(nTd).attr('data-key','inspection_validity').attr('data-value',data);
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
                    "data": "transportation_license_validity",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
//                            if(row.is_published != 0)
                        {
                            $(nTd).addClass('modal-show-for-info-time-set');
                            $(nTd).attr('data-id',row.id).attr('data-name',row.name);
                            $(nTd).attr('data-key','transportation_license_validity').attr('data-value',data);
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
                    "data": "transportation_license_change_time",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
//                            if(row.is_published != 0)
                        {
                            $(nTd).addClass('modal-show-for-info-time-set');
                            $(nTd).attr('data-id',row.id).attr('data-name',row.name);
                            $(nTd).attr('data-key','transportation_license_change_time').attr('data-value',data);
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
                        var $html_record = '';
                        var $html_able = '';
                        var $html_delete = '';
                        var $html_publish = '';
                        var $html_abandon = '';

                        if(row.item_status == 1)
                        {
                            $html_able = '<a class="btn btn-xs item-disable-submit" data-id="'+data+'">禁用</a>';
                        }
                        else
                        {
                            $html_able = '<a class="btn btn-xs item-enable-submit" data-id="'+data+'">启用</a>';
                        }

                        if(row.is_me == 1 && row.active == 0)
                        {
                            $html_publish = '<a class="btn btn-xs item-publish-submit" data-id="'+data+'">发布</a>';
                        }
                        else
                        {
                            $html_publish = '<a class="btn btn-xs btn-default disabled" data-id="'+data+'">发布</a>';
                        }

                        if(row.deleted_at == null)
                        {
                            $html_delete = '<a class="btn btn-xs item-delete-submit" data-id="'+data+'">删除</a>';
                        }
                        else
                        {
                            $html_delete = '<a class="btn btn-xs item-restore-submit" data-id="'+data+'">恢复</a>';
                        }

                        $html_record = '<a class="btn btn-xs item-modal-show-for-modify" data-id="'+data+'">记录</a>';

                        var html =
                            '<a class="btn btn-xs car-edit-show" data-id="'+data+'">编辑</a>'+
                            $html_able+
                            // '<a class="btn btn-xs" href="/item/edit?id='+data+'">编辑</a>'+
                            $html_delete+
                            $html_record+
                            // '<a class="btn btn-xs bg-navy item-admin-delete-permanently-submit" data-id="'+data+'">彻底删除</a>'+
                            // '<a class="btn btn-xs bg-primary item-detail-show" data-id="'+data+'">查看详情</a>'+
                            '';
                        return html;

                    }
                },
            ],
            "drawCallback": function (settings) {

                console.log('car-list-datatable-execute');

//                    let startIndex = this.api().context[0]._iDisplayStart;//获取本页开始的条数
//                    this.api().column(1).nodes().each(function(cell, i) {
//                        cell.innerHTML =  startIndex + i + 1;
//                    });

            },
            "language": { url: '/common/dataTableI18n' },
        });

    }
</script>