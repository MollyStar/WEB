'use strict';

// var conf = require('json-config-reader').read('package.json');

module.exports = {
    root: './',
    config: './config',
    src: './public/asset',
    build: './src/__BUILD__',
    release: './public/asset',
    webpack: function (dev) {
        var c = {
            output: {
                filename: '[name].js'
            },
            resolve: {
                alias: {
                    'jquery': './jquery-bridge.js'
                }
            }
        };

        if (dev) {
            c.devtool = 'source-map'
        }

        return c;
    },
    composer: {
        '*': '/DEV_APP_CENTER_L',
        'master': '/../../APP_CENTER_L'
    }
};

