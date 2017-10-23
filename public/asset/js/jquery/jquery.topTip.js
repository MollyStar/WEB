(function ($) {

    $.topTip = function topTip(message, type) {

        if (message == null) {
            return;
        }

        var layer = $('<div class="ui-top-tip alert" role="alert"></div>');
        if (type == null) {
            type = 'info';
        }
        layer.addClass('alert-' + type);
        layer.html(message);
        layer.appendTo($(document.body));

        layer.animate({
            'margin-bottom': -(layer.outerHeight() + 16)
        }, 200, function () {
            setTimeout(function () {
                layer.fadeOut(1000, function () {
                    layer.remove();
                });
            }, 1600);
        });

        layer.addClass('show');
    };

})(jQuery);