<?php Kernel\View::part('common.header', ['title' => '怪物']) ?>
<div class="row m-sm-0">
    <form id="form" class="form-horizontal" onsubmit="return false;" action="/mob/update">
        <div class="col-sm-12">
            <section class="panel">
                <div class="panel-heading">
                    <h3>
                        怪物
                    </h3>
                </div>
            </section>
            <section class="panel">
                <div class="panel-body">
                    <div class="wide-table-fixed-btns">
                        <button id="sync-btn" href="/mob/sync_simple_names" class="btn btn-lg btn-warning"
                                type="button">同步
                        </button>
                        <button id="save-btn" class="btn btn-lg btn-info" type="submit">保存</button>
                    </div>
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>EP</th>
                            <th>名称</th>
                            <th>中文名称</th>
                            <th>区域</th>
                            <th>排序</th>
                            <th>BOSS?</th>
                            <th>特殊的?</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($data as $item): ?>
                            <tr>
                                <td>
                                    <input class="sign-changed" name="data[<?php echo $item['id']; ?>][changed]"
                                           type="hidden"
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
                                            <option<?php echo $item['area'] === $ak ? ' selected="selected"' : ''; ?>
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
                                <td><input name="data[<?php echo $item['id']; ?>][special]"
                                           class="checkbox" type="checkbox"
                                           value="1"<?php echo $item['special'] == 1 ? ' checked="checked"' : ''; ?>>
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

    form.find('#sync-btn').on('click', function () {

        $.get($(this).attr('href'), function (ret) {
            if (ret && ret.code === 0) {
                alert(ret.msg);
                window.location.reload();
            }
        });

        return false;
    });
</script>
<?php Kernel\View::part('common.footer') ?>
