<?php Kernel\View::part('common.header', ['title' => 'Dashboard']) ?>
    <style>
        .server_status {
            display: inline-block;
            height: 100px;
        }
    </style>
    <div id="server_status" class="row m-lr-0" style="display: none"></div>
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
<?php Kernel\View::part('common.footer') ?>