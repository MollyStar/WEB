require('../jquery/jquery.serializeAssoc');

require('../jquery/jquery.topTip');
require('../jquery/jquery.dialog');
require('../jquery/jquery.alert');
require('../jquery/jquery.confirm');
require('../jquery/jquery.editableSelect');

$(document).ready(function () {
    $(window).scroll(function () {
        var top = $(document).scrollTop();
        $('.splash').css({
            'background-position': '0px -' + (top / 3).toFixed(2) + 'px'
        });
        if (top > 50)
            $('#home > .navbar').removeClass('navbar-transparent');
        else
            $('#home > .navbar').addClass('navbar-transparent');
    });

    $('.wide-table-fixed-btns')
        .append($('<i class="wide-table-fixed-btns-arrow"></i>'))
        .on('click', '.wide-table-fixed-btns-arrow', function (e) {
            e.preventDefault();
            $(this).parent().toggleClass('actived');
        });
});