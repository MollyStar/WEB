<?php Kernel\View::part('common.header', ['title' => '数据库结构']) ?>

<style>
    @import "/asset/css/part/db-structure.min.css";
</style>

<div class="row m-lr-0">
    <div class="col-sm-12">
        <section class="panel">
            <div class="panel-body">
                <button id="save" class="btn btn-warning">保存</button>
            </div>
        </section>
        <section id="indexes" class="panel">
            <div class="panel-body">
                <?php foreach($indexes as $Index => $group):?>
                <label class="btn btn-danger btn-sm"><b><?php echo $Index;?></b></label>
                <?php foreach($group as $item):?>
                <a href="#<?php echo $item['Table_name'];?>" class="btn btn-default btn-sm"><?php echo $item['Table_name'];?></a>
                <?php endforeach;?>
                <?php endforeach;?>
            </div>
        </section>
        <section class="panel">
            <div class="panel-body">
                <div id="render"></div>
            </div>
        </section>
    </div>
</div>
<pre style="display: none" id="source"><?php foreach ($DBStructure as $table): ?>
## <?php echo $table['Name']; ?>


- 引擎: <?php echo $table['Engine']; ?>

- 整理: <?php echo $table['Collation']; ?>

- 备注: <?php echo $table['Comment']; ?>


<?php \Kernel\View::part('pages.tools.part.columns', ['column_head' => $column_head, 'columns' => $table['columns']]);?>

<?php \Kernel\View::part('pages.tools.part.keys', ['key_head' => $key_head, 'keys' => $table['keys']]);?>

<?php endforeach; ?></pre>

<script src="/asset/js/part/db.min.js"></script>
<?php Kernel\View::part('common.footer') ?>