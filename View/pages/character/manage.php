<?php Kernel\View::part('common.header', ['title' => '角色管理']) ?>
<div class="row m-lr-0">
    <div class="col-sm-12">
        <section class="panel">
            <div class="panel-heading">
                <a href="/account" class="btn btn-info pull-right">返回</a>
                <h2>
                    <?php echo $user_info['username']; ?> 的游戏角色
                </h2>
            </div>
        </section>
        <section class="panel">
            <div class="panel-body">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>位置</th>
                        <th>名称</th>
                        <th>颜色</th>
                        <th>职业</th>
                        <th>等级</th>
                        <th>游戏时间(小时)</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($character_list as $character): ?>
                        <tr>
                            <td>
                                <?php echo $character['slot']; ?>
                            </td>
                            <td>
                                <?php echo $character['data']['name']; ?>
                            </td>
                            <td>
                                <?php echo $character['data']['sec'][1]; ?>
                            </td>
                            <td>
                                <?php echo $character['data']['class'][1]; ?>
                            </td>
                            <td>
                                <?php echo $character['data']['level']; ?>
                            </td>
                            <td>
                                <?php echo $character['data']['playTime']; ?>
                            </td>
                            <td>
                                <a class="btn btn-primary btn-xs"
                                   href="/account/character/detail?guildcard=<?php echo $character['guildcard']; ?>&slot=<?php echo $character['slot']; ?>">详情</a>
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
