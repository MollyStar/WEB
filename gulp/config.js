'use strict';

// var conf = require('json-config-reader').read('package.json');

var fs = require('fs');

var config = {
    root: './',
    config: './config',
    src: './public/asset',
    build: './src/__BUILD__',
    release: './public/asset'
};

config.webpack = function (dev) {
    var c = {
        output: {
            filename: '[name].js'
        },
        resolve: {
            alias: {
                'jquery': fs.realpathSync(config.src + '/js/jquery/jquery-bridge.js')
            }
        }
    };

    if (dev) {
        c.devtool = 'source-map'
    }

    return c;
}

module.exports = config;

