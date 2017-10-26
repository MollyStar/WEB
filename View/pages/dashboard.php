<?php Kernel\View::part('common.header', ['title' => 'Dashboard']) ?>
    <style>
        .server_status {
            display: inline-block;
            height: 100px;
        }
    </style>
    <div class="row m-lr-0">
        <div class="col-sm-12">
            <section class="panel">
                <div class="panel-header">
                    <h3 class="panel-title">服务器状态</h3>
                </div>
                <div class="panel-body row">
                    <div id="server_status" class="row m-lr-0" style="display: none"></div>
                </div>
            </section>
            <script>
                $.get('/tools/server/status').done(function (ret) {
                    var wrap = $('#server_status');
                    if (ret) {
                        if (ret.code === 0) {
                            wrap.show();
                            $.each(ret.response, function (_, item) {
                                $('<div class="col-sm-3">' +
                                    '<div class="panel">' +
                                    '<div class="panel-body' + (item[2] ? ' label-success' : ' label-danger') + '">' +
                                    item[1] +
                                    '</div>' +
                                    '</div>' +
                                    '</div>').appendTo(wrap);
                            });
                        }
                    }
                });
            </script>
            <section class="panel">
                <div class="panel-body">
                    <a href="/drop/export" target="_blank" class="btn btn-info">下载掉落配置包</a>
                </div>
            </section>
        </div>
    </div>
<?php Kernel\View::part('common.footer') ?>