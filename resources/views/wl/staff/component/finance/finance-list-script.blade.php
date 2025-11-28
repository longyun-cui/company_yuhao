<script>
    $(function() {

        // 【费用】编辑-显示-编辑
        $(".main-wrapper").off('click', ".finance-edit-show").on('click', ".finance-edit-show", function() {
            var $that = $(this);
            var $row = $that.parents('tr');

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
                "{{ url('/item/item-get-for-finance') }}",
                {
                    _token: $('meta[name="_token"]').attr('content'),
                    operate: "item-get",
                    item_type: "finance",
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
                        var $modal = $('#modal-for-finance-edit');
                        $modal.find('.box-title').html('编辑部门【'+$that.attr('data-id')+'】');
                        $modal.find('input[name="operate[type]"]').val('edit');
                        $modal.find('input[name="operate[id]"]').val($that.attr('data-id'));

                        $modal.find('input[name="name"]').val($response.data.name);
                        $modal.find('input[name="finance_type"][value="'+$response.data.finance_type+'"]').attr('checked', 'checked');
                        console.log($response.data.finance_type);

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



        // 【费用】【费用】显示
        $(".main-wrapper").off('click', ".item-modal-show-for-financial-create").on('click', ".item-modal-show-for-financial-create", function() {
            var $that = $(this);
            var $id = $(this).data('id');
            var $row = $that.parents('tr');
            var $datatable_wrapper = $that.closest('.datatable-wrapper');
            var $item_category = $datatable_wrapper.data('datatable-item-category');
            var $table_id = $datatable_wrapper.find('table').filter('[id][id!=""]').attr("id");

            $('.datatable-wrapper').removeClass('operating');
            $datatable_wrapper.addClass('operating');
            $datatable_wrapper.find('tr').removeClass('operating');
            $row.addClass('operating');

            form_reset('#form-for-finance-financial-create');

            var $modal = $('#modal-for-finance-financial-create');
            $modal.find('.id-title').html('【'+$id+'】');

            $modal.find('input[name="operate[id]"]').val($that.attr('data-id'));
            $modal.find('.follow-create-order-id').html($id);

            $modal.modal('show');
        });
        // 【工单】【费用】提交
        $(".main-wrapper").off('click', "#item-submit-for-finance-financial-create").on('click', "#item-submit-for-finance-financial-create", function() {
            var $that = $(this);
            var $table_id = $that.data('datatable-list-id');

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
                url: "{{ url('/v1/operate/finance/item-financial-save') }}",
                type: "post",
                dataType: "json",
                // target: "#div2",
                // clearForm: true,
                // restForm: true,
                success: function (response, status, xhr, $form) {
                    // 请求成功时的回调
                    if(!response.success)
                    {
                        layer.msg(response.msg);
                    }
                    else
                    {
                        layer.msg(response.msg);

                        // 重置输入框
                        form_reset('#form-for-finance-financial-create');

                        $('#modal-for-finance-financial-create').modal('hide');
                        // $('#modal-for-order-trade-create').modal('hide').on("hidden.bs.modal", function () {
                        //     $("body").addClass("modal-open");
                        // });

                        // $('#'+$table_id).DataTable().ajax.reload(null,false);
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
            $("#form-for-finance-financial-create").ajaxSubmit(options);
        });




    });
</script>