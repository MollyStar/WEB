<?php Kernel\View::part('common.header', ['title' => 'Molly Star 莫莉之星']) ?>
    <style>
        body {
            background: #000000 url("/asset/image/welcome.jpg") no-repeat fixed 50% 50%;
            background-size: cover;
        }

        a {
            display: inline-block;
            padding: .5em 1em;
            color: #c5ecff;
        }

        a .fa {
            -webkit-transform: scale(.8, .8);
            -moz-transform: scale(.8, .8);
            -ms-transform: scale(.8, .8);
            -o-transform: scale(.8, .8);
            transform: scale(.8, .8);
        }

        a:hover {
            color: #fbc7a9;
            text-decoration: none;
        }
    </style>
    <div class="container">
        <div style="padding-top: 60vh;">
            <p style=" text-align: center">
                <a href="/register"><i class="fa fa-chevron-right"></i> 注册 <i class="fa fa-chevron-left"></i></a>
                <a href="/login"><i class="fa fa-chevron-right"></i> 登录 <i class="fa fa-chevron-left"></i></a>
            </p>
            <p style="text-align: center">
                <a href="/notice"><i class="fa fa-chevron-right"></i> 服务器公告 <i class="fa fa-chevron-left"></i></a>
                <a href="/item_drop"><i class="fa fa-chevron-right"></i> 掉落表 <i class="fa fa-chevron-left"></i></a>
            </p>
        </div>
    </div>
<?php Kernel\View::part('common.footer') ?>