require('../jquery/jquery.serializeAssoc');

require('../jquery/jquery.topTip');
require('../jquery/jquery.dialog');
require('../jquery/jquery.alert');
require('../jquery/jquery.editableSelect');

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