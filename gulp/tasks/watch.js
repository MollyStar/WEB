'use strict';

module.exports = function (gulp, PLUGIN, CONF) {
    gulp.task('watch', ['build'], function () {
        gulp.watch([
            CONF.src + '/js/**/*.js',
            '!' + CONF.src + '/js/**/*.min.js'
        ], ['js']);

        gulp.watch([
            CONF.src + '/css/**/*.less',
        ], ['css']);
    });
};