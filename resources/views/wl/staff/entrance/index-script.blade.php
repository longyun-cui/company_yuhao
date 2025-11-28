<script>
    $(function() {


        // 【开关】
        $(".main-content").on('click', "#toggle-button-for-take-order", function() {
            var $that = $(this);
            var $toggle_box = $(this).parents('.toggle-box');

            if($(this).hasClass('toggle-button-on'))
            {
                //
                $.post(
                    "{{ url('/v1/operate/user/field-set') }}",
                    {
                        _token: $('meta[name="_token"]').attr('content'),
                        operate_category: "field-set",
                        column_key: "is_take_order",
                        column_value: 0
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
                            $that.toggleClass('toggle-button-on toggle-button-off');
                            var handle = $(this).find('.toggle-handle');
                            // handle.toggleClass('toggle-on toggle-off');

                            layer.msg('已关闭接单');
                            $toggle_box.find('.toggle-handle-text').html('【已关闭】');
                            handle.animate({'left': '0'}, 'fast');
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
            else
            {
                //
                $.post(
                    "{{ url('/v1/operate/user/field-set') }}",
                    {
                        _token: $('meta[name="_token"]').attr('content'),
                        operate_category: "field-set",
                        column_key: "is_take_order",
                        column_value: 1
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
                            $that.toggleClass('toggle-button-on toggle-button-off');
                            var handle = $(this).find('.toggle-handle');
                            // handle.toggleClass('toggle-on toggle-off');

                            layer.msg('已开启接单');
                            $toggle_box.find('.toggle-handle-text').html('【开启中】');
                            handle.animate({'left': '25px'}, 'fast');
                            $('.menu-of-clue-preferential').show();
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
        });

        // 【开关】
        $(".main-content").on('click', "#toggle-button-for-automatic-dispatching", function() {
            var $that = $(this);
            var $toggle_box = $(this).parents('.toggle-box');

            if($(this).hasClass('toggle-button-on'))
            {
                //
                $.post(
                    "{{ url('/v1/operate/parent-client/field-set') }}",
                    {
                        _token: $('meta[name="_token"]').attr('content'),
                        operate_category: "field-set",
                        column_key: "is_automatic_dispatching",
                        column_value: 0
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
                            $that.toggleClass('toggle-button-on toggle-button-off');
                            var handle = $(this).find('.toggle-handle');
                            // handle.toggleClass('toggle-on toggle-off');

                            layer.msg('已关闭自动派单');
                            $toggle_box.find('.toggle-handle-text').html('【已关闭】');
                            handle.animate({'left': '0'}, 'fast');
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
            else
            {
                //
                $.post(
                    "{{ url('/v1/operate/parent-client/field-set') }}",
                    {
                        _token: $('meta[name="_token"]').attr('content'),
                        operate_category: "field-set",
                        column_key: "is_automatic_dispatching",
                        column_value: 1
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
                            $that.toggleClass('toggle-button-on toggle-button-off');
                            var handle = $(this).find('.toggle-handle');
                            // handle.toggleClass('toggle-on toggle-off');

                            layer.msg('已开启自动派单');
                            $toggle_box.find('.toggle-handle-text').html('【开启中】');
                            handle.animate({'left': '0'}, 'fast');
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
        });

        // 【一键派单】
        $(".main-content").on('click', "#admin-summit-for-automatic-dispatching", function() {
            var $that = $(this);
            var $toggle_box = $(this).parents('.toggle-box');


            layer.msg('确定"一键派单"么', {
                time: 0
                ,btn: ['确定', '取消']
                ,yes: function(index){

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


                    $.post(
                        "{{ url('/v1/operate/delivery/automatic-dispatching-by-admin') }}",
                        {
                            _token: $('meta[name="_token"]').attr('content'),
                            operate: "automatic-dispatching-by-admin"
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
                                layer.msg('已派单');
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
            });


        });


    });
</script>