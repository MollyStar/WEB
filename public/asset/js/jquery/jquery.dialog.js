(function ($) {

    $.dialog = function dialog(content, options) {

        var buttonRes = {
            className: 'btn btn-secondary',
            button: 'BUTTON',
            callback: null,
            context: null
        };

        options = $.extend({
            id: '',
            className: '',
            closeButton: true,
            buttons: []
        }, options || {});

        var foot = [];

        if (options.buttons.length) {
            foot.push('    <div class="modal-footer">');
            options.buttons.forEach(function (item, i) {
                options.buttons[i] = item = $.extend({}, buttonRes, item || {});
                foot.push('    <button type="button" class="' + item.className + ' btn-' + i + '"' + (item.callback ? '' : 'data-dismiss="modal"') + '>' + item.button + '</button>');
            });

            foot.push('    </div>');
        }

        var html = [
            '<div style="display: none" class="ui-dialog modal' + (options.className ? ' ' + options.className : '') + '"' + (options.id ? ' id="' + options.id + '"' : '') + '>',
            '    <div class="modal-dialog">',
            '    <div class="modal-content">',
            !options.closeButton ? '' : '    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">关闭</span></button>',
            '    <div class="modal-body">',
            content,
            '    </div>',
            foot.join(''),
            '    </div>',
            '    </div>',
            '    </div>'
        ].join('');

        var node = $(html);

        if (options.buttons.length) {
            options.buttons.forEach(function (item, i) {
                item.callback && node.on('click', '.btn-' + i, function () {
                    'call' in item.callback && item.callback.call(item.context, node);
                });
            });
        }

        node.modal('show');

        return node;
    }
})(jQuery);