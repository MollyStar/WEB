<?php Kernel\View::part('common.header', ['title' => '物品管理']) ?>
<div class="row m-lr-0">
    <form id="form" class="form-horizontal" onsubmit="return false;" action="/item/update">
        <div class="col-sm-12">
            <section class="panel">
                <div class="panel-heading">
                    <h3>
                        物品管理
                    </h3>
                </div>
            </section>
            <section class="panel">
                <div class="panel-body">
                    <div class="wide-table-fixed-btns">
                        <button id="save-btn" class="btn btn-lg btn-info" type="submit">保存</button>
                    </div>
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>HEX</th>
                            <th>名称</th>
                            <th>中文名称</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($data as $item): ?>
                            <tr>
                                <td>
                                    <input class="sign-changed" name="data[<?php echo $item['hex']; ?>][changed]"
                                           type="hidden"
                                           value="0">
                                    <?php echo $item['hex']; ?>
                                </td>
                                <td><input name="data[<?php echo $item['hex']; ?>][name]"
                                           class="form-control input-sm" type="text"
                                           value="<?php echo $item['name']; ?>">
                                </td>
                                <td><input name="data[<?php echo $item['hex']; ?>][name_zh]"
                                           class="form-control input-sm" type="text"
                                           value="<?php echo $item['name_zh']; ?>">
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </form>
</div>
<script src="/asset/js/part/form-whole-save.min.js"></script>
<script>
    var form = $('#form');
    form.wholeSave();
    form.on('change', 'input,select', function () {
        $(this).parents('tr').find('td:eq(0) input').val(1);
    });
</script>
<?php Kernel\View::part('common.footer') ?>
