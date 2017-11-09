<?php Kernel\View::part('common.topic.header', ['title' => 'Topic']) ?>
    <style>

        h2 {
            color: #999999;
            padding-top: 36px;
            padding-bottom: 5px;
            border-bottom: 2px dotted #cccccc;
            margin-bottom: 20px;
        }

        .panel {
            cursor: pointer;
            display: block;
            -webkit-box-shadow: 0 0 12px -5px black;
            -moz-box-shadow: 0 0 12px -5px black;
            box-shadow: 0 0 12px -5px black;
            -webkit-transition: box-shadow .2s;
            -moz-transition: box-shadow .2s;
            -ms-transition: box-shadow .2s;
            -o-transition: box-shadow .2s;
            transition: box-shadow .2s;
        }

        .panel:hover {
            text-decoration: none;
            -webkit-box-shadow: 0 0 7px #e74c3c;
            -moz-box-shadow: 0 0 7px #e74c3c;
            box-shadow: 0 0 7px #e74c3c;
        }

        .panel-body {
            position: relative;
        }

        #newest_package .panel-body .tips {
            background-color: rgba(255, 255, 255, .9);
            color: #e74c3c;
            font-size: 1.6em;
            line-height: 2em;
            width: 100%;
            text-align: center;
        }

        #newest_package .panel-body {
            padding: 160px 5px 5px;
            background: transparent url("/asset/image/topic/newest_package.jpg") no-repeat 50% 50%;
            background-size: auto 100%;
        }
    </style>
    <div class="container">
        <h2>玩家服务</h2>
        <div class="row">
            <div class="col-sm-4">
                <a id="newest_package" class="panel" href="/topic/newest_package">
                    <div class="panel-body">
                        <div class="tips">新手礼包</div>
                    </div>
                </a>
            </div>
            <div class="col-sm-4">
                <a id="newest_package" class="panel">
                    <div class="panel-body">
                        <div class="tips">武器强化</div>
                    </div>
                </a>
            </div>
            <div class="col-sm-4">
                <a id="newest_package" class="panel">
                    <div class="panel-body">
                        <div class="tips">道具换取</div>
                    </div>
                </a>
            </div>
            <div class="col-sm-4">
                <a id="newest_package" class="panel">
                    <div class="panel-body">
                        <div class="tips">马古养成</div>
                    </div>
                </a>
            </div>
        </div>
        <h2>高手进阶</h2>
        <div class="row">
            <?php if (\Common\UserHelper::isUserLogginedGame()): ?>
                <div class="col-sm-4">
                    <a id="newest_package" class="panel">
                        <div class="panel-body">
                            <div class="tips">战士进阶套装</div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-4">
                    <a id="newest_package" class="panel" href="/topic/newest_package">
                        <div class="panel-body">
                            <div class="tips">枪手进阶套装</div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-4">
                    <a id="newest_package" class="panel" href="/topic/newest_package">
                        <div class="panel-body">
                            <div class="tips">法师进阶套装</div>
                        </div>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php Kernel\View::part('common.topic.footer') ?>