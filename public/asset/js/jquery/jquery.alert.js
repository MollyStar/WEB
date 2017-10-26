(function ($) {

    $.alert = function alert() {

        var title = '';
        var message = '';

        switch (arguments.length) {
            case 1:
                message = arguments[0];
                break;
            case 2:
                var title = arguments[0];
                var message = arguments[1];
                break;
            default:
        }

        var html = [
            '<div class="ui-alert modal" style="display: none">',
            '    <div class="modal-dialog modal-sm">',
            '    <div class="modal-content">',
            '    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">关闭</span></button>',
            !title ? '' : '    <div class="modal-header"><h5 class="modal-title"><i class="fa fa-exclamation-circle"></i> ' + title + '</h5></div>',
            '    <div class="modal-body small">',
            '    <p>' + message + '</p>',
            '    </div>',
            '    <div class="modal-footer" >',
            '    <button type="button" class="btn btn-primary ok" data-dismiss="modal">关闭</button>',
            '    </div>',
            '    </div>',
            '    </div>',
            '    </div>'
        ].join("");

        var node = $(html);

        node.modal('show');
    }
})(jQuery);