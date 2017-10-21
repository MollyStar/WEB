'use strict';

var ws = require('webpack-stream');
var named = require('vinyl-named');
var path = require('path');

module.exports = function (gulp, PLUGIN, CONF) {

    gulp.task('js', function () {
        return gulp.src([
            CONF.src + '/js/part/**/*.js',
            '!' + CONF.src + '/js/part/**/*.min.js'
        ])
            .pipe(PLUGIN.plumber())
            .pipe(named(function (file) {
                var p = path.relative(file.base, file.path);
                return p.slice(0, -path.extname(p).length);
            }))
            .pipe(ws(CONF.webpack(0)))
            .pipe(PLUGIN.uglify())
            .pipe(PLUGIN.rename({
                suffix: '.min'
            }))
            .pipe(gulp.dest(CONF.src + '/js/part'));
    });
};