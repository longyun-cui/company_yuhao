<script>


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




        $('.time-picker').datetimepicker({
            locale: moment.locale('zh-cn'),
            format: 'YYYY-MM-DD HH:mm', // 同时包含日期和时间
            sideBySide: true,           // 并排显示日期和时间选择器
            stepping: 1,               // 时间步长（可选，例如15分钟）
            ignoreReadonly: true
        });
        $('.date-picker').datetimepicker({
            locale: moment.locale('zh-cn'),
            format: "YYYY-MM-DD",
            ignoreReadonly: true
        });
        $('.month-picker').datetimepicker({
            locale: moment.locale('zh-cn'),
            format: "YYYY-MM",
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



        $('.select2-department').select2({
            ajax: {
                url: "{{ url('/v1/operate/select2/select2-department') }}",
                type: 'post',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        _token: $('meta[name="_token"]').attr('content'),
                        department_category: this.data('department-category'),
                        department_type: this.data('department-type'),
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

        $('.select2-team').select2({
            ajax: {
                url: "{{ url('/v1/operate/select2/select2-team') }}",
                type: 'post',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        _token: $('meta[name="_token"]').attr('content'),
                        team_category: this.data('team-category'),
                        team_type: this.data('team-type'),
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

        $('.select2-staff').select2({
            ajax: {
                url: "{{ url('/v1/operate/select2/select2-staff') }}",
                type: 'post',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        _token: $('meta[name="_token"]').attr('content'),
                        staff_category: this.data('staff-category'),
                        staff_type: this.data('staff-type'),
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

        $(".select2-car1").each(function() {
            var $select = $(this);

            // 获取模态框的正确方式
            var $modal = $select.data('modal')
                ? $($select.data('modal'))
                : $(document.body);

            // 确保模态框存在
            if ($modal.length === 0) {
                $modal = $(document.body);
            }

            // 获取模态框的 DOM 元素
            var modalElement = $modal.get(0);

            $select.select2({
                ajax: {
                    // 保持原有配置不变
                    url: "{{ url('/v1/operate/select2/select2-car') }}",
                    type: 'post',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            _token: $('meta[name="_token"]').attr('content'),
                            contact_category: $select.data('contact-category'),
                            contact_type: $select.data('contact-type'),
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
                dropdownParent: modalElement,  // 使用 DOM 元素而非选择器
                minimumInputLength: 0,
                // width: '100%',
                theme: 'classic'
            });
        });


    });


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