<script>
    $(function() {


        /*
            订单
         */
        // 【导出】工单导出
        $(".main-content").on('click', ".submit--for--order-export", function() {
            console.log(".submit--for--order-export.click");

            var that = $(this);
            var $id = that.attr("data-id");
            var $export_type = that.attr("data-type");


            var $month = $('input[name="export-month"]').val();
            var $date = $('input[name="export-date"]').val();

            var $obj = new Object();
            $obj.export_type = $export_type;
            if($('select[name="order-export--order-type"]').val() > 0)  $obj.order_type = $('select[name="order-export--order-type"]').val();
            if($('input[name="order-export--month"]').val())  $obj.month = $('input[name="order-export--month"]').val();
            if($('input[name="order-export--date"]').val())  $obj.date = $('input[name="order-export--date"]').val();
            if($('input[name="order-export--start"]').val())  $obj.order_start = $('input[name="order-export--start"]').val();
            if($('input[name="order-export--ended"]').val())  $obj.order_ended = $('input[name="order-export--ended"]').val();
            if($('select[name="order-export--project"]').val() > 0)  $obj.project = $('select[name="order-export--project"]').val();
            if($('select[name="order-export--car-owner-type"]').val() > 0)  $obj.car_owner_type = $('select[name="order-export--car-owner-type"]').val();

            var $url = url_build('/o1/export/order-export',$obj);
            window.open($url);

        });


        // 【清空重选】
        $(".main-content").on('click', ".filter-empty--for--export", function() {

            var $that = $(this);
            var $filter_box = $that.closest('.filter-box');
            // console.log(1);

            $filter_box.find('textarea.form-filter, input.form-filter, select.form-filter').each(function () {
                $(this).val("");
                $(this).val($(this).data("default"));
            });

            $filter_box.find('select.form-filter option').prop("selected",false);
            $filter_box.find('select.form-filter').find('option:eq(-1)').prop('selected', true);

            $filter_box.find(".select2-reset").val(-1).trigger("change");

        });


    });
</script>