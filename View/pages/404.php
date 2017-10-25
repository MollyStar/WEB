<?php Kernel\View::part('common.header', ['title' => 'Dashboard']) ?>
    <style>
        #sign {
            height: 500px;
            width: 500px;
            position: absolute;
            top: 50%;
            left: 50%;
            -webkit-transform: translate(-50%, -50%);
            -moz-transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            -o-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
            background: transparent url("/asset/image/404.png") no-repeat 50% 50%;
        }
    </style>
    <div id="sign"></div>
<?php Kernel\View::part('common.footer') ?>