<?php Kernel\View::part('common.header', ['title' => '帐户管理']) ?>
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
                        <th>online?</th>
                        <th>帐户名</th>
                        <th>公共银行</th>
                        <th>注册时间</th>
                        <th>GM</th>
                        <th>BAN</th>
                        <th>LOGGED</th>
                        <th>ACTIVE</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($account_list as $account): ?>
                        <tr>
                            <td>
                                <?php echo $account['guildcard']; ?>
                            </td>
                            <td>
                                <span class="btn btn-xs btn-<?php echo $account['isonline'] ? 'success' : 'danger'; ?>"><i
                                            class="fa fa-circle-o"></i></span>
                            </td>
                            <td>
                                <?php echo $account['username']; ?>
                            </td>
                            <td>
                                <a class="btn btn-primary btn-xs"
                                   href="/account/common_bank?guildcard=<?php echo $account['guildcard']; ?>"><i
                                            class="fa fa-edit"></i></a>
                            </td>
                            <td>
                                <?php echo $account['regtime']; ?>
                            </td>
                            <td>
                                <?php echo $account['isgm']; ?>
                            </td>
                            <td>
                                <?php echo $account['isbanned']; ?>
                            </td>
                            <td>
                                <?php echo $account['islogged']; ?>
                            </td>
                            <td>
                                <?php echo $account['isactive']; ?>
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
