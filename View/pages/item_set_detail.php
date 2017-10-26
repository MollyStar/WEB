<?php Kernel\View::part('common.header', ['title' => '套装详情']) ?>
<div class="row m-lr-0">
    <div class="col-sm-12">
        <form id="form" class="form-horizontal">
            <div class="panel-heading">
                <a class="btn btn-info pull-right" href="/item_set/detail/save">返回</a>
                <h3>套装详情</h3>
            </div>
            <section class="panel">
                <div class="panel-body">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">名称</label>
                        <div class="col-sm-5">
                            <input class="form-control required" name="name" type="text">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">备注</label>
                        <div class="col-sm-5">
                            <input class="form-control" name="description" type="text">
                        </div>
                    </div>
                </div>
            </section>
            <section class="panel">
                <div class="panel-body">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>物品编码</th>
                            <th>名称</th>
                            <th>编码</th>
                            <th>数量</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($items as $key => $item): ?>
                            <tr>
                                <td>
                                    <?php echo $item->item; ?>
                                </td>
                                <td>
                                    <?php echo $map_items[$item->item]['name_zh']; ?>
                                </td>
                                <td>
                                    <input class="form-control input-sm" name="code"
                                           value="<?php echo $item->code; ?>">
                                </td>
                                <td>
                                    <input class="form-control input-sm" type="number" maxlength="2" max="99" min="0"
                                           name="num"
                                           value="<?php echo $item->num; ?>">
                                </td>
                                <td>
                                    <button class="btn btn-danger btn-sm delete" type="button"><i
                                                class="fa fa-trash"></i> 删除
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-success add">添加</button>
                </div>
            </section>
        </form>
    </div>
</div>
<script>
    (function ($) {
        var form = $('#form');

        form.on('submit', function (e) {
            var data = {
                mst: form.find('[name="mst"]').val(),
                guildcard: form.find('[name="guildcard"]').val(),
                data: []
            };
            form.find('tbody > tr').each(function () {
                data.data.push({
                    code: $.trim($(this).find('[name="code"]').val()),
                    num: $.trim($(this).find('[name="num"]').val())
                })
            });
            $.post(form.attr('action'), data).done(function (ret) {
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
            var row = '<tr>' +
                '<td></td><td></td><td>' +
                '<input class="form-control input-sm" name="code">' +
                '</td><td>' +
                '<input class="form-control input-sm" type="number" maxlength="2" max="99" min="0" name="num" value="1">' +
                '</td>' +
                '<td><button class="btn btn-danger btn-sm delete" type="button"><i class="fa fa-trash"></i> 删除</button></td>' +
                '</tr>';

            $(row).appendTo(wrap);
        });

        form.on('click', '.delete', function () {
            $(this).parents('tr').remove();
        });
    })(jQuery);
</script>
<?php Kernel\View::part('common.footer') ?>
