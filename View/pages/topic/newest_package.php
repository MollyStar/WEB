<?php Kernel\View::part('common.header', ['title' => 'Dashboard']) ?>
    <style>
        body {
            min-height: 100vh;
            background: white url("/asset/image/topic/newest_package.jpg") no-repeat 80% 7vh;
        }

        .panel-alpha {
            background-color: rgba(255, 255, 255, .8);
        }

        .nav-tabs {
            border: 0 none;
            margin-bottom: 10px;
        }

        .nav-tabs a {
            color: #333;
            text-decoration: none;
        }

        .nav-tabs .fa {
            display: none;
        }

        .nav-tabs a.active {
            color: #18bc9c;
        }

        .nav-tabs .active .fa {
            display: inline-block;
        }

        .tab-content p {
            line-height: 1;
        }
    </style>
    <div class="container">
        <div style="margin-top: 7vh;"></div>
        <div class="row">
            <div class="col-sm-2"></div>
            <div class="col-sm-6">
                <form id="form" class="form-horizontal" onsubmit="return false;" action="/topic/newest_package/get">
                    <section class="panel panel-alpha">
                        <div class="panel-heading">
                            <h3>
                                新手礼包
                            </h3>
                            <p>领取时请确认帐号处于<b class="text-danger"> 离线 </b>状态，<b class="text-info">
                                    公共仓库 </b>留有有足够空位(包括美塞塔余额)，否则会领取失败。<span class="text-warning">每个帐号仅限领取一次</span></p>
                        </div>
                        <div class="panel-body">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <input class="form-control required" type="text" placeholder="帐号" name="username"
                                           autofocus="autofocus" autocomplete="off"/>
                                </div>
                                <div class="form-group">
                                    <input class="form-control required" type="password" placeholder="密码"
                                           name="password"/>
                                </div>
                                <div class="form-group">
                                    <div class="row nav nav-tabs">
                                        <div class="col-xs-4">
                                            <a data-sec="0" href="#HU_disp" class="active" data-toggle="tab"
                                               aria-expanded="true">
                                                <i class="fa fa-check"></i> HU 战士
                                            </a>
                                        </div>
                                        <div class="col-xs-4">
                                            <a data-sec="1" href="#RA_disp" data-toggle="tab" aria-expanded="false">
                                                <i class="fa fa-check"></i> RA 枪手
                                            </a>
                                        </div>
                                        <div class="col-xs-4">
                                            <a data-sec="2" href="#FO_disp" data-toggle="tab" aria-expanded="false">
                                                <i class="fa fa-check"></i> FO 法师
                                            </a>
                                        </div>
                                    </div>
                                    <div class="tab-content">
                                        <div class="tab-pane fade active show" id="HU_disp">
                                            <p>MST：200000</p>
                                            <p>武器：30H销金弧光匕首，15H最后生还者</p>
                                            <p>防具：猎人专用盾</p>
                                            <p>插件：恶魔级/战斗，巨人级/攻击 x3</p>
                                            <p>道具：扩展插槽 x4</p>
                                            <p>玛古：尼德拉 50 100 50 0</p>
                                        </div>
                                        <div class="tab-pane fade" id="RA_disp">
                                            <p>MST：200000</p>
                                            <p>武器：30H销金格林机枪，15H最后的冲击</p>
                                            <p>防具：枪手专用盾</p>
                                            <p>插件：恶魔级/战斗，妖精级/命中 x3</p>
                                            <p>道具：扩展插槽 x4</p>
                                            <p>玛古：娑陀 50 70 80 0</p>
                                        </div>
                                        <div class="tab-pane fade" id="FO_disp">
                                            <p>MST：200000</p>
                                            <p>武器：炎杖阿耆尼，冰杖达衮，雷杖因陀罗</p>
                                            <p>防具：法师专用盾</p>
                                            <p>插件：恶魔级/魔法，天使级/精神 x3</p>
                                            <p>魔法：Lv10低/Lv5中/Lv1高级</p>
                                            <p>道具：扩展插槽 x4</p>
                                            <p>玛古：比玛 100 0 0 100</p>
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
                var data = el.serializeArray();

                data.push({name: 'sec', value: form.find('.nav-tabs a.active').data('sec')});

                $.post(el.attr('action'), data).done(function (ret) {
                    if (ret) {
                        if (ret.code === 0) {
                            success = true;
                            $.topTip(ret.msg, 'success');
                            setTimeout(function () {
                                window.location.reload();
                            }, 1500);
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