<?php Kernel\View::part('common.header', ['title' => '公共银行']) ?>
<style>
    input[type="number"] {
        width: 80px;
    }
</style>
<div class="row m-lr-0">
    <form id="form" class="form-horizontal" onsubmit="return false;" action="/character/bank/save">
        <input type="hidden" name="guildcard" value="<?php echo $user['guildcard']; ?>">
        <div class="col-sm-12">
            <section class="panel">
                <div class="panel-heading">
                    <h1>
                        <?php echo $user['username']; ?> 的公共银行
                    </h1>
                </div>
            </section>
            <section class="panel">
                <div class="panel-heading">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>银行使用 <?php echo $bank_use; ?>/200</th>
                            <th style="width: 200px;">
                                <div class="input-group">
                                    <label class="input-group-addon input-sm">美赛塔</label>
                                    <input class="form-control input-sm"
                                           type="number"
                                           max="999999"
                                           min="0"
                                           name="mst"
                                           value="<?php echo $bank_meseta; ?>"></div>
                            </th>
                        </tr>
                        </thead>
                    </table>
                </div>
                <div class="panel-body">
                    <div class="wide-table-fixed-btns">
                        <button id="save-btn" class="btn btn-lg btn-info" type="submit">保存</button>
                    </div>
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>物品编码</th>
                            <th>名称</th>
                            <th>编码</th>
                            <th>数量</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($items as $key => $item): ?>
                            <tr>
                                <td>
                                    <?php echo $item->itemid; ?>
                                </td>
                                <td>
                                    <?php echo $item->item; ?>
                                </td>
                                <td>
                                    <?php echo $map_items[$item->item]['name_zh']; ?>
                                </td>
                                <td>
                                    <input class="form-control input-sm" name="data[<?php echo $key; ?>][code]"
                                           value="<?php echo $item->code; ?>">
                                </td>
                                <td>
                                    <input class="form-control input-sm" type="number" maxlength="2" max="99" min="0"
                                           name="data[<?php echo $key; ?>][num]"
                                           value="<?php echo $item->num; ?>">
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-success add">添加</button>
                </div>
            </section>
        </div>
    </form>
</div>
<script>
    (function ($) {
        var form = $('#form');

        form.on('submit', function (e) {
            $.post(form.attr('action'), form.serializeArray()).done(function (ret) {
                if (ret) {
                    if (ret.code === 0) {
                        $.topTip(ret.msg);
                        setTimeout(function () {
                            window.location.reload();
                        }, 1500);
                    } else {
                        $.topTip(ret.msg);
                    }
                    return;
                }
                $.topTip('保存中发生错误，请稍后重试');
            }).fail(function () {
                $.topTip('保存中发生错误，请稍后重试');
            });
        });

        form.on('click', '.add', function () {
            var wrap = form.find('table > tbody');
            var num = wrap.find('tr').length;
            var row = '<tr>' +
                '<td></td><td></td><td></td><td>' +
                '<input class="form-control input-sm" name="data[' + num + '][code]">' +
                '</td><td>' +
                '<input class="form-control input-sm" type="number" maxlength="2" max="99" min="0" name="data[' + num + '][num]" value="1">' +
                '</td>' +
                '</tr>';

            $(row).appendTo(wrap);
        });
    })(jQuery);
</script>
<?php Kernel\View::part('common.footer') ?>
