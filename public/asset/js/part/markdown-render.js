var marked = require('../lib/marked/lib/marked');

$('#source').text($('#source').text().replace('_', '\\_'));

var renderer = new marked.Renderer();

renderer.table = function (header, body) {

    var h = $(header);
    var b = $(body);

    if (h.eq(0).children().length != b.eq(0).children().length) {
        body = '<tr><td colspan="8"></td></tr>';
    }

    return '' +
        '<div class="table-responsive">\n' +
        '<table class="table table-bordered">\n' +
        '<thead>\n' +
        header +
        '</thead>\n' +
        '<tbody>\n' +
        body +
        '</tbody>\n' +
        '</table>\n' +
        '</div>\n';
};

$('#render').html(marked($('#source').text(), {renderer: renderer}));