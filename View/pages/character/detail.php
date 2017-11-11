<?php Kernel\View::part('common.header', ['title' => '角色管理']) ?>
<div class="row m-lr-0">
    <div class="col-sm-12">
        <section class="panel">
            <div class="panel-heading">
                <a href="/account/character?guildcard=<?php echo $info['guildCard']; ?>"
                   class="btn btn-info pull-right">返回</a>
                <h2>
                    <?php echo $info['name']; ?>
                </h2>
            </div>
        </section>
        <section class="panel">
            <div class="panel-heading">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>银行使用 <?php echo $bank->used(); ?>/200</th>
                        <th style="width: 200px;">
                            美塞塔 <?php echo $bank->getMST(); ?>
                        </th>
                    </tr>
                    </thead>
                </table>
            </div>
            <div class="panel-body">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>物品编码/名称</th>
                        <th>详情</th>
                        <th>数量</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($bank->items(true) as $key => $item): ?>
                        <tr>
                            <td>
                                <?php echo $item->hex; ?><br/>
                                <?php echo $map_items[$item->hex]['name_zh']; ?>
                            </td>
                            <td>
                                <span class="label label-default"><?php echo join(',', str_split($item->code, 8)); ?></span><br/>
                                <?php \Kernel\View::part('part.item.detail', ['detail' => $item->detail]); ?>
                            </td>
                            <td>
                                <?php echo $item->num; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
        <section class="panel">
            <div class="panel-heading">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>背包使用 <?php echo $inventory->used(); ?>/30</th>
                        <th style="width: 200px;">
                            美塞塔 <?php echo $inventory->getMST(); ?>
                        </th>
                    </tr>
                    </thead>
                </table>
            </div>
            <div class="panel-body">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>物品编码/名称</th>
                        <th>详情</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($inventory->items(true) as $key => $item): ?>
                        <tr>
                            <td>
                                <?php echo $item->hex; ?><br/>
                                <?php echo $map_items[$item->hex]['name_zh']; ?>
                            </td>
                            <td>
                                <span class="label label-default"><?php echo join(',', str_split($item->code, 8)); ?></span><br/>
                                <?php \Kernel\View::part('part.item.detail', ['detail' => $item->detail]); ?>
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
