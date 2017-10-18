<?php Kernel\View::part('common.header', ['title' => '怪物']) ?>
<style>
    #save-btn {
        position: fixed;
        right: 10px;
        bottom: 10px;
    }
</style>
<div class="container">
    <div class="row">
        <form id="form" class="form-horizontal" onsubmit="return false;" action="/mob/update">
            <div class="col-sm-12">
                <section class="panel">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            怪物
                        </h3>
                    </div>
                    <div class="panel-body">
                        <button id="save-btn" class="btn btn-lg btn-info" type="submit">保存</button>
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>EP</th>
                                <th>名称</th>
                                <th>中文名称</th>
                                <th>区域</th>
                                <th>排序</th>
                                <th>BOSS?</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($data as $item): ?>
                                <tr>
                                    <td>
                                        <input name="data[<?php echo $item['id']; ?>][changed]" type="hidden"
                                               value="0">
                                        <?php echo $item['ep_disp']; ?>
                                    </td>
                                    <td><?php echo $item['name']; ?></td>
                                    <td><input name="data[<?php echo $item['id']; ?>][name_zh]"
                                               class="form-control input-sm" type="text"
                                               value="<?php echo $item['name_zh']; ?>">
                                    </td>
                                    <td>
                                        <select class="form-control" name="data[<?php echo $item['id']; ?>][area]">
                                            <option value="">-</option>
                                            <?php foreach ($area[$item['ep']] as $ak => $a): ?>
                                                <option<?php echo $item['area'] === $ak ? ' selected="selected"'
                                                    : ''; ?>
                                                        value="<?php echo $ak; ?>"><?php echo $a[0][1]; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td><input name="data[<?php echo $item['id']; ?>][order]"
                                               class="form-control input-sm" type="number"
                                               value="<?php echo $item['order']; ?>">
                                    </td>
                                    <td><input name="data[<?php echo $item['id']; ?>][boss]"
                                               class="checkbox" type="checkbox"
                                               value="1"<?php echo $item['boss'] == 1 ? ' checked="checked"' : ''; ?>>
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
<script>
    var form = $('#form');

    var submit_lock = false;

    form.on('submit', function () {

        if (submit_lock) return;

        submit_lock = true;

        var data = $(this).serializeArray();

        $.post($(this).attr('action'), data, function (ret) {
            if (ret && ret.code === 0 && ret.response > 0) {
                window.location.reload();
            }

            submit_lock = false;
        });

        return false;
    });

    form.on('change', 'input,select', function () {
        $(this).parents('tr').find('td:eq(0) input').val(1);
    })
</script>
<?php Kernel\View::part('common.footer') ?>
