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
                        <th>MST</th>
                        <th>物品/数量</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($data as $row): ?>
                        <tr>
                            <td>
                                <a href="/item_set/detail?name=<?php echo $row['name']; ?>"><?php echo $row['name']; ?></a>
                            </td>
                            <td style="min-width: 200px;">
                                <?php echo $row['description']; ?>
                            </td>
                            <td>
                                <?php echo $row['mst']; ?>
                            </td>
                            <td>
                                <?php if ($row['items_count']): ?>
                                    <?php foreach ($row['items'] as $item): ?>
                                        <div class="item-group item-group-inline m-b-5">
                                            <span class="form-control input-sm"><?php echo $map_items[$item['hex']]['name_zh']; ?></span><span
                                                    class="form-control input-sm"><sub style="margin-bottom: 5px">&times;</sub><?php echo $item['num']; ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a class="btn btn-sm btn-success"
                                   href="/item_set/send?name=<?php echo $row['name']; ?>">发放</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>
<?php Kernel\View::part('common.footer') ?>
