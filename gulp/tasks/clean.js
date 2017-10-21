'use strict';

module.exports = function (gulp, PLUGIN, CONF) {

    var clean = PLUGIN.clean;

    gulp.task('clean-dev', function () {
        gulp.src([
            CONF.build + '/*',
            '!' + CONF.build + '/.gitignore'
        ], {
            read: false
        })
            .pipe(clean());
    });

    gulp.task('clean', function () {
        gulp.src([
            CONF.release + '/*',
        ], {
            read: false
        })
            .pipe(clean());
    });
};