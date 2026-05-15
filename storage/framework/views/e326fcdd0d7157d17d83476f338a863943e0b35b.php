<script>
    $(function() {

        // 【工单】控制逻辑
        $(".main-wrapper").on('dblclick', function() {
            // 双击后的操作
            // $(this).trigger('click');
        });
        $(".main-wrapper").on('click', ".order--item--page--control", function() {

            var $that = $(this);
            var $id = $that.data('id');
            var $title = $that.data('title');
            var $content = $that.data('content');
            var $icon = $that.data('icon');

            var $order_id = 'order-' + $id;
            var $chart_id = "eChart-id--by--order-" + $id;

            var $clone_class = 'order--item-page--clone';

            var $form_id__for__item_info = 'form--for--order-page--item-info--by--order-' + $id;
            var $form_id__for__item_fee = 'form--for--order-page--item-fee--by--order-' + $id;
            var $form_id__for__item_accounting = 'form--for--order-page--item-accounting--by--order-' + $id;
            var $datatable_id__for__fee = 'datatable--for--order-page--item--fee-record-list--by--order-' + $id;
            var $datatable_id__for__operation = 'datatable--for--order-page--item--operation-record-list--by--order-' + $id;

            // $(".nav-header-title").html($btn.data('title'));

            var $config = {
                type: $that.data('type'),
                unique: 'y',
                id: $order_id,
                target: $order_id,
                title: $title,
                content: $content,
                icon: '<i class="fa fa-male text-red"></i>'
            };

            var $tabLink = $('a[href="#'+ $order_id +'"]');
            var $tabPane = $('#' + $order_id);

            if($tabPane.length)
            {
                console.log('#' + $order_id + '已存在！');
                // 存在则激活
                $tabLink.tab('show');
            }
            else
            {
                console.log('#' + $order_id + '不存在！');
                // 创建新标签页
                createTab($config);
                // 激活新标签页
                $('a[href="#' + $order_id + '"]').tab('show');

                var $clone = $('.'+$clone_class).clone(true);
                $clone.removeClass($clone_class);

                $clone.data('item-id',$id);
                $clone.find('input[name="operate[id]"]').val($id);

                $clone.data('form-id--for--info',$form_id__for__item_info);
                $clone.data('form-id--for--fee',$form_id__for__item_info);
                $clone.data('form-id--for--accounting',$form_id__for__item_info);
                $clone.data('datatable-id--for--operation',$datatable_id__for__operation);
                $clone.data('datatable-id--for--fee',$datatable_id__for__fee);

                $clone.find('.form--for--info').attr('id',$form_id__for__item_info);
                $clone.find('.form--for--fee').attr('id',$form_id__for__item_fee);
                $clone.find('.form--for--accounting').attr('id',$form_id__for__item_accounting);
                $clone.find('.datatable--for--operation').attr('id',$datatable_id__for__operation);
                $clone.find('.datatable--for--fee').attr('id',$datatable_id__for__fee);

                $('#'+$order_id).prepend($clone);

                createTabInit($config);

                var $target = $('#'+$config.target);

                //
                $.post(
                    "<?php echo nl2br(e(url('/o1/order/item-get'))); ?>",
                    {
                        _token: $('meta[name="_token"]').attr('content'),
                        operate: "item-get",
                        item_type: "order",
                        item_id: $id
                    },
                    'json'
                )
                    .done(function($response, status, jqXHR) {

                        $response = JSON.parse($response);
                        if(!$response.success)
                        {
                            if($response.msg) layer.msg($response.msg);
                        }
                        else
                        {
                            var $item = $response.data;
                            order_edit__for__item_info($target.find('#'+$form_id__for__item_info),$item);
                            order_edit__for__item_accounting($target.find('#'+$form_id__for__item_accounting),$item);

                        }
                    })
                    .fail(function(jqXHR, status, error) {
                        layer.msg('服务器错误！');

                    })
                    .always(function(jqXHR, status) {
                        layer.closeAll('loading');
                    });

                form_reset__by__object($('#'+$order_id).find('#'+$form_id__for__item_fee));


                if($.fn.DataTable.isDataTable('#'+$datatable_id__for__fee))
                {
                    console.log($config.id);
                    console.log('【'+$datatable_id__for__fee+'】DataTable 已存在！');
                }
                else
                {
                    console.log($config.id);
                    console.log('【'+$datatable_id__for__fee+'】DataTable 未初始化！');

                    Datatable__for__Order__Item__Fee_Record_List.init('#'+$datatable_id__for__fee,$id);
                    Datatable__for__Order__Item__Operation_Record_List.init('#'+$datatable_id__for__operation,$id);

                    $clone.addClass('page-wrapper');
                    // $clone.find('table').attr('id',$datatable_id);
                    // $clone.find('table.table--for--order').attr('id',$datatable_id_for_order);
                    $clone.find('table.table--for--fee').attr('id',$datatable_id__for__fee);
                    $clone.find('input[name="statistic-driver-by-daily-driver-id"]').val($id);
                    $clone.find('.eChart').attr('id',$chart_id);


                    // Datatable__for__Statistic_Staff_Daily('#'+$datatable_id,$chart_id);

                    // Datatable_Statistic_of_Driver_by_Daily_for_Order('#'+$datatable_id_for_order,$chart_id);
                    // Datatable_Statistic_of_Driver_by_Daily_for_Fee('#'+$datatable_id__for__fee,$chart_id);
                }
            }


            // data-datatable-id="datatable-location-list"
            // data-datatable-target="location-list"
            // data-datatable-clone-object="location-list-clone"
            // data-chart-id="eChart-statistic-company-daily"





        });




        // 【工单】【编辑】提交
        $(".main-content").off('click', ".submit--for--order--item-edit").on('click', ".submit--for--order--item-edit", function() {
            var $that = $(this);
            var $page_wrapper = $that.closest('.page-wrapper');
            var $form_wrapper = $that.closest('.form-wrapper');
            var $form = $form_wrapper.find('form');


            var $index = layer.load(1, {
                shade: [0.3, '#fff'],
                content: '<span class="loadtip">正在提交</span>',
                success: function (layer) {
                    layer.find('.layui-layer-content').css({
                        'padding-top': '40px',
                        'width': '100px',
                    });
                    layer.find('.loadtip').css({
                        'font-size':'20px',
                        'margin-left':'-18px'
                    });
                }
            });

            var options = {
                url: "<?php echo nl2br(e(url('/o1/order/item-save'))); ?>",
                type: "post",
                dataType: "json",
                // target: "#div2",
                // clearForm: true,
                // restForm: true,
                success: function ($response, status, xhr, $form) {
                    // 请求成功时的回调
                    if(!$response.success)
                    {
                        layer.msg($response.msg);
                    }
                    else
                    {
                        layer.msg($response.msg);
                        // order_page__refresh($page_wrapper,$response.data.order);
                        order_edit__for__item_accounting($page_wrapper.find('.form--for--accounting'),$response.data.order);
                    }
                },
                error: function(xhr, status, error, $form) {
                    // 请求失败时的回调
                    layer.closeAll('loading');
                    layer.msg('服务器错误！');
                },
                complete: function(xhr, status, $form) {
                    // 无论成功或失败都会执行的回调
                    layer.closeAll('loading');
                }
            };
            $form.ajaxSubmit(options);
        });


        // 【工单】【编辑】radio 选择车辆所属
        $(".main-wrapper").on('click', ".form--for--info input[name=car_owner_type]", function() {
            var $that = $(this);
            var $form_wrapper = $that.parents('form');
            // checkbox
//            if($(this).is(':checked'))
//            {
//                $('.').show();
//            }
//            else
//            {
//                $('.').hide();
//            }
            // radio
            var $value = $(this).val();
            if($value == 1)
            {
                $form_wrapper.find('.internal-car').show();
                $form_wrapper.find('.external-car').hide();
            }
            else if($value == 9)
            {
                $form_wrapper.find('.internal-car').show();
                $form_wrapper.find('.external-car').hide();
            }
            else if($value == 11)
            {
                $form_wrapper.find('.external-car').show();
                $form_wrapper.find('.internal-car').hide();
            }
            else
            {
                $form_wrapper.find('.external-car').hide();
                $form_wrapper.find('.internal-car').hide();
            }
        });

        // 【工单】【编辑】select2 选择项目
        $('.main-wrapper').on('select2:select', '.form--for--info select[name="project_id"]', function(e) {
            var $that = $(this);
            var $form_wrapper = $that.parents('form');

            // 金额
            $form_wrapper.find('input[name=freight_amount]').val(parseFloat(e.params.data.freight_amount));
            $form_wrapper.find('input[name=financial_receipt_for_invoice_amount]').val(parseFloat(e.params.data.invoice_amount));
            $form_wrapper.find('input[name=financial_receipt_for_invoice_point]').val(parseFloat(e.params.data.invoice_point));
            // 线路-出发地-目的地
            $form_wrapper.find('input[name=transport_route]').val(e.params.data.transport_route);
            $form_wrapper.find('input[name=transport_departure_place]').val(e.params.data.transport_departure_place);
            $form_wrapper.find('input[name=transport_destination_place]').val(e.params.data.transport_destination_place);
            // 距离
            $form_wrapper.find('input[name=transport_distance]').val(e.params.data.transport_distance);
            // 时效
            var $transport_time_limitation = parseFloat((e.params.data.transport_time_limitation / 60).toFixed(2));
            $form_wrapper.find('input[name=transport_time_limitation]').val($transport_time_limitation);
        });

        // 【工单】【编辑】select2 选择车辆
        $('.main-wrapper').on('select2:select', '.form--for--info select[name="car_id"]', function(e) {

            console.log("用户选择了:", e.params.data); // 仅触发1次

            var $that = $(this);
            var $form_wrapper = $that.parents('form');

            var $id = $(this).val();
            var $data = e.params.data;


            // 挂 (select2)
            // $form_wrapper.find('select[name="trailer_id"]').val(null).trigger('change');
            select2FirstOptionSelected($form_wrapper.find('select[name="trailer_id"]'));
            if($data.trailer_er)
            {
                var $data_trailer_er = $data.trailer_er;
                var $trailer_html = $data_trailer_er.name;
                if($data_trailer_er.sub_name) $trailer_html += ' ('+$data_trailer_er.sub_name+')';

                var $trailer_option = new Option($trailer_html, $data.trailer_id, true, true);
                $form_wrapper.find('select[name="trailer_id"]').append($trailer_option).trigger('change');
            }


            // 驾驶员
            $form_wrapper.find('input[name=driver_name]').val('');
            $form_wrapper.find('input[name=driver_phone]').val('');
            $form_wrapper.find('input[name=copilot_name]').val('');
            $form_wrapper.find('input[name=copilot_phone]').val('');


            // 主驾 (select2)
            // $form_wrapper.find('select[name="driver_id"]').val(null).trigger('change');
            select2FirstOptionSelected($form_wrapper.find('select[name="driver_id"]'));
            if($data.driver_er)
            {
                var $driver_er = $data.driver_er;
                var $driver_html = $driver_er.driver_name + '('+$driver_er.driver_phone+')';

                var $driver_option = new Option($driver_html, $data.driver_id, true, true);
                $form_wrapper.find('select[name="driver_id"]').append($driver_option).trigger('change');

                $form_wrapper.find('input[name=driver_name]').val($driver_er.driver_name);
                $form_wrapper.find('input[name=driver_phone]').val($driver_er.driver_phone);
            }
            // 副驾 (select2)
            select2FirstOptionSelected($form_wrapper.find('select[name="copilot_id"]'));
            if($data.copilot_er)
            {
                var $copilot_er = $data.copilot_er;
                var $copilot_html = $copilot_er.driver_name + '('+$copilot_er.driver_phone+')';

                var $copilot_option = new Option($copilot_html, $data.copilot_id, true, true);
                $form_wrapper.find('select[name="copilot_id"]').append($copilot_option).trigger('change');

                $form_wrapper.find('input[name=copilot_name]').val($copilot_er.driver_name);
                $form_wrapper.find('input[name=copilot_phone]').val($copilot_er.driver_phone);
            }

        });

        // 【工单】【编辑】select2 选择主驾
        $('.main-wrapper').on('select2:select', '.form--for--info select[name="driver_id"]', function(e) {

            console.log("用户选择了:", e.params.data); // 仅触发1次

            var $that = $(this);
            var $form_wrapper = $that.parents('form');

            var $that_name = $that.attr('name');
            if($that_name == 'driver_id')
            {
                $form_wrapper.find('input[name=driver_name]').val(e.params.data.driver_name);
                $form_wrapper.find('input[name=driver_phone]').val(e.params.data.driver_phone);
            }
            else if($that_name == 'copilot_id')
            {
                $form_wrapper.find('input[name=copilot_name]').val(e.params.data.text);
                $form_wrapper.find('input[name=copilot_phone]').val(e.params.data.driver_phone);
            }
        });

        // 【工单】【编辑】select2 选择副驾
        $('.main-wrapper').on('select2:select', '.form--for--info select[name="copilot_id"]', function(e) {

            console.log("用户选择了:", e.params.data); // 仅触发1次

            var $that = $(this);
            var $form_wrapper = $that.parents('form');

            var $that_name = $that.attr('name');
            if($that_name == 'driver_id')
            {
                $form_wrapper.find('input[name=copilot_name]').val(e.params.data.driver_name);
                $form_wrapper.find('input[name=copilot_phone]').val(e.params.data.driver_phone);
            }
            else if($that_name == 'copilot_id')
            {
                $form_wrapper.find('input[name=copilot_name]').val(e.params.data.driver_name);
                $form_wrapper.find('input[name=copilot_phone]').val(e.params.data.driver_phone);
            }
        });









        // 【工单】【添加费用】提交
        $(".main-wrapper").off('click', ".submit--for--order--item-fee-create").on('click', ".submit--for--order--item-fee-create", function() {
            var $that = $(this);
            var $page_wrapper = $that.closest('.page-wrapper');
            var $form_wrapper = $that.closest('.form-wrapper');
            var $form = $form_wrapper.find('form');
            var $datatable_id__for__fee = $page_wrapper.data('datatable-id--for--fee');


            var $index = layer.load(1, {
                shade: [0.3, '#fff'],
                content: '<span class="loadtip">正在提交</span>',
                success: function (layer) {
                    layer.find('.layui-layer-content').css({
                        'padding-top': '40px',
                        'width': '100px',
                    });
                    layer.find('.loadtip').css({
                        'font-size':'20px',
                        'margin-left':'-18px'
                    });
                }
            });

            var options = {
                url: "<?php echo nl2br(e(url('/o1/order/item-fee-save'))); ?>",
                type: "post",
                dataType: "json",
                // target: "#div2",
                // clearForm: true,
                // restForm: true,
                success: function ($response, status, xhr, $form) {
                    // 请求成功时的回调
                    if(!$response.success)
                    {
                        layer.msg($response.msg);
                    }
                    else
                    {
                        layer.msg($response.msg);

                        $('#'+$datatable_id__for__fee).DataTable().ajax.reload(null,false);
                        // order_page__refresh($page_wrapper,$response.data.item);
                    }
                },
                error: function(xhr, status, error, $form) {
                    // 请求失败时的回调
                    layer.closeAll('loading');
                    layer.msg('服务器错误！');
                },
                complete: function(xhr, status, $form) {
                    // 无论成功或失败都会执行的回调
                    layer.closeAll('loading');
                }
            };
            $form.ajaxSubmit(options);
        });


        // 【工单】【添加费用】费用类型
        $(".main-wrapper").on('change', '.form--for--fee input[name="fee-type"]', function() {
            console.log('.main-wrapper .form--for--fee input[name="fee-type"] -- change');

            var $that = $(this);
            var $form_wrapper = $that.closest('form');

            var $value = $(this).val();
            if($value == 1)
            {
                // 收款
                $form_wrapper.find('input[name="fee-record-type"][value="81"]').prop('checked', true).trigger('change');
                $form_wrapper.find('.fee-record-type-box').show();
                $form_wrapper.find('.collection-box').show();
                $form_wrapper.find('.advance-box').hide();
                $form_wrapper.find('.fee-title-box').hide();
                $form_wrapper.find('.receipt-box').show();
            }
            else if($value == 99)
            {
                // 费用
                $form_wrapper.find('input[name="fee-record-type"][value="81"]').prop('checked', true).trigger('change');
                $form_wrapper.find('.fee-record-type-box').show();
                $form_wrapper.find('.advance-box').show();
                $form_wrapper.find('.collection-box').hide();
                $form_wrapper.find('.fee-title-box').hide();
                $form_wrapper.find('.fee-box').show();
            }
            else if($value == 101)
            {
                // 订单扣款
                $form_wrapper.find('input[name="fee-record-type"][value="1"]').prop('checked', true).trigger('change');
                $form_wrapper.find('.fee-record-type-box').hide();
                // $form_wrapper.find('.payment-show').hide();
                $form_wrapper.find('.fee-title-box').hide();
                $form_wrapper.find('.deduction-box').show();
            }
            else if($value == 111)
            {
                // 员工罚款
                $form_wrapper.find('input[name="fee-record-type"][value="1"]').prop('checked', true).trigger('change');
                $form_wrapper.find('.fee-record-type-box').hide();
                $form_wrapper.find('.payment-show').hide();
                $form_wrapper.find('.fee-title-box').hide();
                $form_wrapper.find('.fine-box').show();
            }
            else
            {
                $form_wrapper.find('input[name="fee-record-type"][value="1"]').prop('checked', true).trigger('change');
                $form_wrapper.find('.fee-record-type-box').hide();
                // $form_wrapper.find('.payment-show').hide();
            }
        });
        // 【工单】【添加费用】记录类型
        $(".main-wrapper").on('change', '.form--for--fee input[name="fee-record-type"]', function() {
            console.log('.main-wrapper .form--for--fee input[name="fee-record-type"] -- change');

            var $that = $(this);
            var $form_wrapper = $that.closest('form');

            var $value = $(this).val();
            if($value == 81)
            {
                $form_wrapper.find('.payment-show').show();
            }
            else
            {
                // $form_wrapper.find('.payment-show').hide();
                $form_wrapper.find('.payment-show').show();
            }
        });








        // 【工单】【财务核算】手工提交
        $(".main-wrapper").off('click', ".submit--for--order--item-accounting-set").on('click', ".submit--for--order--item-accounting-set", function() {
            var $that = $(this);
            var $page_wrapper = $that.closest('.page-wrapper');
            var $form_wrapper = $that.closest('.form-wrapper');
            var $form = $form_wrapper.find('form');

            var $index = layer.load(1, {
                shade: [0.3, '#fff'],
                content: '<span class="loadtip">正在提交</span>',
                success: function (layer) {
                    layer.find('.layui-layer-content').css({
                        'padding-top': '40px',
                        'width': '100px',
                    });
                    layer.find('.loadtip').css({
                        'font-size':'20px',
                        'margin-left':'-18px'
                    });
                }
            });

            var options = {
                url: "<?php echo nl2br(e(url('/o1/order/item-financial--accounting-save'))); ?>",
                type: "post",
                dataType: "json",
                // target: "#div2",
                // clearForm: true,
                // restForm: true,
                success: function ($response, status, xhr, $form) {
                    // 请求成功时的回调
                    if(!$response.success)
                    {
                        layer.msg($response.msg);
                    }
                    else
                    {
                        layer.msg($response.msg);
                        // order_page__refresh($page_wrapper,$response.data.order);
                        order_edit__for__item_info($page_wrapper.find('.form--for--info'),$response.data.order);
                        order_edit__for__item_accounting($page_wrapper.find('.form--for--accounting'),$response.data.order);
                    }
                },
                error: function(xhr, status, error, $form) {
                    // 请求失败时的回调
                    layer.msg('服务器错误！');
                },
                complete: function(xhr, status, $form) {
                    // 无论成功或失败都会执行的回调
                    layer.closeAll('loading');
                }
            };
            $form.ajaxSubmit(options);
        });

        // 【工单】【财务核算】一键核对
        $(".main-wrapper").off('click', ".submit--for--order--item-one-click-calculation").on('click', ".submit--for--order--item-one-click-calculation", function() {
            var $that = $(this);
            var $page_wrapper = $that.closest('.page-wrapper');
            var $item_id = $page_wrapper.data('item-id');
            var $form_wrapper = $that.closest('.form-wrapper');
            var $form = $form_wrapper.find('form');


            // layer.msg('确定"一键核对"么?', {
            //     time: 0
            //     ,btn: ['确定', '取消']
            //     ,yes: function(index)
            //     {
            //         layer.close(index);

                    //
                    var $index = layer.load(1, {
                        shade: [0.3, '#fff'],
                        content: '<span class="loadtip">正在提交</span>',
                        success: function (layer) {
                            layer.find('.layui-layer-content').css({
                                'padding-top': '40px',
                                'width': '100px',
                            });
                            layer.find('.loadtip').css({
                                'font-size':'20px',
                                'margin-left':'-18px'
                            });
                        }
                    });

                    //
                    $.post(
                        "<?php echo nl2br(e(url('/o1/order/item-financial--one-click-calculation'))); ?>",
                        {
                            _token: $('meta[name="_token"]').attr('content'),
                            operate: "order--item-financial--one-click-calculation",
                            item_id: $item_id
                        },
                        'json'
                    )
                        .done(function($response, status, jqXHR) {
                            console.log('#'+$that.attr('id')+'.post.done.');

                            $response = JSON.parse($response);
                            if(!$response.success)
                            {
                                if($response.msg) layer.msg($response.msg);
                            }
                            else
                            {
                                order_edit__for__item_accounting($form,$response.data.order);
                            }
                        })
                        .fail(function(jqXHR, status, error) {
                            layer.msg('服务器错误！');

                        })
                        .always(function(jqXHR, status) {
                            layer.closeAll('loading');
                        });
        //         }
        //     });
        });

    });




    function order_edit__for__item_info($target,$item)
    {
        console.log('function order_edit__for__item_info() execute.');
        // console.log($target);

        form_reset__by__object($target);

        $target.find('input[name="operate[type]"]').val('edit');
        $target.find('input[name="operate[id]"]').val($item.id);

        $target.find('input[name="assign_date"]').val($item.assign_date);
        $target.find('input[name="task_date"]').val($item.task_date);
        $target.find('input[name="task_number"]').val($item.task_number);

        // 订单类型
        $target.find('input[name="order_type"]').prop('checked', false);
        $target.find('input[name="order_type"][value="'+$item.order_type+'"]').prop('checked', true).trigger('change');

        // select2 客户
        if($item.client_er)
        {
            $target.find('#order-edit-select2-client').append(new Option($item.client_er.name, $item.client_id, true, true)).trigger('change');
        }

        // select2 项目
        if($item.project_er)
        {
            $target.find('select[name="project_id"]').append(new Option($item.project_er.name, $item.project_id, true, true)).trigger('change');
        }

        // select
        $target.find('select[name="client_type"]').val($item.client_type).trigger('change');

        $target.find('input[name="transport_route"]').val($item.transport_route);
        $target.find('input[name="transport_departure_place"]').val($item.transport_departure_place);
        $target.find('input[name="transport_destination_place"]').val($item.transport_destination_place);

        // 距离
        $target.find('input[name="transport_distance"]').val($item.transport_distance);
        // 时效
        var $transport_time_limitation = parseFloat(($item.transport_time_limitation / 60).toFixed(2));
        $target.find('input[name="transport_time_limitation"]').val($transport_time_limitation);

        // 运费 & 油卡 & 串点车费 & 共建车费 & 信息费
        $target.find('input[name="freight_amount"]').val(parseFloat($item.freight_amount));
        $target.find('input[name="freight_oil_card_amount"]').val(parseFloat($item.freight_oil_card_amount));
        $target.find('input[name="freight_extra_amount"]').val(parseFloat($item.freight_extra_amount));
        $target.find('input[name="cooperative_vehicle_amount"]').val(parseFloat($item.cooperative_vehicle_amount));
        $target.find('input[name="financial_fee_for_information"]').val(parseFloat($item.financial_fee_for_information));

        $target.find('input[name="financial_receipt_for_invoice_amount"]').val(parseFloat($item.financial_receipt_for_invoice_amount));
        $target.find('input[name="financial_receipt_for_invoice_point"]').val(parseFloat($item.financial_receipt_for_invoice_point));


        // radio 车辆所属
        $target.find('input[name="car_owner_type"]').prop('checked', false);
        $target.find('input[name="car_owner_type"][value="'+$item.car_owner_type+'"]').prop('checked', true).trigger('change');
        if($item.car_owner_type == 1)
        {
            $('.internal-car').show();
            $('.external-car').hide();
        }
        else if($item.car_owner_type == 9)
        {
            $('.internal-car').show();
            $('.external-car').hide();
        }
        else if($item.car_owner_type == 11)
        {
            $('.internal-car').hide();
            $('.external-car').show();
        }

        // 车型
        $target.find('select[name="car_type"]').val($item.car_type).trigger('change');

        // select2 车辆
        if($item.car_er)
        {
            $target.find('select[name="car_id"]').append(new Option($item.car_er.name, $item.car_id, true, true)).trigger('change');
        }
        // select2 车挂
        if($item.trailer_er)
        {
            var $data_trailer_er = $item.trailer_er;
            var $trailer_html = $data_trailer_er.name;
            if($data_trailer_er.sub_name) $trailer_html += ' ('+$data_trailer_er.sub_name+')';
            var $trailer_option = new Option($trailer_html, $item.trailer_id, true, true);
            $target.find('select[name="trailer_id"]').append($trailer_option).trigger('change');
        }

        // select2 驾驶员
        $target.find('input[name=driver_name]').val('');
        $target.find('input[name=driver_phone]').val('');
        $target.find('input[name=copilot_name]').val('');
        $target.find('input[name=copilot_phone]').val('');
        // select2 主驾
        select2FirstOptionSelected($target.find('select[name="driver_id"]'));
        if($item.driver_er)
        {
            //     $target.find('select[name="driver_id"]').append(new Option($item.driver_er.driver_name, $item.driver_id, true, true)).trigger('change');

            var $data_driver_er = $item.driver_er;
            // var $driver_html = $data_driver_er.driver_name
            //     + '('+$data_driver_er.driver_phone+')'
            //     + ' - '
            //     + $data_driver_er.copilot_name
            //     + '('+$data_driver_er.copilot_phone+')';
            // var $driver_html = $data_driver_er.driver_name + '('+$data_driver_er.driver_phone+')';
            var $driver_html = $data_driver_er.driver_name;
            var $driver_option = new Option($driver_html, $item.driver_id, true, true);
            $target.find('select[name="driver_id"]').append($driver_option).trigger('change');
        }

        // select2 副驾
        select2FirstOptionSelected($target.find('select[name="copilot_id"]'));
        if($item.copilot_er)
        {
            // $target.find('select[name="copilot_id"]').append(new Option($item.copilot_er.driver_name, $item.copilot_id, true, true)).trigger('change');

            var $data_copilot_er = $item.copilot_er;
            // var $copilot_html = $data_copilot_er.driver_name + '('+$data_copilot_er.driver_phone+')';
            var $copilot_html = $data_copilot_er.driver_name;
            var $copilot_option = new Option($copilot_html, $item.copilot_id, true, true);
            $target.find('select[name="copilot_id"]').append($copilot_option).trigger('change');
        }

        $target.find('input[name=driver_name]').val($item.driver_name);
        $target.find('input[name=driver_phone]').val($item.driver_phone);
        $target.find('input[name=copilot_name]').val($item.copilot_name);
        $target.find('input[name=copilot_phone]').val($item.copilot_phone);


        $target.find('input[name="external_car_price"]').val(parseFloat($item.external_car_price));
        $target.find('input[name="external_car"]').val($item.external_car);
        $target.find('input[name="external_trailer"]').val($item.external_trailer);

        $target.find('input[name="driver_name"]').val($item.driver_name);
        $target.find('input[name="driver_phone"]').val($item.driver_phone);
        $target.find('input[name="copilot_name"]').val($item.copilot_name);
        $target.find('input[name="copilot_phone"]').val($item.copilot_phone);

        $target.find('input[name="arrange_people"]').val($item.arrange_people);
        $target.find('input[name="payee_name"]').val($item.payee_name);
        $target.find('input[name="car_supply"]').val($item.car_supply);

        $target.find('textarea[name="description"]').val($item.description);

        $target.find('input[name="field_2"]').prop('checked', false);
        $target.find('input[name="field_2"][value="'+$item.field_2+'"]').prop('checked', true).trigger('change');

    }

    
    function order_edit__for__item_accounting($target,$item)
    {
        console.log('function order_edit__for__item_accounting() execute.');

        form_reset__by__object($target);

        $target.find('input[name="operate[type]"]').val('edit');
        $target.find('input[name="operate[id]"]').val($item.id);

        // $target.find('input[name="accounting_freight_cash"]').val(parseFloat($item.financial_receipt_for_freight_cash));
        // $target.find('input[name="accounting_freight_oil_card"]').val(parseFloat($item.financial_receipt_for_freight_oil_card));
        $target.find('input[name="accounting_freight_cash"]').val(parseFloat($item.freight_amount));
        $target.find('input[name="accounting_freight_oil_card"]').val(parseFloat($item.freight_oil_card_amount));
        $target.find('input[name="accounting_freight_extra_amount"]').val(parseFloat($item.freight_extra_amount));
        $target.find('input[name="accounting_cooperative_vehicle_amount"]').val(parseFloat($item.cooperative_vehicle_amount));
        $target.find('input[name="accounting_external_car_price"]').val(parseFloat($item.external_car_price));

        $target.find('input[name="accounting_invoice_total"]').val(parseFloat($item.financial_receipt_for_invoice_total));
        $target.find('input[name="accounting_invoice_amount"]').val(parseFloat($item.financial_receipt_for_invoice_amount));
        $target.find('input[name="accounting_invoice_point"]').val(parseFloat($item.financial_receipt_for_invoice_point));

        $target.find('input[name="accounting_fee_invoice_total"]').val(parseFloat($item.financial_fee_for_invoice_total));
        $target.find('input[name="accounting_fee_invoice_amount"]').val(parseFloat($item.financial_fee_for_invoice_amount));
        $target.find('input[name="accounting_fee_invoice_point"]').val(parseFloat($item.financial_fee_for_invoice_point));

        $target.find('input[name="accounting_oil_total"]').val(parseFloat($item.financial_fee_for_oil_total));
        $target.find('input[name="accounting_oil_card"]').val(parseFloat($item.financial_fee_for_oil_card));
        $target.find('input[name="accounting_oil_cash"]').val(parseFloat($item.financial_fee_for_oil_cash));
        $target.find('input[name="accounting_oil_mileage"]').val(parseFloat($item.oil_mileage));
        $target.find('input[name="accounting_oil_consumption"]').val(parseFloat($item.oil_consumption));
        $target.find('input[name="accounting_oil_unit_price"]').val(parseFloat($item.oil_unit_price));

        $target.find('input[name="accounting_gas_total"]').val(parseFloat($item.financial_fee_for_gas_total));
        $target.find('input[name="accounting_gas_card"]').val(parseFloat($item.financial_fee_for_gas_card));
        $target.find('input[name="accounting_gas_cash"]').val(parseFloat($item.financial_fee_for_gas_cash));
        $target.find('input[name="accounting_gas_mileage"]').val(parseFloat($item.gas_mileage));
        $target.find('input[name="accounting_gas_consumption"]').val(parseFloat($item.gas_consumption));
        $target.find('input[name="accounting_gas_unit_price"]').val(parseFloat($item.gas_unit_price));

        $target.find('input[name="accounting_toll_etc"]').val(parseFloat($item.financial_fee_for_toll_etc));
        $target.find('input[name="accounting_toll_cash"]').val(parseFloat($item.financial_fee_for_toll_cash));

        $target.find('input[name="accounting_parking"]').val(parseFloat($item.financial_fee_for_parking));

        $target.find('input[name="accounting_salary"]').val(parseFloat($item.financial_fee_for_salary));
        $target.find('input[name="accounting_bonus"]').val(parseFloat($item.financial_fee_for_bonus));

        $target.find('input[name="accounting_information"]').val(parseFloat($item.financial_fee_for_information));
        $target.find('input[name="accounting_administrative"]').val(parseFloat($item.financial_fee_for_administrative));

        $target.find('input[name="accounting_repair"]').val(parseFloat($item.financial_fee_for_repair_cost));
        $target.find('input[name="accounting_maintenance"]').val(parseFloat($item.financial_fee_for_maintenance_cost));
        $target.find('input[name="accounting_inspection"]').val(parseFloat($item.financial_fee_for_inspection_cost));
        $target.find('input[name="accounting_transfer"]').val(parseFloat($item.financial_fee_for_transfer_cost));
        $target.find('input[name="accounting_insurance"]').val(parseFloat($item.financial_fee_for_insurance_cost));
        $target.find('input[name="accounting_loan"]').val(parseFloat($item.financial_fee_for_loan_cost));

        $target.find('input[name="accounting_others"]').val(parseFloat($item.financial_fee_for_others));
        $target.find('textarea[name="accounting_description"]').val($item.financial_description);

        var $statistics_freight_total = parseFloat($item.freight_amount)
            + parseFloat($item.freight_oil_card_amount)
            + parseFloat($item.freight_extra_amount);
        $target.find('input[name="accounting_statistics_freight_total"]').val(parseFloat($statistics_freight_total.toFixed(2)));

        $target.find('input[name="accounting_statistics_deduction_total"]').val(parseFloat($item.financial_deduction_total));
        $target.find('input[name="accounting_statistics_fine_total"]').val(parseFloat($item.financial_fine_total));

        var $income_should = $statistics_freight_total - parseFloat($item.financial_deduction_total);
        $target.find('input[name="accounting_statistics_income_should"]').val(parseFloat($income_should));

        $target.find('input[name="accounting_statistics_income_total"]').val(parseFloat($item.financial_income_total));

        if($item.settlement_period == 31)
        {
            $target.find('input[name="accounting_statistics_income_pending"]').val('月结').css('color','#000');
        }
        else
        {
            var $income_pending = $statistics_freight_total
                - parseFloat($item.financial_deduction_total)
                - parseFloat($item.financial_income_total);
            $target.find('input[name="accounting_statistics_income_pending"]').val(parseFloat($income_pending));
        }

        var $expense_total = parseFloat($item.financial_expense_total)
            + parseFloat($item.cooperative_vehicle_amount)
            + parseFloat($item.external_car_price)
            + parseFloat($item.financial_fee_for_information);
        $target.find('input[name="accounting_statistics_fee_total"]').val(parseFloat($expense_total));

        var $profit = $income_should - parseFloat($expense_total);
        $target.find('input[name="accounting_statistics_profile"]').val(parseFloat($profit.toFixed(2))).css('color','#00a65a');

    }


    function order_page__refresh($target,$item)
    {
        console.log('function order_page__refresh() execute.');

        order_edit__for__item_info($target.find('.form--for--info'),$item);
        order_edit__for__item_accounting($target.find('.form--for--accounting'),$item);

        var $datatable_id__for__operation = $target.data('datatable-id--for--operation');
        $('#'+$datatable_id__for__operation).DataTable().ajax.reload(null,false);

        var $datatable_id__for__fee = $target.data('datatable-id--for--fee');
        $('#'+$datatable_id__for__fee).DataTable().ajax.reload(null,false);
    }


</script>