<script>
    function Datatable__for__Order_List__Financial($tableId)
    {
        let $that = $($tableId);
        let $datatable_wrapper = $that.parents('.datatable-wrapper');
        let $tableSearch = $datatable_wrapper.find('.datatable-search-box');

        $($tableId).DataTable({
            "aLengthMenu": [[10, 50, 200], ["10", "50", "200"]],
            "processing": true,
            "serverSide": true,
            "searching": true,
            "pagingType": "simple_numbers",
            "sDom": '<"dataTables_length_box"l> <"dataTables_info_box"i> <"dataTables_paginate_box"p> <t> <"dataTables_length_box"l> <"dataTables_info_box"i> <"dataTables_paginate_box"p>',
            "order": [],
            "orderCellsTop": true,
            "scrollX": true,
//                "scrollY": true,
            "scrollY": ($(document).height() - 298)+"px",
            "scrollCollapse": true,
            "showRefresh": true,
            "ajax": {
                'url': "{{ url('/o1/order/order-list/datatable-query') }}",
                "type": 'POST',
                "dataType" : 'json',
                "data": function (d) {
                    d._token = $('meta[name="_token"]').attr('content');
                    d.id = $tableSearch.find('input[name="order-id"]').val();
                    d.remark = $tableSearch.find('input[name="order-remark"]').val();
                    d.description = $tableSearch.find('input[name="order-description"]').val();
                    d.status = $tableSearch.find('select[name="order-status"]').val();
                    d.assign_date = $tableSearch.find('input[name="order-assign-date"]').val();
                    d.task_date = $tableSearch.find('input[name="order-task-date"]').val();
                    d.date_start = $tableSearch.find('input[name="order-start"]').val();
                    d.date_ended = $tableSearch.find('input[name="order-ended"]').val();
                    d.name = $tableSearch.find('input[name="order-name"]').val();
                    d.title = $tableSearch.find('input[name="order-title"]').val();
                    d.keyword = $tableSearch.find('input[name="order-keyword"]').val();
                    d.staff = $tableSearch.find('select[name="order-staff"]').val();
                    d.client = $tableSearch.find('select[name="order-client"]').val();
                    d.project = $tableSearch.find('select[name="order-project"]').val();
                    d.car = $tableSearch.find('select[name="order-car"]').val();
                    d.trailer = $tableSearch.find('select[name="order-trailer"]').val();
                    d.car_type = $tableSearch.find('select[name="order-car-type"]').val();
                    d.car_owner_type = $tableSearch.find('select[name="order-car-owner-type"]').val();
                    d.external_car = $tableSearch.find('input[name="order-external-car"]').val();
                    d.inspected_status = $tableSearch.find('select[name="order-inspected-status"]').val();
                    d.inspected_result = $tableSearch.find('select[name="order-inspected-result[]"]').val();
                    d.delivered_status = $tableSearch.find('select[name="order-delivered-status"]').val();
                    d.delivered_result = $tableSearch.find('select[name="order-delivered-result[]"]').val();
                },
            },
            "fixedColumns": {

                @if($me->department_district_id == 0)
                "leftColumns": "@if($is_mobile_equipment) 1 @else 6 @endif",
                "rightColumns": "@if($is_mobile_equipment) 0 @else 1 @endif",
                @else
                "leftColumns": "@if($is_mobile_equipment) 1 @else 4 @endif",
                "rightColumns": "@if($is_mobile_equipment) 0 @else 1 @endif",
                @endif

            },
            "columnDefs": [
            ],
            "columns": [
//                    {
//                        "title": "选择",
//                        "width": "32px",
//                        "data": "id",
//                        "orderable": false,
//                        render: function(data, type, row, meta) {
//                            return '<label><input type="checkbox" name="bulk-id" class="minimal" value="'+data+'"></label>';
//                        }
//                    },
                {
                    "title": "ID",
                    "className": "",
                    "width": "60px",
                    "data": "id",
                    "orderable": true,
                    "orderSequence": ["desc", "asc"],
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(true)
                        {
                            $(nTd).attr('data-id',row.id).attr('data-name','附件');
                            $(nTd).attr('data-key','id').attr('data-value',data);
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        return data;
                    }
                },
                {
                    "title": "类型",
                    "className": "",
                    "width": "60px",
                    "data": "order_type",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(row.is_completed != 1)
                        {
                            $(nTd).addClass('modal-show--for--info-select-set');
                            $(nTd).attr('data-id',row.id).attr('data-name','订单类型');
                            $(nTd).attr('data-key','car_owner_type').attr('data-value',data);
                            $(nTd).attr('data-column-name','订单类型');
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        // if(data == 1)
                        // {
                        //     return '<small class="btn-xs bg-green">自有</small>';
                        // }
                        // else if(data == 11)
                        // {
                        //     return '<small class="btn-xs bg-teal">空单</small>';
                        // }
                        // else if(data == 41)
                        // {
                        //     return '<small class="btn-xs bg-blue">外配·配货</small>';
                        // }
                        // else if(data == 61)
                        // {
                        //     return '<small class="btn-xs bg-purple">外请·调车</small>';
                        // }
                        // else return "有误";
                        if(row.car_owner_type == 1)
                        {
                            return '<small class="btn-xs bg-green">自有</small>';
                        }
                        else if(row.car_owner_type == 11)
                        {
                            return '<small class="btn-xs bg-purple">外请</small>';
                        }
                        else return "有误";
                    }
                },
//                 {
//                     "title": "订单状态",
//                     "className": "",
//                     "width": "80px",
//                     "data": "id",
//                     "orderable": false,
//                     "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
//                         if(row.is_completed != 1)
//                         {
//                             $(nTd).addClass('modal-show--for--attachment');
//                             $(nTd).attr('data-id',row.id).attr('data-name','附件');
//                             $(nTd).attr('data-key','attachment_list').attr('data-value',row.attachment_list);
//                             if(data) $(nTd).attr('data-operate-type','edit');
//                             else $(nTd).attr('data-operate-type','add');
//                         }
//                     },
//                     render: function(data, type, row, meta) {
// //                            return data;
//
//                         if(row.deleted_at != null)
//                         {
//                             return '<small class="btn-xs bg-black">已删除</small>';
//                         }
//
//                         if(row.item_status == 97)
//                         {
//                             return '<small class="btn-xs bg-navy">已弃用</small>';
//                         }
//
//                         if(row.is_published == 0)
//                         {
//                             return '<small class="btn-xs bg-teal">未发布</small>';
//                         }
//                         else
//                         {
//                             if(row.is_completed == 1)
//                             {
//                                 return '<small class="btn-xs bg-olive">已结束</small>';
//                             }
//                         }
//
//                         var $travel_status_html = '';
//                         var $travel_result_html = '';
//                         var $travel_result_time = '';
// //
//                         if(row.travel_status == "待发车")
//                         {
//                             $travel_status_html = '<small class="btn-xs bg-yellow">待发车</small>';
//                         }
//                         else if(row.travel_status == "进行中")
//                         {
//                             $travel_status_html = '<small class="btn-xs bg-blue">进行中</small>';
//                         }
//                         else if(row.travel_status == "已到达")
//                         {
//                             if(row.travel_result == "待收款") $travel_status_html = '<small class="btn-xs bg-orange">待收款</small>';
//                             if(row.travel_result == "已收款") $travel_status_html = '<small class="btn-xs bg-maroon">已收款</small>';
//                             else $travel_status_html = '<small class="btn-xs bg-olive">已到达</small>';
//                         }
//                         else if(row.travel_status == "待收款")
//                         {
//                             $travel_status_html = '<small class="btn-xs bg-maroon">待收款</small>';
//                         }
//                         else if(row.travel_status == "已收款")
//                         {
//                             $travel_status_html = '<small class="btn-xs bg-purple">已收款</small>';
//                         }
//                         else if(row.travel_status == "已完成")
//                         {
//                             $travel_status_html = '<small class="btn-xs bg-olive">已完成</small>';
//                         }
// //
// //
// //                            if(row.travel_result == "正常")
// //                            {
// //                                $travel_result_html = '<small class="btn-xs bg-olive">正常</small>';
// //                            }
// //                            else if(row.travel_result == "超时")
// //                            {
// //                                $travel_result_html = '<small class="btn-xs bg-red">超时</small><br>';
// //                                $travel_result_time = '<small class="btn-xs bg-gray">'+row.travel_result_time+'</small>';
// //                            }
// //                            else if(row.travel_result == "已超时")
// //                            {
// //                                $travel_result_html = '<small class="btn-xs btn-danger">已超时</small>';
// //                            }
// //
//                         return $travel_status_html + $travel_result_html + $travel_result_time;
//
//                     }
//                 },
//                 {
//                     "title": "状态",
//                     "data": "id",
//                     "className": "",
//                     "width": "80px",
//                     "orderable": false,
//                     render: function(data, type, row, meta) {
// //                            return data;
//
//                         if(row.deleted_at != null)
//                         {
//                             return '';
//                         }
//
//                         if(row.is_published == 0)
//                         {
//                             return '';
//                         }
//
//
//                         var $travel_status_html = '';
//                         var $travel_result_html = '';
//
//
//
//                         if(row.travel_result == "正常")
//                         {
//                             $travel_result_html = '<small class="btn-xs bg-olive">正常</small>';
//                         }
//                         else if(row.travel_result == "超时")
//                         {
//                             $travel_result_html = '<small class="btn-xs bg-red">超时</small><br>';
//                         }
//                         else if(row.travel_result == "发车超时")
//                         {
//                             $travel_result_html = '<small class="btn-xs btn-danger">发车超时</small>';
//                         }
//                         else if(row.travel_result == "待收款")
//                         {
//                             $travel_result_html = '<small class="btn-xs bg-orange">待收款</small>';
//                         }
//                         else if(row.travel_result == "已收款")
//                         {
//                             $travel_result_html = '<small class="btn-xs bg-blue">已收款</small>';
//                         }
//
//
//                         if(row.is_completed == 1)
//                         {
//                             $travel_result_html = '<small class="btn-xs bg-grey">已结束</small>';
//                         }
//
//                         return $travel_status_html + $travel_result_html;
//
//                     }
//                 },
                {
                    "title": "派车日期",
                    "name": 'assign_date',
                    "data": 'assign_date',
                    "className": "",
                    "width": "90px",
                    "orderable": true,
                    "orderSequence": ["desc", "asc"],
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(row.is_completed != 1)
                        {
                            var $assign_time_value = '';
                            if(data)
                            {
                                var $date = new Date(data*1000);
                                var $year = $date.getFullYear();
                                var $month = ('00'+($date.getMonth()+1)).slice(-2);
                                var $day = ('00'+($date.getDate())).slice(-2);
                                $assign_time_value = $year+'-'+$month+'-'+$day;
                            }

                            $(nTd).addClass('modal-show--for--info-time-set');
                            $(nTd).attr('data-id',row.id).attr('data-name','派车日期');
                            $(nTd).attr('data-key','assign_time').attr('data-value',$assign_time_value);
                            $(nTd).attr('data-column-name','派车日期');
                            $(nTd).attr('data-time-type','date');
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
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
                    "title": "任务日期",
                    "name": 'task_date',
                    "data": 'task_date',
                    "className": "",
                    "width": "90px",
                    "orderable": true,
                    "orderSequence": ["desc", "asc"],
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(row.is_completed != 1)
                        {
                            var $assign_time_value = '';
                            if(data)
                            {
                                var $date = new Date(data*1000);
                                var $year = $date.getFullYear();
                                var $month = ('00'+($date.getMonth()+1)).slice(-2);
                                var $day = ('00'+($date.getDate())).slice(-2);
                                $assign_time_value = $year+'-'+$month+'-'+$day;
                            }

                            $(nTd).addClass('modal-show--for--info-time-set');
                            $(nTd).attr('data-id',row.id).attr('data-name','派车日期');
                            $(nTd).attr('data-key','assign_time').attr('data-value',$assign_time_value);
                            $(nTd).attr('data-column-name','派车日期');
                            $(nTd).attr('data-time-type','date');
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
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
                // {
                //     "title": "客户",
                //     "data": "client_id",
                //     "className": "",
                //     "width": "120px",
                //     "orderable": false,
                //     "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                //         if(row.is_completed != 1)
                //         {
                //             $(nTd).addClass('modal-show--for--info-select2-set');
                //             $(nTd).attr('data-id',row.id).attr('data-name','客户');
                //             $(nTd).attr('data-key','client_id').attr('data-value',data);
                //             if(row.client_er == null) $(nTd).attr('data-option-name','未指定');
                //             else {
                //                 if(row.client_er.short_name) $(nTd).attr('data-option-name',row.client_er.name);
                //                 else $(nTd).attr('data-option-name',row.client_er.name);
                //             }
                //             $(nTd).attr('data-column-name','客户');
                //             if(row.client_id) $(nTd).attr('data-operate-type','edit');
                //             else $(nTd).attr('data-operate-type','add');
                //         }
                //     },
                //     render: function(data, type, row, meta) {
                //         if(row.client_er)
                //         {
                //             // return '<a href="javascript:void(0);" class="text-black">'+row.client_er.name+'</a>';
                //             return '<a class="client-control" data-id="'+row.client_id+'" data-title="'+row.client_er.name+'">'+row.client_er.name+'</a>';
                //         }
                //         else return '未指定';
                //     }
                // },
                {
                    "title": "项目",
                    "data": "project_id",
                    "className": "",
                    "width": "80px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(row.is_completed != 1)
                        {
                            $(nTd).addClass('modal-show--for--info-select2-set');
                            $(nTd).attr('data-id',row.id).attr('data-name','客户');
                            $(nTd).attr('data-key','client_id').attr('data-value',data);
                            if(row.project_er == null) $(nTd).attr('data-option-name','未指定');
                            else {
                                if(row.project_er) $(nTd).attr('data-option-name',row.project_er.name);
                                else $(nTd).attr('data-option-name','');
                            }
                            $(nTd).attr('data-column-name','项目');
                            if(row.client_id) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(row.project_er)
                        {
                            // return '<a href="javascript:void(0);" class="text-black">'+row.project_er.name+'</a>';
                            return '<a class="project-control" data-id="'+row.project_id+'" data-title="'+row.project_er.name+'">'+row.project_er.name+'</a>';
                        }
                        else return '未指定';
                    }
                },
                {
                    "title": "车辆",
                    "data": "car_id",
                    "className": "",
                    "width": "100px",
                    "orderable": true,
                    "orderSequence": ["desc", "asc"],
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(row.is_completed != 1)
                        {
                            if(row.car_owner_type == 1 || row.car_owner_type == 11 || row.car_owner_type == 41)
                            {
                                $(nTd).addClass('modal-show--for--info-select2-set');
                                $(nTd).attr('data-id',row.id).attr('data-name','车辆');
                                $(nTd).attr('data-key','car_id').attr('data-value',row.car_id);
                                if(row.car_er == null) $(nTd).attr('data-option-name','未指定');
                                else $(nTd).attr('data-option-name',row.car_er.name);
                                $(nTd).attr('data-column-name','车辆');
                                if(row.car_id) $(nTd).attr('data-operate-type','edit');
                                else $(nTd).attr('data-operate-type','add');
                            }
                            else if(row.car_owner_type == 61)
                            {
                                $(nTd).addClass('modal-show--for--info-text-set');
                                $(nTd).attr('data-id',row.id).attr('data-name','车辆');
                                $(nTd).attr('data-key','outside_car').attr('data-value',row.outside_car);
                                $(nTd).attr('data-column-name','车辆');
                                if(row.outside_car) $(nTd).attr('data-operate-type','edit');
                                else $(nTd).attr('data-operate-type','add');
                            }
                        }
                    },
                    render: function(data, type, row, meta) {
                        var car_html = '';
                        if(row.car_owner_type == 1)
                        {
                            if(row.car_er != null)
                            {
                                car_html = '<a class="car-control" data-id="'+row.car_id+'" data-title="'+row.car_er.name+'">'+row.car_er.name+'</a>';
                            }
                        }
                        else
                        {
                            car_html = row.external_car;
                        }
                        return car_html;
                    }
                },
                {
                    "title": "车挂",
                    "data": "trailer_id",
                    "className": "",
                    "width": "120px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(row.is_completed != 1)
                        {
                            if(row.car_owner_type == 1 || row.car_owner_type == 11 || row.car_owner_type == 41)
                            {
                                $(nTd).addClass('modal-show--for--info-select2-set');
                                $(nTd).attr('data-id',row.id).attr('data-name','车挂');
                                $(nTd).attr('data-key','trailer_id').attr('data-value',row.trailer_id);
                                if(row.trailer_er == null) $(nTd).attr('data-option-name','未指定');
                                else $(nTd).attr('data-option-name',row.trailer_er.name);
                                $(nTd).attr('data-column-name','车挂');
                                if(row.trailer_id) $(nTd).attr('data-operate-type','edit');
                                else $(nTd).attr('data-operate-type','add');
                            }
                            else if(row.car_owner_type == 61)
                            {
                                $(nTd).addClass('modal-show--for--info-text-set');
                                $(nTd).attr('data-id',row.id).attr('data-name','车挂');
                                $(nTd).attr('data-key','outside_trailer').attr('data-value',row.outside_trailer);
                                $(nTd).attr('data-column-name','车挂');
                                if(row.outside_car) $(nTd).attr('data-operate-type','edit');
                                else $(nTd).attr('data-operate-type','add');
                            }
                        }
                    },
                    render: function(data, type, row, meta) {
                        var trailer_text = '';
                        var trailer_html = '';
                        if(row.car_owner_type == 1)
                        {
                            if(row.trailer_er != null)
                            {
                                trailer_text = row.trailer_er.name;
                                if(row.trailer_er.sub_name) trailer_text += ' ('+row.trailer_er.sub_name+')';
                                trailer_html = '<a href="javascript:void(0);" class="text-black">'+trailer_text+'</a>';
                            }
                        }
                        else
                        {
                            trailer_html = row.external_trailer;
                        }
                        return trailer_html;
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
                        if(row.is_completed != 1)
                        {
                            $(nTd).addClass('modal-show--for--info-text-set');
                            $(nTd).attr('data-id',row.id).attr('data-name','主驾姓名');
                            $(nTd).attr('data-key','driver_name').attr('data-value',data);
                            $(nTd).attr('data-column-name','主驾姓名');
                            $(nTd).attr('data-text-type','text');
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        var $driver_id = 0;
                        var $driver_name = '';
                        var $driver_phone = '';
                        var $copilot_id = 0;
                        var $copilot_name = '';
                        var $copilot_phone = '';

                        var $driver_text = '';
                        var $driver_html = '';
                        var $copilot_text = '';
                        var $copilot_html = '';


                        // if(row.car_owner_type == 1)
                        // {
                        //     // 主驾
                        //     if(row.driver_er != null)
                        //     {
                        //         $driver_id = row.driver_id;
                        //         $driver_name = row.driver_er.driver_name;
                        //         $driver_phone = row.driver_er.driver_phone;
                        //
                        //         $driver_text = $driver_name + ' (' +  $driver_phone + ')';
                        //         $driver_html = '<a class="driver-control" data-id="'+$driver_id+'" data-title="'+$driver_name+'">'+$driver_text+'</a> <br>';
                        //     }
                        //     // 副驾
                        //     if(row.copilot_er != null)
                        //     {
                        //         $copilot_id = row.copilot_id;
                        //         $copilot_name = row.copilot_er.driver_name;
                        //         $copilot_phone = row.copilot_er.driver_phone;
                        //
                        //         $copilot_text = $copilot_name + ' (' +  $copilot_phone + ')';
                        //         $copilot_html = '<a class="driver-control" data-id="'+$copilot_id+'" data-title="'+$copilot_name+'">'+$copilot_text+'</a>';
                        //     }
                        // }
                        // else
                        // {
                        //     // 主驾
                        //     if(row.driver_phone) $driver_html = row.driver_name + ' (' +  row.driver_phone + ') <br>';
                        //     else $driver_html = row.copilot_name;
                        //     // 副驾
                        //     if(row.copilot_phone) $copilot_html = row.copilot_name + ' (' +  row.copilot_phone + ')';
                        //     else $copilot_html = row.copilot_name;
                        // }

                        // 主驾
                        if(row.driver_phone) $driver_html = row.driver_name + ' (' +  row.driver_phone + ') <br>';
                        else $driver_html = row.copilot_name;
                        // 副驾
                        if(row.copilot_phone) $copilot_html = row.copilot_name + ' (' +  row.copilot_phone + ')';
                        else $copilot_html = row.copilot_name;

                        return $driver_html + $copilot_html;
                    }
                },



                // {
                //     "title": "线路",
                //     "data": "route_type",
                //     "className": "bg-route",
                //     "width": "160px",
                //     "orderable": false,
                //     "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                //         if(row.is_completed != 1)
                //         {
                //             if(data == 1)
                //             {
                //                 $(nTd).addClass('modal-show--for--info-select2-set');
                //                 $(nTd).attr('data-id',row.id).attr('data-name','固定线路');
                //                 $(nTd).attr('data-key','route_id').attr('data-value',row.route_id);
                //                 if(row.route_er == null) $(nTd).attr('data-option-name','未指定');
                //                 else $(nTd).attr('data-option-name',row.route_er.title);
                //                 $(nTd).attr('data-column-name','固定线路');
                //                 if(row.route_id) $(nTd).attr('data-operate-type','edit');
                //                 else $(nTd).attr('data-operate-type','add');
                //             }
                //             else if(data == 11)
                //             {
                //                 $(nTd).addClass('modal-show--for--info-text-set');
                //                 $(nTd).attr('data-id',row.id).attr('data-name','临时线路');
                //                 $(nTd).attr('data-key','route_temporary').attr('data-value',row.route_temporary);
                //                 if(row.route_er == null) $(nTd).attr('data-option-name','未指定');
                //                 $(nTd).attr('data-column-name','临时线路');
                //                 if(row.route_id) $(nTd).attr('data-operate-type','edit');
                //                 else $(nTd).attr('data-operate-type','add');
                //             }
                //         }
                //     },
                //     render: function(data, type, row, meta) {
                //         if(data == 1)
                //         {
                //             if(row.route_er == null) return '--';
                //             else return '<a href="javascript:void(0);">'+row.route_er.title+'</a>';
                //         }
                //         else if(data == 11)
                //         {
                //             if(row.route_temporary) return '[临] ' + row.route_temporary;
                //             else return '[临时]';
                //         }
                //         else return '有误';
                //     }
                // },
                // {
                //     "title": "固定线路",
                //     "className": "bg-route",
                //     "width": "120px",
                //     "data": "route_id",
                //     "orderable": false,
                //     "visible" : true,
                //     "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                //         if(row.is_completed != 1)
                //         {
                //             $(nTd).addClass('modal-show--for--info-select2-set');
                //             $(nTd).attr('data-id',row.id).attr('data-name','固定线路');
                //             $(nTd).attr('data-key','route_id').attr('data-value',row.route_id);
                //             if(row.route_er == null) $(nTd).attr('data-option-name','未指定');
                //             else $(nTd).attr('data-option-name',row.route_er.title);
                //             $(nTd).attr('data-column-name','固定线路');
                //             if(row.route_id) $(nTd).attr('data-operate-type','edit');
                //             else $(nTd).attr('data-operate-type','add');
                //         }
                //     },
                //     render: function(data, type, row, meta) {
                //         if(row.route_er == null) return '--';
                //         else return '<a href="javascript:void(0);">'+row.route_er.title+'</a>';
                //     }
                // },
                // {
                //     "title": "临时线路",
                //     "className": "bg-route",
                //     "width": "120px",
                //     "data": "route_temporary",
                //     "orderable": false,
                //     "visible" : true,
                //     "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                //         if(row.is_completed != 1)
                //         {
                //             $(nTd).addClass('modal-show--for--info-text-set');
                //             $(nTd).attr('data-id',row.id).attr('data-name','临时线路');
                //             $(nTd).attr('data-key','route_temporary').attr('data-value',data);
                //             $(nTd).attr('data-column-name','临时线路');
                //             if(row.route_id) $(nTd).attr('data-operate-type','edit');
                //             else $(nTd).attr('data-operate-type','add');
                //         }
                //     },
                //     render: function(data, type, row, meta) {
                //         if(row.route_temporary) return '' + row.route_temporary;
                //         else return '';
                //     }
                // },

                {
                    "title": "车型",
                    "name": "car_type",
                    "data": "car_type",
                    "className": "",
                    "width": "60px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(row.is_completed != 1)
                        {
                            $(nTd).addClass('modal-show--for--info-text-set');
                            $(nTd).attr('data-id',row.id).attr('data-name','车型');
                            $(nTd).attr('data-key','car_type').attr('data-value',data);
                            $(nTd).attr('data-column-name','车型');
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
                    "title": "任务编号",
                    "name": "task_number",
                    "data": "task_number",
                    "className": "",
                    "width": "100px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(row.is_completed != 1)
                        {
                            $(nTd).addClass('modal-show--for--info-text-set');
                            $(nTd).attr('data-id',row.id).attr('data-name','任务编号');
                            $(nTd).attr('data-key','task_number').attr('data-value',data);
                            $(nTd).attr('data-column-name','任务编号');
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
                    "title": "出发地",
                    "data": "transport_departure_place",
                    "className": "",
                    "width": "100px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(row.is_completed != 1)
                        {
                            $(nTd).addClass('modal-show--for--info-text-set');
                            $(nTd).attr('data-id',row.id).attr('data-name','出发地');
                            $(nTd).attr('data-key','departure_place').attr('data-value',data);
                            $(nTd).attr('data-column-name','出发地');
                            $(nTd).attr('data-text-type','text');
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
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
                    "width": "100px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(row.is_completed != 1)
                        {
                            $(nTd).addClass('modal-show--for--info-text-set');
                            $(nTd).attr('data-id',row.id).attr('data-name','目的地');
                            $(nTd).attr('data-key','destination_place').attr('data-value',data);
                            $(nTd).attr('data-column-name','目的地');
                            $(nTd).attr('data-text-type','text');
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        return data == null ? '--' : data;
                    }
                },

                {
                    "title": "账期",
                    "data": "settlement_period",
                    "className": "",
                    "width": "80px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(row.is_completed != 1)
                        {
                            $(nTd).attr('data-row-index',iRow);

                            $(nTd).addClass('modal-show--for--item-field-set-');
                            $(nTd).attr('data-column-type','radio');
                            $(nTd).attr('data-column-name','账期');

                            $(nTd).attr('data-id',row.id);
                            $(nTd).attr('data-name','运费');
                            $(nTd).attr('data-key','freight_amount');
                            $(nTd).attr('data-value',data);

                            if(row.client_id) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(data == 1)
                        {
                            return '<i class="fa fa-clock-o text-red"></i> 单次结算';
                        }
                        if(data == 3)
                        {
                            return '<i class="fa fa-clock-o text-yellow"></i> 多次结算</small>';
                        }
                        if(data == 7)
                        {
                            return '<i class="fa fa-clock-o text-blue"></i> 周结</small>';
                        }
                        if(data == 31)
                        {
                            return '<i class="fa fa-clock-o text-green"></i> 月结</small>';
                        }
                        else
                        {
                            return '有误';
                        }
                    }
                },
                {
                    "title": "运价",
                    "data": "freight_amount",
                    "className": "bg-fee",
                    "width": "60px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        $(nTd).attr('data-id',row.id);
                        $(nTd).attr('data-name','运价');
                        $(nTd).attr('data-key','freight_amount');
                        $(nTd).attr('data-value',parseFloat(data));
                        $(nTd).attr('data-column-name','运价');
                        $(nTd).addClass('color-blue');
                        $(nTd).addClass('_bold');

                        if(row.is_completed != 1)
                        {
                            $(nTd).addClass('modal-show--for--info-text-set');
                            $(nTd).attr('data-text-type','text');
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        var $data = parseFloat(data);
                        if($data) return $data;
                        else return '--';
                    }
                },
                {
                    "title": "油卡",
                    "data": "freight_oil_card_amount",
                    "className": "bg-fee",
                    "width": "60px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        $(nTd).attr('data-id',row.id);
                        $(nTd).attr('data-name','油卡');
                        $(nTd).attr('data-key','freight_oil_card_amount');
                        $(nTd).attr('data-value',parseFloat(data));
                        $(nTd).attr('data-column-name','运价');
                        $(nTd).addClass('color-blue');
                        $(nTd).addClass('_bold');

                        if(row.is_completed != 1)
                        {
                            $(nTd).addClass('modal-show--for--info-text-set');
                            $(nTd).attr('data-text-type','text');
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        var $data = parseFloat(data);
                        if($data) return $data;
                        else return '--';
                    }
                },
                {
                    "title": "开票金额",
                    "data": "financial_receipt_for_invoice_amount",
                    "className": "bg-fee",
                    "width": "60px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        $(nTd).attr('data-id',row.id);
                        $(nTd).attr('data-name','开票金额');
                        $(nTd).attr('data-key','financial_receipt_for_invoice_amount');
                        $(nTd).attr('data-value',parseFloat(data));
                        $(nTd).attr('data-column-name','开票金额');
                        $(nTd).addClass('color-blue');
                        $(nTd).addClass('_bold');

                        if(row.is_completed != 1)
                        {
                            $(nTd).addClass('modal-show--for--info-text-set');
                            $(nTd).attr('data-text-type','text');
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        var $data = parseFloat(data);
                        if($data) return $data;
                        else return '--';
                    }
                },
                {
                    "title": "票点",
                    "data": "financial_receipt_for_invoice_point",
                    "className": "bg-fee",
                    "width": "60px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        $(nTd).attr('data-id',row.id);
                        $(nTd).attr('data-name','票点');
                        $(nTd).attr('data-key','financial_receipt_for_invoice_point');
                        $(nTd).attr('data-value',parseFloat(data));
                        $(nTd).attr('data-column-name','票点');
                        $(nTd).addClass('color-blue');
                        $(nTd).addClass('_bold');

                        if(row.is_completed != 1)
                        {
                            $(nTd).addClass('modal-show--for--info-text-set');
                            $(nTd).attr('data-text-type','text');
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        var $data = parseFloat(data);
                        if($data) return $data;
                        else return '--';
                    }
                },
                {
                    "title": "共建车费",
                    "data": "cooperative_vehicle_amount",
                    "className": "bg-fee",
                    "width": "60px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        $(nTd).attr('data-id',row.id);
                        $(nTd).attr('data-name','共建车费');
                        $(nTd).attr('data-key','cooperative_vehicle_amount');
                        $(nTd).attr('data-value',parseFloat(data));
                        $(nTd).attr('data-column-name','共建车费');
                        $(nTd).addClass('color-red');
                        $(nTd).addClass('_bold');

                        if(row.is_completed != 1)
                        {
                            $(nTd).addClass('modal-show--for--info-text-set');
                            $(nTd).attr('data-text-type','text');
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        var $data = parseFloat(data);
                        if($data) return $data;
                        else return '--';
                    }
                },
                {
                    "title": "请车价",
                    "data": "external_car_price",
                    "className": "bg-fee",
                    "width": "60px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        $(nTd).attr('data-id',row.id);
                        $(nTd).attr('data-name','请车价');
                        $(nTd).attr('data-key','external_car_price');
                        $(nTd).attr('data-value',parseFloat(data));
                        $(nTd).attr('data-column-name','请车价');
                        $(nTd).addClass('color-blue');
                        $(nTd).addClass('_bold');

                        if(row.is_completed != 1)
                        {
                            $(nTd).addClass('modal-show--for--info-text-set');
                            $(nTd).attr('data-text-type','text');
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        var $data = parseFloat(data);
                        if($data) return $data;
                        else return '--';
                    }
                },
                {
                    "title": "运费现金",
                    "name": "financial_receipt_for_freight_cash",
                    "data": "financial_receipt_for_freight_cash",
                    "className": "bg-fee",
                    "width": "60px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        $(nTd).attr('data-id',row.id);
                        $(nTd).attr('data-name','运费现金');
                        $(nTd).attr('data-key','financial_receipt_for_freight_cash');
                        $(nTd).attr('data-value',parseFloat(data));
                        $(nTd).addClass('color-green');

                        if(row.is_published != 0)
                        {
                            $(nTd).addClass('modal-show--for--order-finance');
                            $(nTd).attr('data-type','all');
                        }
                    },
                    render: function(data, type, row, meta) {
                        var $data = parseFloat(data);
                        if($data) return $data;
                        else return '--';
                    }
                },
                {
                    "title": "运费油卡",
                    "name": "financial_receipt_for_freight_oil_card",
                    "data": "financial_receipt_for_freight_oil_card",
                    "className": "bg-fee",
                    "width": "60px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        $(nTd).attr('data-id',row.id);
                        $(nTd).attr('data-name','运费油卡');
                        $(nTd).attr('data-key','financial_receipt_for_freight_oil_card');
                        $(nTd).attr('data-value',parseFloat(data));
                        $(nTd).addClass('color-green');

                        if(row.is_published != 0)
                        {
                            $(nTd).addClass('modal-show--for--order-finance');
                            $(nTd).attr('data-type','all');
                        }
                    },
                    render: function(data, type, row, meta) {
                        var $data = parseFloat(data);
                        if($data) return $data;
                        else return '--';
                    }
                },
                {
                    "title": "公里数",
                    "name": "oil_mileage",
                    "data": "oil_mileage",
                    "className": "bg-fee",
                    "width": "60px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        $(nTd).attr('data-id',row.id);
                        $(nTd).attr('data-name','公里数');
                        $(nTd).attr('data-key','oil_mileage');
                        $(nTd).attr('data-value',parseFloat(data));
                        $(nTd).addClass('color-red');
                    },
                    render: function(data, type, row, meta) {
                        var $data = parseFloat(data);
                        if($data) return $data;
                        else return '--';
                    }
                },
                {
                    "title": "油耗",
                    "name": "oil_consumption",
                    "data": "oil_consumption",
                    "className": "bg-fee",
                    "width": "60px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        $(nTd).attr('data-id',row.id);
                        $(nTd).attr('data-name','油耗');
                        $(nTd).attr('data-key','oil_consumption');
                        $(nTd).attr('data-value',parseFloat(data));
                        $(nTd).addClass('color-red');
                    },
                    render: function(data, type, row, meta) {
                        var $data = parseFloat(data);
                        if($data) return $data;
                        else return '--';
                    }
                },
                {
                    "title": "油价",
                    "name": "oil_unit_price",
                    "data": "oil_unit_price",
                    "className": "bg-fee",
                    "width": "60px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        $(nTd).attr('data-id',row.id);
                        $(nTd).attr('data-name','油价');
                        $(nTd).attr('data-key','oil_unit_price');
                        $(nTd).attr('data-value',parseFloat(data));
                        $(nTd).addClass('color-red');
                    },
                    render: function(data, type, row, meta) {
                        var $data = parseFloat(data);
                        if($data) return $data;
                        else return '--';
                    }
                },
                {
                    "title": "油费-油卡",
                    "name": "financial_fee_for_oil_card",
                    "data": "financial_fee_for_oil_card",
                    "className": "bg-fee",
                    "width": "60px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        $(nTd).attr('data-id',row.id);
                        $(nTd).attr('data-name','油费-油卡');
                        $(nTd).attr('data-key','financial_fee_for_oil_card');
                        $(nTd).attr('data-value',parseFloat(data));
                        $(nTd).addClass('color-red');
                    },
                    render: function(data, type, row, meta) {
                        var $data = parseFloat(data);
                        if($data) return $data;
                        else return '--';
                    }
                },
                {
                    "title": "油费-现金",
                    "name": "financial_fee_for_oil_cash",
                    "data": "financial_fee_for_oil_cash",
                    "className": "bg-fee",
                    "width": "60px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        $(nTd).attr('data-id',row.id);
                        $(nTd).attr('data-name','油费-现金');
                        $(nTd).attr('data-key','financial_fee_for_oil_cash');
                        $(nTd).attr('data-value',parseFloat(data));
                        $(nTd).addClass('color-red');
                    },
                    render: function(data, type, row, meta) {
                        var $data = parseFloat(data);
                        if($data) return $data;
                        else return '--';
                    }
                },
                {
                    "title": "油费",
                    "name": "financial_fee_for_oil_total",
                    "data": "financial_fee_for_oil_total",
                    "className": "bg-fee",
                    "width": "60px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        $(nTd).attr('data-id',row.id);
                        $(nTd).attr('data-name','费用');
                        $(nTd).attr('data-key','financial_fee_for_oil_total');
                        $(nTd).attr('data-value',parseFloat(data));
                        $(nTd).addClass('color-red');
                    },
                    render: function(data, type, row, meta) {
                        var $data = parseFloat(data);
                        if($data) return $data;
                        else return '--';
                    }
                },
                {
                    "title": "过路费",
                    "name": "financial_fee_for_toll_total",
                    "data": "financial_fee_for_toll_total",
                    "className": "bg-fee",
                    "width": "60px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        $(nTd).attr('data-id',row.id);
                        $(nTd).attr('data-name','费用');
                        $(nTd).attr('data-key','financial_fee_for_toll_total');
                        $(nTd).attr('data-value',parseFloat(data));
                        $(nTd).addClass('color-red');
                    },
                    render: function(data, type, row, meta) {
                        var $data = parseFloat(data);
                        if($data) return $data;
                        else return '--';
                    }
                },
                {
                    "title": "停车费",
                    "name": "financial_fee_for_parking",
                    "data": "financial_fee_for_parking",
                    "className": "bg-fee",
                    "width": "60px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        $(nTd).attr('data-id',row.id);
                        $(nTd).attr('data-name','停车费');
                        $(nTd).attr('data-key','financial_fee_for_parking');
                        $(nTd).attr('data-value',parseFloat(data));
                        $(nTd).addClass('color-red');
                    },
                    render: function(data, type, row, meta) {
                        var $data = parseFloat(data);
                        if($data) return $data;
                        else return '--';
                    }
                },
                {
                    "title": "工资",
                    "name": "financial_fee_for_salary",
                    "data": "financial_fee_for_salary",
                    "className": "bg-fee",
                    "width": "60px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        $(nTd).attr('data-id',row.id);
                        $(nTd).attr('data-name','工资');
                        $(nTd).attr('data-key','financial_fee_for_salary');
                        $(nTd).attr('data-value',parseFloat(data));
                        $(nTd).addClass('color-red');
                    },
                    render: function(data, type, row, meta) {
                        var $data = parseFloat(data);
                        if($data) return $data;
                        else return '--';
                    }
                },
                {
                    "title": "奖金",
                    "name": "financial_fee_for_bonus",
                    "data": "financial_fee_for_bonus",
                    "className": "bg-fee",
                    "width": "60px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        $(nTd).attr('data-id',row.id);
                        $(nTd).attr('data-name','奖金');
                        $(nTd).attr('data-key','financial_fee_for_bonus');
                        $(nTd).attr('data-value',parseFloat(data));
                        $(nTd).addClass('color-red');
                    },
                    render: function(data, type, row, meta) {
                        var $data = parseFloat(data);
                        if($data) return $data;
                        else return '--';
                    }
                },
                {
                    "title": "信息费",
                    "name": "financial_fee_for_information",
                    "data": "financial_fee_for_information",
                    "className": "bg-fee",
                    "width": "60px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        $(nTd).attr('data-id',row.id);
                        $(nTd).attr('data-name','信息费');
                        $(nTd).attr('data-key','financial_fee_for_information');
                        $(nTd).attr('data-value',parseFloat(data));
                        $(nTd).addClass('color-red');
                    },
                    render: function(data, type, row, meta) {
                        var $data = parseFloat(data);
                        if($data) return $data;
                        else return '--';
                    }
                },
                {
                    "title": "管理费",
                    "name": "financial_fee_for_administrative",
                    "data": "financial_fee_for_administrative",
                    "className": "bg-fee",
                    "width": "60px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        $(nTd).attr('data-id',row.id);
                        $(nTd).attr('data-name','管理费');
                        $(nTd).attr('data-key','financial_fee_for_administrative');
                        $(nTd).attr('data-value',parseFloat(data));
                        $(nTd).addClass('color-red');
                    },
                    render: function(data, type, row, meta) {
                        var $data = parseFloat(data);
                        if($data) return $data;
                        else return '--';
                    }
                },
                {
                    "title": "维修费",
                    "name": "financial_fee_for_repair_cost",
                    "data": "financial_fee_for_repair_cost",
                    "className": "bg-fee",
                    "width": "60px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        $(nTd).attr('data-id',row.id);
                        $(nTd).attr('data-name','维修费');
                        $(nTd).attr('data-key','financial_fee_for_repair_cost');
                        $(nTd).attr('data-value',parseFloat(data));
                        $(nTd).addClass('color-red');
                    },
                    render: function(data, type, row, meta) {
                        var $data = parseFloat(data);
                        if($data) return $data;
                        else return '--';
                    }
                },
                {
                    "title": "保养费",
                    "name": "financial_fee_for_maintenance_cost",
                    "data": "financial_fee_for_maintenance_cost",
                    "className": "bg-fee",
                    "width": "60px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        $(nTd).attr('data-id',row.id);
                        $(nTd).attr('data-name','保养费');
                        $(nTd).attr('data-key','financial_fee_for_maintenance_cost');
                        $(nTd).attr('data-value',parseFloat(data));
                        $(nTd).addClass('color-red');
                    },
                    render: function(data, type, row, meta) {
                        var $data = parseFloat(data);
                        if($data) return $data;
                        else return '--';
                    }
                },
                {
                    "title": "审核费",
                    "name": "financial_fee_for_inspection_cost",
                    "data": "financial_fee_for_inspection_cost",
                    "className": "bg-fee",
                    "width": "60px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        $(nTd).attr('data-id',row.id);
                        $(nTd).attr('data-name','审核费');
                        $(nTd).attr('data-key','financial_fee_for_inspection_cost');
                        $(nTd).attr('data-value',parseFloat(data));
                        $(nTd).addClass('color-red');
                    },
                    render: function(data, type, row, meta) {
                        var $data = parseFloat(data);
                        if($data) return $data;
                        else return '--';
                    }
                },
                {
                    "title": "过户费",
                    "name": "financial_fee_for_transfer_cost",
                    "data": "financial_fee_for_transfer_cost",
                    "className": "bg-fee",
                    "width": "60px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        $(nTd).attr('data-id',row.id);
                        $(nTd).attr('data-name','过户费');
                        $(nTd).attr('data-key','financial_fee_for_transfer_cost');
                        $(nTd).attr('data-value',parseFloat(data));
                        $(nTd).addClass('color-red');
                    },
                    render: function(data, type, row, meta) {
                        var $data = parseFloat(data);
                        if($data) return $data;
                        else return '--';
                    }
                },
                {
                    "title": "保险费",
                    "name": "financial_fee_for_insurance_cost",
                    "data": "financial_fee_for_insurance_cost",
                    "className": "bg-fee",
                    "width": "60px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        $(nTd).attr('data-id',row.id);
                        $(nTd).attr('data-name','保险费');
                        $(nTd).attr('data-key','financial_fee_for_insurance_cost');
                        $(nTd).attr('data-value',parseFloat(data));
                        $(nTd).addClass('color-red');
                    },
                    render: function(data, type, row, meta) {
                        var $data = parseFloat(data);
                        if($data) return $data;
                        else return '--';
                    }
                },
                {
                    "title": "贷款费用",
                    "name": "financial_fee_for_loan_cost",
                    "data": "financial_fee_for_loan_cost",
                    "className": "bg-fee",
                    "width": "60px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        $(nTd).attr('data-id',row.id);
                        $(nTd).attr('data-name','贷款费用');
                        $(nTd).attr('data-key','financial_fee_for_loan_cost');
                        $(nTd).attr('data-value',parseFloat(data));
                        $(nTd).addClass('color-red');
                    },
                    render: function(data, type, row, meta) {
                        var $data = parseFloat(data);
                        if($data) return $data;
                        else return '--';
                    }
                },
                {
                    "title": "总费用",
                    "name": "financial_expense_total",
                    "data": "financial_expense_total",
                    "className": "bg-fee",
                    "width": "60px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        $(nTd).attr('data-id',row.id);
                        $(nTd).attr('data-name','费用');
                        $(nTd).attr('data-key','financial_expense_total');
                        $(nTd).attr('data-value',parseFloat(data));
                    },
                    render: function(data, type, row, meta) {
                        var $data = parseFloat(data);
                        if($data) return $data;
                        else return '--';
                    }
                },
                {
                    "title": "订单扣款",
                    "name": "financial_deduction_total",
                    "data": "financial_deduction_total",
                    "className": "bg-fee",
                    "width": "60px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        $(nTd).attr('data-id',row.id);
                        $(nTd).attr('data-name','费用');
                        $(nTd).attr('data-key','financial_deduction_total');
                        $(nTd).attr('data-value',parseFloat(data));
                    },
                    render: function(data, type, row, meta) {
                        var $data = parseFloat(data);
                        if($data) return $data;
                        else return '--';
                    }
                },
                {
                    "title": "应收款",
                    "name": "financial_income_should",
                    "data": "id",
                    "className": "bg-fee",
                    "width": "60px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        var $income_should = parseFloat(row.freight_amount) - parseFloat(row.financial_deduction_total);

                        $(nTd).attr('data-id',row.id);
                        $(nTd).attr('data-name','应收款');
                        $(nTd).attr('data-key','financial_income_should');
                        $(nTd).attr('data-value',parseFloat($income_should));

                        if(row.is_published != 0)
                        {
                            // $(nTd).addClass('color-green');
                            $(nTd).addClass('item-modal-show--for--finance');
                            $(nTd).attr('data-type','all');
                        }
                    },
                    render: function(data, type, row, meta) {
                        var $income_should = parseFloat(row.freight_amount) - parseFloat(row.financial_deduction_total);
                        return parseFloat($income_should);
                    }
                },
                {
                    "title": "已收款",
                    "name": "financial_income_total",
                    "data": "financial_income_total",
                    "className": "bg-fee",
                    "width": "60px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        $(nTd).attr('data-id',row.id);
                        $(nTd).attr('data-name','已收款');
                        $(nTd).attr('data-key','financial_income_total');
                        $(nTd).attr('data-value',parseFloat(data));

                        if(row.is_published != 0)
                        {
                            // $(nTd).addClass('color-green');
                            $(nTd).addClass('item-modal-show--for--finance');
                            $(nTd).attr('data-type','all');
                        }
                    },
                    render: function(data, type, row, meta) {
                        return parseFloat(data);
                    }
                },
                {
                    "title": "待收款",
                    "name": "financial_income_pending",
                    "data": "id",
                    "className": "bg-fee _bold_",
                    "width": "60px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(row.is_published != 0)
                        {
                            var $income_pending = parseFloat(row.freight_amount) - parseFloat(row.financial_deduction_total) - parseFloat(row.financial_income_total);
                            // $(nTd).addClass('color-green');
                            $(nTd).addClass('item-modal-show--for--finance');
                            $(nTd).attr('data-type','all');
                            $(nTd).attr('data-id',row.id).attr('data-name','待收款');
                            $(nTd).attr('data-key','financial_income_pending').attr('data-value',parseFloat($income_pending));
                        }
                    },
                    render: function(data, type, row, meta) {
                        var $income_pending = parseFloat(row.freight_amount) - parseFloat(row.financial_deduction_total) - parseFloat(row.financial_income_total);
                        return parseFloat($income_pending);
                    }
                },
                {
                    "title": "利润",
                    "name": "financial_profit",
                    "data": "id",
                    "className": "bg-fee _bold_",
                    "width": "60px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(row.is_published != 0)
                        {
                            var $profit = parseFloat(row.freight_amount) - parseFloat(row.financial_deduction_total) - parseFloat(row.financial_expense_total);
                            // $(nTd).addClass('color-green');
                            $(nTd).addClass('item-modal-show--for--finance');
                            $(nTd).attr('data-type','all');
                            $(nTd).attr('data-id',row.id).attr('data-name','利润');
                            $(nTd).attr('data-key','financial_profit').attr('data-value',parseFloat($profit));
                        }
                    },
                    render: function(data, type, row, meta) {
                        var $profit = parseFloat(row.freight_amount) - parseFloat(row.financial_deduction_total) - parseFloat(row.financial_expense_total);
                        return parseFloat($profit);
                    }
                },


                {
                    "title": "安排人",
                    "name": "arrange_people",
                    "data": "arrange_people",
                    "className": "",
                    "width": "80px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(row.is_completed != 1)
                        {
                            $(nTd).addClass('modal-show--for--info-text-set');
                            $(nTd).attr('data-id',row.id).attr('data-name','安排人');
                            $(nTd).attr('data-key','arrange_people').attr('data-value',data);
                            $(nTd).attr('data-column-name','安排人');
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
                    "title": "收款人",
                    "name": "payee_name",
                    "data": "payee_name",
                    "className": "",
                    "width": "80px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(row.is_completed != 1)
                        {
                            $(nTd).addClass('modal-show--for--info-text-set');
                            $(nTd).attr('data-id',row.id).attr('data-name','收款人');
                            $(nTd).attr('data-key','payee_name').attr('data-value',data);
                            $(nTd).attr('data-column-name','收款人');
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
                    "title": "距离(km)",
                    "name": "transport_distance",
                    "data": "transport_distance",
                    "className": "bg-route",
                    "width": "60px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(row.is_completed != 1)
                        {
                            $(nTd).addClass('modal-show--for--info-text-set');
                            $(nTd).attr('data-id',row.id).attr('data-name','距离');
                            $(nTd).attr('data-key','transport_distance').attr('data-value',data);
                            $(nTd).attr('data-column-name','距离');
                            $(nTd).attr('data-text-type','text');
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        if(!data) return '';
                        else return data;
                    }
                },
                {
                    "title": "时效(H)",
                    "data": "transport_time_limitation",
                    "className": "",
                    "width": "60px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(row.is_completed != 1)
                        {
                            $(nTd).addClass('modal-show--for--info-text-set');
                            $(nTd).attr('data-id',row.id).attr('data-name','时效');
                            $(nTd).attr('data-key','time_limitation_prescribed').attr('data-value',data);
                            $(nTd).attr('data-column-name','时效');
                            $(nTd).attr('data-text-type','text');
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        return convertMinutesToHoursAndMinutes(data);
                    }
                },
                {
                    "title": "车货源",
                    "name": "car_supply",
                    "data": "car_supply",
                    "className": "",
                    "width": "80px",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(row.is_completed != 1)
                        {
                            $(nTd).addClass('modal-show--for--info-text-set');
                            $(nTd).attr('data-id',row.id).attr('data-name','车货源');
                            $(nTd).attr('data-key','car_supply').attr('data-value',data);
                            $(nTd).attr('data-column-name','车货源');
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
                    "title": "备注",
                    "data": "description",
                    "className": "text-left",
                    "width": "",
                    "orderable": false,
                    "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        if(row.is_completed != 1)
                        {
                            $(nTd).addClass('modal-show--for--info-text-set');
                            $(nTd).attr('data-id',row.id).attr('data-name','备注');
                            $(nTd).attr('data-key','description').attr('data-value',data);
                            $(nTd).attr('data-column-name','备注');
                            $(nTd).attr('data-text-type','textarea');
                            if(data) $(nTd).attr('data-operate-type','edit');
                            else $(nTd).attr('data-operate-type','add');
                        }
                    },
                    render: function(data, type, row, meta) {
                        return data;
//                            if(data) return '<small class="btn-xs bg-yellow">查看</small>';
//                            else return '';
                    }
                },

                {
                    "title": "创建人",
                    "data": "creator_id",
                    "className": "",
                    "width": "80px",
                    "orderable": false,
                    render: function(data, type, row, meta) {
                        return row.creator == null ? '未知' : '<a target="_blank" href="/user/'+row.creator.id+'">'+row.creator.name+'</a>';
                    }
                },
                {
                    "title": "操作",
                    "data": 'id',
                    "className": "",
                    "width": "200px",
                    "orderable": false,
                    render: function(data, type, row, meta) {

                        var $html_edit = '';
                        var $html_detail = '';
                        var $html_travel = '';
                        var $html_journey = '';
                        var $html_finance = '';
                        var $html_record = '';
                        var $html_delete = '';
                        var $html_publish = '';
                        var $html_abandon = '';
                        var $html_completed = '';
                        var $html_verified = '';
                        var $html_follow = '';
                        var $html_fee = '';
                        var $html_accounting = '';
                        var $html_operation_record = '<a class="btn btn-xs bg-default modal-show--for--order--item-operation-record" data-id="'+data+'">记录</a>';
                        var $html_fee_record = '<a class="btn btn-xs bg-default modal-show--for--order-fee-record" data-id="'+data+'">费用记录</a>';


                        $html_fee = '<a class="btn btn-xs modal-show--for--order--item-fee-create" data-id="'+data+'">费用</a>';
                        $html_accounting = '<a class="btn btn-xs modal-show--for--order--item-financial-accounting-set" data-id="'+data+'">财务核对</a>';



                        var $html =
                            $html_follow+
                            $html_fee+
                            $html_accounting+
                            $html_operation_record+
                            // $html_abandon+
                            '';
                        return $html;

                    }
                }
            ],
            "drawCallback": function (settings) {

                console.log('order-list.datatable-query.execute');

//                    let startIndex = this.api().context[0]._iDisplayStart;//获取本页开始的条数
//                    this.api().column(1).nodes().each(function(cell, i) {
//                        cell.innerHTML =  startIndex + i + 1;
//                    });

            },
            "language": { url: '/common/dataTableI18n' },
        });

        // window.dataTableInstances[table_Id] = table;

        // return table;
    }
</script>