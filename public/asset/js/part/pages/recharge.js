require('bootstrap-slider');


var bootslideInputs = $('.boot-slide > input');
var currenty_append = $('#currency_append');
var form = $('#form');

bootslideInputs.slider();
bootslideInputs.on('slide', function (e) {
    $(this).val(e.value);
    $(this).parent().next().find('b').text(e.value);
    currency_append_update();
});

function currency_append_update() {
    var sum = get_current_append();
    currenty_append.text(sum > 0 ? ' + ' + sum : '');
}

function get_current_append() {
    var sum = 0;
    bootslideInputs.each(function () {
        sum += parseInt($(this).val()) * parseInt($(this).data('rate'));
    });
    return sum;
}

var pending = false;
form.on('submit', function (e) {
    e.preventDefault();

    if (pending) {
        return;
    }
    pending = true;

    if (get_current_append() == 0) {
        $.topTip('没有要充入的数量', 'warning');
        return;
    }

    var el = $(this);
    $.post(el.attr('action'), el.serializeArray())
        .done(function (ret) {
            if (ret) {
                if (ret.code === 0) {
                    $.topTip(ret.msg, 'success');
                    setTimeout(function () {
                        window.location.reload();
                    }, 1500);
                    return;
                }
            }
            $.topTip(ret.msg, 'warning');
            pending = false;
        })
        .fail(function () {
            $.topTip('网络错误，请稍候再试', 'warning');
            pending = false;
        });
});
