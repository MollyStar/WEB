<div class="row">
    <?php
    $map_dif = \Kernel\Config::get('server.dif');
    $sec_map = \Kernel\Config::get('server.sec');
    ?>
    <form id="form" class="form-horizontal" onsubmit="return false;" action="/mob/update">
        <div class="col-sm-12">
            <section class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        掉落
                    </h3>
                </div>
                <div class="panel-body">
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
                        <?php foreach ($map_mob_drop as $ek => $ep): ?>
                            <?php foreach ($ep as $ak => $area): ?>
                                <?php foreach ($area as $mob): ?>
                                    <?php foreach ($map_dif as $dk => $dif): ?>
                                        <tr>
                                            <?php if (current($map_dif) === $dif): ?>
                                                <td rowspan="4">
                                                    <?php echo $mob['name_zh']; ?>
                                                </td>
                                            <?php endif; ?>
                                            <td>
                                                <?php echo $dif[2]; ?>
                                            </td>
                                            <?php foreach ($sec_map as $sk => $sec): ?>
                                                <td>
                                                    <?php echo $mob_drop[$dk][$ek][$ak][$mob['name']][$sk]['item']; ?>
                                                </td>
                                            <?php endforeach; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </form>
</div>