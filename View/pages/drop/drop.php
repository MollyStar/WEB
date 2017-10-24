<?php Kernel\View::part('common.header', ['title' => '掉落']) ?>
<?php
$config = \Kernel\Config::get('server');
$map_dif = $config['dif'];
$map_sec = $config['sec'];
$map_ep = $config['ep'];
$map_area = $config['area'];
?>
<style>

    .table {
        min-width: 960px;
    }

    .table td, .table th {
        padding: 0;
    }

    .table > thead > tr > th {
        font-size: 12px;
    }

    .table > tbody > tr:nth-child(8n-3) > td:nth-child(1) {
        background-color: #f8f8f8;
    }

    .table > thead > tr > th:nth-child(n+3),
    .table > tbody > tr:nth-child(4n-3) > td:nth-child(n+3),
    .table > tbody > tr:nth-child(4n-2) > td:nth-child(n+2),
    .table > tbody > tr:nth-child(4n-1) > td:nth-child(n+2),
    .table > tbody > tr:nth-child(4n) > td:nth-child(n+2) {
        font-size: 12px;
        width: 8.5%;
    }

    .table > tbody > tr:nth-child(4n-3) > td:nth-child(n+3),
    .table > tbody > tr:nth-child(4n-2) > td:nth-child(n+2),
    .table > tbody > tr:nth-child(4n-1) > td:nth-child(n+2),
    .table > tbody > tr:nth-child(4n) > td:nth-child(n+2) {
        height: 4em;
        padding: 2px 3px;
        color: #505050;
    }

    .table > thead > tr > th:nth-child(2),
    .table > tbody > tr:nth-child(4n-3) > td:nth-child(2),
    .table > tbody > tr:nth-child(4n-2) > td:nth-child(1),
    .table > tbody > tr:nth-child(4n-1) > td:nth-child(1),
    .table > tbody > tr:nth-child(4n) > td:nth-child(1) {
        font-size: 12px;
        width: 30px;
        text-align: center;
        vertical-align: middle;
    }

    .table > tbody > tr > td, .table > thead > tr > th {
        word-break: break-all;
        word-wrap: break-word;
    }

    .box-drop-unit {
        position: relative;
    }

    .box-drop-unit .droped-item {
        display: inline-block;
        width: 100%;
        padding: 2px;
        border: 1px solid rgba(0, 0, 0, .25);
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
        margin-bottom: 2px;
    }

    .box-drop-unit .droped-item > span {
        display: inline-block;
        width: 100%;
        margin-bottom: 2px;
        border-bottom: 1px solid rgba(0, 0, 0, .25);
    }

    .droped-item {
        position: relative;
        padding-top: 14px !important;
    }

    .droped-item > i {
        /*opacity: .7;*/
        position: absolute;
        top: 1px;
        right: 1px;
        color: #333;
        /*background-color: #666;*/
        padding: 1px 4px;
        font-weight: bold;
        font-style: normal;
        -webkit-transform: scale(.8);
        -moz-transform: scale(.8);
        -ms-transform: scale(.8);
        -o-transform: scale(.8);
        transform: scale(.8);
        -webkit-transform-origin: 100% 0;
        -moz-transform-origin: 100% 0;
        -ms-transform-origin: 100% 0;
        -o-transform-origin: 100% 0;
        transform-origin: 100% 0;
        /*-webkit-border-radius: 4px;*/
        /*-moz-border-radius: 4px;*/
        /*border-radius: 4px;*/
    }
</style>
<style>
    <?php foreach ($map_sec as $sk => $sec):?>

    .table > thead > tr > th:nth-child(<?php echo $sk + 3;?>),
    .table > tbody > tr:nth-child(4n-3) > td:nth-child(<?php echo $sk + 3;?>),
    .table > tbody > tr:nth-child(4n-2) > td:nth-child(<?php echo $sk + 2;?>),
    .table > tbody > tr:nth-child(4n-1) > td:nth-child(<?php echo $sk + 2;?>),
    .table > tbody > tr:nth-child(4n) > td:nth-child(<?php echo $sk + 2;?>) {
        background-color: <?php echo $sec[2];?>;
    }

    <?php endforeach;?>
</style>
<?php switch ($manage): ?>
<?php case 1: ?>
        <?php Kernel\View::part('pages.drop.manage', compact('map_area', 'map_dif', 'map_ep', 'map_sec')) ?>
        <?php break; ?>
    <?php case 0: ?>
        <?php Kernel\View::part('pages.drop.public', compact('map_area', 'map_dif', 'map_ep', 'map_sec')) ?>
        <?php break; ?>
    <?php endswitch; ?>
<?php Kernel\View::part('common.footer') ?>
