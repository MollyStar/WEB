<?php Kernel\View::part('common.header', ['title' => '提示']) ?>
    <style>
        .activity .activity-desk {
            margin-left: 96px;
        }
    </style>

    <script>
        var btn = $('.profile-activity .btn');
        if (btn.attr('wait') > -1) {
            var delay = parseInt(btn.attr('wait'));

            btn.html('[<b>' + delay + '</b>] 秒后自动' + btn.html());

            var delayEl = btn.find('b');

            var lsn = setInterval(function () {
                if (delay <= 1) {
                    window.location.href = btn.attr('href');
                    clearInterval(lsn);
                    return;
                }
                delay -= 1;
                delayEl.text(delay);
            }, 1000);
        }
    </script>
    <div class="container message">
        <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <section class="panel-body">
                        <p>
                            <?php if ($code): ?>
                                <span>[<?php echo $code; ?>]</span>
                            <?php endif; ?>
                            <?php echo $message; ?>
                        </p>
                        <a href="<?php echo $url; ?>"<?php if ($wait): ?> wait="<?php echo $wait; ?>"<?php endif; ?>
                           class="text-warning">返回</a>
                    </section>
                </section>
            </div>
        </div>
    </div>
<?php Kernel\View::part('common.footer') ?>