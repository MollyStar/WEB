<?php Kernel\View::part('common.header', [
    'title' => '公告',
    'style' => ['/asset/css/part/topic', '/asset/css/part/md-table'],
]) ?>
    <div class="container">
        <div class="row m-lr-0">
            <div class="col-sm-12">
                <section class="panel">
                    <div class="panel-body">
                        <div id="render"></div>
                    </div>
                </section>
            </div>
        </div>
    </div>
    <pre style="display: none" id="source"><?php echo $item_changes; ?></pre>
    <script src="/asset/js/part/markdown-render.min.js"></script>
<?php Kernel\View::part('common.footer') ?>