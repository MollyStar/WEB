<?php Kernel\View::part('common.header', ['title' => '套装发放']) ?>
<div class="row m-lr-0">
    <div class="col-sm-12">
        <form id="form" class="form-horizontal" onsubmit="return false;"
              action="/item_set/send_to_account_commonbank">
            <div class="panel-heading">
                <a class="btn btn-info pull-right" href="/item_set">返回</a>
                <h3>套装发放</h3>
            </div>
            <section class="panel">
                <div class="panel-body">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">套装</label>
                        <div class="col-sm-5">
                            <select class="form-control required editable-select" name="name">
                                <?php foreach ($item_set_list as $item): ?>
                                    <option value="<?php echo $item['name']; ?>"<?php echo $item_set->isValid() &&
                                                                                           $item_set->getName() ==
                                                                                           $item['name']
                                        ? ' selected="selected"' : ''; ?>><?php echo $item['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">发给</label>
                        <div class="col-sm-5">
                            <select data-source="/user/ajax_search_account_by_name"
                                    data-source-method="post"
                                    data-source-filter="key" class="form-control required editable-select"
                                    name="guildcard"></select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label"></label>
                        <div class="col-sm-5">
                            <button type="submit" class="btn btn-info btn-lg">发送</button>
                        </div>
                    </div>
                </div>
            </section>
        </form>
    </div>
</div>
<script>
    (function ($) {

        var form = $('#form');

        form.find('.editable-select').editableSelect({
            effects: 'fade',
            additional: true
        });

        form.on('submit', function (e) {
            var form = $(this);
            var data = form.serializeAssoc();
            $.post(form.attr('action'), data).done(function (ret) {
                if (ret) {
                    if (ret.code === 0) {
                        $.topTip(ret.msg, 'success');
                        setTimeout(function () {
                            if (ret.response != null)
                                window.location.href = '?name=' + ret.response;
                            else
                                window.location.reload();
                        }, 1500);
                    } else {
                        $.topTip(ret.msg, 'warning');
                    }
                    return;
                }
                $.topTip('发送中发生错误，请稍后重试', 'warning');
            }).fail(function () {
                $.topTip('发送中发生错误，请稍后重试', 'danger');
            });
        });
    })(jQuery);
</script>
<?php Kernel\View::part('common.footer') ?>
