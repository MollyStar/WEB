<?php Kernel\View::part('common.header', ['title' => '增益效果']) ?>
<div class="row m-lr-0">
    <div class="col-sm-12">
        <section class="panel">
            <div class="panel-heading">
                <h3>
                    增益效果
                </h3>
            </div>
        </section>
        <?php for ($key = 0; $key <= 51; $key++): ?>
            <?php if ($key == 0) {
                continue;
            } ?>
            <section class="panel">
                <div class="panel-heading">
                    <h4>
                        <b><?php echo $key; ?></b>
                        <?php if (isset($map_stat_boosts[$key])): ?>
                            <?php foreach ($map_stat_boosts[$key] as $effect): ?>
                                <?php echo $map_stat[$effect[0]][1]; ?><?php echo ($r = $effect[1] *
                                                                                        $map_stat[$effect[0]][2]) > 0
                                    ? '+' . $r : $r; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </h4>
                </div>
                <?php if (isset($data[$key])): $group = $data[$key]; ?>
                    <div class="panel-body">
                        <table class="table table-bordered">
                            <tbody>
                            <tr>
                                <?php foreach ($group as $k => $item): ?>
                                <?php if ($k > 0 && $k % 4 === 0): ?>
                            </tr>
                            <tr>
                                <?php endif; ?>
                                <td>
                                    <?php echo $item['hex']; ?><br/>
                                    <?php echo $item['name_zh']; ?>
                                </td>
                                <?php endforeach; ?>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </section>
        <?php endfor; ?>
    </div>
</div>
<?php Kernel\View::part('common.footer') ?>
