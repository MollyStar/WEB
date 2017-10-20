<div class="row m-sm-0">
    <div class="col-sm-12">
        <section class="panel">
            <div class="panel-heading">
                <h1>
                    掉落管理
                </h1>
            </div>
            <div class="panel-body"></div>
        </section>
    </div>
    <?php foreach ($map_ep as $ek => $ep): ?>
        <div class="col-sm-12">
            <section class="panel">
                <div class="panel-heading">
                    <h3><?php echo $ep[2]; ?></h3>
                </div>
                <div class="panel-body"></div>
            </section>
            <?php foreach ($map_area[$ek] as $ak => $area): ?>
                <section class="panel">
                    <div class="panel-heading">
                        <h4><?php echo $area[0][1]; ?></h4>
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
                            <?php if ($area_mob = $map_mob_drop[$ek][$ak] ?? null): ?>
                                <?php foreach ($area_mob as $mob): ?>
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
                                                <?php $item = $mob_drop[$dk][$ek][$ak][$mob['name']][$sk]; ?>
                                                <td class="droped-item" data-id="<?php echo $item['hash']; ?>">
                                                    <i><?php echo $item['rate_p']; ?></i>
                                                    <?php echo $item['item_name_zh']; ?><br/>
                                                    <?php echo $item['item_name']; ?>
                                                </td>
                                            <?php endforeach; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <?php if ($area_box = $map_box_drop[$ek][$ak] ?? null): ?>
                                <?php foreach ($area_box as $box): ?>
                                    <?php foreach ($map_dif as $dk => $dif): ?>
                                        <tr>
                                            <?php if (current($map_dif) === $dif): ?>
                                                <td rowspan="4">
                                                    <?php echo $box['name_zh']; ?><br/>
                                                    <?php echo $box['name']; ?>
                                                </td>
                                            <?php endif; ?>
                                            <td>
                                                <?php echo $dif[2]; ?>
                                            </td>
                                            <?php foreach ($map_sec as $sk => $sec): ?>
                                                <?php $items = $box_drop[$dk][$ek][$ak][$box['name']][$sk] ?? []; ?>
                                                <td>
                                                    <?php foreach ($items as $item): ?>
                                                        <fieldset class="droped-item">
                                                            <i><?php echo $item['rate_p']; ?></i>
                                                            <?php echo $item['item_name_zh']; ?><br/>
                                                            <?php echo $item['item_name']; ?>
                                                        </fieldset>
                                                    <?php endforeach; ?>
                                                </td>
                                            <?php endforeach; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
</div>