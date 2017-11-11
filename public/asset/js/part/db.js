require('./markdown-render');

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