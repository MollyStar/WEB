| <?php echo join(' | ', $column_head);?> |
|---<?php echo join('|---', array_fill(0, count($column_head), ''));?>|
<?php foreach($columns as $column):?>
| <?php echo $column['Field'];?> | <?php echo $column['Type'];?> | <?php echo $column['Collation'];?> | <?php echo $column['Null'];?> | <?php echo $column['Key'];?> | <?php echo $column['Default'];?> | <?php echo $column['Extra'];?> | <?php echo $column['Comment'];?> |
<?php endforeach;?>