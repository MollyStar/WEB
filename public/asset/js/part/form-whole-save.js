/**
 * Created by shinate on 2017/10/20.
 */

(function ($) {

    $.fn.wholeSave = function (callback) {
        var form = $(this);
        var submit_lock = false;

        if (callback == null || !$.isFunction(callback)) {
            callback = function (ret) {
                if (ret) {
                    if (ret.code === 0) {
                        $.topTip(ret.msg, 'success');
                        setTimeout(function () {
                            window.location.reload();
                        }, 1500);
                    } else {
                        $.topTip(ret.msg, 'warning');
                    }
                    return;
                }
                $.topTip('保存中发生错误，请稍后重试', 'danger');
            }
        }

        form.on('submit', function (e) {
            e.preventDefault();
            if (submit_lock) return;
            submit_lock = true;
            var data = [];
            form.find('tr.changed').each(function () {
                data = data.concat($(this).find('[name]').serializeArray());
            });
            $.post(form.attr('action'), data)
                .done(callback)
                .fail(function () {
                    $.topTip('保存中发生错误，请稍后重试');
                })
                .always(function () {
                    submit_lock = false;
                });
            return false;
        });

        form.on('change', 'input,select', function () {
            $(this).parents('tr').addClass('changed');
            $(this).parents('tr').find('input.sign-changed').val(1);
        });

        return form;
    };
})(jQuery);