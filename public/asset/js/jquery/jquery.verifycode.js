/**
 * Created by shinate on 2017/10/26.
 */
(function ($) {

    var verify_code_url = '/verifiation.jpg';

    $.fn.verifycode = function () {

        var node = $(this);

        function reflush() {
            node.empty().append($('<img src="' + verify_code_url + '?r=' + (+new Date) + '">'));
        }

        node.on('click', 'img', reflush);

        node.on('reflush', reflush);

        return node;
    }
})(jQuery);