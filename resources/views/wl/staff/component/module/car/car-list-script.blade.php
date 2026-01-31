<script>
    $(function() {


        // 【车辆】添加-显示
        $(".main-wrapper").on('click', ".modal-show--for--car-item-create", function() {
            var $that = $(this);
            var $form_id = $that.data('form-id');
            var $modal_id = $that.data('modal-id');
            var $title = $that.data('title');

            var $datatable_wrapper = $that.closest('.datatable-wrapper');
            var $table_id = $datatable_wrapper.find('table').filter('[id][id!=""]').attr("id");

            form_reset('#'+$form_id);

            var $modal = $('#'+$modal_id);
            $modal.find('input[name="operate[type]"]').val('create');
            $modal.find('input[name="operate[id]"]').val(0);
            $modal.find('.box-title').html($title);
            $modal.find('.edit-submit').attr('data-datatable-list-id',$table_id);
            $modal.find('.radio-btn').show();
            $modal.modal('show');

            $('.modal-select2').select2({
                dropdownParent: $('#'+$modal_id), // 替换为你的模态框 ID
                minimumInputLength: 0,
                width: '100%',
                theme: 'classic'
            });
        });
        // 【车辆】编辑-显示
        $(".main-wrapper").on('click', ".modal-show--for--car-item-edit", function() {
            var $that = $(this);
            var $row = $that.parents('tr');

            var $modal_id = 'modal--for--car-item-edit';
            var $modal = $("#"+$modal_id);

            var $form_id = 'form--for--car-item-edit';
            var $form = $("#"+$form_id);

            var $data = new Object();

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
                "{{ url('/o1/car/item-get') }}",
                {
                    _token: $('meta[name="_token"]').attr('content'),
                    operate: "item-get",
                    item_type: "car",
                    item_id: $that.data('id')
                },
                'json'
            )
                .done(function($response, status, jqXHR) {
                    console.log('done');
                    $response = JSON.parse($response);
                    if(!$response.success)
                    {
                        if($response.msg) layer.msg($response.msg);
                    }
                    else
                    {
                        form_reset("#"+$form_id);

                        $modal.find('.box-title').html('编辑车辆【'+$that.attr('data-id')+'】');
                        $modal.find('input[name="operate[type]"]').val('edit');
                        $modal.find('input[name="operate[id]"]').val($that.attr('data-id'));

                        // 车辆类型 (input[radio])
                        $modal.find('input[name="car_type"]').prop('checked', false);
                        $modal.find('input[name="car_type"][value="'+$response.data.car_type+'"]').prop('checked', true).trigger('change');

                        // 车队 (select2)
                        if($response.data.motorcade_er)
                        {
                            var $motorcade_option = new Option($response.data.motorcade_er.name, $response.data.motorcade_id, true, true);
                            $modal.find('select[name="motorcade_id"]').append($motorcade_option).trigger('change');
                        }
                        // 挂 (select2)
                        if($response.data.trailer_er)
                        {
                            var $trailer_option = new Option($response.data.trailer_er.name, $response.data.trailer_id, true, true);
                            $modal.find('select[name="trailer_id"]').append($trailer_option).trigger('change');
                        }
                        // 主驾 (select2)
                        if($response.data.driver_er)
                        {
                            var $driver_option = new Option($response.data.driver_er.driver_name, $response.data.driver_id, true, true);
                            $modal.find('select[name="driver_id"]').append($driver_option).trigger('change');
                        }
                        // 副驾 (select2)
                        if($response.data.copilot_er)
                        {
                            var $copilot_option = new Option($response.data.copilot_er.driver_name, $response.data.copilot_id, true, true);
                            $modal.find('select[name="copilot_id"]').append($copilot_option).trigger('change');
                        }

                        // $modal.find('select[name="driver_id"]').val($response.data.driver_id).trigger('change');

                        $modal.find('input[name="name"]').val($response.data.name);
                        $modal.find('input[name="linkman_name"]').val($response.data.linkman_name);
                        $modal.find('input[name="linkman_phone"]').val($response.data.linkman_phone);
                        $modal.find('input[name="linkman_address"]').val($response.data.linkman_address);

                        $modal.find('input[name="car_info_type"]').val($response.data.car_info_type);
                        $modal.find('input[name="car_info_owner"]').val($response.data.car_info_owner);
                        $modal.find('input[name="car_info_function"]').val($response.data.car_info_function);
                        $modal.find('input[name="car_info_brand"]').val($response.data.car_info_brand);
                        $modal.find('input[name="car_info_identification_number"]').val($response.data.car_info_identification_number);

                        $modal.find('select[name="trailer_type"]').val($response.data.trailer_type).trigger('change');
                        $modal.find('select[name="trailer_length"]').val($response.data.trailer_length).trigger('change');
                        $modal.find('select[name="trailer_volume"]').val($response.data.trailer_volume).trigger('change');
                        $modal.find('select[name="trailer_weight"]').val($response.data.trailer_weight).trigger('change');
                        $modal.find('select[name="trailer_axis_count"]').val($response.data.trailer_axis_count).trigger('change');

                        $modal.find('input[name="car_info_engine_number"]').val($response.data.car_info_engine_number);
                        $modal.find('input[name="car_info_locomotive_wheelbase"]').val($response.data.car_info_locomotive_wheelbase);
                        $modal.find('input[name="car_info_main_fuel_tank"]').val($response.data.car_info_main_fuel_tank);
                        $modal.find('input[name="car_info_auxiliary_fuel_tank"]').val($response.data.car_info_auxiliary_fuel_tank);
                        $modal.find('input[name="car_info_total_mass"]').val($response.data.car_info_total_mass);
                        $modal.find('input[name="car_info_curb_weight"]').val($response.data.car_info_curb_weight);
                        $modal.find('input[name="car_info_load_weight"]').val($response.data.car_info_load_weight);
                        $modal.find('input[name="car_info_traction_mass"]').val($response.data.car_info_traction_mass);
                        $modal.find('input[name="car_info_overall_size"]').val($response.data.car_info_overall_size);
                        $modal.find('input[name="car_info_purchase_date"]').val($response.data.car_info_purchase_date);
                        $modal.find('input[name="car_info_purchase_price"]').val($response.data.car_info_purchase_price);
                        $modal.find('input[name="car_info_sale_date"]').val($response.data.car_info_sale_date);
                        $modal.find('input[name="car_info_sale_date"]').val($response.data.car_info_sale_date);
                        $modal.find('input[name="car_info_registration_date"]').val($response.data.car_info_registration_date);
                        $modal.find('input[name="car_info_issue_date"]').val($response.data.car_info_issue_date);
                        $modal.find('input[name="car_info_inspection_validity"]').val($response.data.car_info_inspection_validity);
                        $modal.find('input[name="car_info_transportation_license_validity"]').val($response.data.car_info_transportation_license_validity);
                        $modal.find('input[name="car_info_transportation_license_change_time"]').val($response.data.car_info_transportation_license_change_time);

                        $modal.find('input[name="ETC_account"]').val($response.data.ETC_account);

                        var $datatable_wrapper = $that.closest('.datatable-wrapper');
                        var $table_id = $datatable_wrapper.find('table').filter('[id][id!=""]').attr("id");
                        $modal.find('.edit-submit').attr('data-datatable-list-id',$table_id);

                        $modal.modal('show');
                    }
                })
                .fail(function(jqXHR, status, error) {
                    console.log('fail');
                    layer.msg('服务器错误！');

                })
                .always(function(jqXHR, status) {
                    console.log('always');
                    layer.closeAll('loading');
                });

        });
        // 【车辆】编辑-提交
        $(".main-wrapper").on('click', "#submit--for--car-item-edit", function() {
            var $that = $(this);

            var $table_id = $that.data('datatable-list-id');
            var $table = $('#'+$table_id);

            var $modal_id = 'modal--for--car-item-edit';
            var $modal = $("#"+$modal_id);

            var $form_id = 'form--for--car-item-edit';
            var $form = $("#"+$form_id);

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
                url: "{{ url('/o1/car/item-save') }}",
                type: "post",
                dataType: "json",
                // target: "#div2",
                // clearForm: true,
                // restForm: true,
                success: function (response, status, xhr, $form) {
                    // 请求成功时的回调
                    console.log('success');
                    if(!response.success)
                    {
                        layer.msg(response.msg);
                    }
                    else
                    {
                        layer.msg(response.msg);

                        // 重置输入框
                        form_reset('#'+$form_id);

                        $modal.modal('hide').on("hidden.bs.modal", function () {
                            $("body").addClass("modal-open");
                        });

                        $table.DataTable().ajax.reload(null,false);
                    }
                },
                error: function(xhr, status, error, $form) {
                    // 请求失败时的回调
                    console.log('error');
                    layer.closeAll('loading');
                },
                complete: function(xhr, status, $form) {
                    // 无论成功或失败都会执行的回调
                    console.log('always');
                    layer.closeAll('loading');
                }


            };
            $form.ajaxSubmit(options);
        });


        // 【车辆】删除
        $(".main-wrapper").off('click', ".car--item-delete-submit").on('click', ".car--item-delete-submit", function() {
            var $that = $(this);
            var $id = $that.attr('data-id');
            var $row = $that.parents('tr');
            var $datatable_wrapper = $that.closest('.datatable-wrapper');
            var $item_category = $datatable_wrapper.data('datatable-item-category');
            var $table_id = $datatable_wrapper.find('table').filter('[id][id!=""]').attr("id");

            $('.datatable-wrapper').removeClass('operating');
            $datatable_wrapper.addClass('operating');
            $datatable_wrapper.find('tr').removeClass('operating');
            $row.addClass('operating');


            layer.msg('确定"删除"么?', {
                time: 0
                ,btn: ['确定', '取消']
                ,yes: function(index)
                {
                    layer.close(index);

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
                        "{{ url('/o1/car/item-delete') }}",
                        {
                            _token: $('meta[name="_token"]').attr('content'),
                            operate: "car--item-delete",
                            item_category: $item_category,
                            item_id: $that.attr('data-id')
                        },
                        'json'
                    )
                        .done(function($response, status, jqXHR) {
                            console.log('done');
                            $response = JSON.parse($response);
                            if(!$response.success)
                            {
                                if($response.msg) layer.msg($response.msg);
                            }
                            else
                            {
                                $('#'+$table_id).DataTable().ajax.reload(null,false);
                            }
                        })
                        .fail(function(jqXHR, status, error) {
                            console.log('fail');
                            layer.msg('服务器错误！');

                        })
                        .always(function(jqXHR, status) {
                            console.log('always');
                            layer.closeAll('loading');
                        });
                }
                ,btn2: function(index)
                {
                    layer.close(index);
                    $row.removeClass('operating');
                }
            });
        });
        // 【车辆】恢复
        $(".main-wrapper").off('click', ".car--item-restore-submit").on('click', ".car--item-restore-submit", function() {
            var $that = $(this);
            var $id = $that.attr('data-id');
            var $row = $that.parents('tr');
            var $datatable_wrapper = $that.closest('.datatable-wrapper');
            var $item_category = $datatable_wrapper.data('datatable-item-category');
            var $table_id = $datatable_wrapper.find('table').filter('[id][id!=""]').attr("id");

            $('.datatable-wrapper').removeClass('operating');
            $datatable_wrapper.addClass('operating');
            $datatable_wrapper.find('tr').removeClass('operating');
            $row.addClass('operating');


            layer.msg('确定"恢复"么?', {
                time: 0
                ,btn: ['确定', '取消']
                ,yes: function(index)
                {
                    layer.close(index);

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
                        "{{ url('/o1/car/item-restore') }}",
                        {
                            _token: $('meta[name="_token"]').attr('content'),
                            operate: "car--item-restore",
                            item_category: $item_category,
                            item_id: $that.attr('data-id')
                        },
                        'json'
                    )
                        .done(function($response, status, jqXHR) {
                            console.log('done');
                            $response = JSON.parse($response);
                            if(!$response.success)
                            {
                                if($response.msg) layer.msg($response.msg);
                            }
                            else
                            {
                                $('#'+$table_id).DataTable().ajax.reload(null,false);
                            }
                        })
                        .fail(function(jqXHR, status, error) {
                            console.log('fail');
                            layer.msg('服务器错误！');

                        })
                        .always(function(jqXHR, status) {
                            console.log('always');
                            layer.closeAll('loading');
                        });
                }
                ,btn2: function(index)
                {
                    layer.close(index);
                    $row.removeClass('operating');
                }
            });
        });
        // 【车辆】永久删除
        $(".main-wrapper").off('click', ".car--item-delete-permanently-submit").on('click', ".car--item-delete-permanently-submit", function() {
            var $that = $(this);
            var $id = $that.attr('data-id');
            var $row = $that.parents('tr');
            var $datatable_wrapper = $that.closest('.datatable-wrapper');
            var $item_category = $datatable_wrapper.data('datatable-item-category');
            var $table_id = $datatable_wrapper.find('table').filter('[id][id!=""]').attr("id");

            $('.datatable-wrapper').removeClass('operating');
            $datatable_wrapper.addClass('operating');
            $datatable_wrapper.find('tr').removeClass('operating');
            $row.addClass('operating');


            layer.msg('确定"永久删除"么?', {
                time: 0
                ,btn: ['确定', '取消']
                ,yes: function(index)
                {
                    layer.close(index);

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
                        "{{ url('/o1/car/item-delete-permanently') }}",
                        {
                            _token: $('meta[name="_token"]').attr('content'),
                            operate: "car--item-delete-permanently",
                            item_category: $item_category,
                            item_id: $that.attr('data-id')
                        },
                        'json'
                    )
                        .done(function($response, status, jqXHR) {
                            console.log('done');
                            $response = JSON.parse($response);
                            if(!$response.success)
                            {
                                if($response.msg) layer.msg($response.msg);
                            }
                            else
                            {
                                $('#'+$table_id).DataTable().ajax.reload(null,false);
                            }
                        })
                        .fail(function(jqXHR, status, error) {
                            console.log('fail');
                            layer.msg('服务器错误！');

                        })
                        .always(function(jqXHR, status) {
                            console.log('always');
                            layer.closeAll('loading');
                        });
                }
                ,btn2: function(index)
                {
                    layer.close(index);
                    $row.removeClass('operating');
                }
            });
        });


        // 【车辆】启用
        $(".main-wrapper").off('click', ".car--item-enable-submit").on('click', ".car--item-enable-submit", function() {
            var $that = $(this);
            var $id = $that.attr('data-id');
            var $row = $that.parents('tr');
            var $datatable_wrapper = $that.closest('.datatable-wrapper');
            var $item_category = $datatable_wrapper.data('datatable-item-category');
            var $table_id = $datatable_wrapper.find('table').filter('[id][id!=""]').attr("id");

            $('.datatable-wrapper').removeClass('operating');
            $datatable_wrapper.addClass('operating');
            $datatable_wrapper.find('tr').removeClass('operating');
            $row.addClass('operating');


            layer.msg('确定"启用"么?', {
                time: 0
                ,btn: ['确定', '取消']
                ,yes: function(index)
                {
                    layer.close(index);

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
                        "{{ url('/o1/car/item-enable') }}",
                        {
                            _token: $('meta[name="_token"]').attr('content'),
                            operate: "car--item-enable",
                            item_category: $item_category,
                            item_id: $that.attr('data-id')
                        },
                        'json'
                    )
                        .done(function($response, status, jqXHR) {
                            console.log('done');
                            $response = JSON.parse($response);
                            if(!$response.success)
                            {
                                if($response.msg) layer.msg($response.msg);
                            }
                            else
                            {
                                $('#'+$table_id).DataTable().ajax.reload(null,false);
                            }
                        })
                        .fail(function(jqXHR, status, error) {
                            console.log('fail');
                            layer.msg('服务器错误！');

                        })
                        .always(function(jqXHR, status) {
                            console.log('always');
                            layer.closeAll('loading');
                        });
                }
                ,btn2: function(index)
                {
                    layer.close(index);
                    $row.removeClass('operating');
                }
            });

        });
        // 【车辆】禁用
        $(".main-wrapper").off('click', ".car--item-disable-submit").on('click', ".car--item-disable-submit", function() {
            var $that = $(this);
            var $id = $that.attr('data-id');
            var $row = $that.parents('tr');
            var $datatable_wrapper = $that.closest('.datatable-wrapper');
            var $item_category = $datatable_wrapper.data('datatable-item-category');
            var $table_id = $datatable_wrapper.find('table').filter('[id][id!=""]').attr("id");

            $('.datatable-wrapper').removeClass('operating');
            $datatable_wrapper.addClass('operating');
            $datatable_wrapper.find('tr').removeClass('operating');
            $row.addClass('operating');


            layer.msg('确定"禁用"么?', {
                time: 0
                ,btn: ['确定', '取消']
                ,yes: function(index)
                {
                    layer.close(index);

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
                        "{{ url('/o1/car/item-disable') }}",
                        {
                            _token: $('meta[name="_token"]').attr('content'),
                            operate: "car--item-disable",
                            item_category: $item_category,
                            item_id: $that.attr('data-id')
                        },
                        'json'
                    )
                        .done(function($response, status, jqXHR) {
                            console.log('done');
                            $response = JSON.parse($response);
                            if(!$response.success)
                            {
                                if($response.msg) layer.msg($response.msg);
                            }
                            else
                            {
                                $('#'+$table_id).DataTable().ajax.reload(null,false);
                            }
                        })
                        .fail(function(jqXHR, status, error) {
                            console.log('fail');
                            layer.msg('服务器错误！');

                        })
                        .always(function(jqXHR, status) {
                            console.log('always');
                            layer.closeAll('loading');
                        });
                }
                ,btn2: function(index)
                {
                    layer.close(index);
                    $row.removeClass('operating');
                }
            });
        });




        // 【车辆】操作记录
        $(".main-content").off('click', ".modal-show--for--car--item-operation-record").on('click', ".modal-show--for--car--item-operation-record", function() {
            var $that = $(this);
            var $id = $(this).data('id');
            var $row = $that.parents('tr');
            var $datatable_wrapper = $that.closest('.datatable-wrapper');
            var $item_category = $datatable_wrapper.data('datatable-item-category');
            var $table_id = $datatable_wrapper.find('table').filter('[id][id!=""]').attr("id");

            $('.datatable-wrapper').removeClass('operating');
            $datatable_wrapper.addClass('operating');
            $datatable_wrapper.find('tr').removeClass('operating');
            // $row.addClass('operating');

            Datatable__for__Car_Item_Operation_Record_List.init($id);

            var $modal = $('#modal--for--car-item-operation-record-list');
            $modal.find('.id-title').html('【'+$id+'】');
            $modal.modal('show');
        });


    });
</script>