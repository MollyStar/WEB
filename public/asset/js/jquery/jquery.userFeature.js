var FP = require('fingerprintjs2');

(function ($) {
    $.fn.userFeature = function () {

        var node = $(this);

        if (node.get(0).nodeName.toUpperCase() == 'INPUT') {
            new FP().get(function (hash, components) {
                var data = {};
                data.hash = hash;
                $.each(components, function (_, item) {
                    data[item.key] = item.value;
                });
                node.val(JSON.stringify(data));
            });
        }

        return node;
    }
})(jQuery);