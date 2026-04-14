<script>
    function Datatable__for__Car_Location_List($tableId)
    {
        let $that = $($tableId);
        let $datatable_wrapper = $that.parents('.datatable-wrapper');
        let $tableSearch = $datatable_wrapper.find('.datatable-search-box');

        $($tableId).DataTable({
            "aLengthMenu": [[200, 500], ["200", "500"]],
            "processing": true,
            "serverSide": false,
            "searching": false,
            "pagingType": "simple_numbers",
            "sDom": '<"dataTables_length_box"l> <"dataTables_info_box"i> <"dataTables_paginate_box"p> <t>',
            "order": [7,'desc'],
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
                    d.id = $tableSearch.find('input[name="car-id"]').val();
                    d.name = $tableSearch.find('input[name="car-name"]').val();
                    d.car_category = $tableSearch.find('select[name="car-category"]').val();
                    d.car_type = $tableSearch.find('select[name="car-type"]').val();
                    d.item_status = $tableSearch.find('select[name="car-item-status"]').val();
                },
            },
            "fixedColumns": {
                "leftColumns": "@if($is_mobile_equipment) 1 @else 2 @endif",
                "rightColumns": "1"
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
                    "title": "类型",
                    "data": 'car_category',
                    "width": "60px",
                    "orderable": false,
                    render: function(data, type, row, meta) {
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
                    render: function(data, type, row, meta) {
                        if(!data) return '--';
                        else return data;
                    }
                },
                {
                    "title": "车牌号",
                    "data": "name",
                    "className": "text-center",
                    "width": "120px",
                    "orderable": false,
                    render: function(data, type, row, meta) {
                        var $name = data;
                        if(row.pre_name) $name = data + ' (' + row.pre_name + ')';
                        return '<a class="car-control" data-id="'+row.id+'" data-title="'+data+'">'+$name+'</a>';
                    }
                },
                {
                    "title": "车型",
                    "data": "car_info_type",
                    "className": "text-center",
                    "width": "60px",
                    "orderable": false,
                    render: function(data, type, row, meta) {
                        if(!data) return '--';
                        else return data;
                    }
                },
                {
                    "title": "默认车挂",
                    "data": "trailer_id",
                    "className": "text-center",
                    "width": "180px",
                    "orderable": false,
                    render: function(data, type, row, meta) {
                        if(row.trailer_er == null) return '--';
                        else return '<a href="javascript:void(0);">'+row.trailer_er.name+' ('+row.trailer_er.sub_name+')'+'</a>';
                    }
                },
                {
                    "title": "默认主驾",
                    "data": "driver_id",
                    "className": "text-center",
                    "width": "150px",
                    "orderable": false,
                    render: function(data, type, row, meta) {
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
                    "title": "安排日期",
                    "data": "id",
                    "className": "text-center",
                    "width": "100px",
                    "orderable": true,
                    render: function(data, type, row, meta) {
                        if(!row.latest_order) return '--';
                        else return row.latest_order.assign_date;
                    }
                },
                {
                    "title": "出发地",
                    "data": "id",
                    "className": "text-center",
                    "width": "100px",
                    "orderable": true,
                    render: function(data, type, row, meta) {
                        if(!row.latest_order) return '--';
                        else return row.latest_order.transport_departure_place;
                    }
                },
                {
                    "title": "目的地",
                    "data": "id",
                    "className": "text-center",
                    "width": "100px",
                    "orderable": true,
                    render: function(data, type, row, meta) {
                        if(!row.latest_order) return '--';
                        else return row.latest_order.transport_destination_place;
                    }
                },
                {
                    "title": "线路",
                    "data": "id",
                    "className": "text-center",
                    "width": "80px",
                    "orderable": true,
                    render: function(data, type, row, meta) {
                        if(!row.latest_order) return '--';
                        else return row.latest_order.transport_route;
                    }
                },
                {
                    "title": "GPS位置",
                    "data": "location_address_format",
                    "className": "text-left",
                    "width": "600px",
                    "orderable": false,
                    render: function(data, type, row, meta) {
                        if(!data) return '--';
                        else return data;
                    }
                },
                {
                    "title": "GPS时间",
                    "data": "gps_time",
                    "className": "text-center",
                    "width": "120px",
                    "orderable": false,
                    render: function(data, type, row, meta) {
                        if(!data) return '--';
                        if(data == '0000-00-00 00:00:00') return '--';

                        var $date = new Date(data);
                        var $year = $date.getFullYear();
                        var $month = ('00'+($date.getMonth()+1)).slice(-2);
                        var $day = ('00'+($date.getDate())).slice(-2);
                        var $hour = ('00'+$date.getHours()).slice(-2);
                        var $minute = ('00'+$date.getMinutes()).slice(-2);
                        var $second = ('00'+$date.getSeconds()).slice(-2);

                        var $today = new Date();
                        var $currentYear = $today.getFullYear();
                        var $currentMonth = ('00'+($today.getMonth()+1)).slice(-2);
                        var $currentDay = ('00'+($today.getDate())).slice(-2);

                        if($year == $currentYear && $month == $currentMonth && $day == $currentDay) return '今天 '+$hour+':'+$minute;
                        if($year == $currentYear) return $month+'-'+$day+'&nbsp;&nbsp;'+$hour+':'+$minute;
                        else return $year+'-'+$month+'-'+$day+'&nbsp;&nbsp;'+$hour+':'+$minute;
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