<script>
    var Datatable__for__Order__Item__Fee_Record_List = function ($datatable_id,$id) {
        var datatableAjax_order_item_fee_record = function ($datatable_id,$id) {

            var dt_order__item__fee_record = $($datatable_id);
            if($.fn.DataTable.isDataTable(dt_order__item__fee_record))
            {
                // 已经初始化
                console.log($datatable_id + ' // 已经初始化');
                $(dt_order__item__fee_record).DataTable().destroy();
                dt_order__item__fee_record.DataTable().destroy();
            }
            else
            {
                // 未初始化
                console.log($datatable_id+' // 未初始化');
            }

            var ajax_datatable_order_item_fee_record = dt_order__item__fee_record.DataTable({
                "retrieve": true,
                "destroy": true,
//                "aLengthMenu": [[20, 50, 200, 500, -1], ["20", "50", "200", "500", "全部"]],
                "aLengthMenu": [[50], ["50"]],
                "bAutoWidth": false,
                "processing": true,
                "serverSide": true,
                "searching": false,
                "ajax": {
                    'url': "/o1/order/item-fee-record-list/datatable-query?id="+$id,
                    "type": 'POST',
                    "dataType" : 'json',
                    "data": function (d) {
                        d._token = $('meta[name="_token"]').attr('content');
                        d.name = $('input[name="order-fee-name"]').val();
                        d.title = $('input[name="order-fee-title"]').val();
                        d.keyword = $('input[name="order-fee-keyword"]').val();
                        d.type = $('select[name="order-fee-type"]').val();
                        d.status = $('select[name="order-fee-status"]').val();
                    },
                },
                "pagingType": "simple_numbers",
                "sDom": '<t> <"dataTables_length_box" l> <"dataTables_info_box" i> <"dataTables_paginate_box" p>',
                "order": [],
                "orderCellsTop": true,
                "columns": [
                    {
                        "title": "ID",
                        "data": "id",
                        "className": "",
                        "width": "60px",
                        "orderable": true,
                        "orderSequence": ["desc", "asc"],
                        "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                            if(row.is_completed != 1)
                            {
                                $(nTd).addClass('modal-show-for-attachment');
                                $(nTd).attr('data-id',row.id).attr('data-name',row.fee_name);
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
                        "data": "is_recorded",
                        "width": "80px",
                        "orderable": false,
                        render: function(data, type, row, meta) {
                            if(row.deleted_at != null)
                            {
                                return '<small class="btn-xs bg-black">已删除</small>';
                            }

                            if([1,99].includes(row.fee_type))
                            {
                                if(data == 0)
                                {
                                    return '<i class="fa fa-square-o text-orange"></i> 待入账';
                                }
                                else if(data == 1)
                                {
                                    return '<i class="fa fa-check-square-o text-black"></i> 已入账';
                                }
                                else
                                {
                                    return '<i class="fa fa-question-circle text-black"></i> 有误';
                                }
                            }
                            else
                            {
                                return '<i class="fa fa-minus-square-o text-black"></i> 无需记账';
                            }
                        }
                    },
                    // {
                    //     "title": "收支",
                    //     "data": "fee_category",
                    //     "width": "80px",
                    //     "orderable": false,
                    //     render: function(data, type, row, meta) {
                    //         if(data == 1)
                    //         {
                    //             return '<i class="fa fa-sign-in text-green"></i> 收入';
                    //         }
                    //         else if(data == 99)
                    //         {
                    //             return '<i class="fa fa-sign-out text-red"></i> 支出';
                    //         }
                    //         else
                    //         {
                    //             return '<i class="fa fa-question-circle text-black"></i> 有误';
                    //         }
                    //     }
                    // },
                    {
                        "title": "类型",
                        "data": "fee_type",
                        "width": "80px",
                        "orderable": false,
                        render: function(data, type, row, meta) {
                            if(data == 1)
                            {
                                return '<i class="fa fa-sign-in text-green"></i> 收入';
                            }
                            if(data == 99)
                            {
                                return '<i class="fa fa-sign-out text-red"></i> 费用';
                            }
                            else if(data == 101)
                            {
                                return '<i class="fa fa-info-circle text-red"></i> 订单扣款';
                            }
                            else if(data == 111)
                            {
                                return '<i class="fa fa-times-circle text-orange"></i> 司机罚款';
                            }
                            else
                            {
                                return '<i class="fa fa-question-circle text-black"></i> 有误';
                            }
                        }
                    },
                    {
                        "title": "时间",
                        "data": "fee_datetime",
                        "className": "",
                        "width": "120px",
                        "orderable": false,
                        render: function(data, type, row, meta) {
                            if(data) return data.slice(0, -3);
                            else return '';
                        }
                    },
                    {
                        "className": "",
                        "width": "80px",
                        "title": "名目",
                        "data": "fee_title",
                        "orderable": false,
                        "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                            $(nTd).attr('data-id',row.id);
                            $(nTd).attr('data-name','名目');
                            $(nTd).attr('data-key','fee_title');
                            $(nTd).attr('data-value',data);
                        },
                        render: function(data, type, row, meta) {
                            if(data) return data;
                            else return '--';
                        }
                    },
                    {
                        "title": "金额",
                        "data": "fee_amount",
                        "className": "",
                        "width": "80px",
                        "orderable": false,
                        "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                            $(nTd).attr('data-id',row.id);
                            $(nTd).attr('data-name','金额');
                            $(nTd).attr('data-key','fee_amount');
                            $(nTd).attr('data-value',data);
                        },
                        render: function(data, type, row, meta) {
                            if(data) return parseFloat(data);
                            else return '--';
                        }
                    },
                    {
                        "title": "客户",
                        "data": "client_id",
                        "className": "",
                        "width": "60px",
                        "orderable": false,
                        "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
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
                        "width": "60px",
                        "orderable": false,
                        "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
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
                        "title": "车辆",
                        "data": "car_id",
                        "className": "",
                        "width": "80px",
                        "orderable": false,
                        "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        },
                        render: function(data, type, row, meta) {
                            if(row.car_er)
                            {
                                return '<a href="javascript:void(0);" class="text-black">'+row.car_er.car_name+'</a>';
                            }
                            else return '--';
                        }
                    },
                    {
                        "title": "驾驶员",
                        "data": "driver_id",
                        "className": "",
                        "width": "60px",
                        "orderable": false,
                        "fnCreatedCell": function (nTd, data, row, iRow, iCol) {
                        },
                        render: function(data, type, row, meta) {
                            if(row.driver_er)
                            {
                                return '<a href="javascript:void(0);" class="text-black">'+row.driver_er.driver_name+'</a>';
                            }
                            else return '--';
                        }
                    },
                    {
                        "title": "说明",
                        "data": "fee_description",
                        "className": "text-left",
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
                            if(row.creator) return row.creator.name;
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

                            var $return = '';
                            // $return = $year+'-'+$month+'-'+$day;
                            // $return = $year+'-'+$month+'-'+$day+'&nbsp;&nbsp;'+$hour+':'+$minute;
                            // $return = $year+'-'+$month+'-'+$day+'&nbsp;&nbsp;'+$hour+':'+$minute+':'+$second;

                            var $currentYear = new Date().getFullYear();
                            if($year == $currentYear) $return = $month+'-'+$day+'&nbsp;&nbsp;'+$hour+':'+$minute;
                            else $return = $year+'-'+$month+'-'+$day+'&nbsp;&nbsp;'+$hour+':'+$minute;

                            return $return;
                        }
                    },
                    {
                        "title": "操作",
                        "data": "id",
                        "width": "100px",
                        "orderable": false,
                        render: function(data, type, row, meta) {
                            var $html_edit = '';
                            var $html_delete = '';
                            var $html_bookkeeping = '';

                            // 删除
                            if(row.deleted_at == null)
                            {
                                $html_delete = '<a class="btn btn-xs fee--item-delete-submit" data-id="'+data+'">删除</a>';
                                if([1,99].includes(row.fee_type) && row.is_recorded == 0)
                                {
                                    $html_bookkeeping = '<a class="btn btn-xs modal-show--for--fee--item-finance-bookkeeping" data-id="'+data+'">入账</a>';
                                }
                                else
                                {
                                    $html_bookkeeping = '<a class="btn btn-xs btn-default disabled" data-id="'+data+'">入账</a>';
                                }
                            }
                            else
                            {
                                $html_bookkeeping = '<a class="btn btn-xs btn-default disabled">入账</a>';
                                $html_delete = '<a class="btn btn-xs btn-default disabled">删除</a>';
                                // $html_delete = '<a class="btn btn-xs item-admin-restore-submit" data-id="'+data+'">恢复</a>';
                            }


                            $html_record = '<a class="btn btn-xs bg-purple- item-modal-show-for-modify" data-id="'+data+'">记录</a>';

                            var $html =
                                $html_edit+
                                $html_bookkeeping+
                                $html_delete+
                                // $html_record+
                                '';
                            return $html;
                        }
                    }
                ],
                "drawCallback": function (settings) {

                    console.log($datatable_id+' 数据加载完成');

//                    let startIndex = this.api().context[0]._iDisplayStart;//获取本页开始的条数
//                    this.api().column(0).nodes().each(function(cell, i) {
//                        cell.innerHTML =  startIndex + i + 1;
//                    });

                },
                "language": { url: '/common/dataTableI18n' },
            });


        };
        return {
            init: datatableAjax_order_item_fee_record
        }
    }();
</script>