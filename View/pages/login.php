<?php Kernel\View::part('common.header', ['title' => '登录']) ?>
    <style>
        #form {
            background: rgba(255, 255, 255, .9);
            border-radius: 5px;
            -webkit-border-radius: 5px;
            max-width: 420px;
            margin: 130px auto 0;
            position: relative;
            z-index: 1;
        }

        #verify_code {
            box-sizing: border-box;
            padding: 2px;
            margin-top: 10px;
            border: 1px solid #dddddd;
            border-radius: 4px;
        }

        #verify_code img {
            width: 100%;
            cursor: pointer;
        }

        @media (min-width: 576px) {
            #verify_code {
                box-sizing: content-box;
                padding: 2px;
                margin-top: 0;
            }

            #verify_code img {
                height: 32px;
            }
        }
    </style>
    <div class="container">
        <form id="form" onsubmit="return false;" class="form-signin" action="/login/submit">
            <div style="padding: 20px;">
                <div class="form-group">
                    <div id="tips"></div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <i class="input-group-addon fa fa-user" style="width: 38px;"></i>
                        <input class="form-control required" type="text" placeholder="帐号" name="username"
                               autofocus="autofocus"/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <i class="input-group-addon fa fa-lock" style="width: 38px;"></i>
                        <input class="form-control required" type="password" placeholder="密码" name="password"/>
                    </div>
                </div>
                <div class="form-group clearfix">
                    <input class="form-control required col-sm-7 pull-left" type="text" placeholder="验证码"
                           name="verify_code"/>
                    <div id="verify_code" class="col-sm-4 pull-right"></div>
                </div>
                <div class="form-group clearfix">
                    <input type="submit" class="btn btn-info col-sm-12" value="登&nbsp;&nbsp;&nbsp;&nbsp;录"/>
                </div>
            </div>

        </form>
    </div>
    <script>
        (function ($) {
            function reflush() {
                $('#verify_code').empty().append($('<img src="' + verify_code_url + '?r=' + (+new Date) + '">'));
            }

            function tip(msg, type) {
                type = type || 'warning';
                $('#tips').empty().append($('' +
                    '<div class="alert alert-' + type + '">' +
                    '<a href="#" class="close" data-dismiss="alert">&times;</a>' +
                    msg +
                    '</div>'
                ));
            }

            var verify_code_url = '/verifiation.jpg';

            $('#verify_code').on('click', 'img', reflush);

            var success = false;
            $('#form').on('submit', function () {
                if (success) {
                    return;
                }
                var el = $(this);
                $.post(el.attr('action'), el.serializeArray(), function (ret) {
                    if (ret) {
                        if (ret.code === 0) {
                            success = true;
                            tip(ret.msg, 'success');
                            window.location.reload();
                        } else {
                            tip(ret.msg);
                            reflush();
                        }
                        return;
                    }

                    tip('网络错误，请稍候再试');
                    reflush();
                });
            });

            reflush();
        })(jQuery);
    </script>
<?php Kernel\View::part('common.footer') ?>