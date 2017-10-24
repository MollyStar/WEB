<style>
    .droped-item {
        cursor: pointer;
    }

    .droped-item:hover {
        color: #ffffff !important;
        background-color: #333333 !important;
    }

    .droped-item:hover > i {
        color: #d2d2d2 !important;
    }

    .box-drop-unit {
        padding-bottom: 22px !important;
    }

    .box-drop-unit .droped-item:hover > span {
        border-color: rgba(255, 255, 255, .25);
    }

    .box-drop-unit-add {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 20px;
        line-height: 20px;
        -webkit-border-radius: 0;
        -moz-border-radius: 0;
        border-radius: 0;
        -webkit-box-shadow: none;
        -moz-box-shadow: none;
        box-shadow: none;
        padding: 0;
        background-color: rgba(0, 0, 0, .2);
        outline: none;
    }

    .box-drop-unit-add:focus, .box-drop-unit-add:active {
        background-color: rgba(0, 0, 0, .5);
        outline: none;
        -webkit-box-shadow: none;
        -moz-box-shadow: none;
        box-shadow: none;
    }

    .ui-dialog.modal.mob-drop-item > .modal-dialog {
        margin-top: 15%;
    }
</style>
<div class="row m-sm-0">
    <div class="col-sm-12">
        <section class="panel">
            <div class="panel-heading">
                <h1>
                    掉落管理
                </h1>
            </div>
            <div class="panel-body"></div>
        </section>
    </div>
    <form id="form" onsubmit="return false;">
        <?php foreach ($map_ep as $ek => $ep): ?>
            <div class="col-sm-12">
                <section class="panel">
                    <div class="panel-heading">
                        <h3><?php echo $ep[2]; ?></h3>
                    </div>
                    <div class="panel-body"></div>
                </section>
                <?php foreach ($map_area[$ek] as $ak => $area): ?>
                    <section class="panel">
                        <div class="panel-heading">
                            <h4><?php echo $area[0][1]; ?></h4>
                        </div>
                        <div class="panel-body">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>名称</th>
                                    <th>难度</th>
                                    <?php foreach ($map_sec as $sk => $sec): ?>
                                        <th>
                                            <?php echo $sec[1]; ?><br/>
                                            <?php echo $sec[0]; ?>
                                        </th>
                                    <?php endforeach; ?>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if ($area_mob = $map_mob_drop[$ek][$ak] ?? null): ?>
                                    <?php foreach ($area_mob as $mob): ?>
                                        <?php foreach ($map_dif as $dk => $dif): ?>
                                            <tr>
                                                <?php if (current($map_dif) === $dif): ?>
                                                    <td rowspan="4">
                                                        <?php echo $mob['name_zh']; ?><br/>
                                                        <?php echo $mob['name']; ?>
                                                    </td>
                                                <?php endif; ?>
                                                <td>
                                                    <?php echo $dif[2]; ?>
                                                </td>
                                                <?php foreach ($map_sec as $sk => $sec): ?>
                                                    <?php $item = $mob_drop[$dk][$ek][$ak][$mob['name']][$sk]; ?>
                                                    <td data-hash="<?php echo $item['hash']; ?>"
                                                        data-ep="<?php echo $ek; ?>" data-area="<?php echo $ak; ?>"
                                                        data-sec="<?php echo $sk; ?>" data-dif="<?php echo $dk; ?>"
                                                        data-item="<?php echo $item['item_hex']; ?>"
                                                        data-rate="<?php echo $item['rate']; ?>"
                                                        class="mob-drop-unit droped-item">
                                                        <i><?php echo $item['rate_p']; ?></i>
                                                        <?php echo $item['item_name_zh']; ?><br/>
                                                        <?php echo $item['item_name']; ?>
                                                    </td>
                                                <?php endforeach; ?>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                <?php foreach ($map_dif as $dk => $dif): ?>
                                    <tr>
                                        <?php if (current($map_dif) === $dif): ?>
                                            <td rowspan="4">箱子</td>
                                        <?php endif; ?>
                                        <td>
                                            <?php echo $dif[2]; ?>
                                        </td>
                                        <?php foreach ($map_sec as $sk => $sec): ?>
                                            <td class="box-drop-unit"
                                                data-ep="<?php echo $ek; ?>"
                                                data-area="<?php echo $ak; ?>"
                                                data-sec="<?php echo $sk; ?>"
                                                data-dif="<?php echo $dk; ?>">
                                                <?php if ($boxs = $box_drop[$dk][$ek][$ak][$sk] ?? []): ?>
                                                    <?php foreach ($boxs as $box): ?>
                                                        <span class="droped-item"
                                                              data-hash="<?php echo $box['hash']; ?>"
                                                              data-item="<?php echo $box['item_hex']; ?>"
                                                              data-rate="<?php echo $box['rate']; ?>"
                                                              data-order="<?php echo $box['order']; ?>"
                                                              data-lv="<?php echo $box['lv']; ?>">
                                                        <span><?php echo $box['name_zh']; ?></span>
                                                        <i><?php echo $box['rate_p']; ?></i>
                                                            <?php echo $box['item_name_zh']; ?><br/>
                                                            <?php echo $box['item_name']; ?>
                                                    </span>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                                <button class="btn box-drop-unit-add" type="button"><i
                                                            class="fa fa-plus"></i></button>
                                            </td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </section>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </form>
</div>
<script>
    var AREA = <?php echo json_encode($map_box_area_lv);?>;
    var ITEMS = <?php echo $items->toJson();?>;
</script>
<script>
    (function ($) {
        var form = $('#form');

        function items(current) {
            current = current == null || current == '000000' ? null : current;
            var c = [];
            c.push('<select name="item" class="editable-select form-control">');
            $.each(ITEMS, function (_, item) {
                c.push('<option' + (current == item['hex'] ? ' selected="selected"' : '') + ' value="' + item['hex'] + '">' + item['hex'] + ',' + item['name_zh'] + '</option>')
            });
            c.push('</select>');

            return c.join('');
        }

        function area(ep_key, area_key, current) {
            var c = [];
            c.push('<select name="lv" class="editable-select form-control">');
            $.each(AREA[ep_key][area_key][1], function (lk, LV) {
                c.push('<option' + (current == lk ? ' selected="selected"' : '') + ' value="' + lk + '">' + LV[0] + ',' + LV[1] + '</option>')
            });
            c.push('</select>');

            return c.join('');
        }

        function mobItemSelecter() {
            var info = $(this).data();
            var dialog = $.dialog(
                '<form class="form-horizontal">' +
                '<div class="form-group">' +
                '   <div class="control-label col-xs-3">物品</div>' +
                '   <div class="col-xs-8">' +
                '       ' + items(info.item) +
                '   </div>' +
                '</div>' +
                '<div class="form-group">' +
                '   <div class="control-label col-xs-3">掉率</div>' +
                '   <div class="col-xs-8">' +
                '       <input name="rate" class="form-control" type="number" max="255" min="0" step="1" value="' + info.rate + '">' +
                '   </div>' +
                '</div>' +
                '</form>', {
                    className: 'mob-drop-item',
                    buttons: [
                        {
                            className: 'btn btn-info',
                            button: '保存',
                            callback: function (dialog) {
                                var el = $(this);
                                var info = el.data();
                                var data = dialog.find('form').serializeAssoc();
                                if (data.rate > -1 && data.item) {
                                    data.hash = info.hash;
                                    data.type = 'mob';
                                    $.post('/drop/update', data).done(function (ret) {
                                        if (ret) {
                                            if (ret.code === 0) {
                                                $.topTip(ret.msg);
                                                var rep = ret.response;
                                                el.data('item', rep.item);
                                                el.data('rate', rep.rate);
                                                el.html([
                                                    '<i>' + rep.rate_p + '</i>',
                                                    rep.item_info.name_zh + '<br/>',
                                                    rep.item_info.name
                                                ].join("\n"));
                                                dialog.modal('hide');
                                                return;
                                            }
                                            $.topTip(ret.msg, 'warning');
                                        }
                                    }).fail(function () {
                                        $.topTip('保存失败', 'danger');
                                    });
                                }
                            },
                            context: this
                        },
                        {
                            className: 'btn btn-secondary',
                            button: '关闭',
                            callback: function (dialog) {
                                dialog.modal('hide');
                            }
                        }
                    ]
                });

            dialog.on('hidden.bs.modal', function () {
                dialog.remove();
                dialog = null;
            });

            dialog.find('.editable-select').editableSelect({
                effects: 'fade',
                additional: true
            });
        }

        function boxItemSelecter() {
            var pub_info = $(this).parents('.box-drop-unit').data();
            var info = $(this).data();
            var dialog = $.dialog(
                '<form class="form-horizontal">' +
                '<div class="form-group">' +
                '   <div class="control-label col-xs-3">区域(地图)</div>' +
                '   <div class="col-xs-8">' +
                '       ' + area(pub_info.ep, pub_info.area, info.lv) +
                '   </div>' +
                '</div>' +
                '<div class="form-group">' +
                '   <div class="control-label col-xs-3">物品</div>' +
                '   <div class="col-xs-8">' +
                '       ' + items(info.item) +
                '   </div>' +
                '</div>' +
                '<div class="form-group">' +
                '   <div class="control-label col-xs-3">掉率</div>' +
                '   <div class="col-xs-8">' +
                '       <input name="rate" class="form-control" type="number" max="255" min="0" step="1" value="' + info.rate + '">' +
                '   </div>' +
                '</div>' +
                '</form>', {
                    className: 'mob-drop-item',
                    buttons: [
                        {
                            className: 'btn btn-info',
                            button: '保存',
                            callback: function (dialog) {
                                var el = $(this);
                                var parent = el.parents('.box-drop-unit');
                                var info = el.data();
                                var pub_info = parent.data();
                                var data = dialog.find('form').serializeAssoc();
                                if (data.lv > -1 && data.rate > -1 && data.item) {
                                    if (info.hash) {
                                        data.hash = info.hash;
                                    }
                                    data.type = 'box';
                                    data = $.extend({}, data, pub_info);

                                    $.post('/drop/update', data).done(function (ret) {
                                        if (ret) {
                                            if (ret.code === 0) {
                                                $.topTip(ret.msg);
                                                var rep = ret.response;
                                                var item = $('<span class="droped-item"' +
                                                    ' data-hash="' + rep.hash + '"' +
                                                    ' data-item="' + rep.item + '"' +
                                                    ' data-rate="' + rep.rate + '"' +
                                                    ' data-order="' + rep.order + '"' +
                                                    ' data-lv="' + rep.lv + '">' +
                                                    '<span>' + rep.name_zh + '</span>' +
                                                    '<i>' + rep.rate_p + '</i>' + rep.item_info.name_zh +
                                                    '<br/>' + rep.item_info.name +
                                                    '</span>');

                                                var resItem = parent.find('[data-hash="' + data.hash + '"]');
                                                if (resItem.length) {
                                                    resItem.replaceWith(item);
                                                } else {
                                                    item.insertBefore(parent.find('.box-drop-unit-add'));
                                                }
                                                dialog.modal('hide');
                                                return;
                                            }
                                            $.topTip(ret.msg, 'warning');
                                        }
                                    }).fail(function () {
                                        $.topTip('保存失败', 'danger');
                                    });
                                }
                            },
                            context: this
                        },
                        {
                            className: 'btn btn-secondary',
                            button: '关闭',
                            callback: function (dialog) {
                                dialog.modal('hide');
                            }
                        }
                    ]
                });

            dialog.on('hidden.bs.modal', function () {
                dialog.remove();
                dialog = null;
            });

            dialog.find('.editable-select').editableSelect({
                effects: 'fade',
                additional: true
            });
        }

        form.on('click', '.mob-drop-unit.droped-item', function (e) {
            mobItemSelecter.call(this, e);
        });

        form.on('click', '.box-drop-unit > .droped-item', function (e) {
            boxItemSelecter.call(this, e);
        });

        form.on('click', '.box-drop-unit > .box-drop-unit-add', function (e) {
            boxItemSelecter.call(this, e);
        });
    })(jQuery);
</script>