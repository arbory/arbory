const webpack = require('webpack');
const path = require('path');

/**
 * @param {*} mix
 * @param {String} publicDirectory
 */
module.exports = function(mix, publicDirectory) {
    mix.setPublicPath(publicDirectory);

    const assetPath = path.resolve(__dirname, 'resources/assets');

    mix.options({
        processCssUrls: false,
    });

    mix.webpackConfig({
        plugins: [
            new webpack.ProvidePlugin({
                'window.jQuery': 'jquery',
                'window.$': 'jquery',
                'jQuery': 'jquery',
                '$': 'jquery',

            })
        ],
    });

    mix.js(path.resolve(assetPath, 'js/controllers/*'), 'js/controllers/');
    mix.js(path.resolve(assetPath, 'js/include/**/*.js'), 'js/includes.js');
    mix.js(path.resolve(assetPath, 'js/application.js'), 'js/application.js');


    mix.sass(path.resolve(assetPath, 'stylesheets/application.scss'), 'css/application.css');
    mix.sass(path.resolve(assetPath, 'stylesheets/controllers/sessions.scss'), 'css/controllers/');


    mix.copyDirectory('node_modules/ckeditor/', path.resolve(publicDirectory, 'ckeditor'));
    mix.copyDirectory(path.resolve(assetPath, 'js/ckeditor_plugins/'), path.resolve(publicDirectory, 'ckeditor/plugins'));
    // mix.copyDirectory(path.resolve(assetPath, 'images/'), path.resolve(publicDirectory, 'images'));
    mix.copyDirectory(path.resolve(assetPath, 'fonts/'), path.resolve(publicDirectory, 'fonts'));

    mix.version();
    mix.extract();

    return mix;
}