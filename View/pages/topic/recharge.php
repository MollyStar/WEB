<?php Kernel\View::part('common.header', ['title' => '充值', 'style' => ['/asset/css/part/pages/recharge']]) ?>
    <div class="container">
        <div class="col-sm-3"></div>
        <div class="col-sm-6">
            <div class="row">
                <section class="panel panel-topic">
                    <section class="panel-body">
                        <form id="form" class="form-horizontal" onsubmit="return false" action="/topic/recharge/submit">
                            <?php if ($currency): ?>
                                <?php foreach ($currency as $item): ?>
                                    <div class="form-group">
                                        <div class="col-xs-12">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <?php echo $item->name_zh; ?>
                                                </div>
                                                <div class="form-control boot-slide">
                                                    <input data-rate="<?php echo $map_currency[$item->hex][0]; ?>"
                                                           name="currency[<?php echo $item->hex; ?>]" type="text"
                                                           value="0" data-slider-min="0"
                                                           data-slider-max="<?php echo $item->num; ?>"
                                                           data-slider-step="1"
                                                           data-slider-value="0"
                                                           data-slider-id="slider_<?php echo $item->hex; ?>"
                                                           id="input_<?php echo $item->hex; ?>"
                                                           data-slider-tooltip="hide" data-slider-handle="custom"/>
                                                </div>
                                                <div class="input-group-addon">
                                                    <b>0</b> / <?php echo $item->num; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <h4 class="text-center text-danger">
                                    没有可用于充值的道具
                                </h4>
                            <?php endif; ?>
                            <div class="arrow">
                                <i class="fa fa-chevron-down"></i>
                            </div>
                            <section class="panel panel-topic">
                                <section class="panel-body">
                                    <p>莫莉币</p>
                                    <h3 class="text-center"><?php echo $user['currency']; ?><b
                                                id="currency_append" class="text-warning"></b></h3>
                                </section>
                            </section>
                            <input type="submit" class="btn btn-info btn-block" value="提&nbsp;&nbsp;&nbsp;&nbsp;交">
                        </form>
                    </section>
                </section>
            </div>
        </div>
        <div class="col-sm-3"></div>
    </div>
    <script src="/asset/js/part/pages/recharge.min.js"></script>
    <script></script>
<?php Kernel\View::part('common.footer') ?>