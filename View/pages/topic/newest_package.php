<?php Kernel\View::part('common.header', ['title' => 'Dashboard']) ?>
    <style>
        body {
            min-height: 100vh;
            background: white url("/asset/image/topic/newest_package.jpg") no-repeat 70% 7vh;
        }

        .panel-alpha {
            background-color: rgba(255, 255, 255, .8);
        }
    </style>
    <div class="container">
        <div style="margin-top: 15vh;"></div>
        <div class="row">
            <div class="col-sm-2"></div>
            <div class="col-sm-5">
                <form id="form" class="form-horizontal" action="/topic/newest_package_get">
                    <section class="panel panel-alpha">
                        <div class="panel-heading">
                            <h3>
                                新手礼包
                            </h3>
                            <p>领取时请确认帐号处于<b class="text-danger"> 离线 </b>状态，<b class="text-info">
                                    公共仓库 </b>至少留有有<b
                                        class="text-danger"> 5 </b>个空位。<span class="text-warning">每个帐号仅限领取一次</span></p>
                        </div>
                        <div class="panel-body">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <input class="form-control required" type="text" placeholder="帐号" name="username"
                                           autofocus="autofocus"/>
                                </div>
                                <div class="form-group">
                                    <input class="form-control required" type="password" placeholder="密码"
                                           name="password"/>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xs-4 radio">
                                            <label>
                                                <input type="radio" name="sec" value="0" checked="checked">
                                                HU 战士
                                            </label>
                                        </div>
                                        <div class="col-xs-4 radio">
                                            <label>
                                                <input type="radio" name="sec" value="1">
                                                RA 枪手
                                            </label>
                                        </div>
                                        <div class="col-xs-4 radio">
                                            <label>
                                                <input type="radio" name="sec" value="2">
                                                FO 法师
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group col-xs-12">
                                        <input autocomplete="Off" class="form-control required" type="text"
                                               placeholder="验证码"
                                               name="verify_code"/>
                                        <div class="input-group-btn">
                                            <div id="verify_code"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group clearfix">
                                    <input type="submit" class="btn btn-info btn-block"
                                           value="领&nbsp;&nbsp;&nbsp;&nbsp;取"/>
                                </div>
                            </div>
                        </div>
                    </section>
                </form>
            </div>
        </div>
    </div>
    <script>
        (function ($) {
            var form = $('#form');

            $('#verify_code').verifycode();

            var success = false;
            form.on('submit', function (e) {
                e.preventDefault();

                if (success) {
                    return;
                }
                var el = $(this);
                $.post(el.attr('action'), el.serializeArray()).done(function (ret) {
                    if (ret) {
                        if (ret.code === 0) {
                            success = true;
                            $.topTip(ret.msg);
                            form.reset();
                            return;
                        }
                    }
                    $.topTip(ret.msg, 'warning');
                    $('#verify_code').trigger('reflush');
                }).fail(function () {
                    $.topTip('网络错误，请稍候再试', 'warning');
                    $('#verify_code').trigger('reflush');
                });
            });

            $('#verify_code').trigger('reflush');
        })(jQuery);
    </script>
<?php Kernel\View::part('common.footer') ?>