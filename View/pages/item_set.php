<?php Kernel\View::part('common.header', ['title' => '套装管理']) ?>
<div class="row m-lr-0">
    <div class="col-sm-12">
        <section class="panel">
            <div class="panel-heading">
                <a href="/item_set/detail" class="btn btn-info pull-right">添加</a>
                <h3>
                    套装管理
                </h3>
            </div>
        </section>
        <section class="panel">
            <div class="panel-body">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>名称</th>
                        <th>备注</th>
                        <th>物品</th>
                        <th>数量</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($data as $row): ?>
                        <?php if ($row['items_count']): ?>
                            <?php $item = array_shift($row['items']); ?>
                            <tr>
                                <td rowspan="<?php echo $row['items_count']; ?>">
                                    <?php echo $row['name']; ?>
                                </td>
                                <td rowspan="<?php echo $row['items_count']; ?>">
                                    <?php echo $row['description']; ?>
                                </td>
                                <td>
                                    <?php echo $item['name_zh']; ?>
                                </td>
                                <td>
                                    <?php echo $item['num']; ?>
                                </td>
                            </tr>
                            <?php foreach ($row['items'] as $item): ?>
                                <td>
                                    <?php echo $item['name_zh']; ?>
                                </td>
                                <td>
                                    <?php echo $item['num']; ?>
                                </td>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td>
                                    <?php echo $row['name']; ?>
                                </td>
                                <td>
                                    <?php echo $row['description']; ?>
                                </td>
                                <td></td>
                                <td></td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>
<?php Kernel\View::part('common.footer') ?>
