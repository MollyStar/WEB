'use strict';

module.exports = function (gulp, PLUGIN, CONF) {

    gulp.task('images', function () {
        return gulp.src([
            CONF.src + '/images/**/*',
        ])
            .pipe(PLUGIN.plumber())
            .pipe(gulp.dest(CONF.release + '/images'));
    });

    gulp.task('images-dev', function () {
        return gulp.src([
            CONF.src + '/images/**/*',
        ])
            .pipe(PLUGIN.plumber())
            .pipe(gulp.dest(CONF.build + '/images'));
    });
};