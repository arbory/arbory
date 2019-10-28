const webpack = require('webpack');

module.exports = function (mix) {

    mix.setPublicPath('public/arbory');
    mix.webpackConfig({
        resolve: {
            symlinks: false
        },

        plugins: [
            new webpack.ProvidePlugin({
                'window.jQuery'    : 'jquery',
                'window.$'         : 'jquery',
                'jQuery'           : 'jquery',
                '$'                : 'jquery'
            })
        ],
    });

    mix.js(
        'vendor/arbory/arbory/resources/assets/js/controllers/*',
        'js/controllers/'
    );

    mix.babel([
            'vendor/arbory/arbory/resources/assets/js/environment.js',
            'node_modules/jquery/dist/jquery.min.js',
            'node_modules/magnific-popup/dist/jquery.magnific-popup.min.js',
            'node_modules/ckeditor/ckeditor.js',
            'node_modules/ckeditor/adapters/jquery.js',
        ],
        'public/arbory/js/vendor.js'
    );

    mix.js(
        'vendor/arbory/arbory/resources/assets/js/include/**/*.js',
        'js/application.js'
    );

    mix.sass(
        'vendor/arbory/arbory/resources/assets/stylesheets/application.scss',
        'css/application.css'
    );

    mix.sass(
        'vendor/arbory/arbory/resources/assets/stylesheets/controllers/nodes.scss',
        'css/controllers/'
    );

    mix.sass(
        'vendor/arbory/arbory/resources/assets/stylesheets/controllers/sessions.scss',
        'css/controllers/'
    );

    mix.copyDirectory(
        'vendor/ckeditor/ckeditor/',
        'public/arbory/ckeditor/'
    );

    mix.copyDirectory(
        'vendor/arbory/arbory/resources/assets/js/lib/ckeditor/plugins/',
        'public/arbory/ckeditor/plugins/'
    );

    mix.copyDirectory(
        'vendor/arbory/arbory/resources/assets/images/',
        'public/arbory/images/'
    );

    mix.copyDirectory(
        'vendor/unisharp/laravel-filemanager/public/',
        'public/arbory/laravel-filemanager/'
    );

    mix.version();
};
