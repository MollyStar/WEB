<?php Kernel\View::part('common.header', ['title' => '物品']) ?>
<style>
    #save-btn {
        position: fixed;
        right: 10px;
        bottom: 10px;
    }

    #sync-btn {
        position: fixed;
        right: 10px;
        bottom: 76px;
    }
</style>
<div class="container">
    <div class="row">
        <form id="form" class="form-horizontal" onsubmit="return false;" action="/item/update">
            <div class="col-sm-12">
                <section class="panel">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            物品
                        </h3>
                    </div>
                    <div class="panel-body">
                        <button id="save-btn" class="btn btn-lg btn-info" type="submit">保存</button>
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
