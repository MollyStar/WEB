| <?php echo join(' | ', $key_head);?> |
|---<?php echo join('|---', array_fill(0, count($key_head), ''));?>|
<?php foreach($keys as $key):?>
| <?php echo $key['Key_name'];?> | <?php echo $key['Index_type'];?> | <?php echo $key['Non_unique'];?> |  | <?php echo $key['Column_name'];?> | <?php echo $key['Collation'];?> | <?php echo $key['Null'];?> | <?php echo $key['Comment'];?> |
<?php endforeach;?>