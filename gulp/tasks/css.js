'use strict';

module.exports = function (gulp, PLUGIN, CONF) {

    gulp.task('css', function () {
        return gulp.src([
            CONF.src + '/css/part/**/*.less',
        ])
            .pipe(PLUGIN.plumber())
            .pipe(PLUGIN.less())
            .pipe(PLUGIN.cssmin())
            .pipe(PLUGIN.rename({
                suffix: '.min'
            }))
            .pipe(gulp.dest(CONF.release + '/css/part'));
    });
};