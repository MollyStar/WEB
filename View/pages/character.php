<?php Kernel\View::part('common.header', ['title' => '怪物']) ?>
<div class="row m-lr-0">
    <div class="col-sm-12">
        <section class="panel">
            <div class="panel-heading">
                <h1>
                    帐户管理
                </h1>
            </div>
        </section>
        <section class="panel">
            <div class="panel-body">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>帐户名</th>
                        <th>公共银行</th>
                        <th>注册时间</th>
                        <th>GM</th>
                        <th>BAN</th>
                        <th>LOGGED</th>
                        <th>ACTIVE</th>
                        <th>角色</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($character_list as $account): ?>
                        <tr>
                            <td rowspan="<?php echo $account['character_num']; ?>">
                                <?php echo $account['guildcard']; ?>
                            </td>
                            <td rowspan="<?php echo $account['character_num']; ?>">
                                <?php echo $account['username']; ?>
                            </td>
                            <td rowspan="<?php echo $account['character_num']; ?>">
                                <a class="btn btn-primary btn-xs"
                                   href="/character/bank?guildcard=<?php echo $account['guildcard']; ?>"><i
                                            class="fa fa-edit"></i></a>
                            </td>
                            <td rowspan="<?php echo $account['character_num']; ?>">
                                <?php echo $account['regtime']; ?>
                            </td>
                            <td rowspan="<?php echo $account['character_num']; ?>">
                                <?php echo $account['isgm']; ?>
                            </td>
                            <td rowspan="<?php echo $account['character_num']; ?>">
                                <?php echo $account['isbanned']; ?>
                            </td>
                            <td rowspan="<?php echo $account['character_num']; ?>">
                                <?php echo $account['islogged']; ?>
                            </td>
                            <td rowspan="<?php echo $account['character_num']; ?>">
                                <?php echo $account['isactive']; ?>
                            </td>
                            <td>
                                <?php $character = array_shift($account['characters']); ?>
                                <?php echo $character['slot']; ?>
                            </td>
                        </tr>
                        <?php foreach ($account['characters'] as $character): ?>
                            <tr>
                                <td>
                                    <?php echo $character['slot']; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>
<?php Kernel\View::part('common.footer') ?>
