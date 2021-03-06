<?php Kernel\View::part('common.header', ['title' => '登录', 'style' => '/asset/css/part/form-signin']) ?>
    <div class="container">
        <div class="form-signin">
            <form id="form" class="form-horizontal col-xs-12" action="/login/submit">
                <input type="hidden" name="USER_FEATURE">
                <input type="hidden" name="jump" value="<?php echo $jump ? base64_decode(urldecode($jump)) : ''; ?>">
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
                <div class="form-group">
                    <label class="btn btn-default btn-xs">
                        <input class="checkbox-inline" type="checkbox" name="keep_auth" value="1"/>
                        记住我
                    </label>
                    <span class="text-muted">7 天之内免登陆</span>
                </div>
                <div class="form-group clearfix">
                    <input type="submit" class="btn btn-info btn-block" value="登&nbsp;&nbsp;&nbsp;&nbsp;录"/>
                </div>
            </form>
        </div>
    </div>
    <script src="/asset/js/fingerprint2.min.js"></script>
    <script>
        (function ($) {

            $('[name="USER_FEATURE"]').userFeature();

            $('#verify_code').verifycode();

            var pending = false;
            $('#form').on('submit', function (e) {
                e.preventDefault();

                if (pending) {
                    return;
                }
                pending = true;
                var el = $(this);
                $.post(el.attr('action'), el.serializeArray()).done(function (ret) {
                    if (ret) {
                        if (ret.code === 0) {
                            $.topTip(ret.msg, 'success');
                            setTimeout(function () {
                                window.location.href = ret.response || '/';
                            }, 1500);
                            return;
                        }
                    }
                    $.topTip(ret.msg, 'warning');
                    $('#verify_code').trigger('reflush');
                    pending = false;
                }).fail(function () {
                    $.topTip('网络错误，请稍候再试', 'warning');
                    $('#verify_code').trigger('reflush');
                    pending = false;
                });
            });

            $('#verify_code').trigger('reflush');
        })(jQuery);
    </script>
<?php Kernel\View::part('common.footer') ?>