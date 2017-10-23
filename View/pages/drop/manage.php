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
        position: relative;
        padding-bottom: 22px !important;
    }

    .box-drop-unit .droped-item {
        display: inline-block;
        width: 100%;
        padding: 2px;
        border: 1px solid rgba(0, 0, 0, .25);
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
        margin-bottom: 2px;
    }

    .box-drop-unit .droped-item > span {
        display: inline-block;
        width: 100%;
        margin-bottom: 2px;
        border-bottom: 1px solid rgba(0, 0, 0, .25);
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
                                            <td data-ep="<?php echo $ek; ?>" data-area="<?php echo $ak; ?>"
                                                data-sec="<?php echo $sk; ?>" data-dif="<?php echo $dk; ?>"
                                                class="box-drop-unit">
                                                <?php if ($boxs = $box_drop[$dk][$ek][$ak][$sk] ?? []): ?>
                                                    <?php foreach ($boxs as $box): ?>
                                                        <span class="droped-item">
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
    var ITEMS = <?php echo $items->toJson();?>
</script>
<script>
    (function ($) {
        var form = $('#form');

        function items() {
            var c = [];
            c.push('<select name="item" class="editable-select form-control">');
            $.each(ITEMS, function (_, item) {
                c.push('<option value="' + item['hex'] + '">' + item['hex'] + ',' + item['name_zh'] + '</option>')
            });
            c.push('</select>');

            return c.join('');
        }

        function mobItemSelecter() {
            var dialog = $.dialog(
                '<form class="form-horizontal">' +
                '<div class="form-group">' +
                '<div class="col-sm-3">物品</div>' +
                '<div class="col-sm-7">' +
                items() +
                '</div>' +
                '</div>' +
                '<div class="form-group">' +
                '<div class="col-sm-3">掉率</div>' +
                '<div class="col-sm-7"><input name="rate" class="form-control" type="number" max="255" min="0" step="1"></div>' +
                '</div>' +
                '</form>', {
                    buttons: [
                        {
                            className: 'btn btn-info',
                            button: '保存',
                            callback: function (dialog) {
                                console.log(dialog.find('form').serializeArray());
                            }
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

        }

        form.on('click', '.mob-drop-unit.droped-item', function () {
            mobItemSelecter();
        });
        form.on('click', '.box-drop-unit > .droped-item', function () {
            boxItemSelecter();
        });
    })(jQuery);
</script>