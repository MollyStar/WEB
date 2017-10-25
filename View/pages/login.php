<?php Kernel\View::part('common.header', ['title' => '登录']) ?>
    <style>@import "/asset/css/part/form-signin.min.css";</style>
    <div class="container">
        <div class="form-signin">
            <form id="form" class="form-horizontal col-xs-12" action="/login/submit">
                <div class="form-group">
                    <div id="tips"></div>
                </div>
                <div class="form-group">
                    <div class="input-group col-xs-12">
                        <i class="input-group-addon fa fa-user"></i>
                        <input class="form-control required" type="text" placeholder="帐号" name="username"
                               autofocus="autofocus"/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group col-xs-12">
                        <i class="input-group-addon fa fa-lock"></i>
                        <input class="form-control required" type="password" placeholder="密码" name="password"/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group col-xs-12">
                        <input autocomplete="Off" class="form-control required" type="text" placeholder="验证码"
                               name="verify_code"/>
                        <div class="input-group-btn">
                            <div id="verify_code"></div>
                        </div>
                    </div>
                </div>
                <div class="form-group clearfix">
                    <input type="submit" class="btn btn-info btn-block" value="登&nbsp;&nbsp;&nbsp;&nbsp;录"/>
                </div>
            </form>
        </div>
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
            $('#form').on('submit', function (e) {
                e.preventDefault();

                if (success) {
                    return;
                }
                var el = $(this);
                $.post(el.attr('action'), el.serializeArray(), function (ret) {
                    if (ret) {
                        if (ret.code === 0) {
                            success = true;
                            tip(ret.msg, 'success');
                            setTimeout(function () {
                                window.location.href = '/dashboard';
                            }, 1500);
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