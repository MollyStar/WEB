<?php Kernel\View::part('common.header', ['title' => '注册']) ?>
<style>@import "/asset/css/part/form-signin.min.css";</style>
<style>
    body {
        background: #fff url(/asset/image/register_bg.jpg) no-repeat fixed 70% -10%;
    }

    #logo {
        height: 200px;
        width: 350px;
        position: absolute;
        background: transparent url(/asset/image/logo.png) no-repeat 0 0;
        top: -15px;
        left: 36%;
        margin-left: -175px;
        z-index: 0;
    }
</style>
<div id="logo"></div>
<div class="container">
    <div class="form-signin">
        <form id="form" onsubmit="return false;" class="form-horizontal col-xs-12" method="post"
              action="/register/save">
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
                    <i class="input-group-addon fa fa-check"></i>
                    <input class="form-control required" type="password" placeholder="确认密码" name="rpassword"/>
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
                <input type="submit" class="btn btn-info btn-block" value="注&nbsp;&nbsp;&nbsp;&nbsp;册"/>
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
