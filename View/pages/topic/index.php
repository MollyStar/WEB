<?php Kernel\View::part('common.header', ['title' => 'Topic']) ?>
    <div class="topic-banner">
        <div class="container">
            <div class="col-sm-12">
                <div class="row">
                    <section class="panel panel-topic panel-characters">
                        <section class="panel-heading"></section>
                        <section class="panel-body">
                            <div class="row">
                                <?php for ($slot = 0; $slot < 4; $slot++): ?>
                                    <div class="col-sm-3">
                                        <section class="panel panel-topic panel-character">
                                            <?php if ($character = $characters[$slot] ?? null): ?>
                                                <section
                                                        class="panel-body character-sec-<?php echo $character['data']['class'][0]; ?>">
                                                    <p><?php echo $character['data']['name']; ?></p>
                                                    <p>Lv.<?php echo $character['data']['level']; ?></p>
                                                    <p><?php echo $character['data']['sec'][1]; ?></p>
                                                    <p><?php echo $character['data']['class'][1]; ?></p>
                                                    <p>在线 <?php echo $character['data']['playTime']; ?> 小时</p>
                                                </section>
                                            <?php else: ?>
                                                <section class="panel-body character-sec-none">
                                                </section>
                                            <?php endif; ?>
                                        </section>
                                    </div>
                                <?php endfor; ?>
                            </div>
                        </section>
                        <section class="panel-footer"></section>
                    </section>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-sm-7">
                <section class="panel panel-topic">
                    <section class="panel-body">
                        <p class="text-info">通用货币</p>
                        <?php if ($currency): ?>
                            <p class="text-info">本帐户有效货币(公共银行)</p>
                            <table class="table">
                                <tbody>
                                <?php foreach ($currency as $item): ?>
                                    <tr>
                                        <td>
                                            <?php echo $item['name']; ?>
                                        </td>
                                        <td>
                                            <?php echo $item['num']; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </section>
                </section>
            </div>
            <div class="col-sm-5">
                <a class="topic-btn" href="/topic/newest_package">
                    <div class="title">
                        <b>新手礼包</b>
                        <i>领取新手礼包，开始你的梦幻旅程</i>
                    </div>
                    <img src="/asset/image/topic/hg-btn-blue.png">
                </a>
                <a class="topic-btn" href="#">
                    <div class="title">
                        <b>武器强化</b>
                        <i>打造更强的武器，增加属性和命中</i>
                    </div>
                    <img src="/asset/image/topic/hg-btn-blue.png">
                </a>
                <a class="topic-btn" href="#">
                    <div class="title">
                        <b>道具交换</b>
                        <i>换取稀有装备、物品</i>
                    </div>
                    <img src="/asset/image/topic/hg-btn-blue.png">
                </a>
                <a class="topic-btn" href="#">
                    <div class="title">
                        <b>玛古养成</b>
                        <i>省去喂养的烦恼，快速养成属于你玛古</i>
                    </div>
                    <img src="/asset/image/topic/hg-btn-blue.png">
                </a>
                <a class="topic-btn" href="#">
                    <div class="title">
                        <b>拉比的探险</b>
                        <i>可爱的拉比们有可能带回意想不到的东西</i>
                    </div>
                    <img src="/asset/image/topic/hg-btn-blue.png">
                </a>
                <?php if (\Common\UserHelper::isUserLogginedGame()): ?>
                <a class="topic-btn" href="#">
                    <div class="title">
                        <b>战士进阶套装</b>
                    </div>
                    <img src="/asset/image/topic/hg-btn-green.png">
                </a>
                <a class="topic-btn" href="#">
                    <div class="title">
                        <b>枪手进阶套装</b>
                    </div>
                    <img src="/asset/image/topic/hg-btn-green.png">
                </a>
                <a class="topic-btn" href="#">
                    <div class="title">
                        <b>法师进阶套装</b>
                    </div>
                    <img src="/asset/image/topic/hg-btn-green.png">
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>

<?php Kernel\View::part('common.footer') ?>