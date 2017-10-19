<?php
$config = \Kernel\Config::get('server');
$map_dif = $config['dif'];
$map_sec = $config['sec'];
$map_ep = $config['ep'];
$map_area = $config['area'];
?>
<style>
    .table > thead > tr > th {
        font-size: 12px;
    }

    .table > thead > tr > th:nth-child(n+3),
    .table > tbody > tr:nth-child(4n-3) > td:nth-child(n+3),
    .table > tbody > tr:nth-child(4n-2) > td:nth-child(n+2),
    .table > tbody > tr:nth-child(4n-1) > td:nth-child(n+2),
    .table > tbody > tr:nth-child(4n) > td:nth-child(n+2) {
        font-size: 12px;
        width: 8.5%;
    }

    .table > tbody > tr:nth-child(4n-3) > td:nth-child(2),
    .table > tbody > tr:nth-child(4n-2) > td:nth-child(1),
    .table > tbody > tr:nth-child(4n-1) > td:nth-child(1),
    .table > tbody > tr:nth-child(4n) > td:nth-child(1) {
        font-size: 12px;
        width: 30px;
    }

    .table > tbody > tr > td, .table > thead > tr > th {
        word-break: break-all;
        word-wrap: break-word;
    }
</style>
<div class="row m-sm-0">
    <div class="col-sm-12">
        <section class="panel">
            <div class="panel-heading">
                <h1>
                    掉落
                </h1>
            </div>
            <div class="panel-body"></div>
        </section>
    </div>
    <?php foreach ($map_mob_drop as $ek => $ep): ?>
        <div class="col-sm-12">
            <section class="panel">
                <div class="panel-heading">
                    <h3><?php echo $map_ep[$ek][2]; ?></h3>
                </div>
                <div class="panel-body"></div>
            </section>
            <?php foreach ($ep as $ak => $area): ?>
                <section class="panel">
                    <div class="panel-heading">
                        <h4><?php echo $map_area[$ek][$ak][0][1]; ?></h4>
                    </div>
                    <div class="panel-body">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>名称</th>
                                <th>难度</th>
                                <?php foreach ($map_sec as $sk => $sec): ?>
                                    <th>
                                        <?php echo $sec[1]; ?><br/>
                                        <?php echo $sec[0]; ?>
                                    </th>
                                <?php endforeach; ?>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($area as $mob): ?>
                                <?php foreach ($map_dif as $dk => $dif): ?>
                                    <tr>
                                        <?php if (current($map_dif) === $dif): ?>
                                            <td rowspan="4">
                                                <?php echo $mob['name_zh']; ?><br/>
                                                <?php echo $mob['name']; ?>
                                            </td>
                                        <?php endif; ?>
                                        <td>
                                            <?php echo $dif[2]; ?>
                                        </td>
                                        <?php foreach ($map_sec as $sk => $sec): ?>
                                            <td>
                                                <?php echo $mob_drop[$dk][$ek][$ak][$mob['name']][$sk]['item_name_zh']; ?>
                                            </td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
</div>