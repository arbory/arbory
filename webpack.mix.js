const mix = require('laravel-mix');
const webpack = require('webpack');

mix.setPublicPath('dist');

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

mix.js('resources/assets/js/controllers/*', 'js/controllers/');

mix.js('resources/assets/js/include/**/*.js', 'js/includes.js');

mix.js('resources/assets/js/application.js', 'js/application.js');


mix.sass('resources/assets/stylesheets/application.scss', 'css/application.css');

mix.sass('resources/assets/stylesheets/controllers/sessions.scss', 'css/controllers/');


mix.copyDirectory('node_modules/ckeditor/', 'dist/ckeditor/');

mix.copyDirectory('resources/assets/js/ckeditor_plugins/', 'dist/ckeditor/plugins/');

mix.copyDirectory('resources/assets/images/', 'dist/images/');
mix.copyDirectory('resources/assets/fonts/', 'dist/fonts/');

mix.version();
mix.extract();