<script>
    $(function() {


        // 【费用】添加-显示
        $(".main-wrapper").on('click', ".modal-show--for--fee-item-create", function() {
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
        // 【财务】编辑-提交
        $(".main-wrapper").on('click', "#submit--for--fee-item-edit", function() {
            var $that = $(this);

            var $table_id = $that.data('datatable-list-id');
            var $table = $('#'+$table_id);

            var $modal_id = 'modal--for--fee-item-edit';
            var $modal = $("#"+$modal_id);

            var $form_id = 'form--for--fee-item-edit';
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
                url: "{{ url('/o1/fee/item-save') }}",
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
                        form_reset("#"+$form_id);

                        $modal.modal('hide').on("hidden.bs.modal", function () {
                            $("body").addClass("modal-open");
                        });

                        $('#'+$table_id).DataTable().ajax.reload(null,false);
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




        // 【费用】财务入账-显示
        $(".main-wrapper").off('click', ".modal-show--for--fee-item-finance-bookkeeping").on('click', ".modal-show--for--fee-item-finance-bookkeeping", function() {
            var $that = $(this);
            var $id = $(this).data('id');
            var $row = $that.parents('tr');
            var $datatable_wrapper = $that.closest('.datatable-wrapper');
            var $item_category = $datatable_wrapper.data('datatable-item-category');
            var $table_id = $datatable_wrapper.find('table').filter('[id][id!=""]').attr("id");

            // Datatable__for__Order_Item_Fee_Record_List.init($id);

            $('.datatable-wrapper').removeClass('operating');
            $datatable_wrapper.addClass('operating');
            $datatable_wrapper.find('tr').removeClass('operating');
            $row.addClass('operating');

            form_reset('#form--for--fee-item-finance-bookkeeping');

            var $modal = $('#modal--for--fee-item-finance-bookkeeping');
            $modal.find('.id-title').html('【'+$id+'】');

            $modal.find('#item-submit--for--fee-item-finance-bookkeeping').data('item-id',$id);
            $modal.find('#item-submit--for--fee-item-finance-bookkeeping').data('datatable-list-id',$table_id);
            $modal.find('input[name="operate[id]"]').val($that.attr('data-id'));
            $modal.find('.finance-create-order-id').html($id);

            $modal.find('.finance-transaction-title-inn').html($row.find('td[data-key=fee_title]').html());
            $modal.find('.finance-transaction-amount-inn').html($row.find('td[data-key=fee_amount]').html());

            $modal.modal('show');
        });
        // 【费用】财务入账-提交
        $(".main-wrapper").off('click', "#item-submit--for--fee-item-finance-bookkeeping").on('click', "#item-submit--for--fee-item-finance-bookkeeping", function() {
            var $that = $(this);
            var $item_id = $that.data('item-id');
            var $table_id = $that.data('datatable-list-id');
            var $table = $('#'+$table_id);
            var $row = $('#'+$table_id).find('[data-key="id"][data-value='+$item_id+']').parents('tr');

            var $modal_id = 'modal--for--fee-item-finance-bookkeeping';
            var $modal = $("#"+$modal_id);

            var $form_id = 'form--for--fee-item-finance-bookkeeping';
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
                url: "{{ url('/o1/fee/item-finance-bookkeeping') }}",
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

                        // 重置输入框
                        form_reset("#"+$modal_id);

                        $modal.modal('hide');
                        // $('#modal--for--order-trade-create').modal('hide').on("hidden.bs.modal", function () {
                        //     $("body").addClass("modal-open");
                        // });

                        $('#'+$table_id).DataTable().ajax.reload(null,false);

                        // var $fee = $response.data.fee;
                        // console.log($row);
                        // console.log($fee);
                        // update_order_row($row,$order);
                    }
                },
                error: function(xhr, status, error, $form) {
                    // 请求失败时的回调
                    layer.closeAll('loading');
                    console.log($(this).attr('id')+'.click.error');
                    layer.msg('服务器错误！');
                },
                complete: function(xhr, status, $form) {
                    // 无论成功或失败都会执行的回调
                    layer.closeAll('loading');
                    console.log($(this).attr('id')+'.click.complete');
                }


            };
            $("#form--for--fee-item-finance-bookkeeping").ajaxSubmit(options);
        });



        // 【费用】操作记录
        $(".main-content").off('click', ".modal-show--for--fee-item-operation-record").on('click', ".modal-show--for--fee-item-operation-record", function() {
            var $that = $(this);
            var $id = $(this).data('id');
            var $datatable_wrapper = $that.closest('.datatable-wrapper');
            var $item_category = $datatable_wrapper.data('datatable-item-category');
            var $table_id = $datatable_wrapper.find('table').filter('[id][id!=""]').attr("id");

            Datatable__for__Fee_Item_Operation_Record_List.init($id);

            $('#modal--for--fee-item-operation-record-list').modal('show');
        });


    });
</script>