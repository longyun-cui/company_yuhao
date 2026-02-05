<script>
    // 设置中文
    moment.locale('zh-cn');

    var $wl_common_department_list = {!! json_encode($wl_common_department_list, JSON_HEX_TAG)!!};
    // console.log($wl_common_department_list);


    (function ($) {
        $.getUrlParam = function (name) {
            var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
            var r = window.location.search.substr(1).match(reg);
            if (r != null) return unescape(r[2]); return null;
        }
    })(jQuery);


    $(function() {


        $(document).on('click', '.dropdown-menu.non-auto-hide', function(e) {
            e.stopPropagation();
        });


        // localStorage.removeItem('last_delivery_id');

        // setInterval(query_last_delivery, 60000);
        // $("#datepicker").flatpickr({
        //     locale: "zh",  // 使用中文
        //     dateFormat: "Y-m-d H:i",  // 日期时间格式
        //     enableTime: true  // 启用时间选择
        // });


        // $('.time-picker').flatpickr({
        //     locale: "zh",  // 使用中文
        //     dateFormat: "Y-m-d H:i",  // 日期时间格式
        //     enableTime: true,  // 启用时间选择
        //     time_24hr: true, // 使用24小时制
        // });
        $('.time-picker').datetimepicker({
            // 1. 格式控制是否显示时间
            format: 'YYYY-MM-DD HH:mm',  // 包含HH:mm自动启用时间选择
            // format: 'YYYY-MM-DD',      // 只显示日期

            // 2. 显示模式
            sideBySide: true,           // ✅ 并排显示日期和时间选择器
            inline: false,              // 内联模式

            // 3. 工具栏按钮
            showTodayButton: true,      // 今天按钮
            showClear: true,            // 清除按钮
            showClose: true,            // 关闭按钮

            // 4. 语言
            locale: moment.locale('zh-cn'),          // 中文

            // 6. 工具栏位置
            toolbarPlacement: 'bottom', // 'top' 或 'bottom'

            ignoreReadonly: true
        });
        // $('.time-picker').datetimepicker({
        //     format: 'YYYY-MM-DD HH:mm', // 同时包含日期和时间
        //     locale: moment.locale('zh-cn'),
        //
        //     // 显示工具栏按钮
        //     showTodayButton: true,       // 显示"今天"按钮
        //     showClear: true,            // 显示"清除"按钮
        //     showClose: true,            // 显示"关闭"按钮
        //
        //     // 时间选择相关 - 启用时间选择会显示确定/取消按钮
        //     timePicker: true,           // 启用时间选择
        //     toolbarPlacement: 'bottom',  // 工具栏位置: 'top', 'bottom'
        //
        //     sideBySide: true,           // 并排显示日期和时间选择器
        //     stepping: 1,               // 时间步长（可选，例如15分钟）
        //     ignoreReadonly: true
        // });

        $('.date-picker').datetimepicker({
            // 1. 格式控制是否显示时间
            format: 'YYYY-MM-DD',  // 包含HH:mm自动启用时间选择
            // format: 'YYYY-MM-DD',      // 只显示日期

            // 2. 显示模式
            sideBySide: true,           // ✅ 并排显示日期和时间选择器
            inline: false,              // 内联模式

            // 3. 工具栏按钮
            showTodayButton: true,      // 今天按钮
            showClear: true,            // 清除按钮
            showClose: true,            // 关闭按钮

            // 4. 语言
            locale: moment.locale('zh-cn'),          // 中文

            // 6. 工具栏位置
            toolbarPlacement: 'bottom', // 'top' 或 'bottom'

            ignoreReadonly: true
        });
        $('.month-picker').datetimepicker({
            format: "YYYY-MM",
            locale: moment.locale('zh-cn'),
            ignoreReadonly: true
        });


        $('.form_datetime').datetimepicker({
            locale: moment.locale('zh-cn'),
            format: "YYYY-MM-DD HH:mm",
            ignoreReadonly: true
        });
        $(".form_date").datepicker({
            language: 'zh-CN',
            format: 'yyyy-mm-dd',
            todayHighlight: true,
            autoclose: true,
            ignoreReadonly: true
        });




        $('.select2-car').select2({
        // $(".main-content").on('select2', ".select2-car", function() {
            ajax: {
                url: "{{ url('/v1/operate/select2/select2-car') }}",
                type: 'post',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        _token: $('meta[name="_token"]').attr('content'),
                        contact_category: this.data('contact-category'),
                        contact_type: this.data('contact-type'),
                        keyword: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function (data, params) {

                    params.page = params.page || 1;
                    return {
                        results: data,
                        pagination: {
                            more: (params.page * 30) < data.total_count
                        }
                    };
                },
                cache: true
            },
            escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
            // dropdownParent: this.data('modal'), // 替换为你的模态框 ID
            // dropdownParent: function() {
            //     // 获取当前元素的 modal 属性
            //     var modalSelector = $(this).data('modal');
            //     return $(modalSelector).length ? $(modalSelector) : $(document.body);
            // },
            minimumInputLength: 0,
            // width: '100%',
            theme: 'classic',
            placeholder: "搜索或选择车辆..."
        });





        // 公司
        $('.select2--company').each(function() {
            var $this = $(this);

            var $dropdownParent = $(document.body);
            var $modalSelector = $this.data('modal');
            if ($modalSelector)
            {
                $dropdownParent = $($modalSelector);
            }

            $this.select2({
                ajax: {
                    url: "{{ url('/o1/select2/select2--company') }}",
                    type: 'post',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            _token: $('meta[name="_token"]').attr('content'),
                            item_category: this.data('item-category'),
                            item_type: this.data('item-type'),
                            keyword: params.term,
                            page: params.page
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data,
                            pagination: {
                                more: (params.page * 30) < data.total_count
                            }
                        };
                    },
                    cache: true
                },
                escapeMarkup: function (markup) { return markup; },
                dropdownParent: $dropdownParent,
                minimumInputLength: 0,
                theme: 'classic'
            });


            $this.change(function() {

                var $company_id = $(this).val();

                var $department_target = $(this).data('department-target');
                $($department_target).val(null).trigger('change');
                $($department_target).data('company-id',$company_id);

                var $team_target = $(this).data('team-target');
                $($team_target).val(null).trigger('change');
                $($team_target).data('company-id',$company_id);

                var $staff_target = $(this).data('staff-target');
                $($staff_target).val(null).trigger('change');
                $($staff_target).data('company-id',$company_id);

            });
        });
        // 部门
        $('.select2--department').each(function() {
            var $this = $(this);

            var $dropdownParent = $(document.body);
            var $modalSelector = $this.data('modal');
            if ($modalSelector)
            {
                $dropdownParent = $($modalSelector);
            }

            $this.select2({
                ajax: {
                    url: "{{ url('/o1/select2/select2--department') }}",
                    type: 'post',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            _token: $('meta[name="_token"]').attr('content'),
                            department_category: $(this.data('department-category')).find('input[type="radio"]:checked').val(),
                            item_category: this.data('item-category'),
                            item_type: this.data('item-type'),
                            company_id: this.data('company-id'),
                            keyword: params.term,
                            page: params.page
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data,
                            pagination: {
                                more: (params.page * 30) < data.total_count
                            }
                        };
                    },
                    cache: true
                },
                escapeMarkup: function (markup) { return markup; },
                dropdownParent: $dropdownParent,
                minimumInputLength: 0,
                theme: 'classic'
            });


            $this.change(function() {

                var $department_id = $(this).val();

                var $team_target = $(this).data('team-target');
                $($team_target).val(null).trigger('change');
                $($team_target).data('department-id',$department_id);

                var $staff_target = $(this).data('staff-target');
                $($staff_target).val(null).trigger('change');
                $($staff_target).data('department-id',$department_id);
            });
        });
        // 团队
        $('.select2--team').each(function() {
            var $this = $(this);

            var $dropdownParent = $(document.body);
            var $modalSelector = $this.data('modal');
            if ($modalSelector)
            {
                $dropdownParent = $($modalSelector);
            }

            $this.select2({
                ajax: {
                    url: "{{ url('/o1/select2/select2--team') }}",
                    type: 'post',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            _token: $('meta[name="_token"]').attr('content'),
                            item_category: this.data('item-category'),
                            item_type: this.data('item-type'),
                            department_id: this.data('department-id'),
                            keyword: params.term,
                            page: params.page
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data,
                            pagination: {
                                more: (params.page * 30) < data.total_count
                            }
                        };
                    },
                    cache: true
                },
                escapeMarkup: function (markup) { return markup; },
                dropdownParent: $dropdownParent,
                minimumInputLength: 0,
                theme: 'classic'
            });


            $this.change(function() {

                var $team_id = $(this).val();

                var $staff_target = $(this).data('staff-target');
                $($staff_target).val(null).trigger('change');
                $($staff_target).data('team-id',$team_id);
            });
        });
        // 员工
        $('.select2--staff').each(function() {
            var $this = $(this);

            var $dropdownParent = $(document.body);
            var $modalSelector = $this.data('modal');
            if ($modalSelector)
            {
                $dropdownParent = $($modalSelector);
            }

            $this.select2({
                ajax: {
                    url: "{{ url('/o1/select2/select2--staff') }}",
                    type: 'post',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            _token: $('meta[name="_token"]').attr('content'),
                            item_category: this.data('item-category'),
                            item_type: this.data('item-type'),
                            department_id: this.data('department-id'),
                            team_id: this.data('team-id'),
                            keyword: params.term,
                            page: params.page
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data,
                            pagination: {
                                more: (params.page * 30) < data.total_count
                            }
                        };
                    },
                    cache: true
                },
                escapeMarkup: function (markup) { return markup; },
                dropdownParent: $dropdownParent,
                minimumInputLength: 0,
                theme: 'classic'
            });
        });


        // 车队
        $('.select2--motorcade').each(function() {
            var $this = $(this);

            var $dropdownParent = $(document.body);
            var $modalSelector = $this.data('modal');
            if ($modalSelector)
            {
                $dropdownParent = $($modalSelector);
            }

            $this.select2({
                ajax: {
                    url: "{{ url('/o1/select2/select2--motorcade') }}",
                    type: 'post',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            _token: $('meta[name="_token"]').attr('content'),
                            item_category: this.data('item-category'),
                            item_type: this.data('item-type'),
                            keyword: params.term,
                            page: params.page
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data,
                            pagination: {
                                more: (params.page * 30) < data.total_count
                            }
                        };
                    },
                    cache: true
                },
                escapeMarkup: function (markup) { return markup; },
                dropdownParent: $dropdownParent,
                minimumInputLength: 0,
                theme: 'classic'
            });
        });
        // 车辆
        $('.select2--car').each(function() {
            var $this = $(this);

            var $dropdownParent = $(document.body);
            var $modalSelector = $this.data('modal');
            if ($modalSelector)
            {
                $dropdownParent = $($modalSelector);
            }

            $this.select2({
                ajax: {
                    url: "{{ url('/o1/select2/select2--car') }}",
                    type: 'post',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            _token: $('meta[name="_token"]').attr('content'),
                            item_category: this.data('item-category'),
                            item_type: this.data('item-type'),
                            car_type: this.data('car-type'),
                            motorcade_id: this.data('motorcade-id'),
                            keyword: params.term,
                            page: params.page
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data,
                            pagination: {
                                more: (params.page * 30) < data.total_count
                            }
                        };
                    },
                    cache: true
                },
                templateSelection: function(data, container) {
                    // 检查是否为有效选中项（避免空数据打印）
                    if (data.id && data.text)
                    {
                        // console.log("Selected:", data.id, data.text);
                        // console.log(data);
                    }
                    if(data.driver_er)
                    {
                        $(data.element).data("car-id",data.id);
                        $(data.element).data("trailer-id",data.trailer_id);
                        $(data.element).data("driver-id",data.driver_id);
                        $(data.element).data("driver-name",data.driver_er.driver_name);
                        $(data.element).data("driver-phone",data.driver_er.driver_phone);
                        $(data.element).data("copilot-id",data.copilot_id);
                        $(data.element).data("copilot-name",data.driver_er.copilot_name);
                        $(data.element).data("copilot-phone",data.driver_er.copilot_phone);
                    }
                    else
                    {
                        $(data.element).data("car-id",0);
                        $(data.element).data("trailer-id",0);
                        $(data.element).data("driver-id",0);
                        $(data.element).data("driver-name",'');
                        $(data.element).data("driver-phone",'');
                        $(data.element).data("copilot-id",0);
                        $(data.element).data("copilot-name",'');
                        $(data.element).data("copilot-phone",'');
                    }
                    return data.text;
                },
                escapeMarkup: function (markup) { return markup; },
                dropdownParent: $dropdownParent,
                minimumInputLength: 0,
                theme: 'classic'
            });
        });
        // 司机
        $('.select2--driver').each(function() {
            var $this = $(this);

            var $dropdownParent = $(document.body);
            var $modalSelector = $this.data('modal');
            if ($modalSelector)
            {
                $dropdownParent = $($modalSelector);
            }

            $this.select2({
                ajax: {
                    url: "{{ url('/o1/select2/select2--driver') }}",
                    type: 'post',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            _token: $('meta[name="_token"]').attr('content'),
                            item_category: this.data('item-category'),
                            item_type: this.data('item-type'),
                            company_id: this.data('company-id'),
                            department_id: this.data('department-id'),
                            team_id: this.data('team-id'),
                            motorcade_id: this.data('motorcade-id'),
                            keyword: params.term,
                            page: params.page
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data,
                            pagination: {
                                more: (params.page * 30) < data.total_count
                            }
                        };
                    },
                    cache: true
                },
                escapeMarkup: function (markup) { return markup; },
                dropdownParent: $dropdownParent,
                minimumInputLength: 0,
                theme: 'classic'
            });
        });


        // 客户
        $('.select2--client').each(function() {
            var $this = $(this);

            var $dropdownParent = $(document.body);
            var $modalSelector = $this.data('modal');
            if ($modalSelector)
            {
                $dropdownParent = $($modalSelector);
            }

            $this.select2({
                ajax: {
                    url: "{{ url('/o1/select2/select2--client') }}",
                    type: 'post',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            _token: $('meta[name="_token"]').attr('content'),
                            item_category: this.data('item-category'),
                            item_type: this.data('item-type'),
                            keyword: params.term,
                            page: params.page
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data,
                            pagination: {
                                more: (params.page * 30) < data.total_count
                            }
                        };
                    },
                    cache: true
                },
                escapeMarkup: function (markup) { return markup; },
                dropdownParent: $dropdownParent,
                minimumInputLength: 0,
                theme: 'classic'
            });
        });
        // 项目
        $('.select2--project').each(function() {
            var $this = $(this);

            var $dropdownParent = $(document.body);
            var $modalSelector = $this.data('modal');
            if ($modalSelector)
            {
                $dropdownParent = $($modalSelector);
            }

            $this.select2({
                ajax: {
                    url: "{{ url('/o1/select2/select2--project') }}",
                    type: 'post',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            _token: $('meta[name="_token"]').attr('content'),
                            item_category: this.data('item-category'),
                            item_type: this.data('item-type'),
                            keyword: params.term,
                            page: params.page
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data,
                            pagination: {
                                more: (params.page * 30) < data.total_count
                            }
                        };
                    },
                    cache: true
                },
                escapeMarkup: function (markup) { return markup; },
                dropdownParent: $dropdownParent,
                minimumInputLength: 0,
                theme: 'classic'
            });
        });


    });





    // 表单初始化
    function form_reset($form_id)
    {
        console.log($form_id+'.form_reset');
        var $form = $($form_id);
        // $form.find('.select2-container').remove();
        // input
        $form.find('textarea.form-control, input.form-control').each(function () {
            $(this).val("");
            $(this).val($(this).data('default'));

        });

        // radio
        $form.find('input[type="radio"][data-default="default"]').prop('checked', true).trigger('change');

        // select
        $form.find('select option').prop("selected",false);
        $form.find('select').find('option:eq(0)').prop('selected', true).trigger('change');


        // $form.find(".select2-box").val(-1).trigger("change");
        // $form.find(".select2-box").val("-1").trigger("change");
        // selectFirstOption($form_id + " .select2-box");
        $.each( $form.find(".select2-box"), function(index, element) {
            select2FirstOptionSelected(element);
        });

        // $form.find(".select2-box-c").val(-1).trigger("change");
        // $form.find(".select2-box-c").val("-1").trigger("change");
        // selectFirstOption($form_id + " .select2-box-c");
        $.each( $form.find(".select2-box-c"), function(index, element) {
            select2FirstOptionSelected(element);
        });


        $.each( $form.find(".select2-reset"), function(index, element) {
            select2FirstOptionSelected(element);
        });


        $form.find(".select2-multi-box-c").val([]).trigger('change');
        $form.find(".select2-multi-box-c").val(null).trigger('change');
        $form.find(".select2-multi-box-c").empty().trigger('change');
    }


    //
    function selectFirstOption(selector)
    {
        var $select = $(selector);
        var firstVal = $select.find('option:first').val();
        if(firstVal)
        {
            console.log('selectFirstOption is');
            $select.val(firstVal).trigger('change');
        }
        else
        {
            console.log('selectFirstOption not');
            // $select.val([]).trigger('change');
            $select.val(null).trigger('change');
        }
    }

    //
    function select2FirstOptionSelected(dom)
    {
        var $dom = $(dom);
        var firstVal = $dom.find('option:first').val();
        if(firstVal)
        {
            $dom.val(firstVal).trigger('change');
        }
        else
        {
            $dom.val(null).trigger('change');
        }
    }





    function select2_client_init(selector,$default)
    {
        var $element = $(selector);
        if ($element.data('select2'))
        {
            $element.select2('destroy'); // 销毁旧实例
        }

        // 重新初始化
        $element.select2({
            ajax: {
                url: "{{ url('/v1/operate/select2/select2_client') }}",
                type: 'post',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        _token: $('meta[name="_token"]').attr('content'),
                        keyword: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function (data, params) {

                    params.page = params.page || 1;
                    return {
                        results: data,
                        pagination: {
                            more: (params.page * 30) < data.total_count
                        }
                    };
                },
                cache: true
            },
            escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
            minimumInputLength: 0,
            theme: 'classic'
        });

        if($default) $element.val($default).trigger('change');
    }

    function select2_project_init(selector,$default)
    {
        var $element = $(selector);
        if ($element.data('select2'))
        {
            $element.select2('destroy'); // 销毁旧实例
        }

        // 重新初始化
        $element.select2({
            ajax: {
                url: "{{ url('/v1/operate/select2/select2_project') }}",
                type: 'post',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        _token: $('meta[name="_token"]').attr('content'),
                        keyword: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function (data, params) {

                    params.page = params.page || 1;
                    return {
                        results: data,
                        pagination: {
                            more: (params.page * 30) < data.total_count
                        }
                    };
                },
                cache: true
            },
            escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
            minimumInputLength: 0,
            theme: 'classic'
        });

        if($default) $element.val($default).trigger('change');
    }

    function select2_location_district_init(selector,$default)
    {
        var $element = $(selector);
        if ($element.data('select2'))
        {
            $element.select2('destroy'); // 销毁旧实例
        }

        var $city_dom = $($element.data('city-target'));
        var $city_value = '';
        // var $city_value = $city_dom.val();

        // 重新初始化
        $element.select2({
            ajax: {
                url: "{{ url('/v1/operate/select2/select2_location' ) }}",
                type: 'post',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        _token: $('meta[name="_token"]').attr('content'),
                        keyword: params.term, // search term
                        page: params.page,
                        type: 'district',
                        location_city: $city_value
                    };
                },
                processResults: function (data, params) {

                    params.page = params.page || 1;
                    return {
                        results: data,
                        pagination: {
                            more: (params.page * 30) < data.total_count
                        }
                    };
                },
                cache: true
            },
            escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
            minimumInputLength: 0,
            theme: 'classic'
        });

        if($default) $element.val($default).trigger('change');
    }




    function convertMinutesToHoursAndMinutes($totalMinutes)
    {
        const $hours = Math.floor($totalMinutes / 60);
        const $minutes = $totalMinutes % 60;
        let result = "";
        if($hours > 0)
        {
            if($minutes == 0) result += ($hours+'H');
            else result += ($hours+'小时');
        }
        if($minutes > 0)
        {
            result += ($minutes+'分钟');
        }
        return result;
    }



    // 是否为本人登录
    function is_only_me()
    {
        $.post(
            "/is_only_me",
            {
                _token: $('meta[name="_token"]').attr("content")
            },
            function(result){
                if(result.result != 'access')
                {
                    // layer.msg('该账户在其他设备登录或退出，即将跳转登录页面！');
                    layer.msg('登录失效，请重新登录！');
                    setTimeout(function(){
                        location.href = "{{ url('/logout_without_token') }}";
                    }, 600);
                }
            }
        );

    }


    // 是否为IP白名单
    function is_ip_login()
    {
        $.post(
            "/is_ip_login",
            {
                _token: $('meta[name="_token"]').attr("content")
            },
            function(result)
            {
                if(result.result != 'access')
                {
                    layer.msg('IP禁止登录！');
                    setTimeout(function(){
                        location.href = "{{ url('/logout_without_token') }}";
                    }, 600);
                }
            }
        );

    }


    function query_last_delivery()
    {

        $.post(
            "/query_last_delivery",
            {
                _token: $('meta[name="_token"]').attr('content')
            },
            'json'
        )
            .done(function($response) {
                // console.log('done');
                $response = JSON.parse($response);

                if(!$response.success)
                {
                    if($response.msg) layer.msg($response.msg);
                }
                else
                {
                    // layer.msg("请求成功！");
                    // console.log($response.data);
                    if($response.data.last_delivery)
                    {
                        var $last_id = $response.data.last_delivery.id;
                        console.log($last_id);

                        var $last_delivery_id = localStorage.getItem('last_delivery_id');
                        if($last_delivery_id)
                        {
                            if($last_id > $last_delivery_id)
                            {
                                localStorage.setItem('last_delivery_id',$last_id);
                                $('.notification-dom').show();
                                alertSound();
                            }
                        }
                        else
                        {
                            localStorage.setItem('last_delivery_id',$last_id);
                            $('.notification-dom').show();
                            alertSound();
                        }
                    }
                }
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                console.log('fail');
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
                // layer.msg('服务器错误！');

            })
            .always(function(jqXHR, textStatus) {
                // console.log('always');
                // console.log(jqXHR);
                // console.log(textStatus);
            });

    }

    function alertSound() // 声音提示
    {
        // $("body").append('<embed src="/resource/common/sounds/ding.mp3" autostart="true" hidden="true" loop="false">');
        var audio = new Audio('/resource/common/sounds/ding.mp3');
        audio.preload = 'auto';
        audio.play();
    }




    function filter(str)
    {
        // 特殊字符转义
        str += ''; // 隐式转换
        str = str.replace(/%/g, '%25');
        str = str.replace(/\+/g, '%2B');
        str = str.replace(/ /g, '%20');
        str = str.replace(/\//g, '%2F');
        str = str.replace(/\?/g, '%3F');
        str = str.replace(/&/g, '%26');
        str = str.replace(/\=/g, '%3D');
        str = str.replace(/#/g, '%23');
        return str;
    }


    function formateObjToParamStr(paramObj)
    {
        const sdata = [];
        for (let attr in paramObj)
        {
            sdata.push('${attr}=${filter(paramObj[attr])}');
        }
        return sdata.join('&');
    }


    function url_build(path, params)
    {
        var url = "" + path;
        var _paramUrl = "";
        // url 拼接 a=b&c=d
        if(params)
        {
            _paramUrl = Object.keys(params).map(function (k) {
                return [encodeURIComponent(k), encodeURIComponent(params[k])].join("=");
            }).join("&");
            _paramUrl = "?" + _paramUrl
        }
        return url + _paramUrl
    }


    function go_back()
    {
        var $url = window.location.href;  // 返回完整 URL (https://www.runoob.com/html/html-tutorial.html?id=123)
        var $origin = window.location.origin;  // 返回基础 URL (https://www.runoob.com/)
        var $domain = document.domain;  // 返回域名部分 (www.runoob.com)
        var $pathname = window.location.pathname;  // 返回路径部分 (/html/html-tutorial.html)
        var $search= window.location.search;  // 返回参数部分 (?id=123)
    }


    // date 代表指定的日期，格式：2018-09-27
    // day 传-1表始前一天，传1表始后一天
    // JS获取指定日期的前一天，后一天
    function getNextDate(date, day)
    {
        var dd = new Date(date);
        dd.setDate(dd.getDate() + day);
        var y = dd.getFullYear();
        var m = dd.getMonth() + 1 < 10 ? "0" + (dd.getMonth() + 1) : dd.getMonth() + 1;
        var d = dd.getDate() < 10 ? "0" + dd.getDate() : dd.getDate();
        return y + "-" + m + "-" + d;
    };


    // console.log($(window).height());  // 浏览器当前窗口可视区域高度
    // console.log($(document).height());  // 浏览器当前窗口文档的高度
    // console.log($(document.body).height());  // 浏览器当前窗口文档 body 的高度
    // console.log($(document.body).outerHeight(true));  // 文档body 的总高度 （border padding margin)




    // function copyToClipboard(text)
    // {
    //     // 创建一个隐藏的textarea元素
    //     var textarea = document.createElement("textarea");
    //
    //     // 设置要复制的文本内容
    //     textarea.value = text;
    //
    //     // 添加该元素到页面上（但不显示）
    //     document.body.appendChild(textarea);
    //
    //     // 选中并复制文本
    //     textarea.select();
    //     document.execCommand('copy');
    //
    //     // 移除该元素
    //     document.body.removeChild(textarea);
    //
    //     console.log('已经写入：'+text)
    // }
    // copyToClipboard('123321');
    // copyToClipboard('135');





    function select2_department_init(selector,$default)
    {
        var $element = $(selector);
        if ($element.data('select2'))
        {
            $element.select2('destroy'); // 销毁旧实例
        }

        // 重新初始化
        $element.select2({
            ajax: {
                url: "{{ url('/v1/operate/select2/select2_department') }}",
                type: 'post',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        _token: $('meta[name="_token"]').attr('content'),
                        keyword: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function (data, params) {

                    params.page = params.page || 1;
                    return {
                        results: data,
                        pagination: {
                            more: (params.page * 30) < data.total_count
                        }
                    };
                },
                cache: true
            },
            escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
            minimumInputLength: 0,
            theme: 'classic'
        });

        if($default) $element.val($default).trigger('change');
    }


</script>