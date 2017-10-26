(function ($) {

    $.confirm = function confirm(message, reslove, reject) {

        var resloveRes = {
            button: '确认', callback: null, context: null
        };
        var rejectRes = {
            button: '取消', callback: null, context: null
        };

        if ($.type(reslove) == 'object') {
            reslove = $.extend({}, resloveRes, reslove || {});
        } else if ($.type(reslove) == 'function') {
            reslove = $.extend({}, resloveRes, {callback: reslove});
        } else {
            reslove = resloveRes;
        }

        if ($.type(reject) == 'object') {
            reject = $.extend({}, rejectRes, reject || {});
        } else if ($.type(reject) == 'function') {
            reject = $.extend({}, rejectRes, {callback: reject});
        } else {
            reject = rejectRes;
        }

        var html = [
            '<div class="ui-confirm modal" style="display: none">',
            '    <div class="modal-dialog modal-sm">',
            '    <div class="modal-content">',
            '    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">关闭</span></button>',
            '    <div class="modal-body small">',
            '    <p>' + message + '</p>',
            '    </div>',
            '    <div class="modal-footer" >',
            '    <button type="button" class="btn btn-primary ok"' + (reslove.callback ? '' : 'data-dismiss="modal"') + '>' + reslove.button + '</button>',
            '    <button type="button" class="btn btn-secondary cancel"' + (reject.callback ? '' : 'data-dismiss="modal"') + '>' + reject.button + '</button>',
            '    </div>',
            '    </div>',
            '    </div>',
            '    </div>'
        ].join("");

        var node = $(html);

        reslove.callback && node.on('click', '.ok', function () {
            'call' in reslove.callback && reslove.callback.call(reslove.context, node);
        });

        reject.callback && node.on('click', '.cancel', function () {
            'call' in reject.callback && reject.callback.call(reject.context, node);
        });

        node.modal('show');

        return node;
    }
})(jQuery);