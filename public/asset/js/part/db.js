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

$('#save').on('click', function () {
    (function download(filename, text) {
        var pom = document.createElement('a');
        pom.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(text));
        pom.setAttribute('download', filename);

        if (document.createEvent) {
            var event = document.createEvent('MouseEvents');
            event.initEvent('click', true, true);
            pom.dispatchEvent(event);
        }
        else {
            pom.click();
        }
    })('DBStructure.md', $('#source').text())
});

$('#render').on('click', 'table > thead > tr > th', function copyColumnName() {
    if ($(this).index() === 0) {
        var columns = [];
        $(this).parents('table').find('tbody tr').each(function () {
            columns.push($(this).find('td:eq(0)').text());
        });

        var txa = $('<textarea></textarea>').appendTo($(document.body));
        txa.val(JSON.stringify(columns)).select();

        try {
            var successful = document.execCommand('copy');
            var msg = successful ? 'successful' : 'unsuccessful';
            console.log('Copying text command was ' + msg);
        } catch (err) {
            console.log('Oops, unable to copy');
        }

        txa.remove();
    }
});